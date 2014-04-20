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

 //
 // Names
 //
 
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
} )->conditions ( array( "number" => "\d{1,2}" ) );

$app->get ( '/name/:number',
function ($number) use ($app) {
	header ( "Content-Type: text/plain" );
	echo implode ( "\n", NameManager::getNames ( $number ) );
} )->conditions ( array( "number" => "\d{1,2}" ) );

//
// Football
//

$app->get ( '/sports/league/', 'json',
function () use ($app) {
	$app->render(200, array('data' => LeagueManager::getTable () ) );
} );

$app->get ( '/sports/league/:country', 'json',
function ($country) use ($app) {
	$app->render(200, array('data' => LeagueManager::getTable( $country ) ) );
} );

$app->get ( '/sports/league/:country/:division', 'json',
function ($country, $division)use ($app) {
	$app->render(200, array('data' => LeagueManager::getTable ( $country, $division ) ) );
} );

$app->get ( '/sports/news/:country', 'json',
function ($country)use ($app) {
	$app->render(200, array('data' => LeagueManager::getNews ( $country ) ) );
} );

$app->get ( '/sports/news/:country/:division',  'json',
function ($country, $division) use ($app) {
	$app->render(200, array('data' => LeagueManager::getNews ( $country, $division ) ) );
} );

$app->get ( '/sports/update',  'json',
function () use ($app) {
	LeagueManager::downloadAllLeagues ();
	$app->render(200, array());	
} );

//
// Reset
// 


$app->get ( '/reset/website', 'json',
function () use ($app) {			
	$app->render(200, array('data' => PasswordResetManager::getList()));
} );

$app->put ( '/reset/website/:id', 'json',
function ($id) use ($app) {
	$record = $app->request()->getBody();
	PasswordResetManager::update($record, $id);
	$app->render(200, array());	
} );

$app->post ( '/reset/website', 'json',
function () use ($app) {
	$record = $app->request()->getBody();
	PasswordResetManager::insert($record);
	$app->render(200, array());
} );

$app->delete ( '/reset/website/:id', 'json',
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


