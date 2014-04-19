<?php
include_once "model/League.php";
include_once "scrapers/FootballDataScraper.php";
include_once "Configuration.php";


class LeagueManager {
	
	private static function scrapeLeague($country = 'portugal', $division = 1) {
		$config = Configuration::getConfiguration ();
		
		$league = "$country..$division";
		
		if (! array_key_exists ( $league, $config )) {
			throw new Exception ( "The league '$league' does not exist." );
		}
		
		$league = $config[$league];
		$file = $league['local'];
		include_once "scrapers/" . $league['scraper'] . ".php";
		$scraper = new $league['scraper'] ( $file );
		$league = new League ();
		$sequence = 0;
		foreach ( $scraper->getRawData () as $rawMatch ) {
			
			$homeTeam = $league->getTeam ( $rawMatch["HomeTeam"] );
			$awayTeam = $league->getTeam ( $rawMatch["AwayTeam"] );
			$scoreHomeTeam = $rawMatch["FTHG"];
			$scoreAwayTeam = $rawMatch["FTAG"];
				
			$match = new Match (
					$homeTeam,
					$awayTeam,
					$scoreHomeTeam,
					$scoreAwayTeam,
				    $sequence++ );
			$homeTeam->addHomeMatch ( $match );
			$awayTeam->addAwayMatches ( $match );
		}
		
		return $league;
	}
	
	static function getTable($country = 'portugal', $division = 1) {
		$league = LeagueManager::scrapeLeague($country, $division);
		$league->sortTable();
		return $league->getTable ();
	}
	
	static function getShapeTable($country = 'portugal', $division = 1) {
		$league = LeagueManager::scrapeLeague($country, $division);
		$league->sortTable();
		return $league->getTable ();
	}
	
	static function downloadAllLeagues() {
		$config = Configuration::getConfiguration ();
		foreach ( $config as $item ) {
			$league = file_get_contents ( $item['remote'] );
			file_put_contents ( $item['local'], $league );
		}
	}
	static function getNews($country = 'portugal', $division = 1) {
		$config = Configuration::getConfiguration ();
		$league = "$country..$division";
		
		if (! array_key_exists ( $league, $config )) {
			throw new Exception ( "This league does not exist." );
		}
		$league = $config[$league];
		return array(
					'feed' => $league['rss']
		);
	}
}