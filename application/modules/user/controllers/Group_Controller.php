<?php

use user\models\Group;

class Group_Controller extends Admin_Controller{
	
	public function __construct(){

		parent::__construct();

		if (! Current_User::isSuperUser()) redirect();

		$this->breadcrumb->append_crumb('User', site_url('user'));
		$this->breadcrumb->append_crumb('Groups', site_url('user/group'));
	}
	
	public function index(){

		$groupRepo = $this->doctrine->em->getRepository('user\models\Group');

		$groups = $groupRepo->getGroupList();
		$numusers = array();

		foreach ($groupRepo->getUserCount() as $c) {
			$numusers[$c['group_id']] = $c['numusers']; 
		}

		$this->templatedata['groups'] = $groups;
		$this->templatedata['numusers'] = $numusers;
        $this->templatedata['page_title'] = 'List Groups';
		$this->templatedata['maincontent'] = 'user/group/list';
		$this->load->theme('master',$this->templatedata);
	}
	
	public function add(){
		
		$this->breadcrumb->append_crumb('Add a Group', "#");
		
		if($this->input->post()){
			$this->form_validation->set_rules('name','Group Name','trim|required|is_unique[ys_groups.name]');
			$this->form_validation->set_rules('description','Group Description','trim|required');
			
			if($this->form_validation->run($this)){
				
				$post = $this->input->post();
				
				$group = new Group();
				$group->setName($this->input->post('name'));
				$group->setDescription($this->input->post('description'));
				
				if(isset($post['mtoOnly']) and $post['mtoOnly'] == "Y")
					$group->forMtoOnly();
				
				$this->doctrine->em->persist($group);
				$this->doctrine->em->flush();
				
				if($group->id() and Current_User::isSuperUser() and user_access('manage group permissions')){
						
					$this->message->set("New group was added successfully", 'success',TRUE,'feedback');
					redirect('user/group/permissions/'.$group->id());
				
				}else{
						
					$this->message->set("Could not add the new group. Please try again.", 'error',TRUE,'feedback');
					redirect('user/group');
				}
			}else{
                $this->templatedata['has_error'] = TRUE;
            }
		}
		
		$this->templatedata['maincontent'] = 'user/group/add';
		$this->load->theme('master',$this->templatedata);
	}
	
	public function edit($group_id = ''){
		
		if($group_id == '' || $group_id == 1)
			redirect();
		
		$group = $this->doctrine->em->find('user\models\Group', $group_id);
		if(!$group)
			redirect();

        $post = array();
		
		if($this->input->post()){
			
			$this->form_validation->set_rules('name','Group Name','trim|required');
			$this->form_validation->set_rules('description','Group Description','trim|required');
			if($this->input->post('name') != $group->getName())	
				$this->form_validation->set_rules('name','Group Name','trim|required|is_unique[ys_groups.name]');
			
			if($this->form_validation->run($this)){
								
				$post = $this->input->post();
				
				$group->setName($this->input->post('name'));
				$group->setDescription($this->input->post('description'));
		
				$this->doctrine->em->persist($group);
				$this->doctrine->em->flush();
		
				$this->message->set("Group was saved successfully", 'success',TRUE,'feedback');
				redirect('user/group');
			
			}
		}
		
		$this->breadcrumb->append_crumb('Edit Group', "#");

        $this->templatedata['post'] = $post;
		$this->templatedata['group'] = $group;
        $this->templatedata['page_title'] = 'GROUP | '.$group->getName();
		$this->templatedata['maincontent'] = 'user/group/editgroup';
		$this->load->theme('master',$this->templatedata);
	}
	
	public function copygroup($group_id = ''){
		
		if(!$this->input->is_ajax_request())
			redirect();
		
		$res = array();
		
		if($group_id == '' || $group_id == Group::SUPER_ADMIN)
			$res['response'] = 'fail';
		
		$group = $this->doctrine->em->find('user\models\Group', $group_id);
		if(!$group)
			$res['response'] = 'fail';
		
		$new = clone $group;
		$new->setName($group->getName().'-clone-'.time());
		$new->clonePermissions();
		
		$this->doctrine->em->persist($new);
		$this->doctrine->em->flush();
		
		if($new->id()){
			
			$res['response'] = 'success';
			$res['group_id'] = $new->id();
		}
		
		echo json_encode($res);
	}
	
	public function permissions($group_id = ''){

        if($group_id == '' || $group_id == Group::SUPER_ADMIN || !user_access('manage group permissions'))
			redirect();
		
		$group = $this->doctrine->em->find('user\models\Group', $group_id);
		if(!$group)
			redirect();
		
		if($this->input->post()){
			
			$group->resetPermissions();
			$assignedPermissions = $this->input->post('permission');
			
			if ($assignedPermissions) {
				
				foreach($assignedPermissions as $k => $v){
					$perm = $this->doctrine->em->find('user\models\Permission', $k);
					$group->addPermission($perm);
				}
				
				$this->doctrine->em->persist($group);
				$this->doctrine->em->flush();
				
				$this->message->set("Group permissions were set successfully.", 'success', TRUE, 'feedback');
				redirect('user/group');
			} 
			
			else {
				
				$this->message->set("Please check atleast one permission for this group!", 'error', TRUE, 'feedback');
				redirect('user/group/permissions/'.$group_id);
			}
		}
		$this->breadcrumb->append_crumb('Edit Group Permissions', "#");
		
		$permission_index = array();
		$group_permissions = $group->getPermissions();
		
		foreach($group_permissions as $p)
			$permission_index[] = $p->id();	
	
		$gRepo = $this->doctrine->em->getRepository('user\models\Group');
		$db_permissions = $gRepo->getAllPermissions();
		$db_perms = array();
		foreach ($db_permissions as $d){
			$db_perms[$d['name']] = $d['perm_id'];
		}
		
		$this->templatedata['db_permissions'] = $db_perms;
		$this->templatedata['all_permissions'] = ModuleManager::permissionArray();
		$this->templatedata['group'] = $group;
		$this->templatedata['group_permissions'] = $permission_index;
        $this->templatedata['page_title'] = 'Group Permissions | '.$group->getName();
		$this->templatedata['maincontent'] = 'user/group/editpermissions';
		$this->load->theme('master',$this->templatedata);
	}
}