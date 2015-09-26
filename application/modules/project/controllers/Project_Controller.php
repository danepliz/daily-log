<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');

use project\models\Project;
use project\models\ProjectMeta;


class Project_Controller extends Admin_Controller{


    public function __construct(){
        parent::__construct();

        $this->breadcrumb->append_crumb('Project', site_url('project'));
        $this->load->helper(['sheepit_helper']);
    }


    public function index(){

        $offset = 0;

        $projectRepository = $this->doctrine->em->getRepository('project\models\Project');
        $projects = $projectRepository->listProjects();

        $this->templatedata['page_title'] = 'PROJECT | List';
        $this->templatedata['projects'] = $projects;
        $this->templatedata['maincontent'] = 'project/index';
        $this->templatedata['offset'] = $offset;
        $this->load->theme('master', $this->templatedata);
    }

    public function detail($slug){

        $projectRepository = $this->doctrine->em->getRepository('project\models\Project');
        $project = $projectRepository->findOneBy(['slug' => $slug]);

        if( ! $project ){
            redirect('dashboard');
        }

        $this->templatedata['page_title'] = $project->getName();
        $this->templatedata['project'] = $project;
        $this->templatedata['maincontent'] = 'project/detail';
        $this->load->theme('master', $this->templatedata);
    }

    public function add($id = ''){

        if( $id != '' and !user_access('edit project')) redirect('dashboard');

        if( $id == '' and !user_access('add project') ) redirect('dashboard');

        $project = NULL;

        if( $id != '' ){
            $project = $this->doctrine->em->find('project\models\Project', $id);
            if( !$project ){
                redirect('dashboard');
            }
        }

        if( $post = $this->input->post() ){

            $this->form_validation->set_rules('name', 'Project Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('description', 'Project Description', 'trim');

            if( $this->form_validation->run() === TRUE ){

                $project = ( $project instanceof Project ) ? $project : new Project();
                $project->setName($post['name'])
                    ->setDescription($post['description']);

                if( count($project->getMeta()) ){
                    foreach($project->getMeta() as $m){
                       $this->doctrine->em->remove($m);
                    }
                }

                if(count($post['meta'])){
                    foreach($post['meta'] as $meta){

                        $projectMeta = new ProjectMeta();
                        $projectMeta->setMetaKey($meta['key'])
                            ->setMetaValue($meta['value'])
                            ->setProject($project);

                        if( isset($meta['allow']) ){
                            $projectMeta->markAsShowToAll();
                        }else{
                            $projectMeta->markAsDoNotShowToAll();
                        }

                        $this->doctrine->em->persist($projectMeta);
                        $project->addMeta($projectMeta);
                    }
                }

                $this->doctrine->em->persist($project);

                try{
                    $this->doctrine->em->flush();
                    $this->message->set('Project Added Successfully', 'success', TRUE,'feedback');
                    redirect('project');
                }catch(\Exception $e){
                    $this->templatedata['error'] = $e->getMessage();
                }

            }

        }

        $pageTitle = ($id != '')? $project->getName() : 'Add';

        $this->templatedata['page_title'] = 'PROJECT | '.$pageTitle;
        $this->templatedata['project'] = $project;
        $this->templatedata['maincontent'] = 'project/add';
        $this->load->theme('master', $this->templatedata);
    }




}






