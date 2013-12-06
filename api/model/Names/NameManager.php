<?php


/**
 * This class exposes a method that will give you back random names. It is
 * useful to generate
 * @author Gerik Bonaert
 *
 */
class NameManager {
	public static function getNames($number = 1) {
		$lastNames = NameManager::getLastNames ( $number );
		$firstNames = NameManager::getFirstNames ( $number );
		$name = array();
		for($i = 0; $i < $number; $i ++) {
			$name[] = $firstNames[$i] . " " . $lastNames[$i];
		}
		return $name;
	}
	public static function getLastNames($number = 1) {
		return NameManager::randomLine ( "data/last_names.txt", 12484, $number );
	}
	public static function getFirstNames($number = 1) {
		return NameManager::randomLine ( "data/first_names.txt", 8607, $number );
	}
	private static function randomLine($fileName, $maxLines, $number) {
		$names = array();
		$lines = array();
		for($i = 0; $i < $number; $i ++) {
			$lines[] = rand ( 1, $maxLines );
		}
		$stopLine = max ( $lines );
		$handle = fopen ( $fileName, "r" );
		if ($handle) {
			$random_line = null;
			$line = null;
			$count = 0;
			while ( ($line = fgets ( $handle, 4096 )) !== false ) {
				$count ++;
				if (in_array ( $count, $lines )) {
					$names[] = trim ( $line );
				}
				if ($stopLine < $count) {
					break;
				}
			}
			fclose ( $handle );
			return $names;
		}
	}
}