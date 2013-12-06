<?php

include_once "Scraper.php";
include_once "League.php";

class LeagueManager {
	
	static function listTable( $country = 'portugal' ) {
		$file = "http://www.football-data.co.uk/mmz4281/1314/P1.csv";
		$scraper = new Scraper ( $file );
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
}