<?php

include_once "Team.php";

class League {
    static $points = 3;
    private $teams = array();

    function getTeam($teamName) {
        if (! array_key_exists ( $teamName, $this->teams )) {
            $team = new Team ( $teamName );
            $this->teams[$teamName] = $team;
        } else {
            $team = $this->teams[$teamName];
        }
        return $team;
    }
    function getTeams() {
        return $this->teams;
    }
   
    function sortTable() {
        usort($this->teams, function($a , $b) {
        	$summaryA = $a->getSummary();
            $pointsA = $summaryA['points'];
            $summaryB = $b->getSummary();
            $pointsB = $summaryB['points'];
            
            if ($pointsA == $pointsB) {
               return 0;
            }
            return ($pointsA > $pointsB) ? -1 : 1;
        });
    }
 
    function getTable() {
        $table = array();
        $position = 1;
        foreach ($this->teams as $team) {
        	$teamSummary = $team->getSummary();
        	$teamSummary['position'] = $position;
            $table[] = $teamSummary;
        	$position++;
        }
        return $table;
    }
}
