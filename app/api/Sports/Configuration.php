<?php

class Configuration {
	
	static function getConfiguration() {
		$jsonConfig = file_get_contents("Sports/config/leagues.json");
		return json_decode($jsonConfig, true);
	}
	
}