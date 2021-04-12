<?php
// ==========================================
// Configuration for page numbers
// ==========================================

$perpage = "15"; // How many results to show per page
$pageshow = "10"; // How many pages you want to show in the direction bar

$records = mysql_num_rows( $exec );


/**
 * Only run this script if the number of records retrieved from the query
 * is greater than the number of records displayed per page
 *
 */
if( $records > $perpage ) {
	$page_num = ceil($records / $perpage);
	if(isset($_GET["page"])) $page=$_GET["page"]; else $page=1;
	$vstart = $perpage * ($page-1);
	$page_start = floor(($page-1)/ $pageshow ) * $pageshow ;
	$page_end = $page_start + $pageshow;
		
	for ($p=$page_start+1 ; ($p <= $page_end) && ($p <= $page_num)  ; $p++ ) {
		if ($page == $p) $direct_bar .= "<b>$p</b> ";
		else $direct_bar .= "<a href='{$_SERVER['PHP_SELF']}?{$_SERVER['QUERY_STRING']}&page=$p'>$p</a> ";
	}

	
	if ($records > $vstart+$perpage ) {
		$next_p=$page+1;
		$next_list = "<a href='{$_SERVER['PHP_SELF']}?{$_SERVER['QUERY_STRING']}&page=$next_p'>Next >></a> \n";
	}
		
		
	if ($page>1) {
		$prev_p=$page-1;
		$prev_list="<a href='{$_SERVER['PHP_SELF']}?{$_SERVER['QUERY_STRING']}&page=$prev_p'><< Prev</a>\n";
	}
	
	
	
	/**
	 * Make further LIMIT modifications to the query to enable pagination
	 * ignore this if the user has selected the "View All" option
	 */
	if( $_GET["view_all"] != "true" ) {
		$query .= " LIMIT $vstart,$perpage";
		$exec = mysql_query( $query );// or die(mysql_error());
	}//if

}// end checking for $records > $perpage
?>