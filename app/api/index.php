<?php
/**
 * Step 1: Require the Slim Framework
 *
 */
require 'vendor/autoload.php';

include_once "Sports/LeagueManager.php";
include_once "Names/NameManager.php";
include_once "Reset/PasswordResetManager.php";


/**
 * Step 2: Instantiate a Slim application
 */
$app = new \Slim\Slim ();

$app->add(new \Slim\Middleware\ContentTypes());
function json(){
	$app = \Slim\Slim::getInstance();
	$app->view(new \JsonApiView());
	$app->add(new \JsonApiMiddleware());
}


/**
 * Step 3: Define the Slim application routes
 *
 * Here we define several Slim application routes that respond
 * to appropriate HTTP request methods.
 */

$app->get ( '/name/',
function () use ($app) {
	header ( "Content-Type: text/plain" );
	echo implode ( "\n", NameManager::getNames () );
} );

$app->get ( '/name/first',
function () {
	header ( "Content-Type: text/plain" );
	echo implode ( "\n", NameManager::getFirstNames () );
} );

$app->get ( '/name/last',
function () use ($app){
	header ( "Content-Type: text/plain" );
	echo implode ( "\n", NameManager::getLastNames () );
} );

$app->get ( '/name/first/:number',
function ($number) use ($app) {
	header ( "Content-Type: text/plain" );
	echo implode ( "\n", NameManager::getFirstNames ( $number ) );
} )->conditions ( array( "number" => "\d{1,2}" ) );

$app->get ( '/name/last/:number',
		function ($number) use ($app) {
			header ( "Content-Type: text/plain" );
			echo implode ( "\n", NameManager::getLastNames ( $number ) );
		} )->conditions ( array(
								"number" => "\d{1,2}"
) );

$app->get ( '/name/:number',
		function ($number) use ($app) {
			header ( "Content-Type: text/plain" );
			echo implode ( "\n", NameManager::getNames ( $number ) );
		} )->conditions ( array(
								"number" => "\d{1,2}"
) );

$app->get ( '/sports/league/',
		function () use ($app){
			header ( "Content-Type: application/json" );
			echo json_encode ( LeagueManager::getTable () );
		} );

$app->get ( '/sports/league/:country',
		function ($country) use ($app){
			header ( "Content-Type: application/json" );
			try {
				echo json_encode ( LeagueManager::getTable( $country ) );
			} catch ( Exception $e ) {
				echo json_encode (
						array(
							'result' => 'fail',
							'message' => $e->getMessage ()
						) );
			}
		} );

$app->get ( '/sports/league/:country/:division',
		function ($country, $division)use ($app) {
			header ( "Content-Type: application/json" );
			try {
				echo json_encode (
						LeagueManager::getTable ( $country, $division ) );
			} catch ( Exception $e ) {
				echo json_encode (
						array(
							'result' => 'fail',
							'message' => $e->getMessage ()
						) );
			}
		} );

$app->get ( '/sports/news/:country',
		function ($country)use ($app) {
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
		function ($country, $division) use ($app){
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
		function () use ($app) {
			header ( "Content-Type: application/json" );
			LeagueManager::downloadAllLeagues ();
			echo json_encode ( array(
									'result' => 'ok'
			) );
		} );


$app->get ( '/password_reset/website', 'json',
function () use ($app) {			
	$app->render(200, array('data' => PasswordResetManager::getList()));
} );

$app->put ( '/password_reset/website/:id', 'json',
function ($id) use ($app) {
	$record = $app->request()->getBody();
	PasswordResetManager::update($record, $id);
	$app->render(200, array());	
} );

$app->post ( '/password_reset/website', 'json',
function () use ($app) {
	$record = $app->request()->getBody();
	PasswordResetManager::insert($record);
	$app->render(200, array());
} );

$app->delete ( '/password_reset/website/:id', 'json',
function ($id) use ($app) {
	PasswordResetManager::delete($id);
	$app->render(200, array());
} );

/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run ();


