<?php


class Ajax_Controller extends Xhr{


    public function getBranchForm($branchID = NULL ){

        $response = array();
        $data = array();

        if( $branchID and $branchID !== "" ){

            $branch = $this->doctrine->em->find('branch\models\Branch', $branchID);

            if( $branch ){
                $data['branch'] = $branch;
            }else{
                $response['status'] = 'failure';
                $response['message'] = 'Branch Not Found';
            }
        }

        $this->load->theme('branch/edit_branch_template', $data);
    }

    public function toggleBranchStatus($branchID){

        $response = array();

        if($branchID and $branchID !== ""){
            $branch = $this->doctrine->em->find('branch\models\Branch', $branchID);

            if( $branch ){

                $statusDesc = ' Enabled ';

                if( $branch->isActive() ){
                    $branch->markAsInactive();
                    $statusDesc = ' Disabled ';
                }else{
                    $branch->markAsActive();
                }

                $this->doctrine->em->persist($branch);

                try{
                    $this->doctrine->em->flush();

                    $response['status'] = 'success';
                    $response['currentStatus'] = $branch->isActive();
                    $response['message'] = $branch->getName().$statusDesc.'successfully.';

                }catch (\Exception $e){
                    $response['status'] = 'error';
                    $response['message'] = 'Unable to change status. '.$e->getMessage();
                }

            }else{
                $response['status'] = 'error';
                $response['message'] = 'Could not find the branch';
            }
        }else{
            $response['status'] = 'error';
            $response['message'] = 'Could not find the branch';
        }
        echo json_encode($response);
    }

    public function saveBranch(){
        $post = $_POST;
        $branch = NULL;
        $response = array();
        $response['status'] = 'error';
        $response['message'] = 'Unable to edit branch';
        if( isset($post['id']) and $post['id'] !== '' and user_access('administer branch') ){
            $branchID = $post['id'];
            $branch = $this->doctrine->em->find('branch\models\Branch', $branchID);
            if( $branch ){
                $branch->setName($post['name']);
                $branch->setDescription($post['description']);
                if( isset($post['status']) and $post['status'] == true ){
                    $branch->markAsActive();
                }else{
                    $branch->markAsInactive();
                }

                $this->doctrine->em->persist($branch);

                try{
                    $this->doctrine->em->flush();
                    $this->message->set('Branch Updated Successfully.', 'success', TRUE, 'feedback');
                    $response['status'] = 'success';
                    $response['message'] = 'Branch Updated Successfully.';

                }catch (\Exception $e){
                    $response['message'] = 'Unable to update branch. '.$e->getMessage();
                }
            }
        }

        echo json_encode($response);
    }



}