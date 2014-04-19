<?php

include_once "Match.php";
include_once "League.php";

class Team {
    private $name;
    private $awayMatches;
    private $homeMatches;
    function __construct($name) {
        $this->name = $name;
        $this->homeMatches = array();
        $this->awayMatches = array();
    }
    function getName() {
        return $this->name;
    }
    function getAllMatches() {
        return array_merge ( $this->homeMatches, $this->awayMatches );
    }
    function getHomeMatches() {
        return $this->homeMatches;
    }
    function getAwayMatches() {
        return $this->awayMatches;
    }
    function addHomeMatch($match) {
        $this->homeMatches[] = $match;
    }
    function addAwayMatches($match) {
        $this->awayMatches[] = $match;
    }
    function getWonMatches() {
        $allMatches = $this->getAllMatches ();
        $wonMatches = array();
        foreach ( $allMatches as $match ) {
            if ($match->isWon ( $this )) {
                $wonMatches[] = $match;
            }
        }
        return $wonMatches;
    }
    function getLostMatches() {
        $allMatches = $this->getAllMatches ();
        $lostMatches = array();
        foreach ( $allMatches as $match ) {
            if ($match->isLost ( $this )) {
                $lostMatches[] = $match;
            }
        }
        
        return $lostMatches;
    }
    function getDrawMatches() {
        $allMatches = $this->getAllMatches ();
        $drawMatches = array();
        foreach ( $allMatches as $match ) {
            if ($match->isDraw ( $this )) {
                $drawMatches[] = $match;
            }
        }
        return $drawMatches;
    }
    
    function getShapeSummary($totalGames = 0) {
		$allMatches = getAllMatches();
		usort($this->teams, function($a , $b) {
            $sequenceA = $a->getSequence();
            $sequenceB = $b->getSequence();
            
            if ($sequenceA == $sequenceB) {
               return 0;
            }
            return ($sequenceA > $sequenceB) ? -1 : 1;
        });
		
		$fiveMatches = array_slice($allMatches, 0 , 5);
		
		$homeWins = 0;
		$homeDraws = 0;
		$homeLosses = 0;
		$homeConceded = 0;
		$homeScored = 0;
		foreach ( $fiveMatches as $match ) {
			if ($match->isWon ($this)) {
				$homeWins ++;
			} elseif ($match->isDraw ()) {
				$homeDraws ++;
			} else {
				$homeLosses ++;
			}
			$homeConceded += $match->getScoreAwayTeam();
			$homeScored += $match->getScoreHomeTeam();
		}
		
		return array(
		'name' => $this->name,
		'played' => count($this->awayMatches) + count($this->homeMatches),
		'points' => ($homeWins * League::$points + $homeDraws) + ($awayWins * League::$points + $awayDraws),
		'wins' => $homeWins + $awayWins,

		);
    }
    
    function getSummary() {
        $homeWins = 0;
        $homeDraws = 0;
        $homeLosses = 0;
        $homeConceded = 0;
        $homeScored = 0;
        foreach ( $this->homeMatches as $match ) {
            if ($match->isWon ($this)) {
                $homeWins ++;
            } elseif ($match->isDraw ()) {
                $homeDraws ++;
            } else {
                $homeLosses ++;
            }
            $homeConceded += $match->getScoreAwayTeam();
            $homeScored += $match->getScoreHomeTeam();
        }
        
        $awayWins = 0;
        $awayDraws = 0;
        $awayLosses = 0;
        $awayConceded = 0;
        $awayScored = 0;
        foreach ( $this->awayMatches as $match ) {
            if ($match->isWon ($this)) {
                $awayWins ++;
            } elseif ($match->isDraw ()) {
                $awayDraws ++;
            } else {
                $awayLosses ++;
            }
            $awayConceded += $match->getScoreHomeTeam();
            $awayScored += $match->getScoreAwayTeam();
        }
        
        
        return array(
            'name' => $this->name,
        	'played' => count($this->awayMatches) + count($this->homeMatches),
            'points' => ($homeWins * League::$points + $homeDraws) + ($awayWins * League::$points + $awayDraws),
            'wins' => $homeWins + $awayWins,
        	'gScored' => $homeScored + $awayScored,
        	'gConceded' => $homeConceded + $awayConceded,
        	'gDifference' => $homeScored + $awayScored - $homeConceded - $awayConceded,
            'draws' => $homeDraws + $awayDraws,
            'losses' => $homeLosses + $awayLosses,
            'pointsHome' => $homeWins * League::$points + $homeDraws,
            'homeWins' => $homeWins,
            'homeDraws' =>$homeDraws,
            'homeLosses' =>$homeLosses,
        	'homeScore' => $homeScored,
        	'homeConceded' => $homeConceded,
        	'homeDifference' => $homeScored - $homeConceded,
            'pointsAway' => $awayWins * League::$points + $awayDraws,
            'awayWins' =>$awayWins,
            'awayDraws' =>$awayDraws,
            'awayLosses' =>$awayLosses,
        	'awayScore' => $awayScored,
        	'awayConceded' => $awayConceded,
        	'awayDifference' => $awayScored - $awayConceded,
        )
        ;
    }
}
