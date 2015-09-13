<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');



function getSheepitMultipleFromElement($name, $elements, $template_class = '', $maxFormCounts = 4, $initFormCounts = 1, $minFormCounts = 1)
{
    $explodedName = explode('_', $name);

    $elementLabel = ucwords( implode(" ", $explodedName) );
    $elementID = lcfirst( str_replace(" ", "", $elementLabel) );
    $templateElementID = 'id="' . $elementID . '_template"';
    $removeCurrentLinkID = 'id="' . $elementID . '_remove_current"';
    $noFormTemplateID = 'id="' . $elementID . '_noforms_template"';
    $formControlsID = 'id="' . $elementID . '_controls"';
    $addAnotherLinkID = 'id="' . $elementID . '_add"';

    $elemOut = '';
    foreach($elements as $elem){
        $inputName = $elem['name'];
        $elemClass = ' class=" '.$elem['classes'].' " ';
        $elemName = ' name="'.$name.'['.$inputName.'][#index#]" ';
        $elemID = ' id="' . $elementID . '_#index#_'. $elem['name'].'" ';

        $explodedElemName = explode('_', $elem['name']);
//        $labelName = $explodedElemName[count( $explodedElemName ) - 1];
        $elemOut .= '<div class="form-group-sm" >';
        $elemOut  .= '<label for="'.$elem['name'].'">'.ucwords(implode(' ', $explodedElemName)).'</label>';
        if( $elem['type'] == 'textarea' ){
            $elemOut .= '<textarea '.$elemName. $elemClass . $elemID .'></textarea>';
        }else{
            $elemOut .= '<input type="'.$elem['type'].'" '.$elemName. $elemClass . $elemID .' />';
        }
        $elemOut .= '</div>';
    }

    $template_class = $template_class ? : 'col-md-12';
    $output = '';
    $output .= '<div id="'. $elementID .'" class="sheepit_template" >'; //class="col-md-12"

    $output .= '<div '. $templateElementID .'>'; //template start //class="'.$template_class.' margin sheepit_template"
    $output .= '<div class="col-md-11">';
    $output .= $elemOut;
    $output .= '</div>';
    $output .= '<div class="col-md-1"><label>&nbsp;</label><a '. $removeCurrentLinkID .' title="remove"><i class="icon fa fa-2x fa-times-circle"></i></a></div>';
    $output .= '</div>'; //template end

    $output .= '<div '. $noFormTemplateID .' class="col-md-12">No ' . $elementLabel . '</div>'; // no form template

    $output .= '<div '. $formControlsID .' class="col-md-12">'; //controls start
    $output .= '<div '. $addAnotherLinkID.'><a class="btn btn-default"><i class="fa fa-plus-square"></i>&nbsp; Add Another '. $elementLabel .'</a></div>';
    $output .= '</div>'; //controls end

    $output .= '</div>';


    $script = '<script type="text/javascript">';
    $script .= '$(document).ready(function() {
            var '. $elementID .' = $("#'.$elementID.'").sheepIt({
                separator: "",
                allowRemoveLast: false,
                allowRemoveCurrent: true,
                allowRemoveAll: false,
                allowAdd: true,
                allowAddN: true,
                maxFormsCount: '. $maxFormCounts .',
                minFormsCount: '. $minFormCounts .',
                iniFormsCount: '. $initFormCounts .'
            });
        });';
    $script .= '</script>';

    echo $output . $script;
}

function getSheepitFromElement($name, $elements, $template_class = '', $maxFormCounts = 4, $initFormCounts = 1, $minFormCounts = 1)
{
    $explodedName = explode('_', $name);

    $elementLabel = ucwords( implode(" ", $explodedName) );
    $elementID = lcfirst( str_replace(" ", "", $elementLabel) );
    $templateElementID = 'id="' . $elementID . '_template"';
    $removeCurrentLinkID = 'id="' . $elementID . '_remove_current"';
    $noFormTemplateID = 'id="' . $elementID . '_noforms_template"';
    $formControlsID = 'id="' . $elementID . '_controls"';
    $addAnotherLinkID = 'id="' . $elementID . '_add"';

    $lbl = ucwords(implode(" ", $explodedName));

    $elemOut = '';
    foreach($elements as $elem){
        $inputName = $elem['name'];
        $elemClass = ' class=" '.$elem['classes'].' " ';
        $elemName = ' name="'.$name.'['.$inputName.'][#index#]" ';
        $elemID = ' id="' . $elementID . '_#index#_'. $elem['name'].'" ';
        if( $elem['type'] == 'textarea' ){
            $elemOut .= '<textarea '.$elemName. $elemClass . $elemID .'></textarea>';
        }else{
            $elemOut .= '<input type="'.$elem['type'].'" '.$elemName. $elemClass . $elemID .' />';
        }
    }

    $output = '';
    $output .= '<div class="form-group-sm" >';
    $output  .= '<label>'.$lbl.'</label>';
    $output .= '<div id="'. $elementID .'" class="sheepit_template" >'; //class="col-md-12"

    $output .= '<div '. $templateElementID .' class="sheepit_template_input_wrapper"  >'; //template start //class="'.$template_class.' margin sheepit_template"
    $output .= '<div class="col-md-11">';

    $output .= $elemOut;

    $output .= '</div>';
    $output .= '<div class="col-md-1"><a '. $removeCurrentLinkID .' title="remove"><i class="icon fa fa-times"></i></a></div>';
    $output .= '</div>'; //template end

    $output .= '<div '. $noFormTemplateID .' class="col-md-12">No ' . $elementLabel . '</div>'; // no form template

    $output .= '<div '. $formControlsID .' class="col-md-12">'; //controls start
    $output .= '<div '. $addAnotherLinkID.'><a class="btn btn-default"><i class="fa fa-plus-square"></i>&nbsp; Add Another '. $elementLabel .'</a></div>';
    $output .= '</div>'; //controls end

    $output .= '</div>';
    $output .= '</div>';


    $script = '<script type="text/javascript">';
    $script .= '$(document).ready(function() {
            var '. $elementID .' = $("#'.$elementID.'").sheepIt({
                separator: "",
                allowRemoveLast: false,
                allowRemoveCurrent: true,
                allowRemoveAll: false,
                allowAdd: true,
                allowAddN: true,
                maxFormsCount: '. $maxFormCounts .',
                minFormsCount: '. $minFormCounts .',
                iniFormsCount: '. $initFormCounts .'
            });
        });';
    $script .= '</script>';

    echo $output . $script;
}