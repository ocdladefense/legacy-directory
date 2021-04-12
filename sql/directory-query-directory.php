<?php


// =========================================================
// Query to retrieve search results
// =========================================================
$query = 'SELECT members.id, CONCAT(name_last,", ",name_first) AS full_name, address_www, name_company, address_line_1, address_line_2, address_city, address_state, address_zip, contact_types, interests, bar_number, suffix, title, ci.value AS work_phone, ci.publish AS work_phone_publish
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
		
LEFT JOIN member_addresses ma ON (members.id = ma.member_id AND ma.a_type="MA") 
LEFT JOIN member_contact_info ci ON (members.id = ci.contact_id AND ci.type="work") ';
?>