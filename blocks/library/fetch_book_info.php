<?php
//fetches book info and returns json for it
require_once('../../config.php');
require_once('locallib.php');
require_login();

/**
gets the info for the book specified
**/
global $DB;
$barcode = required_param('barcode');

//reverts a json of the entire info about the book, also checks iff the booknis currently issued
