<?php 

use user\models\User;

use Yarsha\YQL\YQLExecuter;

use report\models\Report;
use report\models\ReportGroup;
use user\models\Group;


class Report_Controller extends Admin_Controller{
	
	private $columns;
	
	public function __construct(){
		parent::__construct();
        $this->breadcrumb->append_crumb('Reports', site_url('report'));
	}
	
	public function index(){
		redirect('report/viewlist');
	}
	
	public function viewlist($offset = 0){

        if (!user_access(array(
								'view report',
								))
						) redirect();
		
		$repo = $this->doctrine->em->getRepository('report\models\ReportGroup');
		
		$reports= $repo->getReportsList();
		
		$subreports = array();
		
		foreach($reports as $rep_group)
		{
			$subreports[$rep_group['id']] = $this->doctrine->em->getRepository('report\models\Report')->findBy(array('reportgroup'=>$rep_group['id']));
		}
		
		$otherreports=$this->doctrine->em->getRepository('report\models\Report')->findBy(array('reportgroup'=>NULL));

		$this->breadcrumb->append_crumb('List', site_url('report/viewlist'));
		
		$this->templatedata['subreports']=&$subreports ;
		$this->templatedata['otherreports']=&$otherreports;
        $this->templatedata['page_title'] = 'Report List';
		
		$this->templatedata['reports'] = &$reports;
		$this->templatedata['offset'] = $offset;
		$this->templatedata['maincontent'] = 'report/list';
		$this->load->theme('master',$this->templatedata);
	}
	
	public function addreportquery(){
		
		if (!user_access(array(
								'add report',
								))
						) redirect();
		
		$groupRepo = $this->doctrine->em->getRepository('user\models\Group');
		
		if ($this->input->post('gen-report')){
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('sqlquery', 'Report Generator Query', 'required|trim|callback_isValidQuery');
			
			if ($this->form_validation->run($this)){
				
				if ($this->db->simple_query($this->input->post('sqlquery'))){
					
					$this->templatedata['output'] = ($this->db->query($this->input->post('sqlquery'))->num_rows() > 0) ? $this->db->query($this->input->post('sqlquery'))->result_array() : NULL;
					$this->templatedata['validbutempty'] = ($this->db->query($this->input->post('sqlquery'))->num_rows() == 0);
				} 
				
			}
			
		}
		
		
		if ($this->input->post('save-report')){
			
			$this->load->library('form_validation');
	
			$this->form_validation->set_rules('description', 'Report Descriptions', 'required');
			$this->form_validation->set_rules('sqlquery', 'Report Generator Query', 'required|trim|callback_isValidQuery');
			
			if ($this->form_validation->run($this)){
				
				if ($this->db->simple_query($this->input->post('sqlquery'))){
					
						$report = new Report();
						
						$usrgrp = is_array($this->input->post('usrgrp')) ? $this->input->post('usrgrp') : array( 0 => Group::SUPER_ADMIN);
						$query = $this->input->post('sqlquery');
						
						$report->setName(trim($this->input->post('name')));
						$report->setDescr($this->input->post('description'));
						$report->setSqlQuery(str_replace(';', '', $query));
						$report->setUserGroups($usrgrp);
						
						$this->doctrine->em->persist($report);
						$this->doctrine->em->flush();

						if ($report->id()) {

							$this->message->set("Report saved successfully.", 'success', TRUE, 'feedback');
							redirect('report/viewlist');

						}
				
				}
				
			}
			
		}
		
		$this->breadcrumb->append_crumb('Configuration', site_url('config'));
		$this->breadcrumb->append_crumb('Reports',site_url('report/viewlist'));
		$this->breadcrumb->append_crumb('Add Report', current_url());
		
		$this->templatedata['groups'] = &$groupRepo->getGroupList();
		$this->templatedata['maincontent'] = 'report/addquery';
		$this->load->theme('master',$this->templatedata);
		
	}
	
	public function editor(){
	
		$groupRepo = $this->doctrine->em->getRepository('user\models\Group');
		$reportRepo = $this->doctrine->em->getRepository('report\models\Report');
		$reportGrpRepo = $this->doctrine->em->getRepository('report\models\ReportGroup');
	
		if($this->input->post('gen-report')) {
			$this->form_validation->set_rules('sqlquery', 'Report Generator Query', 'required|trim|callback_isValidQuery');
			if ($this->form_validation->run($this)) $this->render(NULL, 'col');
		}
	
		if ($this->input->post('save-report')){
	
			$this->form_validation->set_rules('sqlquery', 'Report Generator Query', 'required|trim|callback_isValidQuery');
			$this->form_validation->set_rules('name', 'Report Name', 'required');
			$this->form_validation->set_rules('description', 'Report Descriptions', 'required');
	
			if ($this->form_validation->run($this)){
	
				$report = new Report();
				
				if (($newGroup = $this->input->post('reportgroup')) != '') {
						
					$exist = $reportGrpRepo->findOneByName($newGroup);
					if (! $exist) {
						$r_group = new ReportGroup($newGroup);
						$this->doctrine->em->persist($r_group);
						$this->doctrine->em->flush();
						if ($r_group->id()) {
							$report->setGroup($r_group);
						}
					} else $report->setGroup($exist);
						
				} else if ( is_numeric($r_gID = $this->input->post('reportgroup_id')) ) {
					$report->setGroup($this->doctrine->em->find('report\models\ReportGroup', $r_gID));
				}
	
				$usrgrp = is_array($this->input->post('usrgrp')) ? $this->input->post('usrgrp') : array( 0 => Group::SUPER_ADMIN);
				$query = $this->input->post('sqlquery');
	
				$report->setName(trim($this->input->post('name')));
				$report->setDescr($this->input->post('description'));
				$report->setSqlQuery(str_replace(';', '', $query));
				$report->setUserGroups($usrgrp);
	
				$this->doctrine->em->persist($report);
				$this->doctrine->em->flush();
	
				if ($report->id()) {
	
					$this->message->set("Report saved successfully.", 'success', TRUE, 'feedback');
					redirect('report/viewlist');
	
				}
			}
		}

        $this->templatedata['page_title'] = 'Add Report';
		$this->templatedata['reportgroups'] = & $reportRepo->getReportGroups();
		$this->templatedata['groups'] = &$groupRepo->getGroupList();
		$this->templatedata['maincontent'] = 'report/editor';
		$this->load->theme('master',$this->templatedata);
	}
	
	public function edit($slug){
		
				
		if (!user_access(array(
								'edit report',
								))
						) redirect();
		
		
		$reportRepo = $this->doctrine->em->getRepository('report\models\Report');
		$report = $reportRepo->findOneBy(array(
										'slug' => $slug,
									));
		 
		if (!$report or !report_access($report->id())) redirect();
		
		$this->templatedata['output'] = ($this->db->query($report->getSqlQuery())->num_rows() > 0) ? $this->db->query($report->getSqlQuery())->result_array() : NULL;
		$this->templatedata['validbutempty'] = ($this->db->query($report->getSqlQuery())->num_rows() == 0);
		$groupRepo = $this->doctrine->em->getRepository('user\models\Group');
		
		$this->templatedata['newoutput'] = FALSE;
		$this->templatedata['editmode'] = FALSE;
		
		if ($this->input->post('gen-report')){
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('sqlquery', 'Report Generator Query', 'required|trim|callback_isValidQuery');
			
			if ($this->form_validation->run($this)){
				
				if ($this->db->simple_query($this->input->post('sqlquery'))){
					
					$this->templatedata['output'] = ($this->db->query($this->input->post('sqlquery'))->num_rows() > 0) ? $this->db->query($this->input->post('sqlquery'))->result_array() : NULL;
					$this->templatedata['newoutput'] = TRUE;
					$this->templatedata['validbutempty'] = ($this->db->query($this->input->post('sqlquery'))->num_rows() == 0);
				} 
				
			} else $this->templatedata['editmode'] = TRUE;
			
		}
		
		if ($this->input->post('save-report')){
			
			$this->load->library('form_validation');
	
			$this->form_validation->set_rules('description', 'Report Descriptions', 'required');
			$this->form_validation->set_rules('sqlquery', 'Report Generator Query', 'required|trim|callback_isValidQuery[edit]');
			
			if ($this->form_validation->run($this)){
				
				if ($this->db->simple_query($this->input->post('sqlquery'))){
						
						$usrgrp = is_array($this->input->post('usrgrp')) ? $this->input->post('usrgrp') : array( 0 => Group::SUPER_ADMIN);
						$query = $this->input->post('sqlquery');
						
						$report->setName(trim($this->input->post('name')));
						$report->setDescr($this->input->post('description'));
						$report->setSqlQuery(str_replace(';', '', $query));
						$report->setUserGroups($usrgrp);
						
						$this->doctrine->em->persist($report);
						$this->doctrine->em->flush();

						$this->message->set("Report saved successfully.", 'success', TRUE, 'feedback');
						redirect('report/viewlist');
					
					}
				
			}
			
		}
		
		$this->breadcrumb->append_crumb('Configuration', site_url('config'));
		$this->breadcrumb->append_crumb('Reports',site_url('report/viewlist'));
		$this->breadcrumb->append_crumb('Edit Report', current_url());
		
		$this->templatedata['report'] = &$report;
		$this->templatedata['groups'] = &$groupRepo->getGroupList();
		$this->templatedata['maincontent'] = 'report/editreport';
		$this->load->theme('master',$this->templatedata);
	
	}
	
	public function change($slug = NULL) {
		
		$groupRepo = $this->doctrine->em->getRepository('user\models\Group');
		
		if (!$slug) redirect();
		
		$reportRepo = $this->doctrine->em->getRepository('report\models\Report');
		$reportGrpRepo = $this->doctrine->em->getRepository('report\models\ReportGroup');
		$report = $reportRepo->findOneBy(array(
				'slug' => $slug,
		));
		
		if (!$report or !report_access($report->id())) redirect();
		
		$this->templatedata['editmode'] = FALSE;
		
		if($this->input->post('gen-report')) { 
			$this->form_validation->set_rules('sqlquery', 'Report Generator Query', 'required|trim|callback_isValidQuery[' .$report->id() .']');
			if ($this->form_validation->run($this)) $this->render(NULL, 'col');
			$this->templatedata['editmode'] = TRUE;
		}
		
		if ($this->input->post('save-report')){
			
			$this->form_validation->set_rules('sqlquery', 'Report Generator Query', 'required|trim|callback_isValidQuery[' .$report->id() .']');
			$this->form_validation->set_rules('name', 'Report Name', 'required');
			$this->form_validation->set_rules('description', 'Report Descriptions', 'required');
				
			if ($this->form_validation->run($this)){
				
				if (($newGroup = $this->input->post('reportgroup')) != '') {
					
					$exist = $reportGrpRepo->findOneByName($newGroup);
					if (! $exist) {
						$r_group = new ReportGroup($newGroup);
						$this->doctrine->em->persist($r_group);
						$this->doctrine->em->flush();
						if ($r_group->id()) {
							$report->setGroup($r_group);
						}
					} else $report->setGroup($exist);	
					
				} else if ( is_numeric($r_gID = $this->input->post('reportgroup_id')) ) {
					$report->setGroup($this->doctrine->em->find('report\models\ReportGroup', $r_gID));
				}
				
				$usrgrp = is_array($this->input->post('usrgrp')) ? $this->input->post('usrgrp') : array( 0 => Group::SUPER_ADMIN);
				$query = $this->input->post('sqlquery');
					
				$report->setName(trim($this->input->post('name')));
				$report->setDescr($this->input->post('description'));
				$report->setSqlQuery(str_replace(';', '', $query));
				$report->setUserGroups($usrgrp);
					
				$this->doctrine->em->persist($report);
				$this->doctrine->em->flush();
					
				if ($report->id()) {
						
					$this->message->set("Report saved successfully.", 'success', TRUE, 'feedback');
					redirect('report/viewlist');
						
				}
			}	
		}

		$this->breadcrumb->append_crumb('Modify: '.$report->getName(), current_url());
		
		$this->templatedata['report'] = & $report;
		$this->templatedata['reportgroups'] = & $reportRepo->getReportGroups();
		$this->templatedata['groups'] = & $groupRepo->getGroupList();
		$this->templatedata['maincontent'] = 'report/modify';
		$this->load->theme('master',$this->templatedata);
		
	} 
	
	public function execute($slug, $offset = 0){
		
		$perpage = 15;
		
		$reportRepo = $this->doctrine->em->getRepository('report\models\Report');
		$report = $reportRepo->findOneBy(array(
										'slug' => $slug,
									));
		 
		if (!$report or !report_access($report->id())) redirect();
				
		if ($this->input->post('save-report')) {
			
			$usrgrp = is_array($this->input->post('usrgrp')) ? $this->input->post('usrgrp') : array();
			$report->setUserGroups($usrgrp);
			
			$this->doctrine->em->persist($report);
			$this->doctrine->em->flush();

			$this->message->set("Report Premission saved successfully.", 'success', TRUE, 'feedback');
			redirect('report/viewlist');

		}
		
		$total = $this->db->query($report->getSqlQuery())->num_rows();
		
		if ($total > 0) {
			
			if ($total > $perpage) {
				
				$this->load->library('pagination');
					
				$config['base_url'] = base_url()."report/execute/".$slug;
				$config['total_rows'] = $total;
				$config['per_page'] = $perpage;
				$config['uri_segment'] = 4;
				$config['prev_link'] = 'Previous';
				$config['next_link'] = 'Next';
					
				$this->pagination->initialize($config);
				$this->templatedata['pagination'] = $this->pagination->create_links();
	
				$output = $this->db->query($report->getSqlQuery(). ' LIMIT '. $offset . ' , ' . $perpage)->result_array();
				
			}  else $output = $this->db->query($report->getSqlQuery())->result_array();
		
		} else {
			$output = NULL;
		} 

		$this->breadcrumb->append_crumb($report->getName(), current_url());
		
		
		$this->templatedata['report'] = &$report;
		$this->templatedata['output'] = &$output;
		$this->templatedata['maincontent'] = 'report/exec';
		$this->load->theme('master',$this->templatedata);

		
	}
	
	public function result($slug = NULL) {
		
		
		if (!$slug) redirect();
		
		$reportRepo = $this->doctrine->em->getRepository('report\models\Report');
		$report = $reportRepo->findOneBy(array(
				'slug' => $slug,
		));
			
		if (!$report or !report_access($report->id())) redirect();
			
		$this->render($report->getSqlQuery(), 'col');
		//die($report->getSqlQuery());
		
		$this->templatedata['report'] = &$report;

		$this->breadcrumb->append_crumb('Preview: '.$report->getName(), current_url());
		
		$this->templatedata['maincontent'] = 'report/exec-tql';
		$this->load->theme('master',$this->templatedata);
		
	} 
	
	public function delete($slug){

		if (!user_access(array(
								'delete report',
								))
						) redirect();
		
		$reportRepo = $this->doctrine->em->getRepository('report\models\Report');
		$report = $reportRepo->findOneBy(array(
										'slug' => $slug,
									));
		 
		if (!$report or !report_access($report->id())) redirect();
		
		$this->doctrine->em->remove($report);
		$this->doctrine->em->flush();
		
		$this->message->set("Report Deleted successfully.", 'success', TRUE, 'feedback');
		redirect('report/viewlist');
		
	}
	
	public function exportxls($slug){
		
		$reportRepo = $this->doctrine->em->getRepository('report\models\Report');
		$report = $reportRepo->findOneBy(array(
										'slug' => $slug,
									));
		 
		if (!$report or !report_access($report->id())) redirect();
				
		$name = str_replace(array('"', "'", ',', ' '), '_',$report->getName().date(' Y m d H i'));
		
		$this->output->set_header('Content-type: application/octet-stream');
		$this->output->set_header("Content-Disposition: attachment; filename=$name.xls");
		$this->output->set_header("Pragma: no-cache");
		$this->output->set_header("Expires: 0");
		
		$data['report'] = &$report;
		$data['output'] = &$this->db->query($report->getSqlQuery())->result_array();
		
		$this->load->view('admin/reportxls',$data);
	}
	
		
	public function dumpxls($slug){
	
		$reportRepo = $this->doctrine->em->getRepository('report\models\Report');
		$report = $reportRepo->findOneBy(array(
				'slug' => $slug,
		));

		if (!$report or !report_access($report->id())) redirect();
	
		$name = str_replace(array('"', "'", ',', ' '), '_', $report->getName().date(' Y m d H i'));
	
		$this->output->set_header('Content-type: application/octet-stream');
		$this->output->set_header("Content-Disposition: attachment; filename=$name.xls");
		$this->output->set_header("Pragma: no-cache");
		$this->output->set_header("Expires: 0");
	
		$this->render($report->getSqlQuery(), 'col', TRUE);
		
		$this->templatedata['report'] = &$report;
	
		$this->load->view('admin/dumpxls', $this->templatedata);
	
	}
	
	private function generateGrandTotal($result, $display=''){
		
		$html = '';
	
		if(isset($result['aggregates'])){
			
			$agg = $result['aggregates'];
			$html .="<h3>Grand Total</h3>";
			$html .= "<table border='0' width='100%' cellpadding='0' cellspacing='0' class='grand-total'>";
			
			if ($display == 'col') {
				
				$html .= "<tr>";
				foreach ($agg as $col => $val) $html .= "<th>{$col}</th>";
				$html .= "</tr>";
				
				$html .= "<tr class='aggregate'>";
				foreach ($agg as $col => $val)
				{
					$sum = $val['SUM'];
					$aggregateSum = ( $sum == floor($sum) )? $sum : number_format($sum, 2, '.', ',');
					$html .= "<td align='center' title='Grand Total'>". $aggregateSum ."</td>";
				}
				$html .= "</tr>";
			} 
			
			if ($display == 'row' ) {
				
				foreach ($agg as $col => $val)
				{
					$sum = $val['SUM'];
					$aggregateSum = ( $sum == floor($sum) )? $sum : number_format($sum, 2, '.', ',');
					$html .= "<tr class='aggregate'><th>{$col}</th><td align='center' title='Grand Total'>". $aggregateSum ."</td></tr>";
				}
				
			}
			
			if ($display == '')	{
				
			  $html .= "<tr class='aggregate'>";
				foreach($this->columns as $c){
					if(isset($agg[$c])){
						$sum = $agg[$c]['SUM'];
						$aggregateSum = ( $sum == floor($sum) )? $sum : number_format($sum, 2, '.', ',');
						$html .= "<td title='Grand Total'>". $aggregateSum . "</td>";
					}else{
						$html .= "<td>&nbsp;</td>";
					}
				}
				$html .= "</tr>";
			
			}	
			
			$html .= "</table>";
		}
		
		return $html;
	}
	
	private function generateTable($table,$head){
	
		$txns = $table['transactions'];
	
		$first = $txns[0];
		$columns = array_keys($first);
		$this->columns = $columns;
		$html = '';
	
		$html .="<div class='col-md-12 margin text-right'>{$head} {$table['rowCount']} Record(s)</div>";
		$html .= "<table class='table genreport'>";
		$html .= "<tr>";
		
		foreach($columns as $c){
			$html .= "<th>{$c}</th>";
		}
		$html .= "</tr>";
		
		foreach($txns as $tx){
			
			$html .= "<tr>";
				
			foreach($columns as $c){
				$html .= "<td>{$tx[$c]}</td>";
			}
				
			$html .= "</tr>";
		}
	
		if(isset($table['aggregates'])){
			
			$agg = $table['aggregates'];
			$html .= "<tr class='aggregate'>";
			
			foreach($columns as $c){
				if(isset($agg[$c])){
					$sum = $agg[$c]['SUM'];
					$aggregateSum = ( $sum == floor($sum) )? $sum : number_format($sum, 2, '.', ',');
					$html .= "<td title='Sub Total'>". $aggregateSum . "</td>";
				}else{
					$html .= "<td>&nbsp;</td>";
				}
			}
			
			$html .= "</tr>";
		}
	
		$html .= "</table>";
	
		return $html;
	}
	
	public function render($tql = NULL, $display = '', $xls = FALSE) {
		if (! $tql)
			if ($this->input->post('sqlquery')) $tql = $this->input->post('sqlquery');
		if (! $tql) redirect();
		
			
		$exec = new YQLExecuter($tql, $this->db);
		$result = $exec->getResult();


			
		$output = '';
		$filterhtml = '';
		$hasResult = TRUE;
		$filters = $exec->getResultFilters();
		
		if(count($filters) > 0) {
			
			$checks = array();
			
			$agentFilters = 0;
			foreach($filters as $k => $f){ 
				
				$check = $f->name.' '.$f->getFilterLabel();
				
				if ($f->name == 'or' or array_search($check, $checks) !== FALSE) {
					unset($filters[$k]);
					continue;
				}
				
				$checks[] = $check;
			}
			
			$filterDisplay = ( $xls )? 'style="display:none"' : '';

            $output .= '<div class="col-md-12 compact filter-area noprint" '.$filterDisplay.'>';

//            $output .= '<h3>Filter Report</h3>';
            $output .= '<div class="row">';





//			$output .= '<table border="0" cellspacing="0" cellpadding="0" class="compact filter-area noprint" '.$filterDisplay.'><tbody>';
			
//			$output .= '<tr class="noprint" ><th align="left" style="background:#CCC !important; color:#333" '.$filterDisplay.' > Filter Report </th></tr><tr><td id="report-filter-elements">
//				<style>
//					#report-filter-elements input, #report-filter-elements select{min-width:200px !important; color:#333;}
//					#report-filter-elements span{min-width:200px !important; font-weight:normal; display:block; margin-bottom:5px;}
//				</style>';
			
			
			$filterValues = "";
				
			$filterhtml .= '<div class="filters" style="display: none;">';

			foreach($filters as $f){
				if (! $xls) 
				{
					$output .= $f->getFilterElement();
				} 
				else 
				{
					if ($f->name == 'agent' and is_numeric($f->subagent)) 
					{
						$output .= $this->doctrine->em->find('agent\models\Agent', $f->subagent)->getName();
					}
				}
				
				$filterValues .= $f->getFilterValue();
			}

			$filterhtml .= '</div>';
			
//			$output .= '</td></tr></tbody></table>';

            $style = ( !$xls )? 'printOnly' : '';
            $valueDisplay = ( !$xls )? 'style="display:none"' : '';
            if (! $xls)
                $output	.=	'<div class="col-md-12 inner-placeholder noprint" style="margin: 5px 0">
								<input type="submit" value="Regenerate Report" class="btn btn-primary" id="submit-filter" name="gen-report" />
						</div>';

            $output .= '<div class="col-md-12 '.$style.'" '.$valueDisplay.' >'.$filterValues.'</div>';

            $output .= '</div>'; //row
            $output .= '</div>';
			

			

			

		}
		
// 		log_message('info', $filterValues);
			
		if($result['rowCount'] > 0){
			if($result['tablized']){
				foreach($result['result'] as $t => $d){
						$output .= $this->generateTable($d,$t);
				}
			}else{
				$output .= $this->generateTable($result,'Total ');
			}
			
			//$output .= "<div class='clear'></div><div class='col-md-12 margin text-right'>Total Records: ".$result['rowCount']."</div>";//'<p class="element" style="background:#e5e5e5"><strong>Total Records : </strong>'..'</p>';
	
			$output .= $this->generateGrandTotal($result, $display);
			
		}else{
			$output .= '<p style="margin: 5px 0; padding: 5px;">No matching results found. You can change the filters above (if any).</p>';
			$hasResult = FALSE;
		}
			
		$this->templatedata['query_result'] = $output;
		$this->templatedata['filter'] = $filterhtml;
		$this->templatedata['xls'] = $xls;
		$this->templatedata['hasResult'] = $hasResult;
	}
	
	public function isValidQuery($query, $rid=NULL){
	
		$repo = $this->doctrine->em->getRepository('report\models\Report');
	
		$report = $repo->findOneBy(array('sqlquery'=>str_replace(';', '', $query)));
	
		if (isset($rid)) {
				
			if ($report and $report->id() != $rid) {
				$this->form_validation->set_message('isValidQuery', 'This Report Generator Query already exists. <br/>');
				return FALSE;
			}
		} else {
			if ($report) {
				$this->form_validation->set_message('isValidQuery', 'This Report Generator Query already exists. <br/>');
				return FALSE;
			}
		}
	
		if (!preg_match("/^select (.*)/i", trim($query)) > 0) {
			$this->form_validation->set_message('isValidQuery', 'Report Generator Query must contain valid SELECT statements only. <br/>');
			return FALSE;
		}
			
		$prep_query = str_replace(array("\n", "\r\n", "\r"), ' ', $query);
		$prep_query = preg_replace('!\s+!', ' ', $prep_query);
	
		if ((strtolower(substr($prep_query, 0, 8))=='select *') or strstr($prep_query,'.*')!==FALSE) {
			$this->form_validation->set_message('isValidQuery', 'Report Generator Query may not contain WILD selector [ * ]. <br/>');
			return FALSE;
		}
	
		$delimeter_position = strpos(trim($prep_query), ';');
	
		if ($delimeter_position===FALSE) {
		} else {
				
			if ($delimeter_position+1 != strlen(trim($prep_query))) {
	
				$this->form_validation->set_message('isValidQuery', 'Report Generator Query may not contain MULTIPLE statements delimited by semicolon ( ; ). <br/>');
				return FALSE;
			}
		}
	
		return TRUE;
	}

	public function dailysummary($date = NULL){
		
		if (!$date) $date = date('Y-m-d');
		
		$level = \Current_User::user()->getLevel();
		$arepo = $this->doctrine->em->getRepository('models\Agent');
		$trepo = $this->doctrine->em->getRepository('models\Transaction');
		
		$agents = $arepo->getAgentsBasedOnUserLevel('ALL');
		$transactions = $agentsOL = $filters = array();

		$fromdate = $todate = date('Y-m-d');
		$requestAgent = $label = '';
		
		if ($this->input->get('do') == 'xls') {
			$fromdate = $this->input->get('fromdate');
			$todate = $this->input->get('todate');
			$requestAgent = $this->input->get('agent');
		} else {
			
			if ($this->input->post('fromdate')) 
				$fromdate = $this->input->post('fromdate');
			if ($this->input->post('todate'))
				$todate = $this->input->post('todate');
			if ($this->input->post('agent'))
				$requestAgent = $this->input->post('agent');
			
		}
		
		$filters['fromdate'] = $fromdate . ' 00:00:00';
		$filters['todate'] = $todate . ' 23:59:59';
		
		
		foreach ($agents as $agent) {
			$agentID = $agent['agent_id'];
			$agentsOL[$agentID] = $agent;
			$pagentID = $agent['parentAgent_id'] ?: $agentID;
				
			if ($level == User::USERLEVEL_SA) {
				if ($agentID == $pagentID) continue;
			} else if ($requestAgent) {
				if ($level == User::USERLEVEL_PA) {
					if ($requestAgent != $agentID) continue;
				}
				else if ($requestAgent != $pagentID) continue;
			}
				
			$transaction = & $trepo->getTransactionsForSummary($agentID, $filters);
			if (empty($transaction)) continue;
			if (! isset($transactions[$pagentID])) $transactions[$pagentID] = array();
			
			$transactions[$pagentID][$agentID] = $transaction;
		}
		
		unset($agents);
	# label string	
		$label =($level == User::USERLEVEL_PA) ? 'Sub Agents' : 'Principal Agents';
		if ($requestAgent) $label = $agentsOL[$requestAgent]['name']; 
			
		if ($level == User::USERLEVEL_SA) {
			$agentID = \Current_user::getAgents()->agent_id;
			$label = $agentsOL[$agentID]['name'];
		}
		
		$label .= (substr($fromdate, 0, 10) == substr($todate, 0, 10))
			? ' as on ' . substr($fromdate, 0, 10) : ' from ' . substr($fromdate, 0, 10) . ' to ' . substr($todate, 0, 10);
	
		$this->breadcrumb->append_crumb('Summary Report', current_url());
		
		$this->templatedata['agents'] = & $agentsOL;
		$this->templatedata['level'] = $level;
		$this->templatedata['label'] = $label;
		$this->templatedata['transactions'] = & $transactions;
		
		if ($this->input->get('do') == 'xls') {
			$name = str_replace(array('"', "'", ',', ' '), '_', $label);
			
			$this->output->set_header('Content-type: application/octet-stream');
			$this->output->set_header("Content-Disposition: attachment; filename=$name.xls");
			$this->output->set_header("Pragma: no-cache");
			$this->output->set_header("Expires: 0");
			
			$this->load->view(($level == User::USERLEVEL_PA) ? "admin/txnreportxlsPA" : "admin/txnreportxls", $this->templatedata);	
			return;
		}
		$this->templatedata['maincontent'] = ($level == User::USERLEVEL_PA) ? "report/summaryPA" : "report/summary";
		$this->load->theme('master', $this->templatedata);
	}
	
	public function adtl()
	{	
		if(!user_access('view adtl report')) redirect();
		$filters = array();	

		if( $this->input->post() ) $filters = $this->input->post(); 
		
		if (!user_access(array('view report',))) redirect();
		
		$this->load->helper('country/country');
		
		$countries  = getOperatingCountries();
		
		$repo = $this->doctrine->em->getRepository('report\models\ReportGroup');
		$reports= $repo->getAdtlReport($filters);
		
		$country_name = ( isset($filters['country']) and is_numeric($filters['country']) ) ? $this->doctrine->em->find('location\models\country', $filters['country'])->getName() : 'ANY';
		if( isset($filters['state']) and is_array($filters['state']) ){
			foreach ($filters['state'] as $k => $v){
				$state_name[] = $this->doctrine->em->find('location\models\state', $v)->getShortName();
			}
		}
		else{
			$state_name[] = 'ANY';
		}
		
		$this->breadcrumb->append_crumb('Preview: ADTL List', site_url('report/adtl'));
		
		$this->templatedata['reports'] = &$reports;
		$this->templatedata['filters'] = $filters;
		$this->templatedata['countries'] = & $countries;
		$this->templatedata['state_name'] = & $state_name;
		$this->templatedata['country_name'] = & $country_name;
		$this->templatedata['maincontent'] = 'report/adtl';
		
		$this->load->theme('master',$this->templatedata);
		
	}

	public function dumpPdf($slug)
	{
		$reportRepo = $this->doctrine->em->getRepository('report\models\Report');
		$report = $reportRepo->findOneBy(array(
				'slug' => $slug,
		));
			
		if (!$report or !report_access($report->id())) redirect();
		
		$name = str_replace(array('"', "'", ',', ' '), '_', $report->getName().date(' Y m d H i'));
		
		$this->load->library('Dompdf_gen');
		
		$this->render($report->getSqlQuery(), 'col', TRUE);
		
		$this->templatedata['report'] = $report;
		
		$this->load->view('admin/dumpxls', $this->templatedata);
		
		$html = $this->output->get_output();
			
		$this->dompdf->load_html($html);
		
		$this->dompdf->render();
		
		$this->dompdf->stream($name.".pdf");	
	}
	
	public function exportADTL( $arg = null){
	
		if(!user_access('view adtl report')) redirect();
	
		$filters = array();
		$country_name = 'ANY';
		$state_name = array();
		if($this->input->post())
		{
			$filters = $this->input->post();
			$country_name = ( isset($filters['country']) and is_numeric($filters['country']) ) ? $this->doctrine->em->find('location\models\country', $filters['country'])->getName() : 'ANY';
			
			if( isset($filters['state']) and is_array($filters['state']) ){
				foreach ($filters['state'] as $k => $v){
					$state_name[] = $this->doctrine->em->find('location\models\state', $v)->getShortName();
				}
			}
			else{
				$state_name[] = 'ANY';
			}
		}
	
	
		$reportRepo = $this->doctrine->em->getRepository('report\models\Report');
		$report = $reportRepo->getAdtlReport($filters);
	
		$name = str_replace(array('"', "'", ',', ' '), '_','ADTL Report'.date(' Y m d H i'));
	
	
		$this->templatedata['reports'] = &$report;
		$this->templatedata['filters'] = &$filters;
		$this->templatedata['country_name'] = $country_name;
		$this->templatedata['state_name'] = $state_name;
	
		if(!is_null($arg) && $arg == 'pdf' ){
			$this->load->library('Dompdf_gen');
				
			$this->load->view('admin/adtlreportxls', $this->templatedata);
			$html = $this->output->get_output();
	
			$this->dompdf->load_html($html);
				
			$this->dompdf->render();
				
			$this->dompdf->stream($name.".pdf");
			return ;
		}
	
		
		$this->output->set_header('Content-type: application/octet-stream');
		$this->output->set_header("Content-Disposition: attachment; filename=$name.xls");
		$this->output->set_header("Pragma: no-cache");
		$this->output->set_header("Expires: 0");
		
		$this->load->view('admin/adtlreportxls',$this->templatedata);
	
	}
	
	
	
}

