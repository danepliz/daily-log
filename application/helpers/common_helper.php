<?php


function getPagination($totalDataCount, $base_url, $uriSegment, $methodGET = FALSE, $perPageDataCount = PER_PAGE_DATA_COUNT, $previousLink = 'Previous', $nextLink = 'Next'){
    $CI = CI::$APP;
    $CI->load->library('pagination');
    $config['base_url'] = base_url().$base_url;
    $config['total_rows'] = $totalDataCount;
    $config['per_page'] = $perPageDataCount;
    $config['uri_segment'] = $uriSegment;
    $config['prev_link'] = $previousLink;
    $config['next_link'] = $nextLink;
    $config['page_query_string'] = $methodGET;

    $config['full_tag_open']		= '<ul class="pagination">';
    $config['full_tag_close']		= '</ul>';
    $config['first_tag_open']		= '<li>';
    $config['first_tag_close']	    = '</li>';
    $config['last_tag_open']		= '<li>';
    $config['last_tag_close']		= '</li>';
    $config['first_url']			= ''; // Alternative URL for the First Page.
    $config['cur_tag_open']		    = '<li class="active"><a href="javascript:void(0)" class="current">';
    $config['cur_tag_close']		= '</a></li>';
    $config['next_tag_open']		= '<li>';
    $config['next_tag_close']		= '</li>';
    $config['prev_tag_open']		= '<li>';
    $config['prev_tag_close']		= '</li>';
    $config['num_tag_open']		    = '<li>';
    $config['num_tag_close']		= '</li>';

    $CI->pagination->initialize($config);
    $link = $CI->pagination->create_links();

    $out = '<div class="dataTables_paginate paging_bootstrap">';
    $out .= $link;
    $out .= '</div>';
    return $out;
}

/**
 * @return array(
        'filters' [filter keys and values],
        'post' [posted values],
        'param' [uri params],
        'getURI',
        'offset'
     )
 */
function getFiltersFromURL(){

    $CI = CI::$APP;

    $filters = array();
    $getURI = '';
    $param =  NULL;
    $post = NULL;
    $offset = NULL;

    if(($post = $CI->input->get())){
//        $filters = $post;

        foreach($post as $k=>$v){
            $v = str_replace('"', '', strip_tags($v));
            if($k !== 'per_page' and $k !== 'submit') $param .=  $k.'='.$v.'&';
            if( $k == 'per_page' ) $offset = $v;
            $post[$k] = $v;
        }

        $filters = $post;
//        show_pre($post); die;

        $param = substr($param,0,-1);

        $getURI = '?' . http_build_query($post, '', '&');
    }

    return array(
        'filters' => $filters,
        'post' => $post,
        'param' => $param,
        'getURI' => $getURI,
        'offset' => $offset
    );
}