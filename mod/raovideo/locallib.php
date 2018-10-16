<?php
defined('MOODLE_INTERNAL') || die();
require_once('renderer.php');

function get_akamaized_url($url){
	global $CFG;
	require_once("$CFG->dirroot/mod/raovideo/akamai_token_v2.php");
    $c = new Akamai_EdgeAuth_Config();
	$g = new Akamai_EdgeAuth_Generate();
    $vid_window = ((int)get_config('raovideo','hours')*60+(int)get_config('raovideo','minutes'))*60;
	$c->set_window($vid_window);
	$c->set_start_time('now');
    $akamai_server = get_config('raovideo','akamai_server');
    $url = $akamai_server.$url;
    $tmp = explode('/',$url);
    $akamai_video_path_sub =  rtrim($url,end($tmp));
    $dir= substr($akamai_video_path_sub,33);
    $dir=  rtrim($dir,"/");
    $c->set_acl("/$dir*");
	$c->set_algo('sha256');
	$c->set_key(get_config('raovideo','akamai_key'));
	$token = $g->generate_token($c);
	return $url.'?hdnea='.$token;	
}

function get_video_resource($vid){
    global $DB;
    $video = $DB->get_record('raovideo',array("id"=>$vid));
    return $video;
}

function get_video_akamai($vid){
    global $DB;
    $video = $DB->get_record('raovideo',array("id"=>$vid));
    return get_akamaized_url($video->url);
}