<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');




class Ajax_Controller extends Xhr{


    public function searchMember(){

        $queryString = $this->input->post('q');
        $projectId = $this->input->post('p');

        $projectRepo = $this->doctrine->em->getRepository('project\models\Project');

        $users = $projectRepo->searchForMembers($queryString, $projectId);

        $usersArray = [];
        if(count($users))
        {
            foreach($users as $u)
            {
                $usersArray[] = [
                    'id' => $u->id(),
                    'email' => $u->getEmail(),
                    'name' => $u->getFullname(),
                    'image' => getImageTag($u->getGravatar('30')),
                ];
            }
        }

        echo json_encode($usersArray);
    }

    public function addMember()
    {
        $projectId = $this->input->post('project');
        $userId = $this->input->post('user');

        $response['status'] = 'error';

        if( $projectId != '' and $userId != ''){
            $project = $this->doctrine->em->find('project\models\Project', $projectId);
            $user = $this->doctrine->em->find('user\models\User', $userId);

            if( $project and $user ){

                $mArr = [];
                if(count($project->getMembers())){
                    foreach($project->getMembers() as $m){
                        $mArr[] = $m->id();
                    }
                }

                if( ! in_array($userId, $mArr) ){
                    $project->addMember($user);
                    $this->doctrine->em->persist($project);

                    try{
                        $this->doctrine->em->flush();
                        $html = '<div class="col-md-2 m-wrap">';
                        $html .= getImageTag(
                            $user->getGravatar(200, 'wavatar'),
                            [
                                'data-toggle'=> 'tooltip',
                                'data-placement'=> 'bottom',
                                'title'=> $user->getFullname().'<br />'.$user->getEmail()
                            ]
                        );
                        $html .= '<br />'.$user->getFullname();
                        $html .= '</div>';
                        $response['status'] = 'success';
                        $response['member'] = $html;
                    }catch (\Exception $e){
                        $response['message'] = $e->getMessage();
                    }
                }else{
                    $response['message'] = 'already a member';
                }


            }else{
                $response['message'] = 'Member or Project Not Found';
            }
        }else{
            $response['message'] = 'Incomplete Parameters';
        }

        echo json_encode($response);

    }


}






