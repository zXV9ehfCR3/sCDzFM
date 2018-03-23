<?php
$HPKey = '63727b99-1128-4d37-bfd1-7bf2c48dc1af';
$player = $_GET['name'];
$arrContextOptions=array(
"ssl"=>array(
"verify_peer"=>false,
"verify_peer_name"=>false,
),
);  
//$json = file_get_contents('./test_player.json');
$json = file_get_contents('https://api.hypixel.net/player?key=' . $HPKey . '&name=' . $player . '', false, stream_context_create($arrContextOptions));
//    API COMMENTED OUT - USING DOWNLOADED VERSION
$RawPlayer = json_decode($json, true);
if ($RawPlayer['player'] == null) {
$json = file_get_contents('https://api.hypixel.net/player?key=' . $HPKey . '&uuid=' . $player . '', false, stream_context_create($arrContextOptions));
//    API COMMENTED OUT - USING DOWNLOADED VERSION
$RawPlayer = json_decode($json, true);
if ($RawPlayer['player'] == null) {
header("Location: ../index.php");
}
}
?>
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="content-language" content="en-us" />
    <title>BSG - Stats
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="../etc/jquery-3.3.1.min.js">
    </script>
    <script src="main.js">
    </script>
    <link rel="stylesheet" type="text/css" media="screen" href="../etc/main.css" />
  </head>
  <body>
    <header>
      <div id="div-title">
        <h1 id="title">Hypixel | Blitz Survival Games Stats
        </h1>
        <a href="..">
          <button id="back-button">&#8678;
          </button>
        </a>
      </div>
      <br/>
    </header>
    <main id="bsg-stats">
<?php 
$bsg = file_get_contents('../etc/hungergames.json');
$bsgInfo = json_decode($bsg, true);

// Usernames

echo '<div class="statbox">';
echo '<h1 id="username">' . $RawPlayer['player']['displayname'] . '</h1>';
echo '</div>';

// Wins + Kills + Deaths Safeguards

if ($RawPlayer['player']['stats']['HungerGames']['kills'] != null) {
	$kills = $RawPlayer['player']['stats']['HungerGames']['kills'];
}
else {
	$kills = 0;
}

if ($RawPlayer['player']['stats']['HungerGames']['deaths'] == null) {
	$deaths = 1;
}
else {
	$deaths = $RawPlayer['player']['stats']['HungerGames']['deaths'];
}

if ($RawPlayer['player']['stats']['HungerGames']['wins'] != null) {
	$wins = $RawPlayer['player']['stats']['HungerGames']['wins'];
}
else {
	$wins = 0;
}

if ($RawPlayer['player']['stats']['HungerGames']['wins_teams'] == null) {
	$team_wins = 0;
}
else {
	$team_wins = $RawPlayer['player']['stats']['HungerGames']['wins_teams'];
}

// General Stats

echo '<div class="statbox" id="general">';
echo '<h1 id="general-header">General Stats</h1>';
echo '<div class="dropdown">';
echo '<div class="dropdown-header">';
echo '<h2><a href="#kills" data-elem="+" id="kills">Kill Information</a></h2>';
echo '</div>';
echo '<div class="dropdown-body kills">';
echo '<p><b>Total Kills:</b> ' . number_format($kills) . '</p>';

if ($RawPlayer['player']['stats']['HungerGames']['deaths'] == null) {
	echo '<p><b>Total Deaths:</b> 0</p>';
}
else {
	echo '<p><b>Total Deaths:</b> ' . number_format($deaths) . '</p>';
}
$rambo = $RawPlayer['player']['achievements']['blitz_war_veteran'];
if ($rambo == null) {
  $rambo = 0;
}

$kdr = $kills / $deaths;
$kdr = number_format((float)$kdr, 3, '.', '');
echo '<p><b>KDR (Kill-Death Ratio):</b> ' . $kdr . '</p>';
echo '</div>';
echo '</div>';
echo '<div class="dropdown">';
echo '<div class="dropdown-header">';
echo '<h2><a href="#wins" data-elem="+" id="wins">Win Information</a></h2>';
echo '</div>';
echo '<div class="dropdown-body wins">';
echo '<p><b>Solo wins:</b> ' . number_format($wins) . '</p>';
echo '<p><b>Team wins:</b> ' . number_format($team_wins) . '</p>';
echo '<p><b>Total Wins:</b> ' . number_format(($wins + $team_wins)) . '</p>';
$wlr = ($team_wins + $wins) / ($team_wins + $wins + $deaths);
$wlr = number_format((float)$wlr, 3, '.', '');
echo '<p><b>W/L Ratio:</b> ' . $wlr . '</p>';
echo '<p><b>Rambo Wins:</b> ' . $rambo . '</p>';
echo '</div>';
echo '</div>';
echo '</div>';
$kits = [];

// Coin + Kits Calculations

foreach($bsgInfo['kits']['list'] as $key => $value) {
	$lower_kit = strtolower($key);
	if (gettype($RawPlayer['player']['stats']['HungerGames'][$lower_kit]) == integer) {
		$kits[$lower_kit] = $RawPlayer['player']['stats']['HungerGames'][$lower_kit] + 1;
	}
}

$blitzes = [];

foreach($bsgInfo['blitzes']['list'] as $key => $value) {
	$lower_blitz = strtolower($key);
	foreach($RawPlayer['player']['stats']['HungerGames']['packages'] as $val) {
		if ($val == $lower_blitz) {
			$blitzes[$lower_blitz] = $bsgInfo['blitzes']['list'][$key]['cost'];
		}
	}
}

$coins_array = array(
	'2' => 80,
	'3' => 480,
	'4' => 1480,
	'5' => 4480,
	'6' => 16480,
	'7' => 66480,
	'8' => 166480,
	'9' => 416480,
	'10' => 1416480
);
$coins_array_non = array(
	'2' => 80,
	'3' => 400,
	'4' => 1000,
	'5' => 3000,
	'6' => 12000,
	'7' => 50000,
	'8' => 100000,
	'9' => 250000,
	'10' => 1000000
);
$has_two = 0;
$has_three = 0;
$has_four = 0;
$has_five = 0;
$has_six = 0;
$has_seven = 0;
$has_eight = 0;
$has_nine = 0;

foreach($kits as $key => $value) {
	if ($value == 2) {
		$coins+= $coins_array['2'];
		$has_two+= 1;
	}
	else
	if ($value == 3) {
		$coins+= $coins_array['3'];
		$has_three+= 1;
	}
	else
	if ($value == 4) {
		$coins+= $coins_array['4'];
		$has_four+= 1;
	}
	else
	if ($value == 5) {
		$coins+= $coins_array['5'];
		$has_five+= 1;
	}
	else
	if ($value == 6) {
		$coins+= $coins_array['6'];
		$has_six+= 1;
	}
	else
	if ($value == 7) {
		$coins+= $coins_array['7'];
		$has_seven+= 1;
	}
	else
	if ($value == 8) {
		$coins+= $coins_array['8'];
		$has_eight+= 1;
	}
	else
	if ($value == 9) {
		$coins+= $coins_array['9'];
		$has_nine+= 1;
	}
	else
	if ($value == 10) {
		$coins+= $coins_array['10'];
		$has_ten+= 1;
	}

	$coins+= $bsgInfo['kits']['list'][strtoupper($key) ]['cost'];
}

$coins_used_on_kits = $coins;
$kit_count_array = array(
	'2' => $has_two,
	'3' => $has_three,
	'4' => $has_four,
	'5' => $has_five,
	'6' => $has_six,
	'7' => $has_seven,
	'8' => $has_eight,
	'9' => $has_nine,
);
$top_val = 0;

foreach($kit_count_array as $key => $value) {
	$key_int = (int)$key + 1;
	$key = (string)$key_int;
	if ($value != 0) {
		if ($top_val < $coins_array_non[$key]) {
			$top_val = $coins_array_non[$key];
		}
	}
}

foreach($blitzes as $value) {
	$coins+= $value;
}

$coins_used_on_stars = $coins - $coins_used_on_kits;
$current_coins = $RawPlayer['player']['stats']['HungerGames']['coins'];

if ($current_coins > 1000000) $top_val = 1250000;

if ($current_coins > 1250000) $top_val = 1350000;

if ($current_coins > 1350000) $top_val = 1400000;

if ($current_coins > 1400000) $top_val = 1412000;

if ($current_coins > 1412000) $top_val = 1415000;

if ($current_coins > 1415000) $top_val = 1416000;

if ($current_coins > 1416000) $top_val = 1416400;

if ($current_coins > 1416400) $top_val = 1414380;

if ($current_coins > 1416480) $top_val = $current_coins * 2;

if ($top_val * 2 == $current_coins) {
	$coins_from_next_x = 'You are ' . $current_coins . ' coins above the most possible coins needed to buy an X.';
}
else if ($has_ten == 32) {
  $coins_from_next_x = 'Congrats, you don\'t have any Xs to buy at this time... Go outside.';
}
else {
	$coins_from_next_x = $top_val - $current_coins;
}

// Coin + Kits Stats

echo '<div class="statbox">';
echo '<h1 id="coin-kits-header">Extras</h1>';
echo '<div class="dropdown">';
echo '<div class="dropdown-header">';
echo '<h2><a href="#coins" data-elem="+" id="coins">Coins</a></h2>';
echo '</div>';
echo '<div class="dropdown-body coins">';
echo '<p><b>Total Coins:</b> ' . number_format($coins) . '</p>';
echo '<p><b>Current Coins:</b> ' . number_format($current_coins) . '</p>';
echo '<p><b>Coins spent on kits:</b> ' . number_format($coins_used_on_kits) . '</p>';
echo '<p><b>Coins spent on stars:</b> ' . number_format($coins_used_on_stars) . '</p>';
if (gettype($coins_from_next_x) != string) {
  echo '<p><b>Coins from next X:</b> ' . number_format($coins_from_next_x) . '</p>';
} else {
  echo '<p>' . $coins_from_next_x . '</p>';
}

echo '</div>';
echo '</div>';
echo '<div class="dropdown">';
echo '<div class="dropdown-header">';
echo '<h2><a href="#kits" data-elem="+" id="kits">Kits</a></h2>';
echo '</div>';
echo '<div class="dropdown-body kits">';

if ($has_ten > 0) {
	echo '<p id="x"><b>X</b>: ';
	foreach($kits as $key => $value) {
		if ($value == 10) {
			echo $key . ' ';
		}
	}

	echo '</p>';
}

if ($has_nine > 0) {
	echo '<p><b>IX</b>: ';
	foreach($kits as $key => $value) {
		if ($value == 9) {
			echo $key . ' ';
		}
	}

	echo '</p>';
}

if ($has_eight > 0) {
	echo '<p><b>VIII</b>: ';
	foreach($kits as $key => $value) {
		if ($value == 8) {
			echo $key . ' ';
		}
	}

	echo '</p>';
}

if ($has_seven > 0) {
	echo '<p><b>VII</b>: ';
	foreach($kits as $key => $value) {
		if ($value == 7) {
			echo $key . ' ';
		}
	}

	echo '</p>';
}

if ($has_six > 0) {
	echo '<p><b>VI</b>: ';
	foreach($kits as $key => $value) {
		if ($value == 6) {
			echo $key . ' ';
		}
	}

	echo '</p>';
}

if ($has_five > 0) {
	echo '<p><b>V</b>: ';
	foreach($kits as $key => $value) {
		if ($value == 5) {
			echo $key . ' ';
		}
	}

	echo '</p>';
}

if ($has_four > 0) {
	echo '<p><b>IV</b>: ';
	foreach($kits as $key => $value) {
		if ($value == 4) {
			echo $key . ' ';
		}
	}

	echo '</p>';
}

if ($has_three > 0) {
	echo '<p><b>III</b>: ';
	foreach($kits as $key => $value) {
		if ($value == 3) {
			echo $key . ' ';
		}
	}

	echo '</p>';
}

if ($has_two > 0) {
	echo '<p><b>II</b>: ';
	foreach($kits as $key => $value) {
		if ($value == 2) {
			echo $key . ' ';
		}
	}

	echo '</p>';
}
echo '</div>';
echo '</div>';
// Default Calculations
$default_kit = $RawPlayer['player']['stats']['HungerGames']['defaultkit'];
if ($default_kit == null) {
  $default_kit = "No Default Kit";
}
$finisher = $RawPlayer['player']['stats']['HungerGames']['chosen_finisher'];
if ($finisher == null) {
  $finisher = "No Finisher";
}
$taunt = $RawPlayer['player']['stats']['HungerGames']['chosen_taunt'];
if ($taunt == null) {
  $taunt = "No";
}

echo '<div class="dropdown">';
echo '<div class="dropdown-header">';
echo '<h2><a href="#defaults" data-elem="+" id="defaults">Information on Defaults</a></h2>';
echo '</div>';
echo '<div class="dropdown-body defaults">';
echo '<p><b>Default Kit:</b> ' . $default_kit . ' ' . $kits[$default_kit] . '</p>';
echo '<p><b>Selected Finisher:</b> ' . ucwords(strtolower($finisher)) . '</p>';
echo '<p><b>Selected Taunt:</b> ' . ucwords(strtolower(str_replace('_',' ', $taunt))) . ' Taunt</p>';
echo '</div>';
echo '</div>';
echo '</div>';
?>
    </main>
  </body>
</html>