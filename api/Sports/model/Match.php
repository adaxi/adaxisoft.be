<?php

include_once "Team.php";

class Match {
    private $homeTeam;
    private $awayTeam;
    private $scoreHomeTeam;
    private $scoreAwayTeam;
    function __construct($homeTeam, $awayTeam, $scoreHomeTeam, $scoreAwayTeam) {
        $this->awayTeam = $awayTeam;
        $this->homeTeam = $homeTeam;
        $this->scoreAwayTeam = $scoreAwayTeam;
        $this->scoreHomeTeam = $scoreHomeTeam;
    }
    function isDraw() {
        return $this->scoreHomeTeam === $this->scoreAwayTeam;
    }
    function isWon($team) {
        if ($team == null) {
            throw new Exception ( "No team was given." );
        }
        if ($team !== $this->homeTeam && $team !== $this->awayTeam) {
            $givenTeamName = $team->getName ();
            $homeTeamName = $this->homeTeam->getName ();
            $awayTeamName = $this->awayTeam->getName ();
            throw new Exception (
                    "Given team was not part of the match! Given: '$givenTeamName', Expected: '$homeTeamName' or '$awayTeamName'" );
        }
        if ($team === $this->homeTeam &&
                 $this->scoreHomeTeam > $this->scoreAwayTeam) {
            return true;
        }
        if ($team === $this->awayTeam &&
                 $this->scoreHomeTeam < $this->scoreAwayTeam) {
            return true;
        }
        return false;
    }
    function isLost($team) {
        return ! $this->isDraw () && ! $this->isWon ( $team );
    }
    function toString() {
        return $this->homeTeam->getName () . " " . $this->scoreHomeTeam . " - " .
                 $this->awayTeam->getName () . " " . $this->scoreAwayTeam;
    }
}

