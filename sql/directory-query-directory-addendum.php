<?php

if(  isset($_GET["first_name"]) && $_GET["first_name"]!="" ) {
	$query.= 'WHERE name_first LIKE "%'.$_GET["first_name"].'%"'; $cond=true;
	}

if(  isset($_GET["last_name"]) && $_GET["last_name"]!="" ) {
	if($cond) $query.= ' AND name_last LIKE "%'.$_GET["last_name"].'%"';
	else { $query.= ' WHERE name_last LIKE "%'.$_GET["last_name"].'%"'; $cond=true; }
	}

if(  isset($_GET["letter_name"]) && $_GET["letter_name"]!="" ) {
	if($cond) $query.= ' AND name_last LIKE "'.$_GET["letter_name"].'%"';
	else { $query.= ' WHERE name_last LIKE "'.$_GET["letter_name"].'%"'; $cond=true; }
	}
	
if(  isset($_GET["company_name"]) && $_GET["company_name"]!="" ) {
	if($cond) $query.= ' AND name_company LIKE "%'.$_GET["company_name"].'%"';
	else { $query.= ' WHERE name_company LIKE "%'.$_GET["company_name"].'%"'; $cond=true; }
}

if(  isset($_GET["city"]) && $_GET["city"]!="" ) {
	if($cond) $query.= ' AND address_city LIKE "%'.$_GET["city"].'%"';
	else { $query.= ' WHERE address_city LIKE "%'.$_GET["city"].'%"'; $cond=true; }
}

if(  isset($_GET["county"]) && $_GET["county"]!="" ) {
	if($cond) $query.= ' AND address_county LIKE "%'.$_GET["county"].'%"';
	else $query.= ' WHERE address_county LIKE "%'.$_GET["county"].'%"';
}


$query.=" ORDER BY name_last";
?>