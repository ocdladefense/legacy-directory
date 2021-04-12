<?php


// =========================================================
// Query to retrieve search results
// =========================================================
	$query='
	
	SELECT members.id, CONCAT(name_last,", ",name_first) AS full_name, address_www, name_company, address_line_1, address_line_2, address_city, address_state, address_zip, contact_types, interests, publish, member_addresses.publish AS publish_address, bar_number, suffix, title
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