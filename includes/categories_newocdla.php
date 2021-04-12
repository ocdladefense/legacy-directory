<?php
//include connection file.php here//

$wd_was = getcwd();

// Load the settings file.
require('\inetpub\ocdla\html\sites\ocdla\settings.php');


chdir("\inetpub\ocdla\sqlconn");
require("sql-conn-aws.php");

chdir("\inetpub\ocdla\php_pkg");
require("form_helper.php"); //$aoi_options, $contact_type_options
require("profile_funcs.php");
chdir($wd_was);
$debug=false;

// include database connection files

// ========================================================================
// Faster query when searching within Areas of Interest
// ========================================================================
switch ( $_GET["category"] ) {
	case "contact_type": $query = 'SELECT occupation AS note_description FROM data_occupations ORDER BY occupation'; $link = "quick_search_newocdla.php?category=contact_type&contact_type=";break;
		
	case "aoi": $query = 'SELECT interest AS note_description FROM data_interests ORDER BY interest'; $link = "quick_search_newocdla.php?category=aoi&aoi=";break;
	
	case "city": $query = 'SELECT DISTINCT address_city FROM member_addresses ORDER BY address_city'; $link = "quick_search_newocdla.php?category=city&city=";break;
	
	case "county": $query = 'SELECT DISTINCT address_county FROM member_addresses ORDER BY address_county'; $link = "quick_search_newocdla.php?category=county&county=";break;

}//switch




$exec = @mysql_query( $query );


?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>OCDLA Member Directory</title>
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
	background-color: #5A7882;
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

<!-- BEGIN DIRECTORY SEARCH BOX -->


<!-- BEGIN CONTENT - ARI -->
	<div id="lasso_dynamic_content">


<!-- begin search form -->                    
		<form METHOD="GET" ACTION="quick_search_newocdla.php" enctype="multipart/form-data">
			<img src="/images/images-buttons/arrow-trans.png" width="7" height="10" alt="arrow" /> <a href="javascript: history.go(-1);">Back to Previous Page</a>
            
            
            <table width="1100px" border="0" cellspacing="5px" cellpadding="3px" style="width:1100px; background-color:#B9B1DC;">
				<tr>
				<td rowspan="2"><b>New Search:</b></td>
				<td>Occupation/Field:&nbsp;<select name="contact_type" onChange="formReset( this );">
				
				<?php echo getContactTypes("All Members"); ?>
				
				</select> 
				
				&nbsp;&nbsp;Area of Interest:&nbsp;<select name="aoi" onChange="formReset( this );">
				
				<?php echo getInterests("All Interests"); ?>
				
				</select>
				
				</td></tr>
				
				
				<tr><td><?php echo 'First Name:&nbsp;<input type="text" name="first_name" size="13" value="'.$_GET["first_name"].'" maxlength="35" />';?>
				&nbsp;&nbsp;<?php echo 'Last Name:&nbsp;<input type="text" name="last_name" size="13" value="'.$_GET["last_name"].'" maxlength="35" />';?>
				&nbsp;&nbsp;<?php echo 'Company Name:&nbsp;<input type="text" name="company_name" size="13" value="'.$_GET["company_name"].'" maxlength="35" />';?>
				&nbsp;&nbsp;<?php echo 'City:&nbsp;<input type="text" name="city" size="13" value="'.$_GET["city"].'" maxlength="35" />';?>
				&nbsp;&nbsp;<?php echo 'County:&nbsp;<input type="text" name="county" size="13" value="'.$_GET["county"].'" maxlength="35" />';?>
				&nbsp;&nbsp;<input type="submit" name="Submit" value="Search">	</td>
				</tr>
			</table>
		</form> <!-- end search form -->

        
         <p id="resultsinfo" class="largertextheader"><b><?php echo "Showing $records results for this search.";?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;


   
         </p>

         <?php 



//begin displaying page numbers and database results

if($exec && $debug) { echo "Query successfully completed.<P>$query"; echo '<p>Number of Rows: '.mysql_num_rows( $exec ).'</p>';
}else if($debug) echo "$query<p>".mysql_error()."</p>";



//set title of the results table
if($_GET["category"]=="city") $results_title="Search results for the city of ".$_GET["city"];
else if($_GET["category"]=="county") $results_title="Search results for ".$_GET["county"]." County";
else if($_GET["category"]=="aoi") $results_title="Search results for ".$_GET["aoi"];
else if($_GET["category"]=="contact_type") $results_title="Search results for ".$_GET["contact_type"];

      	$display_table = "<table cellspacing=\"0px\" cellpadding=\"0px\" border=\"0\"><tr>";

$ROWS = 20;      	
$colnumber = 0;
	
	do {
	
		$index = 0;
		$display_table .= '<td valign="top"><ul>  ';
		do {
			$row = mysql_fetch_row( $exec );
			$display_table .= "<li><a href=\"$link$row[0]\">$row[0]</a></li>";
			$index++;
			$i++;
		} while ( $i < mysql_num_rows( $exec ) && $index <= $ROWS  );
			
		$display_table .= '</ul></td>  ';
		$colnumber++;
		
	} while ( $i < mysql_num_rows( $exec ) );

$display_table .= '</tr></table>';
echo $display_table;
?>

		<!-- end database results -->


			</div><!-- end #lasso_dynamic_content -->


          </td>
        </tr>
      </table><!-- end inner table -->



</body>


<script language="JavaScript">
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
	echo "<script type=\"text/javascript\">
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
