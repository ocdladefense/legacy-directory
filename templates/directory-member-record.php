<?php
$display_table = '
<table width="750" border="0" cellspacing="1" cellpadding="4" bgcolor="#CCCCCC">
      <tr valign="top" bgcolor="">
        <td class="text" colspan="2" bgcolor="660066"><b><font color="FFFFFF">MEMBER INFORMATION</font></b></td>
      </tr><tr valign="top" bgcolor="FFFFFF">
        <td class="text" align="right" width="24%" bgcolor="#FFFFFF">
          <font color="000000">
          <!--First member info row Name-->

          Name:</font></td>
        <td class="text" width="76%" bgcolor="DBE1E6">
          <!--First Member Name-->'.$row["full_name"].'</td>

      </tr>
      <tr valign="top" bgcolor="FFFFFF">
        <td class="text" align="right" width="24%" bgcolor="FFFFFF" height="4">
          <font color="000000"> OSB#&nbsp;/&nbsp;PSID#:</font></td>

        <td class="text" width="76%" bgcolor="DBE1E6" height="4">'.$row["bar_number"].'</td>
      </tr>

      <tr valign="top" bgcolor="FFFFFF">
        <td class="text" align="right" width="24%" bgcolor="FFFFFF" height="4">
          <font color="000000"> Organization:</font></td>
        <td class="text" width="76%" bgcolor="DBE1E6" height="4">'.$row["name_company"].'</td>
      </tr>
';
$tmp="<i>This user has chosen not to publish this information.</i>";
if( $row["publish"]==0 ) {
	$address_line_1=$tmp;
	$address_line_2=$tmp;
}
else {
	$address_line_1=$row["address_line_1"];
	$address_line_2=$row["address_line_2"];
}


$display_table.='
      <tr valign="top" bgcolor="FFFFFF">
        <td class="text" height="2" width="24%" align="right" bgcolor="FFFFFF">Address Line 1:<font color="000000"></font></td>
        <td class="text" height="2" width="76%" bgcolor="DBE1E6">'.$address_line_1.'</td>
      </tr>
      <tr valign="top" bgcolor="FFFFFF">
        <td class="text" align="right" width="24%" bgcolor="FFFFFF">Address Line 2:</td>
        <td class="text" width="76%" bgcolor="DBE1E6">'.$address_line_2.'</td>

      </tr>
      <tr valign="top" bgcolor="FFFFFF">
        <td class="text" align="right" width="24%" bgcolor="FFFFFF"><font color="000000">City, State, Zip:</font></td>
        <td class="text" width="76%" bgcolor="DBE1E6">'.$row["address_city"].', '.$row["address_state"].'  '.$row["address_zip"].'</td>

      </tr>
      <tr valign="top" bgcolor="FFFFFF">
        <td class="text" align="right" width="24%" bgcolor="FFFFFF">Phone:</td>
        <td class="text" width="76%" bgcolor="DBE1E6">'.phoneNumberFormat( $work_phone ).'</td>

      </tr>
      <tr valign="top" bgcolor="FFFFFF">
        <td class="text" align="right" width="24%" bgcolor="FFFFFF">Cell Phone:</td>
        <td class="text" width="76%" bgcolor="DBE1E6">'.phoneNumberFormat($cell_phone).'</td>
      </tr>
      <tr valign="top" bgcolor="FFFFFF">
        <td class="text" align="right" width="24%" bgcolor="FFFFFF"><font color="000000">Fax:</font></td>
        <td class="text" width="76%" bgcolor="DBE1E6">'.phoneNumberFormat($fax_number).'</td>

      </tr>

      <tr valign="top" bgcolor="FFFFFF">
        <td class="text" align="right" width="24%" bgcolor="FFFFFF"><font color="000000">E-Mail:</font></td>
        <td class="text" width="76%" bgcolor="DBE1E6"><a href="'.$emails_url.'">'.$emails.'</a></td>
      </tr>
      <tr valign="top" bgcolor="FFFFFF">
        <td class="text" align="right" width="24%" bgcolor="FFFFFF"><font color="000000">Web:</font></td>
        <td class="text" width="76%" bgcolor="DBE1E6"><a href="http://'.$row["address_www"].'">'.$row["address_www"].'</a></td>

      </tr>

      <tr valign="top" bgcolor="FFFFFF">
        <td class="text" align="right" width="24%" bgcolor="FFFFFF">Areas of Interest:</td>
        <td class="text" width="76%" bgcolor="DBE1E6">'.$row["interests"].'</td>
      </tr>
    </table>';