<?php

include_once 'libs/DomCrawler/Crawler.php';


class LeSoirScraper {
	private $rawData;
	function __construct($file) {
		$html = file_get_contents ( $file );

		$crawler = new Symfony\Component\DomCrawler\Crawler ();
		$crawler->addHtmlContent ( $html );

		$divs = $crawler->filterXPath ( "//div[@class='div_idalgo_content_calendar_match']" );
		foreach ( $divs as $div ) {
			$match = array();
			$hasScore = false;
			foreach ( $div->childNodes as $a ) {
				if ($a->nodeName !== 'a') {
					continue;
				}
				$attributes = $a->attributes;
				$attribute = $attributes->getNamedItem ( 'class' );
				if (! $attribute) {
					continue;
				}
				$class = $attribute->nodeValue;
				
				if (preg_match("/^a_idalgo_content_calendar_match_local /", $class)) {
					$match['HomeTeam'] = trim ( $a->nodeValue );
				} elseif (preg_match("/^a_idalgo_content_calendar_match_score /", $class)) {
					if (! preg_match ( '/\d+-\d+/', $a->nodeValue )) {
						continue;
					}
					$hasScore = true;
					$score = explode ( '-', $a->nodeValue );
					$match['FTHG'] = trim ( $score[0] );
					$match['FTAG'] = trim ( $score[1] );
				} elseif (preg_match("/^a_idalgo_content_calendar_match_visitor /", $class)) {
					$match['AwayTeam'] = trim ( $a->nodeValue );
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