<?php
include_once 'libs/DomCrawler/Crawler.php';


class EurofotbalScraper {
	private $rawData;
	function __construct($file) {
		$html = file_get_contents ( $file );
		
		$crawler = new Symfony\Component\DomCrawler\Crawler ();
		$crawler->addHtmlContent ( $html );
		
		$table = $crawler->filterXPath ( "//table[@class='matches']" );
		foreach ( $table->children () as $tr ) {
			$match = array();
			$hasScore = false;
			foreach ( $tr->childNodes as $td ) {
				$class = $td->attributes->getNamedItem ( 'class' )->nodeValue;
				if ($class === "teams") {
					$teams = explode ( ' - ', $td->nodeValue );
					$match['HomeTeam'] = trim ( $teams[0] );
					$match['AwayTeam'] = trim ( $teams[1] );
				} elseif ($class === 'res nbr') {
					if (!preg_match('/\d+:\d+/', $td->nodeValue)) {
						continue;
					}
					$hasScore = true;
					$score = explode ( ':', $td->nodeValue );
					$match['FTHG'] = trim ( $score[0] );
					$match['FTAG'] = trim ( $score[1] );
					break;
				}
			}
			if ($hasScore) {
				$this->rawData[] = $match;
			}
		}
		
	}
	function getRawData() {
		return $this->rawData;
	}
}