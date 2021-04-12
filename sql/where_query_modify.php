<?php
if(  !empty( $_GET["first_name"] )  ) {
	$query.= 'WHERE name_first LIKE "%'.$_GET["first_name"].'%"'; $cond=true;
	}

if(  !empty( $_GET["last_name"] )  ) { //last_name
	if($cond) $query.= ' AND name_last LIKE "%'.$_GET["last_name"].'%"';
	else { $query.= ' WHERE name_last LIKE "%'.$_GET["last_name"].'%"'; $cond=true; }
	}

if(  !empty( $_GET["letter_name"] )  ) { //letter_name
	if($cond) $query.= ' AND name_last LIKE "'.$_GET["letter_name"].'%"';
	else { $query.= ' WHERE name_last LIKE "'.$_GET["letter_name"].'%"'; $cond=true; }
	}

if(  !empty( $_GET["company_name"] )  ) { //company_name
	if($cond) $query.= ' AND name_company LIKE "%'.$_GET["company_name"].'%"';
	else { $query.= ' WHERE name_company LIKE "%'.$_GET["company_name"].'%"'; $cond=true; }
}

if(  !empty( $_GET["city"] )  ) {  //city
	if($cond) $query.= ' AND address_city LIKE "%'.$_GET["city"].'%"';
	else { $query.= ' WHERE address_city LIKE "%'.$_GET["city"].'%"'; $cond=true; }
}

if(  !empty( $_GET["county"] )  ) {
	if($cond) $query.= ' AND address_county LIKE "%'.$_GET["county"].'%"';
	else $query.= ' WHERE address_county LIKE "%'.$_GET["county"].'%"';
}


$query.=" AND is_member=1 ORDER BY name_last";