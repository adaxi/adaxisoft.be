<?php
include_once 'libs/DomCrawler/Crawler.php';


class RtpScraper {
	private $rawData;
	function __construct($file) {
		$html = file_get_contents ( $file );
		
		$crawler = new Symfony\Component\DomCrawler\Crawler ();
		$crawler->addHtmlContent ( $html );
		
		$tables = $crawler->filterXPath ( "//table[@class='CalendarioTable']" );
		foreach ( $tables as $table ) {
			foreach ( $table->childNodes as $tr ) {
				if ($tr->nodeName !== 'tr') {
					continue;
				}
				$match = array();
				$hasScore = false;
				$rowNumber = $tr->childNodes->length;
				for($i = 0; $i < $rowNumber; $i ++) {
					$td = $tr->childNodes->item ( $i );
					if (! $td instanceof DOMElement || $td->nodeName !== 'td') {
						continue;
					}
					$attributes = $td->attributes;
					$attribute = $attributes->getNamedItem ( 'class' );
					if (! $attribute) {
						continue;
					}
					$class = $attribute->nodeValue;
					
					if ($class === "Colun0") {
						$match['HomeTeam'] = trim ( $td->nodeValue );
					} elseif ($class === "Colun2") {
						$match['AwayTeam'] = trim ( $td->nodeValue );
					} elseif ($class === 'Colun3') {
						if (! preg_match ( '/\d+-\d+/', $td->nodeValue )) {
							continue;
						}
						$hasScore = true;
						$score = explode ( '-', $td->nodeValue );
						$match['FTHG'] = trim ( $score[0] );
						$match['FTAG'] = trim ( $score[1] );
					}
				}
				if ($hasScore) {
					$this->rawData[] = $match;
				}
			}
		}
	}
	function getRawData() {
		return $this->rawData;
	}
}