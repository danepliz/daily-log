<?php

function tb_getTopBar(){
	CI::$APP->load->theme('common/topbar');
}

function tb_getBrandBar(){
	CI::$APP->load->theme('common/brand');
}

function tb_getMainNav(){
	CI::$APP->load->theme('common/mainnav');
}