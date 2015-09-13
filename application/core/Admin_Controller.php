<?php

//use models\PrivilegeCard,
use user\models\User;
use user\models\Group;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_Controller extends MY_Controller {

    var $templatedata = array();
    var $mainmenu = NULL;

    public function __construct() {

        parent::__construct();

        System::init();

        //$this->output->enable_profiler(TRUE);

        $this->load->library('session');

        $this->load->library('CssCrush');

        if (!$this->session->userdata('user_id')) {
            $redirectUrl = substr(current_url(), strlen(base_url()) );

            if( $_GET ){
                $getURI = '?' . http_build_query($_GET, '', '&');
                $redirectUrl .= $getURI;
            }

            $this->session->set_userdata('redirect_url', $redirectUrl);
            redirect('auth/login/'.$redirectUrl);
        }

        $this->load->library('breadcrumb');
        $this->breadcrumb->append_crumb('Dashboard', site_url());

        $this->templatedata['mainmenu'] = $this->mainmenu;

        $this->templatedata['_CONFIG'] = $this->_CONFIG;
        $this->templatedata['flashdata'] = $this->session->flashdata('feedback');

        $this->templatedata['scripts'] = array();
        $this->templatedata['stylesheets'] = array();
        $currentUser = Current_User::user();
        $this->templatedata['current_user'] = $currentUser;

        if (\Options::get('user1st_login', '0') == '1') {

            if (($currentUser->isFirstLogin())) {

                $pwdChangeLink = '<a href="'.site_url('auth/changepwd').'">here</a>';

                $this->message->set("Logging in for first time! Please Click ".$pwdChangeLink." to change your password.", 'alert', TRUE, 'feedback');
//                redirect(site_url('auth/changepwd'));
            }
        }

//        check for any critical messages
        $_critical_messages = $this->message->get('alert', 'critical');

        if (count($_critical_messages) > 0) {
            $this->templatedata['critical_alerts'] = $_critical_messages;
        }

        $_feedbacks = $this->message->get(FALSE, 'feedback');

        if (count($_feedbacks) > 0) {
            $this->templatedata['feedback'] = $_feedbacks;
        }

        $this->benchmark->mark('admin_controller_end');



        if ($currentUser->isActive() === FALSE) {
            $this->session->sess_destroy();
            redirect('auth/login');
        }

        if (Current_User::isAlreadyLogged()) {

            $this->session->sess_destroy();
            $this->templatedata['maincontent'] = 'config/user-already-logged-in';
            $this->load->theme('master', $this->templatedata);
        }

        $admin = Current_User::isSuperUser();

        if (is_numeric($main_user = $this->session->userdata('main_user'))) {
            $main_user = $this->doctrine->em->find('user\models\User', $main_user);

            if ($main_user) {
                $this->templatedata['user_switch'] = array(
                    'text' => 'You are currently using Application   as ' . Current_User::user()->getFullName() . '. Revert back to <a href="' . site_url('auth/revert') . '" style="color:#f99;">Main User</a> when you are done.',
                    'type' => 'warning',
                    'layout' => 'top',
                    'theme' => 'defaulta',
                );
            }
        }

        if ($admin or $main_user) {
            
        } else {

//            if (\Options::get('user1st_login', '0') == '1') {
//
//                if (($currentUser->isFirstLogin())) {
//
//                    $this->message->set("Logging in for first time! Please change your password.", 'error', TRUE, 'feedback');
//                    redirect(site_url('auth/changepwd'));
//                }
//            }

            if (\Options::get('userpwd_expirable', '0') == '1'
                    and ( \Options::get('userpwd_expiry_days', '1000') >= '10')
            ) {

                if (
                        (isValidDate(($currentUser->pwdLastChangedOn()->format('Y-m-d')))) // bypass invalid timestamp
                        and ( time() - strtotime($currentUser->pwdLastChangedOn()->format('Y-m-d'))) // number of seconds from last pwd change to now
                        >=
                        ((\Options::get('userpwd_expiry_days', '1000')) * 86400) // number of seconds after which pwd expires
                ) {

                    $this->message->set("Your password has expired!! Please change your password.", 'error', TRUE, 'feedback');
                    redirect(site_url('auth/changepwd'));
                }
            }
        }

        if (\Options::get('site_maintenance', '0') == '1') {

            $force_maintenance = TRUE;

            $autoresume = (\Options::get('site_maintenance_resume', '0') == '1') ? TRUE : FALSE;

            if ($autoresume) {

                $resume_date_time = \Options::get('site_maintenance_resume_after', '0000-00-00 00:00');

                $resume_date = substr($resume_date_time, 0, 10);

                if (isValidDate($resume_date)) {

                    $resume_timestamp = strtotime($resume_date_time . ':00');

                    if ($resume_timestamp > 0 and $resume_timestamp < time()) {

                        $force_maintenance = FALSE;
                    }
                }
            }

            if ($force_maintenance) {

                if ($admin) {
                    $this->templatedata['site_maintenance'] = array(
                        'text' => 'Site is Currently in Maintenance Mode. Please <a href="' . site_url('config/settings') . '#maintenance" target="_blank" style="color:#f99;">deactivate</a> this mode when you are done.',
                        'type' => 'information',
                        'layout' => 'top',
                        'theme' => 'default',
                    );
                } else {

                    $this->templatedata['maincontent'] = 'config/site-maintenance';
                    $this->load->theme('master', $this->templatedata);
                }
            }
        }
    }

}
