<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');




class Ajax_Controller extends Xhr{


    public function searchMember(){

        $queryString = $this->input->post('q');
        $projectId = $this->input->post('p');

        $projectRepo = $this->doctrine->em->getRepository('project\models\Project');

        $users = $projectRepo->searchForMembers($queryString, $projectId);

        $data['post'] = $this->input->post();
        $data['users'] = $users;
        echo json_encode($data);
    }


}






