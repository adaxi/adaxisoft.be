<?php

include_once "model/League.php";
include_once "scrapers/FootballDataScraper.php";
include_once "Configuration.php";

class LeagueManager {
	
	static function listTable( $country = 'portugal', $division = 1 ) {
		
		$config = Configuration::getConfiguration();
		
		$league = "$country..$division";
		
		if (!array_key_exists($league, $config)) {
			throw new Exception("This league does not exist.");
		}
		
		$league = $config[$league];
		$file = $league['local'];
		$scraper = new FootballDataScraper( $file );
		$league = new League ();
		foreach ( $scraper->getRawData () as $rawMatch ) {
			$homeTeam = $league->getTeam ( $rawMatch["HomeTeam"] );
			$awayTeam = $league->getTeam ( $rawMatch["AwayTeam"] );
			$scoreHomeTeam = $rawMatch["FTHG"];
			$scoreAwayTeam = $rawMatch["FTAG"];
		
			$match = new Match ( $homeTeam, $awayTeam, $scoreHomeTeam, $scoreAwayTeam );
			$homeTeam->addHomeMatch ( $match );
			$awayTeam->addAwayMatches ( $match );
		}
		$league->sortTable();
		return $league->getTable();
	}
	
	static function downloadAllLeagues() {
		$config = Configuration::getConfiguration();
		foreach ($config as $item) {
			$league = file_get_contents($item['remote']);
			file_put_contents($item['local'], $league);
		}
	}
}