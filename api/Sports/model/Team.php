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
    function getSummary() {
        $homeWins = 0;
        $homeDraws = 0;
        $homeLosses = 0;
        foreach ( $this->homeMatches as $match ) {
            if ($match->isWon ($this)) {
                $homeWins ++;
            } elseif ($match->isDraw ()) {
                $homeDraws ++;
            } else {
                $homeLosses ++;
            }
        }
        
        $awayWins = 0;
        $awayDraws = 0;
        $awayLosses = 0;
        foreach ( $this->awayMatches as $match ) {
            if ($match->isWon ($this)) {
                $awayWins ++;
            } elseif ($match->isDraw ()) {
                $awayDraws ++;
            } else {
                $awayLosses ++;
            }
        }
        
        
        return array(
            'name' => $this->name,
        	'played' => count($this->awayMatches) + count($this->homeMatches),
            'points' => ($homeWins * League::$points + $homeDraws) + ($awayWins * League::$points + $awayDraws),
            'wins' => $homeWins + $awayWins,
            'draws' => $homeDraws + $awayDraws,
            'losses' => $homeLosses + $awayLosses,
            'pointsHome' => $homeWins * League::$points + $homeDraws,
            'homeWins' => $homeWins,
            'homeDraws' =>$homeDraws,
            'homeLosses' =>$homeLosses,
            'pointsAway' => $awayWins * League::$points + $awayDraws,
            'awayWins' =>$awayWins,
            'awayDraws' =>$awayDraws,
            'awayLosses' =>$awayLosses,
        )
        ;
    }
}