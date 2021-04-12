<?php

// ========================================================================
// Faster query when searching within Areas of Interest
// ========================================================================
if($_GET["id"] != "" ) {
	$query='

	SELECT members.id, CONCAT(name_last,", ",name_first) AS full_name, work_phone, cell_phone, fax_number, address_www, name_company, emails, address_line_1, address_line_2, address_city, address_state, address_zip, contact_types, interests, bar_number, suffix
		FROM members
		LEFT JOIN

				(
				SELECT mem_id, contact_types, interests FROM

					(SELECT member_id AS mem_id, note AS contact_types FROM member_interests WHERE note_type='.PROFILE_CONTACT_TYPE.'
					)
					AS ids

					LEFT JOIN

					(SELECT member_id, GROUP_CONCAT(note SEPARATOR ", ") AS interests FROM member_interests WHERE note_type='.PROFILE_AREA_OF_INTEREST.' GROUP BY member_id ORDER BY member_id
					)
					AS cinterests

					ON ( ids.mem_id = cinterests.member_id )
				)

			AS mem_info ON ( members.id = mem_info.mem_id )

			LEFT JOIN member_addresses ON ( members.id = member_addresses.member_id AND member_addresses.a_type="MA" )
			WHERE members.id='.$_GET["id"];
}//

// ========================================================================
// Faster query when searching within Areas of Interest
// ========================================================================
else if($_GET["aoi"] != "All Interests" && $_GET["aoi"] != "" ) {
	$query='

	SELECT members.id, CONCAT(name_last,", ",name_first) AS full_name, work_phone, name_company, emails, address_city, address_state, address_zip, contact_types, interests, bar_number, suffix
		FROM members
		JOIN

				(
				SELECT mem_id, contact_types, interests FROM

					(SELECT member_id AS mem_id, note AS contact_types FROM member_interests WHERE note_type='.PROFILE_CONTACT_TYPE.'
					)
					AS ids

					LEFT JOIN

					(SELECT member_id, GROUP_CONCAT(note SEPARATOR ", ") AS interests FROM member_interests WHERE note_type='.PROFILE_AREA_OF_INTEREST.' GROUP BY member_id ORDER BY member_id
					)
					AS cinterests

					ON ( ids.mem_id = cinterests.member_id ) WHERE interests LIKE "%'.$_GET["aoi"].'%"
				)

			AS mem_info ON ( members.id = mem_info.mem_id )

			LEFT JOIN member_addresses ON ( members.id = member_addresses.member_id AND member_addresses.a_type="MA" )

	';
}//





// =========================================================
// Faster query when searching within contact types
// =========================================================
else if($_GET["contact_type"] != "All Members" && $_GET["contact_type"] != "" ) {
	$query='

	SELECT members.id, CONCAT(name_last,", ",name_first) AS full_name, work_phone, name_company, emails, address_city, address_state, address_zip, contact_types, interests, bar_number, suffix
		FROM members
		JOIN

				(
				SELECT mem_id, contact_types, interests FROM

					(SELECT member_id AS mem_id, note AS contact_types FROM member_interests WHERE note_type='.PROFILE_CONTACT_TYPE.' AND note LIKE "%'.$_GET["contact_type"].'%"
					)
					AS ids

					LEFT JOIN

					(SELECT member_id, GROUP_CONCAT(note SEPARATOR ", ") AS interests FROM member_interests WHERE note_type='.PROFILE_AREA_OF_INTEREST.' GROUP BY member_id ORDER BY member_id
					)
					AS cinterests

					ON ( ids.mem_id = cinterests.member_id )
				)

			AS mem_info ON ( members.id = mem_info.mem_id )

			LEFT JOIN member_addresses ON ( members.id = member_addresses.member_id AND member_addresses.a_type="MA" )

	';
}//

// =========================================================
// Query to retrieve search results
// =========================================================
else $query = 'SELECT members.id, CONCAT(name_last,", ",name_first) AS full_name, work_phone, name_company, emails, address_city, address_state, address_zip, contact_types, interests, bar_number, suffix

	FROM members

	LEFT JOIN

	(
	SELECT mem_id, contact_types, interests FROM

		(SELECT member_id AS mem_id, GROUP_CONCAT(note SEPARATOR ", ") AS contact_types FROM member_interests WHERE note_type='.PROFILE_CONTACT_TYPE.' GROUP BY member_id ORDER BY member_id) AS ctypes

		LEFT JOIN

		(SELECT member_id, GROUP_CONCAT(note SEPARATOR ", ") AS interests FROM member_interests WHERE note_type='.PROFILE_AREA_OF_INTEREST.' GROUP BY member_id ORDER BY member_id) AS cinterests

		ON (ctypes.mem_id = cinterests.member_id)

	)
	AS mem_interests

	ON (members.id = mem_interests.mem_id)

LEFT JOIN member_addresses ma ON (members.id = ma.member_id AND ma.a_type="MA") ';

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