<?php

use user\models\Group;
use user\models\User;

class User_Controller extends Admin_Controller
{

    public function __construct()
    {
        $this->mainmenu = MAINMENU_USER;
        parent::__construct();

        $this->load->helper(array( 'user/user', 'security'));
        $this->load->library('form_validation');
        if (strpos(current_url(), 'changepwd') !== FALSE or strpos(current_url(), 'profile') !== FALSE) $this->breadcrumb->append_crumb('My Account', 'javascript:;');

        else $this->breadcrumb->append_crumb('Users', site_url('user'));
    }

    public function index()
    {
        if (!user_access_or(array('view user', 'administer user', 'reset password', 'allow user switching')))
            redirect();

        $filters = $countries = array();

        $param = '';
        $geturi = '';
        $post = NULL;

        $offset = $this->input->get('per_page');

        $users = $this->doctrine->em->getRepository('user\models\User')->getUserList($offset, PER_PAGE_DATA_COUNT, $filters);
        $total = count($users);

        if ($total > PER_PAGE_DATA_COUNT) {
            $this->templatedata['pagination'] = getPagination($total, 'user/index?' . $param, 3);
        }

        $this->breadcrumb->append_crumb('Users List', site_url('user'));

        $this->templatedata['users'] = &$users;
        $this->templatedata['offset'] = $offset ? $offset : 0;
        $this->templatedata['filters'] = $filters;
        $this->templatedata['post'] = $post;
        $this->templatedata['countries'] = $countries;
        $this->templatedata['page_title'] = 'USER | Lists';
        $this->templatedata['maincontent'] = 'user/list';
        $this->load->theme('master', $this->templatedata);

    }

    public function add()
    {
        if (!user_access(  array( 'administer user' ) ) ) redirect();


        if ($this->input->post()) {
            $this->form_validation->set_rules('group_id', 'Role', 'required');
            $this->form_validation->set_rules('full_name', 'First Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'trim|email|required|is_unique[ys_users.email]');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|matches[confirmpassword]');
            $this->form_validation->set_rules('confirmpassword', 'Confirm Password', 'trim|required');
            $this->form_validation->set_rules('address', 'Address', 'required');
            $this->form_validation->set_rules('mobile', 'Mobile');

            if ($this->form_validation->run($this)) {
                $user = new User();
                $fullName = strip_tags(trim($this->input->post("full_name")));
                $address = strip_tags(trim($this->input->post('address')));
                $countryID = $this->input->post("country");
                $groupsId = $this->input->post('group_id');
                $branchId = $this->input->post('branch');

                $api_key = md5(microtime().rand(100, 900));

                $groups = $this->doctrine->em->find('user\models\Group', $groupsId);


                $user->setFullName($fullName);
                $user->setAddress($address);
                $user->setEmail(strip_tags(trim($this->input->post("email"))));
                $user->setPhone(strip_tags(trim($this->input->post("phone"))));
                $user->setMobile(strip_tags(trim($this->input->post("mobile"))));
                $user->setGroup($groups);
                $user->setPassword(strip_tags(trim($this->input->post("password"))));
                $user->setApiKey($api_key);
                $user->markFirstLogin();
                $user->setPwdLastChangedOn();
                $user->setLastLogged();
                $user->activate();

                $this->doctrine->em->persist($user);
                $this->doctrine->em->flush();

                if ($user->id()) {
                    $this->message->set('User added successfully.', 'success', TRUE, 'feedback');
                    \Events::trigger('user_add', array('user' => $user, 'username' => $user->getEmail(), 'password' => $this->input->post("password")));

                } else {
                    $this->message->set("Could not add User", 'error', TRUE, 'feedback');
                }
                redirect('user');
            } else {
                $post = $this->input->post();
                $this->templatedata['post'] = &$post;
            }
        }

        $this->breadcrumb->append_crumb('Create User', admin_url('user/add'));

        $currentUser = Current_User::user();

        $gRepo = $this->doctrine->em->getRepository('user\models\Group');
        $groups = $gRepo->getGroupList();

        $filters = array();

        $this->templatedata['currentUser'] = &$currentUser;
        $this->templatedata['groups'] = &$groups;
        $this->templatedata['page_title'] = 'USER | Add';
        $this->templatedata['maincontent'] = 'user/add';
        $this->load->theme('master', $this->templatedata);
    }

    public function profile()
    {
        $user = Current_User::user();

        if ( $this->input->post() ) {

            $this->form_validation->set_rules('fullName', 'Full Name', 'trim|required');
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric');
            $this->form_validation->set_rules('address', 'Address', 'trim|required');

            if ($this->form_validation->run($this)) {

                $user->setPhone($this->input->post('phone'));
                $user->setMobile($this->input->post('mobile'));
                $user->setAddress($this->input->post('address'));

                $this->doctrine->em->persist($user);

                try{
                    $this->doctrine->em->flush();
                    $this->message->set('Profile upadated successfully.', 'success', TRUE, 'feedback');
                    redirect('user/profile');
                }catch (\Exception $e){
                    die($e->getMessage());
                    $this->message->set('Unable to update profile, please try again', 'error', TRUE, 'feedback');
                    redirect('user/profile');
                }
            } else {
                $this->message->set(validation_errors(), 'error', TRUE, 'feedback');
            }
        }

        $this->templatedata['user'] = $user;
        $this->templatedata['maincontent'] = 'user/profile';
        $this->templatedata['page_title'] = $user->getFullName();
        $this->breadcrumb->append_crumb('Profile [ ' . $user->getFullName() . ' ]', 'user/profile');
        $this->load->theme('master', $this->templatedata);
    }

    public function edit($userID)
    {

        if (!user_access('administer user')) redirect();

        $urepo = $this->doctrine->em->getRepository('user\models\User');

        if (!($user = $urepo->find($userID)) or $user->isDeleted() or !$user->isActive()) redirect('user');


        $currentUser = Current_User::user();
        $userGroup = $currentUser->getGroup()->id();

        $editPermission = \Current_User::canActOn($user);

        if (!$editPermission) {
            $this->message->set('User cannot be edited.', 'error', TRUE, 'feedback');
            redirect('user');
        }

        if ($this->input->post()) {
            $this->form_validation->set_rules('fullName', 'Full Name', 'trim|required');
            $this->form_validation->set_rules('phone', 'Phone', 'trim|numeric');
            $this->form_validation->set_rules('mobile', 'Mobile');
            $this->form_validation->set_rules('address', 'Address', 'trim|required');
            $this->form_validation->set_rules('group_id', 'Group', 'trim|numeric|required');

            if ($this->form_validation->run($this)) {
                $user->setFullName($this->input->post("fullName"));
                $user->setPhone($this->input->post("phone"));
                $user->setMobile($this->input->post("mobile"));
                $user->setAddress($this->input->post("address"));
                $groups = $this->doctrine->em->find('user\models\Group', $this->input->post("group_id"));
                $user->setGroup($groups);

                $this->doctrine->em->persist($user);

                try {
                    $this->doctrine->em->flush();

                    $this->message->set('User edited successfully.', 'success', TRUE, 'feedback');

                    log_message('info', "User details for {$user->getFullName()} edited.");

                } catch (Exception $e) {
                    $this->message->set("Could not edit User", 'error', TRUE, 'feedback');
                }
                redirect('user');
            }
        }

        $gRepo = $this->doctrine->em->getRepository('user\models\Group');
        $groups = $gRepo->getGroupList();

        $this->templatedata['maincontent'] = 'user/edit';
        $this->breadcrumb->append_crumb('Edit User', site_url('user/edit'));

        $this->templatedata['groups'] = &$groups;
        $this->templatedata['currentUser'] = &$currentUser;
        $this->templatedata['user'] = $user;
        $this->templatedata['page_title'] = 'USER | '.$user->getFullName();
        $this->templatedata['agents'] = &$agentsList;
        $this->load->theme('master', $this->templatedata);

    }

    public function block()
    {
        $userID = $this->input->post('id');

        if (!user_access(
            array(
                'administer user',
            )
        )
        ) redirect();

        $response['status'] = 'error';
        $response['message'] = '';


        $urepo = $this->doctrine->em->getRepository('user\models\User');

        if (!($user = $urepo->find($userID))
            or $user->id() == 1 or $user->id() == Current_User::user()->id()
        ) redirect('user');

        if (\Current_User::canActOn($user) and $user->isActive() == TRUE) {
            $user->deactivate();
            $this->doctrine->em->persist($user);

            try {
                $this->doctrine->em->flush();
                log_message('info', 'User' . $user->getFullName() . 'marked as blocked');
                $response['status'] = 'success';
                $response['message'] = 'The User "' . $user->getFullName() . '" has been Blocked.';
                $this->message->set($user->getFullName() . " has been Blocked !!", 'success', TRUE, 'feedback');
            }catch (Exception $e){
                $response['message'] = 'Unable to block the user. '.$e->getMessage();
            }
        }

        //redirect('user');
        echo json_encode($response);
    }

    public function unblock()
    {
        $userID = $this->input->post('id');

        if (!user_access(
            array(
                'administer user',
            )
        )
        ) redirect();

        $response['status'] = 'error';
        $response['message'] = '';

        $urepo = $this->doctrine->em->getRepository('user\models\User');

        if (!($user = $urepo->find($userID))
            or $user->id() == 1 or $user->id() == Current_User::user()->id()
        ) redirect('user');

        if (\Current_User::canActOn($user) and !$user->isActive()) {
            $user->activate();
            $this->doctrine->em->persist($user);
            try {
                $this->doctrine->em->flush();
                log_message('info', 'User' . $user->getFullName() . 'marked as unblocked');
                $response['status'] = 'success';
                $response['message'] = 'The User "' . $user->getFullName() . '" has been Unblock.';
                $this->message->set($user->getFullName() . " has been UnBlocked !!", 'success', TRUE, 'feedback');
            }catch (Exception $e){
                $response['message'] = 'Unable to unblock the user. '.$e->getMessage();
            }
//            $this->doctrine->em->flush();
//            $this->message->set($user->getFullName() . " has been Unblocked !!", 'success', TRUE, 'feedback');
        }

        //redirect('user');
        echo json_encode($response);


    }

    public function delete()
    {
        $userID = $this->input->post('id');

        $response['status'] = 'error';
        $response['message'] = '';

        if (!user_access(
            array(
                'administer user',
            )
        )
        ) redirect();

        $urepo = $this->doctrine->em->getRepository('user\models\User');

        if (!($user = $urepo->find($userID))
            or $user->id() == 1 or $user->id() == Current_User::user()->id()
        ) redirect('user');

        if (\Current_User::canActOn($user) and $user->isDeleted() == FALSE) {
            $user->markAsDeleted();
            $this->doctrine->em->persist($user);
            try {
                $this->doctrine->em->flush();
                log_message('info', 'User ' . $user->getFullName() . ' marked as deleted');
                $response['status'] = 'success';
                $response['message'] = 'The User "' . $user->getFullName() . '" has been Deleted.';
                $this->message->set($response['message'], 'success', true, 'feedback');
            } catch (Exception $e) {
                // $this->message->set("Cannot delete " . $user->getFullName() . ' There are other data associated with it.', 'error', TRUE, 'feedback');
                $response['message'] = 'Unable to delete User. ' . $e->getMessage();}
            }else{
                $response['message'] = 'user Not Found.';
            }
            echo json_encode($response);
    }



    public function addgroup()
    {
        if ($this->input->post()) {
            $this->form_validation->set_rules('group_name', 'Group Name', 'required');
            if ($this->form_validation->run($this)) {
                $status = ($this->input->post('isActive') == 1) ? "active" : "inactive";

                $group = new user\models\Group;
                $group->setName($this->input->post('group_name'));
                $group->setStatus($status);

                $this->doctrine->em->persist($group);
                $this->doctrine->em->flush();

                if ($group->id()) {
                    $this->session->set_success_flashdata('feedback', "Group added successfully.");
                    admin_redirect('user#group');
                }

            }
        }
        $this->breadcrumb->append_crumb('Add Group', admin_url('user/addgroup'));
        $this->templatedata['maincontent'] = 'user/admin/group/add';
        $this->load->view('admin/master', $this->templatedata);
    }

    public function editgroup($group_id)
    {
        if ($group_id == ROLE_SUPER_ADMIN)
            admin_redirect('user#group');

        $group = $this->doctrine->em->find('user\models\Group', $group_id);

        $this->templatedata['group'] = &$group;
        if ($this->input->post()) {
            $this->form_validation->set_rules('group_name', 'Group Name', 'required');
            if ($this->form_validation->run($this)) {
                $status = ($this->input->post('isActive') == 1) ? "active" : "inactive";

                $group->setName($this->input->post('group_name'));
                $group->setStatus($status);

                $this->doctrine->em->persist($group);
                $this->doctrine->em->flush();


                $this->session->set_success_flashdata('feedback', 'Group saved successfully.');
                admin_redirect('user#group');
            }
        }
        $this->breadcrumb->append_crumb('Edit Group', admin_url('user/editgroup'));
        $this->templatedata['maincontent'] = 'user/admin/group/edit';
        $this->load->view('admin/master', $this->templatedata);
    }

    public function deletegroup($group_id)
    {
        if ($group_id == ROLE_SUPER_ADMIN)
            admin_redirect('user#group');
        $group = $this->doctrine->em->find('user\models\Group', $group_id);

        foreach ($group->getUsers() as $u) {
            $_user_groups = &$u->getGroup();
            if ($_user_groups->contains($group) AND $_user_groups->count() == 1) {
                $u->unassignGroup($group);

                $_ug = $this->doctrine->em->find('user\models\Group', ROLE_UNASSIGNED);
                $u->assignGroup($_ug);
            }
            $this->doctrine->em->persist($u);
        }


        $this->doctrine->em->remove($group);
        $this->doctrine->em->flush();

        admin_redirect('user#group');
    }

    public function editgrouppermissions($group_id)
    {
        if ($group_id == ROLE_SUPER_ADMIN)
            admin_redirect('user#group');

        $group = $this->doctrine->em->find('user\models\Group', $group_id);

        $allpermissions =& $this->um->getPermissions();

        $role_permission = array();
        foreach ($group->getPermissions() as $ur)
            $role_permission[] = $ur->id();

        $_allpermissions = array();
        foreach ($allpermissions as $ar)
            $_allpermissions[] = $ar->id();

        if ($this->input->post()) {
            foreach ($this->input->post('permissions') as $p) {
                $key = array_search($p, $role_permission);
                if ($key === FALSE) {
                    $permission = $this->doctrine->em->find("user\models\Permissions", $p);
                    $group->assignPermission($permission);
                } else
                    unset($role_permission[$key]);
            }

            foreach ($role_permission as $ur) {
                $permission = $this->doctrine->em->find("user\models\Permissions", $ur);
                $role->unassignPermission($permission);
            }

            $this->doctrine->em->persist($group);
            $this->doctrine->em->flush();

            $this->session->set_success_flashdata('feedback', 'Permissions saved successfully.');
            admin_redirect('user');
        }

        $this->templatedata['groupPermissions'] = $role_permission;
        $this->templatedata['allpermissions'] = $allpermissions;
        $this->templatedata['group'] = &$group;
        $this->templatedata['maincontent'] = 'user/admin/editgrouppermission';
        $this->load->view('admin/master', $this->templatedata);
    }

    public function changepwd()
    {

        $user = Current_User::user();

        if ($this->input->post()) {

            $this->form_validation->set_rules('oldpwd', 'Old Password', 'trim|required|callback_checkOldPwd');
            $this->form_validation->set_rules('newpwd', 'New Password', 'trim|required|min_length[6]|matches[conpwd]');
            $this->form_validation->set_rules('conpwd', 'Confirm Password', 'trim|required|min_length[6]');

            if ($this->form_validation->run($this)) {

                $user->setPassword($this->input->post('newpwd'));
                $user->setPwdLastChangedOn();
                if ($user->isFirstLogin()) $user->unmarkFirstLogin();

                $this->doctrine->em->persist($user);
                $this->doctrine->em->flush();

                $this->message->set("Password changed successfully.", 'success', TRUE, 'feedback');
                redirect('dashboard');
            }
        }

        $this->breadcrumb->append_crumb('Change Password', '#');
        $this->templatedata['page_title'] = 'Change Password';
        $this->templatedata['maincontent'] = 'user/changepwd';
        $this->load->theme('master', $this->templatedata);
    }

    public function resetpwd($userID)
    {

        if (!user_access(
            array(
                'reset password',
            )
        )
        ) redirect();


        $urepo = $this->doctrine->em->getRepository('user\models\User');

        if (!($user = $urepo->find($userID))
            or $user->isDeleted() or !$user->isActive()
        ) redirect('user');

        if ($userID == Current_User::user()->id() or !\Current_User::canActOn($user)) redirect('user');

        if ($this->input->post()) {

            $this->form_validation->set_rules('newpwd', 'New Password', 'trim|required|min_length[6]|matches[conpwd]');
            $this->form_validation->set_rules('conpwd', 'Confirm Password', 'trim|required|min_length[6]');

            if ($this->form_validation->run($this)) {

                $user->setPassword($this->input->post('newpwd'));
                if (!$user->isFirstLogin()) $user->markFirstLogin();

                $this->doctrine->em->persist($user);
                $this->doctrine->em->flush();

                $this->message->set("Password reset successfully.", 'success', TRUE, 'feedback');
                redirect('user');
            }
        }

        $this->breadcrumb->append_crumb('Reset Password', '#');
        $this->templatedata['maincontent'] = 'user/resetpwd';
        $this->load->theme('master', $this->templatedata);
    }

    public function checkOldPwd($oldpwd)
    {

        $this->load->library('password');
        $currentUser = Current_User::user();

        $isOldPasswordCorrect = $this->password->validate_password($oldpwd, $currentUser->getSalt(), $currentUser->getSecrete());
        $isNewPasswordMatchedToOldPassword = $this->password->validate_password($this->input->post('newpwd'), $currentUser->getSalt(), $currentUser->getSecrete());

        if ( !$isOldPasswordCorrect ) {

            $this->form_validation->set_message('checkOldPwd', 'The Old Password is Wrong.<br/>');
            return false;
        }

        if ( $isNewPasswordMatchedToOldPassword ) {

            $this->form_validation->set_message('checkOldPwd', 'The New Password must be different than Old Password.<br/>');
            return false;

        }

    }

    public function updatelevel()
    {

        $users = CI::$APP->db->query("
SELECT u.id, u.username FROM ys_users u LEFT JOIN ys_groups g on g.id = u.groups_id WHERE (userlevel IS NULL OR userlevel = '') AND (u.id > 1) AND (g.id <> '" . Group::SUPER_ADMIN . "')
				")->result_array();

        if (!empty($users)) {
            $userLevels = array(
                'PA' => User::USERLEVEL_PA,
                'SA' => User::USERLEVEL_SA,
            );

            foreach ($users as $u) {
                $user = $this->doctrine->em->find('user\models\User', $u['id']);
                if ($user) {
                    $agent = $user->getAgent();

                    $agent->getParentAgent() ? $user->setLevel($userLevels['SA']) : $user->setLevel($userLevels['PA']);
                    $this->doctrine->em->persist($user);
                }

            }

            try {
                $this->doctrine->em->flush();
                $this->message->set("The userlevel updated for all users.", 'success', TRUE, 'feedback');
            } catch (\Exception $e) {

            }
        }

        redirect();

    }

}