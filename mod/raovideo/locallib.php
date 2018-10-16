<?php
defined('MOODLE_INTERNAL') || die();
require_once('renderer.php');

function get_akamai_token($url){
	global $CFG;
	require_once("$CFG->dirroot/mod/raovideo/akamai_token_v2.php");
    $c = new Akamai_EdgeAuth_Config();
	$g = new Akamai_EdgeAuth_Generate();
	$c->set_window($CFG->video_window);
	$c->set_start_time('now');
    //$c->set_ip($CFG->server_ip);
    $akamai_server = $CFG->akamai_server;
    $url = $akamai_server.$url;
    $video_path = end(explode('/',$url));
    $akamai_video_path_sub =  rtrim($url,$video_path);
    $dir= substr($akamai_video_path_sub,33);
    $dir=  rtrim($dir,"/");
    //$c->set_acl($video_path);
    $c->set_acl("/$dir*");
	$c->set_algo('sha256');
	$c->set_key($CFG->akamai_key);
	$token = $g->generate_token($c);
	return $url.'?hdnea='.$token;	
    }