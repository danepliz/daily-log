<?php

use user\models\User;

class Auth_Controller extends MY_Controller {

    protected $templateData = array();

    public function __construct() {

        parent::__construct();

        $_feedbacks = $this->message->get(FALSE, 'feedback');
        if (count($_feedbacks) > 0) {
            $this->templateData['feedback'] = $_feedbacks;
        }
//        $this->lang->load('auth');
    }

    public function authenticate() {
        if ($this->_validate_login() === FALSE) {
            $this->login();
            return;
        }
        $user = Current_User::user();
        $user->setLastLogged();
        $this->doctrine->em->persist($user);
        $this->doctrine->em->flush();

        $redirectUri = 'dashboard';

        if( $this->session->userdata('redirect_url') ){
            $redirectUri = $this->session->userdata('redirect_url');
        }

        redirect($redirectUri);
    }

    public function login() {

        if ($this->session->userdata('user_id'))
            redirect('dashboard');
        else {
            $this->load->theme('auth/login', $this->templateData);
        }
    }

    private function _validate_login() {
        $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean|callback_chklogin');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_message('chklogin', 'Invalid login. Please try again.');

        return $this->form_validation->run($this);
    }

    public function chklogin($username) {
        return Current_User::login($username, $this->input->post('password'));
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('dashboard');
    }

    public function switchuser($userId) {

        if (!user_access('allow user switching'))
            redirect('user');

        if (\Options::get('site_maintenance', '0') == '1') {

            $this->message->set("Cannot switch user while Site is in Maintenance mode !!", 'error', TRUE, 'feedback');
            redirect('dashboard');
        }

        $currentUser = Current_User::user();

        $user = $this->doctrine->em->find('user\models\User', $userId);

        if (!$user or $user->isDeleted() or ! $user->isActive()
                or $user->id() == 1 or $user->id() == $currentUser->id()
        )
            redirect('user');

        if (!\Current_User::canActOn($user)) {
            $this->message->set("Cannot switch to the user: '{$user->getFullName()}' !!", 'error', TRUE, 'feedback');
            redirect('dashboard');
        }

        $msg = $currentUser->getFullName() . ' has switched to ' . $user->getFullName();

        if (Current_User::switchto($userId)) {

            log_message('info', $msg);
            \Events::trigger('user_switch', array('user' => $currentUser->getFullName(), 'switchedTo' => $user->getFullName()));
            $this->message->set("Successfully switched to " . $user->getFullName() . ".", 'success', TRUE, 'feedback');
            redirect('dashboard');
        } else {

            $this->message->set("Cannot switch user !!", 'error', TRUE, 'feedback');
            redirect('user');
        }
    }

    public function revert() {

        if (is_numeric($main_user = $this->session->userdata('main_user'))) {

            $main_user = $this->doctrine->em->find('user\models\User', $main_user);

            if (!$main_user or $main_user->isDeleted() or ! $main_user->isActive())
                redirect('dashboard');

            $this->session->set_userdata('user_id', $main_user->id());

            if ($this->session->userdata('user_id') == $main_user->id()) {

                log_message('info', 'Reverted back to ' . $main_user->getFullName());
                $this->session->unset_userdata('main_user');

                $this->message->set("Successfully reverted back to " . $main_user->getFullName() . ".", 'success', TRUE, 'feedback');
                redirect('user');
            } else {

                $this->message->set("Cannot revert back user !!", 'error', TRUE, 'feedback');
                redirect('dashboard');
            }
        } else
            redirect('dashboard');
    }

    public function changepwd() {

        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }

        $user = Current_User::user();

        if ($this->input->post()) {

            $this->form_validation->set_rules('oldpwd', 'Old Password', 'trim|required|callback_checkOldPwd');
            $this->form_validation->set_rules('newpwd', 'New Password', 'trim|required|min_length[6]|matches[conpwd]');
            $this->form_validation->set_rules('conpwd', 'Confirm Password', 'trim|required|min_length[6]');

            if ($this->form_validation->run($this)) {

                $user->setPassword(md5(trim($this->input->post('newpwd'))));
                $user->setPwdLastChangedOn();
                if ($user->isFirstLogin())
                    $user->unmarkFirstLogin();

                $this->doctrine->em->persist($user);
                $this->doctrine->em->flush();

                $this->message->set("Password changed successfully.", 'success', TRUE, 'feedback');
                redirect('dashboard');
            }
        }
        $data = array();
        $data['current_user'] = $user;

//        $data['mainstyler'] = CssCrush::file(BASEPATH . '../assets/themes/' . config_item('current_theme') . '/css/mainstyler.css');
//        $data['menustyler'] = CssCrush::file(BASEPATH . '../assets/themes/' . config_item('current_theme') . '/css/menu.css');
//        $data['printstyler'] = CssCrush::file(BASEPATH . '../assets/themes/' . config_item('current_theme') . '/css/print.css');

        $this->load->library('breadcrumb');
        $this->breadcrumb->append_crumb('Dashboard', site_url());

        $_feedbacks = $this->message->get(FALSE, 'feedback');
        if (count($_feedbacks) > 0) {
            $data['feedback'] = $_feedbacks;
        }

        $data['maincontent'] = 'auth/forcechangepwd';
        $this->load->theme('master', $data);
    }

    public function checkOldPwd($oldpwd) {

        if (Current_User::user()->getPassword() != md5($oldpwd)) {

            $this->form_validation->set_message('checkOldPwd', 'The Old Password is Wrong.<br/>');
            return false;
        }

        if (md5($oldpwd) == md5($this->input->post('newpwd'))) {

            $this->form_validation->set_message('checkOldPwd', 'The New Password must be different than Old Password.<br/>');
            return false;
        }
    }

    public function forgotpassword() {
        $userrepo = $this->doctrine->em->getRepository('user\models\User');

        if ($this->input->post()) {
            $this->form_validation->set_rules('transborder_username_recovery', 'Username', 'trim|required');
            $this->form_validation->set_rules('transborder_email_recovery', 'Email Address', 'trim|required|callback__checkemail');

            if ($this->form_validation->run($this)) {
                $user = $userrepo->findOneBy(array('username' => $this->input->post('transborder_username_recovery'),
                    'email' => $this->input->post('transborder_email_recovery')));

                $user->setResetToken($userrepo->generatePasswordResetKey());
                $user->setTokenRequested(new \DateTime());

                $this->doctrine->em->persist($user);
                $this->doctrine->em->flush();

                $data = array('template' => 'pwd-token',
                    'user' => $user,
                );

                $message = $this->load->theme('emails/master', $data, TRUE);

                $this->load->config('email', TRUE);
                $email_config = $this->config->item('email');

                $this->load->library('email');
                $this->email->initialize($email_config);

                $this->email->from($email_config['from_email'], $email_config['from_email_name']);
                $this->email->to($this->input->post('transborder_email_recovery'));
                $this->email->subject('Agent Portal Password Recovery');
                $this->email->message($message);

                $this->email->send();
                $this->message->set("Instructions to reset your password has been emailed to you.", 'success', TRUE, 'feedback');
                redirect('auth/forgotpassword');
            }
        }

        $this->load->theme('auth/pwd-recovery', $this->templateData);
    }

    public function _checkemail($email) {
        $userrepo = $this->doctrine->em->getRepository('user\models\User');

        $user = $userrepo->findOneBy(array('username' => $this->input->post('transborder_username_recovery'),
            'email' => $email));

        if (!$user) {
            $this->form_validation->set_message('_checkemail', 'The email and username combination was not found in the system.');
            return FALSE;
        }

        return TRUE;
    }

    public function recoverpassword($code) {

        $userrepo = $this->doctrine->em->getRepository('user\models\User');

        $user = $userrepo->findOneBy(array('resetToken' => $code,
            'tokenUsed' => NULL));

        if (!$user)
            redirect();

        if ($this->input->post()) {
            $this->form_validation->set_rules('transborder_new_pwd', 'Password', 'trim|required');
            $this->form_validation->set_rules('transborder_new_pwd_confirm', 'Password Confirmation', 'trim|required|matches[transborder_new_pwd]');

            if ($this->form_validation->run($this)) {
                $user->setPassword(md5($this->input->post('transborder_new_pwd')));
                $user->setTokenUsed(new \DateTime());
                $this->doctrine->em->persist($user);
                $this->doctrine->em->flush();

                $this->message->set("Your password has been reset. You can now login with your new password.", 'success', TRUE, 'feedback');
                redirect('auth/login');
            }
        }

        $this->templateData['user'] = $user;

        $this->load->theme('auth/pwd-reset', $this->templateData);
    }

}
