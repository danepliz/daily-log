<?php

function ui_icon($type){
	$map = array(	'edit'		=>	'ui-icon-pencil',
					'add'		=>	'ui-icon-plusthick',
					'delete'	=>	'ui-icon-trash',
					'view'		=>	'ui-icon-arrowthick-1-e'
				);
	
	$icon = $map[$type];
	
	echo '<span class="ui-icon '.$icon.'"></span>';
}

function action_button($type,$link,$attr = array()){
	$map = array(
                    'list'          =>  'fa-bars',
                    'edit'			=>	'fa-pencil-square',
					'add'			=>	'fa-plus-square',
					'delete'		=>	'fa-minus-square',
					'view'			=>	'fa-external-link-square',
					'permissions'	=>	'fa-wrench',
					'copy'			=>	'fa-copy',
					'block'			=>	'fa-lock',
					'unblock'		=>  'fa-unlock',
					'doc'			=>  'fa-file-text',
					'minimize'		=>	'fa-minus-square',
					'search'		=>  'fa-search',
					'close' 		=>  'fa-times-square',
					'ledger'        =>  'fa-book',
					'download'		=>  'fa-download',
                    'help'          =>  'fa-question-circle',
                    'rate'          =>  'fa-dollar',
                    'ticked'        => 'fa-check-circle',
                    'email'         => 'fa-inbox',
                    'print'         => 'fa-print',
                    'generate'      => 'fa-external-link',
                    'switch'        => 'fa-exchange',
                    'undelete'      => 'fa-undo',
                     'permittedUser' => 'fa fa-check-square'
			);
	
	$icon = isset($map[$type]) ? $map[$type] : 'fa-'.$type;

    $link = ( $link !== '#' && $link !== "" )? site_url($link) : current_url().'#';
	
	//build attributes
	$attributes = '';
	$class = '';

	if(is_array($attr)){
		foreach($attr as $key => $value){
			if(strtolower($key) != 'class')
				$attributes .= $key.'="'.$value.'" ';
			else
				$class .= ' '.$value;
		}
	}
    return '<a href="'.$link.'" '.$attributes.' class="action-icon '.$class.'"><i class="fa '.$icon.'"></i></a>';

}

function no_results_found($message = ""){
    $message = ( $message == "" )? 'No Records Found' : $message;

    echo '<div class="alert alert-danger">'.$message.'</div>';
}

function inputWrapper($name, $label, $value = NULL, $attributes = NULL, $wrapperClass = ''){
    $out =  '<div class="form-group-sm '.$wrapperClass.'">';
    $out .= '<label for="'.$name.'">'.$label.'</label>';
    $out .= '<input type="text" name="'.$name.'" value="'.$value.'" '.$attributes.' />';
    $out .= '</div>';

    return  $out;
}

function textAreaWrapper($name, $label, $value = NULL, $attributes = NULL, $wrapperClass = ''){
    $out =  '<div class="form-group-sm '.$wrapperClass.'">';
    $out .= '<label for="'.$name.'">'.$label.'</label>';
    $out .= '<textarea name="'.$name.'"  '.$attributes.' cols="20" >'.$value.'</textarea>';
    $out .= '</div>';

    return  $out;
}

function getStatusActionWrapper($objID, $status, $remoteUrl = ""){

    $statusDesc = 'Disabled';
    $statusBtnClass = 'bg-maroon';
    $action = ($remoteUrl == "")? '' : 'onClick="Yarsha.toggleStatus(this, \''.$remoteUrl.'\')"';

    if( $status ){
        $statusDesc = 'Enabled';
        $statusBtnClass = 'bg-olive';
    }

    return  '<span data-object-id="'.$objID.'" class="status-desc '.$statusBtnClass.'" '.$action.' >'.$statusDesc.'</span>';
}

function clearDiv($class = ''){
    return '<div class="clear '.$class.'"></div>';
}

function checkBoxWrapper($name, $label, $value = NULL, $attributes = NULL, $checked = FALSE, $wrapperClass = ''){

    $checkedAttr = ( $checked )? 'checked="checked"' : '';
    $out =  '<div class="form-group-sm checkbox'.$wrapperClass.'" style="margin-top:2rem">';
    $out .= '<label>';
    $out .= '<input type="checkbox" name="'.$name.'" '.$attributes.' value="'.$value.'" '.$checkedAttr.' class="simple">';
    $out .= $label.'</label>';
    $out .= '</div>';

    return  $out;
}

function selectElementWrapper($elem, $label, $labelFor = '', $class=''){
    $out = '<div class="form-group-sm '.$class.'">';
    $out .= '<label for="'.$labelFor.'">'.$label.'</label>';
    $out .= $elem;
    $out .= '</div>';
    return $out;
}

function getMonthDropDown($name,$selected=NULL,$attributes="")
{
    $month = '<select name="'.$name.'" '.$attributes.'>';

    $months = array(
        1 => 'january',
        2 => 'february',
        3 => 'march',
        4 => 'april',
        5 => 'may',
        6 => 'june',
        7 => 'july',
        8 => 'august',
        9 => 'september',
        10 => 'october',
        11 => 'november',
        12 => 'december');

    echo form_dropdown($name,$months,$selected,$attributes);
}

function getDayDropDown($name, $selected=NULL,$attributes="")
{
    $days = array(
        1  => 1,
        2  => 2,
        3  => 3,
        4  => 4,
        5  => 5,
        6  => 6,
        7  => 7,
        8  => 8,
        9  => 9,
        10 => 10,
        11 => 11,
        12 => 12,
        13 => 13,
        14 => 14,
        15 => 15,
        16 => 16,
        17 => 17,
        18 => 18,
        19 => 19,
        20 => 20,
        21 => 21,
        22 => 22,
        23 => 23,
        24 => 24,
        25 => 25,
        26 => 26,
        27 => 27,
        28 => 28,
        29 => 29,
        30 => 30,
        31 => 31,
        32 => 32);

    echo form_dropdown($name,$days,$selected,$attributes);

}


 function getTabsTemplate($templateArray= [],$currentTab=''){

     if( $currentTab == '' or !array_key_exists($currentTab, $templateArray) ){
         if( count($templateArray) ){
             foreach($templateArray as $k => $v){
                 $currentTab = $k;
                 break;
             }
         }
     }

//     $currentTab = ( isset($_GET['t']) and array_key_exists($_GET['t'], $templateArray))? $_GET['t'] : 'details';

     $out = '<div class="col-md-12">';
     if(count($templateArray)){
         $tabLinks = '';
         $tabContents = '';

         $count = 1;
         foreach($templateArray as $key => $template){

             $activeClass = ($currentTab == $key)? 'class="active"' : '';
             $active = ($currentTab == $key)? 'active' : '';
             if( isset($template['type']) and $template['type'] == 'link' ){
                 $tabLinks .= '<li class="tab-external-link"><a href="'.site_url($template['link']).'">'.$template['label'].'</a></li>';
             }else{
                 $tabLinks .= '<li role="presentation" '.$activeClass.'><a href="#'.$key.'" class="tabbed" rel="'.$key.'" data-toggle="tab">'.$template['label'].'</a></li>';
                 $tabContents .= '<div class="tab-pane" id="'.$key.'">';
                 $tabContents .= \CI::$APP->load->theme($template['template'], $template['data'], true);
                 $tabContents .= '</div>';
             }


             $count++;
         }


         $out .= '<ul class="nav nav-tabs">';
         $out .= $tabLinks;
         $out .= '</ul>';
         $out .= '<div class="tab-content">';
         $out .= '<div class="row">';
         $out .= $tabContents;
         $out .= '</div>';
         $out .= '</div>';

         $out .= "<script type='text/javascript'>
                    $(document).ready(function(){

                        /* TABS SCRIPT */
                        $('.tab-pane').not('#'+$('ul.nav-tabs li.active').children('a').attr('rel')).hide();
                        $('a.tabbed').click(function(){
                            $('ul.nav-tabs li').removeClass('active');
                            $(this).parent('li').addClass('active');
                            var chk=$(this).attr('rel');
                            $('div.tab-pane').hide();
                            $('div#'+chk).show();
                        });
                        /* TABS SCRIPT ENDS */
                    });
                </script>";

     }
     $out .= '</div>';

     return $out;





 }



function alertBox($message = "", $type = "success", $dismiss = FALSE){
    $out = '<div class="alert alert-'.$type.'" style="margin:1em" >';
    if( $dismiss ){
        $out .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
    }
    $out .= $message;
    $out .= '</div>';

    return $out;
}

/**
 * @array $buttons ['type'=>'type of button', 'link' => 'link','others' =>'string']
 */
function actionWrapper($buttons){

    $output = '<div class="col-xs-12 action-wrapper">';

    if(count($buttons)){
        foreach($buttons as $b){

            $link = ( isset($b['link']) and $b['link'] != '' )? $b['link'] : '#';

            $others = ( isset($b['others']) and $b['others'] != '' )? $b['others'] : '';

            $type = getClassByButtonType($b['type']);
            $label = $type['label'];
            $iconClass = $type['class'];

            if( ! isset($b['permissions']) or ! count($b['permissions']) or user_access_or($b['permissions']) ){
                $output .= "<a href=\"{$link}\" class=\"btn btn-default\" {$others} ><i class=\"fa {$iconClass} \"></i>{$label}</a>";
            }

        }
    }




    $output .= '</div>';

    return $output;
}

function getClassByButtonType($type){
    switch($type){
        case 'add':
            $class = 'fa-plus-circle';
            $label = 'Add New';
            break;
        case 'delete':
            $class = 'fa-minus-circle';
            $label = 'Delete';
            break;
        case 'edit':
            $class = 'fa-pencil-square';
            $label = 'Edit';
            break;
        case 'cancel':
            $class = 'fa-times-circle';
            $label = 'Cancel';
            break;
        case 'back':
            $class = 'fa-chevron-circle-left';
            $label = 'Back';
            break;
        default:
            $class = 'fa-circle';
            $label = 'Button';
            break;
    }

    return [
        'label'=> $label,
        'class' => $class
    ];
}

function panelWrapperOpen($class = 'col-md-12', $title = ''){
    $out =  '<div class="'.$class.'"> <div class="panel panel-default">';
    if( $title != '' ){
        $out .= '<div class="panel-heading"><h3 class="panel-title">'.$title.'</h3></div>';
    }
    $out .= "<div class='panel-body'>";
    return $out;

}

function panelWrapperClose(){
    return "</div></div></div>";
}



