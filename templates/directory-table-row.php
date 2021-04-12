<?php



$display_table .= '<tr '.$class.'><td><a href="search-result.php?id='.$row["id"].'">'.$row["full_name"].'</a></td><td width="120px"> '.$row["name_company"].'</td><td width="120px"> '.phoneNumberFormat( $work_phone ).'</td><td> '.$row["address_city"].'</td><td width="160px"><a href="search-result.php?id='.$row["id"].'">View Email</a></td><td> '.$row["contact_types"].'</td><td width="200px"> '.$row["interests"].'</td></tr>';