<?php
//include connection file.php here//
$wd_was = getcwd();
require('\inetpub\ocdla\html\sites\ocdla\settings.php');
chdir("\inetpub\ocdla\sqlconn");
require_once("sql_conn2.php");
chdir("\inetpub\ocdla\php_pkg");
/* $aoi_options, $contact_type_options */
require("form_helper.php");
require('profile_form_elements.php');
chdir("\inetpub\ocdla\html\includes");
require('Url.php');
chdir($wd_was);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>OCDLA Membership Directory</title>
<link href="/stylesheets/htmlpopups.css" rel="stylesheet" type="text/css" />
</head>

<body>

<!-- BEGIN DIRECTORY SEARCH BOX -->
<table width="750" border="0" cellspacing="1px" cellpadding="15px" bgcolor="#CCCCCC">
  <tr bgcolor="#FFFFFF"> 
  
  
 <!-- begin OCDLA quick search -->
    <td valign="middle" colspan="2"> 
   
      <p><!--<img src="/images/images-buttons/arrow-trans.png" width="7" height="10" alt="arrow" /> <a href="javascript: history.go(-1);">Back to Previous Page</a>--></p>
      <h3>        OCDLA Online Membership Directory Search</h3>
      <!-- <span style="width:300px; text-align:right;">[<a href="memberdirectory.lasso">Advanced Search</a>]</span>--></p>

 
	</td>
	
</tr>

<tr bgcolor="#FFFFFF">
  	<td valign="top">
  	

      <p><b>Browse by Category:</b> <br />

&#149;<a href="/membership/categories_newocdla.php?category=city">City</a>	<br /> &#149;<a href="/membership/categories_newocdla.php?category=county">County</a>
	
<br />
&#149;<a href="/membership/categories_newocdla.php?category=aoi">Areas of Interest</a>
	
	<br />
&#149;<a href="/membership/categories_newocdla.php?category=contact_type">Occupation/Field</a>


      <p><b>Membership Directory Section PDFs, Updated Daily</b><br />
  &#149;<a a href="pdf/current_members_alpha.pdf">Last Name</a>
  <br />
        
  &#149;<a href="pdf/current_members_city.pdf">City</a> 	<br />
        
  &#149;<a a href="pdf/current_members_interests.pdf">Members Interests</a>		<br />
        
  &#149;<a href="pdf/current_members_nonlawyers.pdf">Nonlawyer Members</a><br />
        
  &#149;<a href="pdf/current_contracts.pdf">Public Defense Contracts</a><br />
	<div>
 		<a href="pdf/OCDLA_2015_Membership_Directory.pdf">
 			<img src="directory.jpg" style="float:right;border:none;" />
 		</a>
      <p><strong>Complete Membership Directory PDF</strong>
      <a href="#" onclick="openRequestedPopup(); return false;">
      	<img src="../images/help.gif" alt="" width="15px" height="15px" style="border:none;" />
      </a>
      <br />
          <a href="pdf/OCDLA_2015_Membership_Directory.pdf">Download</a>
           — also includes these sections, updated each September: <br />
        • Public Defender Boards <br />
        • Office of Public Defense Services Staff<br />
        • Federal Public Defender Offices<br />
        <br />
       
        <br />
  </div>
</p>
    <p>Members: For referrals from OCDLA's Expert Witness Database, please email <a href="mailto:jroot@ocdla.org">jroot@ocdla.org</a>. This is a members’ only service.</p></td>


  	<td valign="top"> 
      <p><b>Search:</b></p>

<form method="get" action="quick_search_newocdla.php" enctype="multipart/form-data">

  	<table cellspacing="6px" cellpadding="2px">
  	<tr><td><p>Occupation/Field:</p></td><td>
  	<select name="contact_type" onChange="formReset( this );">
  	
  					<?php echo getAllContactTypes("All Members"); ?>

	</select>
	</td></tr>
<tr><td><p>Area of Interest:</p></td><td><select name="aoi" onChange="formReset( this );">


				<?php echo getInterests("All Interests"); ?>

</select></td></tr>
  	<tr><td><p>First Name:</p></td><td><input type="text" name="first_name" size="20" value="" maxlength="35" /></td></tr>
  	<tr><td><p>Last Name:</p></td><td><input type="text" name="last_name" size="20" value="" maxlength="35" /></td></tr>
  	<tr><td><p>Company Name:</p></td><td>
<input type="text" name="company_name" size="20" value="" maxlength="35" /> </td></tr>
  	<tr><td><p>City:</p></td><td><input type="text" name="city" size="20" value="" maxlength="35" /></td></tr>
  	
  	<tr><td colspan="2">&nbsp;</td></tr>
  	<tr><td>&nbsp;</td><td align="middle"><input type="submit" name="Submit" value="Search">	</td></tr>
  	</table>
  	</form>
  	
  	</td>

  	
  </tr>

  
  <tr bgcolor="#FFFFFF"> 
   
  </tr>
  <tr bgcolor="#FFFFFF"> 
    <td class="text" colspan="2"><!--<a href="memberdirectory.lasso?criteria_type=all&criteria=ALL">ALL</a>&nbsp;&nbsp;-->
         <p><b>Browse by Last Name:</b><br />
	<a href="quick_search_newocdla.php?letter_name=A">A</a>&nbsp; 
	<a href="quick_search_newocdla.php?letter_name=B">B</a>&nbsp; 
	<a href="quick_search_newocdla.php?letter_name=C">C</a>&nbsp; 
	<a href="quick_search_newocdla.php?letter_name=D">D</a>&nbsp; 
	<a href="quick_search_newocdla.php?letter_name=E">E</a>&nbsp; 
	<a href="quick_search_newocdla.php?letter_name=F">F</a>&nbsp;  
	<a href="quick_search_newocdla.php?letter_name=G">G</a>&nbsp; 
	<a href="quick_search_newocdla.php?letter_name=H">H</a>&nbsp; 
	<a href="quick_search_newocdla.php?letter_name=I">I</a>&nbsp; 
	<a href="quick_search_newocdla.php?letter_name=J">J</a>&nbsp; 
	<a href="quick_search_newocdla.php?letter_name=K">K</a>&nbsp; 
	<a href="quick_search_newocdla.php?letter_name=L">L</a>&nbsp; 
	<a href="quick_search_newocdla.php?letter_name=M">M</a>&nbsp; 
	<a href="quick_search_newocdla.php?letter_name=N">N</a>&nbsp; 
	<a href="quick_search_newocdla.php?letter_name=O">O</a>&nbsp; 
	<a href="quick_search_newocdla.php?letter_name=P">P</a>&nbsp; 
	<a href="quick_search_newocdla.php?letter_name=Q">Q</a>&nbsp; 
	<a href="quick_search_newocdla.php?letter_name=R">R</a>&nbsp; 
	<a href="quick_search_newocdla.php?letter_name=S">S</a>&nbsp; 
	<a href="quick_search_newocdla.php?letter_name=T">T</a>&nbsp; 
	<a href="quick_search_newocdla.php?letter_name=U">U</a>&nbsp; 
	<a href="quick_search_newocdla.php?letter_name=V">V</a>&nbsp; 
	<a href="quick_search_newocdla.php?letter_name=W">W</a>&nbsp; 
	<a href="quick_search_newocdla.php?letter_name=X">X</a>&nbsp; 
	<a href="quick_search_newocdla.php?letter_name=Y">Y</a>&nbsp; 
	<a href="quick_search_newocdla.php?letter_name=Z">Z</a>&nbsp; 

	</p>
	
	</td>

  </tr>
</table>

<!-- END DIRECTORY SEARCH BOX -->
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp; </p>
</body>


<div id="helpbox" style="visibility:hidden; position:absolute; top:400px; left:300px; background-color:#999999; width:200px; height:200px;">A help screen here</div>

</body>


<script type="text/javascript">
<!--
var WindowObjectReference;
var strWindowFeatures = "width=200,height=300,left=200,top=400,location=no,resizable=yes,scrollbars=yes,status=yes";

function openHelpBox()
{
	HelpBox = document.getElementById( "helpbox" );
	HelpBox.style.visibility = "visible";
}

function openRequestedPopup()
{
  WindowObjectReference = window.open("<?php echo Url::GetLink(); ?>/help.php#directory",
"OCDLA Online Help", strWindowFeatures);
}



function formReset(obj) {
	if ( obj.name == "contact_type" ) document.forms[0].aoi.value = "All Interests";
	//window.alert(document.forms.length);
	else if ( obj.name == "aoi" ) document.forms[0].contact_type.value="All Members";
	
}	
-->
</script>


</html>