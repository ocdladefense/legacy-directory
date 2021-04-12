<?php 
// Include connection file.php here.
define('DEBUG',FALSE);
require('\inetpub\ocdla\html\sites\ocdla\settings.php');

define('PATH_TO_TEMPLATE','\inetpub\ocdla\html\membership\templates');

$wd_was = getcwd();
chdir("\inetpub\ocdla\sqlconn");
require("sql-conn-aws.php");

chdir("\inetpub\ocdla\php_pkg");
require("form_helper.php"); //$aoi_options, $contact_type_options
require("query_builder.php");
require("profile_form_elements.php");
include("profile_regex.php");
chdir($wd_was);
$dir_processor="";




if($_GET["aoi"] != "All Interests" && !empty($_GET["aoi"])) {
	$dir_processor = 'aoi_get_member_by';
}

elseif($_GET["contact_type"] != "All Members" && !empty($_GET["contact_type"])) {
	$dir_processor = 'contact_type_get_member_by';
}

elseif( $_GET["category"] == "county" ) {
	$dir_processor = 'directory-query-directory-by-text-field';
}

/* if querying by letter name */
elseif( !empty($_GET["letter_name"]) ) {
	$dir_processor='directory-query-directory-by-alpha';
}

/* if querying by one of the fields */
elseif( !empty($_GET["first_name"]) || !empty($_GET["last_name"]) || !empty($_GET["company_name"]) || !empty($_GET["city"]) ) {
	$dir_processor = 'directory-query-directory-by-text-field';
}

else {
	$dir_processor = 'directory-query-directory';

}

require( 'sql/'.$dir_processor .'.php' );
/**
 * This include may make further modifications to the query
 * to the WHERE clause
 */
include('sql/where_query_modify.php');

$debug = FALSE;

if(!$exec=mysql_query($query)){
	e_notify( "The query could not be completed: ".mysql_error()."\n\n{$query}" );
}


/**
 * Requery using a modified version of the query to enable pagingation
 * especially with a LIMIT quantifier
 */
include('sql/paging_query_modify.php');