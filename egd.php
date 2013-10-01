<?php

// ---------------------------------------------------------------------------------------------------------------------
// fields: name,declared,rank,gor1,gor2,club,cc,link

add_shortcode('egd', 'igo_shortcode_egd');
function igo_shortcode_egd($atts) {
	extract(
		shortcode_atts(
			array(
				'players' => null,
				'fields' => 'name,declared,gor2,club,cc,link'
			),
			$atts
		)
	);
	// create player table
	if ($players != null) {
		$playerPins = preg_split("/\s*,\s*/", $players);

		// get the data
		$p = array();
		foreach ($playerPins as $pin) {
			$data = igo_get_player_data($pin);
			$p[$data['Gor'] . '_' . $data['Name'] . $data['Last_Name']] = $data;
		}
		// sort by rank
		krsort($p, SORT_NUMERIC);

		// build the table
		$head = "<table class='wgo-player-table'><thead><tr>";
		foreach (preg_split("/\s*,\s*/", $fields) as $field) {
			switch ($field) {
				case "name":
					$head .= "<th>Name</th>";
					break;
				case "declared":
					$head .= "<th>Rank</th>";
					break;
				case "rank":
					$head .= "<th>Rank</th>";
					break;
				case "gor1":
				case "gor2":
					$head .= "<th>GoR</th>";
					break;
				case "club":
					$head .= "<th>Club</th>";
					break;
				case "cc":
					$head .= "<th>Country</th>";
					break;
				case "link":
					$head .= "<th>EGD</th>";
					break;
				default:
					break;
			}
		}

		$rows = "";
		foreach ($p as $data) {
			$rows .= "<tr>";
			foreach (preg_split("/\s*,\s*/", $fields) as $field) {
				switch ($field) {
					case "name":
						$rows .= "<td>" . $data['Name'] . " " . $data['Last_Name'] . "</td>";
						break;
					case "declared":
						$rows .= "<td>" . $data['Grade'] . "</td>";
						break;
					case "rank":
						$rows .= "<td>" . igo_gor_to_rank($data['Gor']) . "</td>";
						break;
					case "gor1":
						$rows .= "<td>" . $data['Gor'] . "</td>";
						break;
					case "gor2":
						$rows .= "<td>" . $data['Gor'] . " (". igo_gor_to_rank($data['Gor']) . ")</td>";
						break;
					case "club":
						$rows .= "<td>" . $data['Club'] . "</td>";
						break;
					case "cc":
						$rows .= "<td>" . $data['Country_Code'] . "</td>";
						break;
					case "link":
						$rows .= "<td><a href='http://www.europeangodatabase.eu/EGD/Player_Card.php?&key=" . $data['Pin_Player'] . "'>link</a></td>";
						break;
					default:
						break;
				}
			}
			$rows .= "</tr>";
		}
		return $head . "</tr></thead><tbody>" . $rows . "</tbody></table>";
	}
}

// ---------------------------------------------------------------------------------------------------------------------

function igo_get_player_data($pin) {
	$json = get_post_meta(get_the_ID(), $pin, true);
	$data = json_decode($json, true);
	if ($data != NULL) {
		if(!array_key_exists('refresh', $data)) {
			$data['refresh'] = 5;
		} else {
			$data['refresh']--;
		}
	}
	if ($data == NULL || $data['refresh'] == 0) {
		$json = file_get_contents('http://www.europeangodatabase.eu/EGD/GetPlayerDataByPIN.php?pin=' . $pin);
		$data = json_decode($json, true);
	}
	update_post_meta(get_the_ID(), $pin, json_encode($data));
	return $data;
}

// ---------------------------------------------------------------------------------------------------------------------

function igo_gor_to_rank($gor) {
	// make sure the go rank value is always a number which can be divided by 100, e.g. 478 becomes 500, 743 becomes 700.
	// Based on 2100 = 1D we can simply calculate the rank
	$gor = $gor - ($gor % 50);
	$gor = $gor + ($gor % 100);
	switch ($gor) {
		case 2800:
			return "8d";
		case 2700:
			return "7d";
		case 2600:
			return "6d";
		case 2500:
			return "5d";
		case 2400:
			return "4d";
		case 2300:
			return "3d";
		case 2200:
			return "2d";
		case 2100:
			return "1d";

		case 2000:
			return "1k";
		case 1900:
			return "2k";
		case 1800:
			return "3k";
		case 1700:
			return "4k";
		case 1600:
			return "5k";
		case 1500:
			return "6k";
		case 1400:
			return "7k";
		case 1300:
			return "8k";
		case 1200:
			return "9k";
		case 1100:
			return "10k";
		case 1000:
			return "11k";
		case 900:
			return "12k";
		case 800:
			return "13k";
		case 700:
			return "14k";
		case 600:
			return "15k";
		case 500:
			return "16k";
		case 400:
			return "17k";
		case 300:
			return "18k";
		case 200:
			return "19k";
		case 100:
			return "20k";
		default:
			return "?";
	}
}
?>