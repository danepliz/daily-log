<?php

function report_permissions(){
	return array(	
	
			'add report'		=>	'Add new report generator query.'
			,'report menu'		=>	'Access the permitted reports from mainmenu.'
			,'view report'		=>	'View existing reports as a list.'
			,'edit report'		=> 	'Modify report permission.'
			,'delete report'	=> 	'Delete report.'
			,'view adtl report'=>	'View ADTL report'		
		);

}
$report = new \MainMenuItem();
$report->setId('MM_REPORT');
$report->setName("Reports");
$report->setPermissions(array('report menu', 'add report', 'view report', 'edit report', 'delete report'));
$report->setIcon('fa-file-text');
$report->setRoute(site_url('report'));
\MainMenu::register($report);
































/*
$allReports = CI::$APP->db->get('ys_reports')->result_array();
foreach ($allReports as $k=>$r) {
	if (!report_access($r['id'])) unset($allReports[$k]);
}

$summary = FALSE;
if (! user_access('report menu') or count($allReports) < 1) {
	
	$report = new \MainMenuItem();
	$report->setId('MM_REPORT');
	$report->setName("Reports");
	$report->setPermissions(NULL);
	$report->setRoute(site_url('report'));
	\MainMenu::register($report);
	
// 	$item = new \MainMenuItem();
// 	$item->setId('MM_REPORT_ITEM');
// 	$item->setName('Daily Transaction Summary');
// 	$item->setPermissions(NULL);
// 	$item->setParent($report);
// 	$item->setRoute(site_url('report/dailysummary'));
// 	\MainMenu::register($item);
	
	$summary = TRUE;
}

if (count($allReports) > 0) {
	
	unset($allReports);
	
	if (! $summary) {
		
		$report = new \MainMenuItem();
		$report->setId('MM_REPORT');
		$report->setName("Reports");
		$report->setPermissions(array('report menu'));
		$report->setRoute(site_url('report'));
		\MainMenu::register($report);
		
// 		$item = new \MainMenuItem();
// 		$item->setId('MM_REPORT_ITEM');
// 		$item->setName('Daily Transaction Summary');
// 		$item->setParent($report);
// 		$item->setPermissions(NULL);
// 		$item->setRoute(site_url('report/dailysummary'));
// 		\MainMenu::register($item);
		
		$item = new \MainMenuItem();
		$item->setId('MM_REPORT_DTLR');
		$item->setName('ADTL Report');
		$item->setPermissions(array('view adtl report'));
		$item->setParent($report);
		$item->setRoute(site_url('report/adtl'));
		\MainMenu::register($item);
	}
	
	$reportgroups 	= CI::$APP->db->get('ys_report_groups')->result_array();
	$groups 		= array();
	
	foreach ($reportgroups as $rg) {
		
		$reports = CI::$APP->db->get_where('ys_reports',  array('reportgroup_id' => $rg['id']))->result_array();
		foreach ($reports as $k=>$r) {
			if (!report_access($r['id'])) unset($reports[$k]);
		}
	
		if (count($reports) > 0) {
			
			$group = new \MainMenuItem();
			$group->setId('MM_REPORT_GROUP_' . $rg['id']);
			$group->setName($rg['name']);
			$group->setPermissions(array('report menu'));
			$group->setRoute(current_url().'#');
			$group->setParent($report);
			\MainMenu::register($group);
		
			foreach ($reports as $r) {
				
				$item = new \MainMenuItem();
				$item->setId('MM_REPORT_ITEM_'.$r['id']);
				$item->setName(ucwords($r['name']));
				$item->setPermissions(array('report menu'));
				$item->setParent($group);
				$item->setRoute(site_url('report/result/'.$r['slug']));
				\MainMenu::register($item);
			}
	
		}
	}
	
	$miscReports = CI::$APP->db->query('SELECT `id`, `name`, `slug` FROM (`ys_reports`) WHERE `reportgroup_id` IS NULL')->result_array();
	
	foreach ($miscReports as $k=>$r) {
		if (!report_access($r['id'])) unset($miscReports[$k]);
	}
	
	if (count($miscReports) > 0) {
			
	
// 		$group = new \MainMenuItem();
// 		$group->setId('MM_REPORT_GROUP_MISC');
// 		$group->setName('Miscellaneous');
// 		$group->setPermissions(array('report menu'));
// 		$group->setRoute(current_url().'#');
// 		$group->setParent($report);
// 		\MainMenu::register($group);
	
		foreach ($miscReports as $r) {
	
			$item = new \MainMenuItem();
			$item->setId('MM_REPORT_ITEM_'.$r['id']);
			$item->setName(ucwords($r['name']));
			$item->setPermissions(array('report menu'));
		//	$item->setParent($group);
			$item->setParent($report);
			$item->setRoute(site_url('report/result/'.$r['slug']));
			\MainMenu::register($item);
		}
	
	}
	
}



/*
// the following code maybe necessary if reports are to be shown without grouping 

$reports = CI::$APP->db->get('ys_reports')->result_array();

if (($total = count($reports)) > 0) {
	
	foreach ($reports as $k=>$r) {
		
		if (!report_access($r['id'])) unset($reports[$k]);
		
	}
	
	if (count($reports) > 0) {
		
		$report = new \MainMenuItem();
		$report->setId('MM_REPORT');
		$report->setName("Reports");
		$report->setPermissions(array('report menu'));
		$report->setRoute(current_url().'#');
		\MainMenu::register($report);
		
		foreach ($reports as $r) {
			 
			$viewReport = new \MainMenuItem();
			$viewReport->setId('MM_REPORT_EXEC_'.$r['id']);
			$viewReport->setName(ucwords($r['name']));
			$viewReport->setPermissions(array('report menu'));
			$viewReport->setParent($report);
			$viewReport->setRoute(site_url('report/result/'.$r['slug']));
			\MainMenu::register($viewReport);
		}
	}
}

*/
