<?php
// Include connection file.php here.

define('DEBUG',FALSE);
define('PATH_TO_TEMPLATE','\inetpub\ocdla\html\membership\templates');
$debug = FALSE;



// Load the settings file.
require('\inetpub\ocdla\html\sites\ocdla\settings.php');

$wd_was = getcwd();
// include database connection files
chdir("\inetpub\ocdla\sqlconn");
require("sql-conn-aws.php");

// require('/inetpub/ocdla/html/includes/settings.php');
// require('/inetpub/ocdla/html/includes/debug.php'); /* include debug functions for logging */
require('/inetpub/ocdla/html/includes/DBQuery.php');
require('/inetpub/ocdla/html/includes/contact.php');

chdir("\inetpub\ocdla\php_pkg");
require("form_helper.php"); //$aoi_options, $contact_type_options
require("query_builder.php");
require("profile_form_elements.php");
include("profile_regex.php");

chdir($wd_was);
require('sql/directory-query-directory-by-id.php');

$exec = mysql_query( $query );
	
$contact_id = !empty($_GET["id"]) ? trim($_GET['id']) : FALSE;
$contact = new_contact( $contact_id )->getInfo();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>OCDLA Member Directory - QuickSearch</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="/stylesheets/htmlpopups.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../css/law.css" type="text/css" />
<link href="css/global_styles.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
#lasso_dynamic_content {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
}
#lasso_dynamic_content table, #resultsinfo, span {
	margin-top: 15px;
	margin-right: 10px;
	margin-bottom: 0px;
	margin-left: 10px;
}

#lasso_dynamic_content th {
	background-color: #660066;
	color: #FFF;
	text-transform: uppercase;
	font-size: 24px;
	font-weight: bold;
}
#lasso_dynamic_content table, #lasso_dynamic_content td, #lasso_dynamic_content th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
}

.altrow
{
background-color:#FFFFFF;
}

.row
{
background-color:#DBE1E6;
}

-->
</style>





</head>

<body>

<!-- BEGIN CONTENT - ARI -->
	<div id="lasso_dynamic_content">



<?php /*include('directory-search-form.html');*/ ?>

<p><img src="/images/images-buttons/arrow-trans.png" width="7" height="10" alt="arrow" /> <a href="javascript: history.go(-1);">Back to Previous Page</a></p>
<p><img src="/images/images-buttons/arrow-trans.png" width="7" height="10" alt="arrow" /> <a href="/membership/memberdirectory_newocdla.php">Back to Directory</a></p>
         <p id="resultsinfo" class="largertextheader"><b><?php echo "Showing $records results for this search.";?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			</p>

 <?php
if(TRUE) {
	//begin displaying page numbers and database results

	
	$row = mysql_fetch_assoc( $exec );
	
	$tmp="<i>This user has chosen not to publish this information.</i>";
	if( $contact['work']['publish']==0 && !empty($contact['work']['value'])) $work_phone=$tmp;
		else $work_phone = $contact['work']['value'];
	if( $contact['cell']['publish']==0 && !empty($contact['cell']['value']) ) $cell_phone=$tmp;
		else $cell_phone = $contact['cell']['value'];
	if( $contact['fax']['publish']==0 && !empty($contact['fax']['value']) ) $fax_number = $tmp;
		else $fax_number = $contact['fax']['value'];
	if( $contact['email']['publish']==0 && !empty($contact['email']['value']) ) {
		$emails=$tmp;
		$emails_url="#";
	} else {
		$emails = $contact['email']['value'];
		$emails_url="mailto:{$emails}";
	}		
	
	chdir(PATH_TO_TEMPLATE);
	require('directory-member-record.php');
	
	 } else {

// set up display for multiple results
 $display_table = '

	<table bgcolor="#660066" cellspacing="1" cellpadding="4" width="1100">
		<tr>
			<th colspan="8">'.$results_title.'</th>
      	</tr>
      	<tr>
			<td><font color="#FFFFFF">Name</font></td>
			<td><font color="#FFFFFF">Company</font></td>
			<td><font color="#FFFFFF">Phone</font></td>
			<!--<th><font color="#FFFFFF">CELL PHONE</font></th>-->
			<td><font color="#FFFFFF">City</font></td>
			<td><font color="#FFFFFF">Email</font></td>
			<td><font color="#FFFFFF">Occupation/Field</font></td>
			<td width="115"><font color="#FFFFFF">Interests</font></td>
		</tr>';

	for($i = 0; $i < mysql_num_rows( $exec ); $i++) {
		$row = mysql_fetch_assoc($exec);
		if($i % 2 > 0 ) $class="class=\"altrow\"";
			else $class="class=\"row\"";				 
		chdir(PATH_TO_TEMPLATE);
		require('directory-table-row.php');

	}//for

}//else


echo $display_table;




?>

			</table><!-- end database results -->


			</div><!-- end #lasso_dynamic_content -->

</body>



<script type="text/javascript">
<!--
	function formReset(obj) {
		if ( obj.name == "contact_type" ) document.forms[0].aoi.value = "All Interests";
		//window.alert(document.forms.length);
		else if ( obj.name == "aoi" ) document.forms[0].contact_type.value="All Members";

	}//

	function contactTypeSet( val ) {
		document.forms[0].contact_type.value = val;

	}//	method contactTypeSet

	function areaOfInterestSet( val ) {
		document.forms[0].aoi.value = val;
	}//method areaOfInterestSet
-->
</script>

<?php
	echo "<script type='text/javascript'>
	<!--  ";
	if ( $_GET["contact_type"] != "" ) echo "
	contactTypeSet('".$_GET["contact_type"]."');
	";
	if ( $_GET["aoi"] != "" ) echo "
	areaOfInterestSet('".$_GET["aoi"]."');
	";
	echo "
	-->
	</script>";
?>


</html>
