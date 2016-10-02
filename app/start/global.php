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

use Illuminate\Support\Facades\Session;
use Insight\Settings\Setting;

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

//Log::useFiles(storage_path().'/logs/laravel.log');

$logFile = 'log-'.php_sapi_name().'.txt';

Log::useDailyFiles(storage_path().'/logs/'.$logFile);

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
    if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException)
    {
        Log::error('NotFoundHttpException Route: ' . Request::url() );
    }
    Flash::error('Sorry, your request could not be completed.');
    Log::error($exception);
    //return Redirect::home();
});

// TODO: find out how to register multiple exceptions to use the same behaviour
App::error(function(Laracasts\Validation\FormValidationException $exception, $code)
{
    return Redirect::back()->withInput()->withErrors($exception->getErrors());
});

App::error(function(Insight\Sourcing\Exceptions\SourcingRequestImportFileException $exception, $code)
{
    Flash::error($exception->getErrors()['errorMessages']);
    Session::flash('errorDetails',$exception->getErrors()['errorDetails']);

    return Redirect::back()->withInput()->withErrors($exception->getErrors());
});

App::error(function(Insight\ProductRequests\Exceptions\ProductRequestUploadFileException $exception, $code)
{
    Flash::error($exception->getErrors()['errorMessages']);
    Session::flash('errorDetails',$exception->getErrors()['errorDetails']);

    return Redirect::back()->withInput()->withErrors($exception->getErrors());
});

App::error(function(Symfony\Component\HttpKernel\Exception\NotFoundHttpException $exception, $code)
{
    Log::error('NotFoundHttpException Route: ' . Request::url() );
//    Flash::error('Sorry, the page you were looking for was not found or does not exist.');
//    return Redirect::home();
});

App::error(function(Illuminate\Database\Eloquent\ModelNotFoundException $exception, $code)
{
    Log::error('ModelNotFoundException');
    Flash::error('Sorry, the page you were looking for was not found or does not exist.');
    return Redirect::home();
});

App::error(function(Insight\Portal\Exceptions\WebservicesUnavailableException $exception, $code)
{
    Log::error('WebservicesUnavailableException');
    Flash::error('Sorry, your request could not be completed at this time. Please try again later.');
    return Redirect::home();
});

$formExceptions = [
    'Insight\Sourcing\Exceptions\SourcingRequestFormException',
    'Insight\ProductRequests\Exceptions\ProductRequestFormException',
];

App::error(function(Exception $exception, $code) use ($formExceptions)
{
    if ( in_array(get_class($exception),$formExceptions))  {

        Log::error('SourcingRequestFormException', ['message' => $exception->getMessage(), 'errors' => $exception->getErrors()]);
        Flash::error($exception->getMessage());

        return Redirect::back()->withInput()->withErrors($exception->getErrors());
    }
    elseif (app('env') == 'production') {
        Flash::error('There was an error processing your request.');
        return Redirect::home();
    }


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



/*
|--------------------------------------------------------------------------
| Require The View Composers
|--------------------------------------------------------------------------
|
|
*/

require app_path().'/composers.php';



/*
|--------------------------------------------------------------------------
| 404 Handler // redirect to home page for now
|--------------------------------------------------------------------------
|
|
|
*/
App::missing(function($exception)
{
    //return Response::view('errors.error404', array(), 404);
    return Redirect::route('home');
});

/*
|--------------------------------------------------------------------------
| Repository Bindings
|--------------------------------------------------------------------------
|
|
|
*/

App::bind('Insight\Users\UserRepositoryInterface', 'Insight\Users\UserRepository');

App::singleton('SystemSettings', function()
{
    return Setting::whereName('system')->first()->settings();
});