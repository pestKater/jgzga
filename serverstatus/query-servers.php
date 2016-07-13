<?php
require_once("config.inc.php");
require_once("gameq/src/GameQ/Autoloader.php");

if (isset($_POST['query-servers']) && $_POST['query-servers'] == true)
{

	// Call the class, and add your servers.
	$gq = \GameQ\GameQ::factory();
	$gq->addServers($servers);

	// You can optionally specify some settings
	$gq->setOption('timeout', 3); //in seconds

	// Send requests, and parse the data
	$results = $gq->process();
	
	// Wrapper
	echo "<div class='server-wrapper'>\n";
	
	foreach ($results as $key => $server) {
		
		if($server['gq_online']) {
			
			// Feld für Server
			echo "<div class='server'>\n";
			
				// Hostname ausgeben
				$server['gq_hostname'] = preg_replace('@(sixupdater://([\w-.]+)+(:\d+)?(/([\w/_.-]*(\?\S+)?)?)?)@', '<a href="$1">$1</a>', $server['gq_hostname']);
				echo "<h4>".$server['gq_hostname']."</h4>";
			
				echo "<div class='infos'>\n";
				
					echo "<ul>\n";
						echo "<li><span>Adresse:</span> ". $server['gq_address'] . ":" . $server['port'] ."</li>\n";
						echo "<li><span>Spieler:</span> " . $server['gq_numplayers'] . "/" . $server['gq_maxplayers'] ."</li>\n";
					echo "</ul>";
				
				echo "</div>"; // Close Infos-left
				
				echo "<div class='infos'>\n";
					
					echo "<ul>\n";
				
					if($server['game_descr'] != "Arma 3") {
						$server['game_descr'] = substr($server['game_descr'], 0, 20);
						echo "<li><span>Mission:</span> " . $server['game_descr'] ."</li>\n";
						echo "<li><span>Karte:</span> " . $server['gq_mapname'] ."</li>\n";
					} else {
						echo "<li><span class='nomission'>Keine Mission gestartet</span></li>\n";
					}
					
					echo "</ul>";
					
				echo "</div>"; // Close Infos-right
				echo "<div class='clear-float'></div>\n";
			
			echo "</div>\n"; // Close Server
			
		}
		
	}
	
	echo "</div>\n"; // Close Server
}