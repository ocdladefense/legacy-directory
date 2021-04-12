<?php

require('quick_search_init.php');
// File: quick_search_newocdla.php
// ** Display search results from the following kinds of searches
// - Area of Interest: search by a member's area of interest
// - Contact Type: e.g., Lawyer, Executive, Investigator
// - County: e.g., find all OCDLA members in Lane county
// - Letter Name: e.g., find all OCDLA members with the last name, A as in "Allen"
// - 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>OCDLA Member Directory - QuickSearch</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="/stylesheets/htmlpopups.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../css/law.css" type="text/css">
<link href="css/global_styles.css" rel="stylesheet" type="text/css">
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



<?php include('directory-search-form.html'); ?>


         <p id="resultsinfo" class="largertextheader"><b><?php echo "Showing $records results for this search.";?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			</p>

         <?php
		  // Below will show the page numbers
         if( $records > $perpage ) {
         echo "<span>Pages: $prev_list : $direct_bar : $next_list&nbsp;&nbsp;&nbsp;";
         	if ( $_GET["view_all"] == "true" ) {
         		$inactive = "style=\"color:#666666;\"";
         	}//if
         	echo "<a $inactive href='$PHP_SELF?contact_type=".$_GET["contact_type"]."&first_name=".$_GET["first_name"]."&last_name=".$_GET["last_name"]."&company_name=".$_GET["company_name"]."&city=".$_GET["city"]."&aoi=".$_GET["aoi"]."&county=".$_GET["county"]."&letter_name=".$_GET["letter_name"]."&page=$page&view_all=true'>View All</a> \n</span>";
         }// if


//begin displaying page numbers and database results

if($exec && DEBUG) { echo "Query successfully completed.<P>$query"; echo '<p>Number of Rows: '.mysql_num_rows( $exec ).'</p>';
}else if(DEBUG) echo "$query<p>".mysql_error()."</p>";



//set title of the results table
if($_GET["category"]=="city") $results_title="Search results for the city of ".$_GET["city"];
else if($_GET["category"]=="county") $results_title="Search results for ".$_GET["county"]." County";
else if($_GET["category"]=="aoi") $results_title="Search results for ".$_GET["aoi"];
else if($_GET["category"]=="contact_type") $results_title="Search results for ".$_GET["contact_type"];

if ( $_GET["id"] != "" ) {

	$row = mysql_fetch_assoc( $exec );
	chdir(PATH_TO_TEMPLATE);
	require('directory-member-record.php');

 } else {

 $display_table = '

  <table bgcolor="#660066" cellspacing="1" cellpadding="4" width="1100">
      	<th colspan="8">'.$results_title.'</th>
      		</tr><tr>

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
		 if( isset($row["work_phone_publish"]) && $row["work_phone_publish"]==0 ) {
		 	$work_phone=$tmp;
		 }
		 else {
		 	$work_phone=$row["work_phone"];
		 }
		// $work_phone = 'phone_number';
		if($i % 2 > 0 ) $class="class=\"altrow\""; else $class="class=\"row\"";				 
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