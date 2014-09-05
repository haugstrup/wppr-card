<?php

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories(array(

	app_path().'/commands',
	app_path().'/controllers',
	app_path().'/models',
	app_path().'/database/seeds',

));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a basic log file setup which creates a single file for logs.
|
*/

Log::useFiles(storage_path().'/logs/laravel.log');

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

App::error(function(Exception $exception, $code)
{
    Log::error($exception);

    $trace = $exception->getTrace();

    // Hacked together opbeat integration.
    $ch = curl_init("https://opbeat.com/api/v1/organizations/{$_ENV['OPBEAT_ORG']}/apps/{$_ENV['OPBEAT_APP']}/errors/");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $query = Request::query();
    $payload = json_encode(array(
      "message" => $exception->getMessage(),
      "stacktrace" => array("trace" => $exception->getTraceAsString()),
      "culprit" => $trace[0]['class']."@".$trace[0]['function'],
      "http" => array(
        "method" => Request::method(),
        "url" => Request::url(),
        "query_string" => $query ? $query["query"] : '',
        "data" => Request::isMethod('post') ? Input::all() : new stdClass(),
      ),
    ));

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Authorization: Bearer ' . $_ENV['OPBEAT_SECRET'],
      "Content-type: application/json",
      'User-Agent: SFPD/1.0'
    ));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

    curl_exec($ch);
    curl_close($ch);

});

/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenance mode is in effect for the application.
|
*/

App::down(function()
{
	return Response::make("Be right back!", 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

require app_path().'/filters.php';
