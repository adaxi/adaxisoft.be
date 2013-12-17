<?php
/**
 * Step 1: Require the Slim Framework
 *
 */
require 'lib/Slim/Slim.php';

include_once "Sports/LeagueManager.php";
include_once "Names/NameManager.php";

\Slim\Slim::registerAutoloader ();

/**
 * Step 2: Instantiate a Slim application
 */
$app = new \Slim\Slim ();

sleep(5);

/**
 * Step 3: Define the Slim application routes
 *
 * Here we define several Slim application routes that respond
 * to appropriate HTTP request methods.
 */

$app->get ( '/name/',
		function () {
			header ( "Content-Type: text/plain" );
			echo implode ( "\n", NameManager::getNames () );
		} );

$app->get ( '/name/first',
		function () {
			header ( "Content-Type: text/plain" );
			echo implode ( "\n", NameManager::getFirstNames () );
		} );

$app->get ( '/name/last',
		function () {
			header ( "Content-Type: text/plain" );
			echo implode ( "\n", NameManager::getLastNames () );
		} );

$app->get ( '/name/first/:number',
		function ($number) {
			header ( "Content-Type: text/plain" );
			echo implode ( "\n", NameManager::getFirstNames ( $number ) );
		} )->conditions ( array(
								"number" => "\d{1,2}"
) );

$app->get ( '/name/last/:number',
		function ($number) {
			header ( "Content-Type: text/plain" );
			echo implode ( "\n", NameManager::getLastNames ( $number ) );
		} )->conditions ( array(
								"number" => "\d{1,2}"
) );

$app->get ( '/name/:number',
		function ($number) {
			header ( "Content-Type: text/plain" );
			echo implode ( "\n", NameManager::getNames ( $number ) );
		} )->conditions ( array(
								"number" => "\d{1,2}"
) );

$app->get ( '/sports/league/',
		function () {
			header ( "Content-Type: application/json" );
			echo json_encode ( LeagueManager::listTable () );
		} );

$app->get ( '/sports/league/:country',
		function ($country) {
			header ( "Content-Type: application/json" );
			try {
				echo json_encode ( LeagueManager::listTable ( $country ) );
			} catch ( Exception $e ) {
				echo json_encode (
						array(
							'result' => 'fail',
							'message' => $e->getMessage ()
						) );
			}
		} );

$app->get ( '/sports/league/:country/:division',
		function ($country, $division) {
			header ( "Content-Type: application/json" );
			try {
				echo json_encode (
						LeagueManager::listTable ( $country, $division ) );
			} catch ( Exception $e ) {
				echo json_encode (
						array(
							'result' => 'fail',
							'message' => $e->getMessage ()
						) );
			}
		} );

$app->get ( '/sports/news/:country',
		function ($country) {
			header ( "Content-Type: application/json" );
			try {
				echo json_encode ( LeagueManager::getNews ( $country ) );
			} catch ( Exception $e ) {
				echo json_encode (
						array(
							'result' => 'fail',
							'message' => $e->getMessage ()
						) );
			}
		} );

$app->get ( '/sports/news/:country/:division',
		function ($country, $division) {
			header ( "Content-Type: application/json" );
			try {
				echo json_encode (
						LeagueManager::getNews ( $country, $division ) );
			} catch ( Exception $e ) {
				echo json_encode (
						array(
							'result' => 'fail',
							'message' => $e->getMessage ()
						) );
			}
		} );

$app->get ( '/sports/update',
		function () {
			header ( "Content-Type: application/json" );
			LeagueManager::downloadAllLeagues ();
			echo json_encode ( array(
									'result' => 'ok'
			) );
		} );

/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run ();


