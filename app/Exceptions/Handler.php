<?php

namespace App\Exceptions;

use App\Exceptions\InvalidDomainException;
use App\Exceptions\InvalidLicenseException;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		AuthorizationException::class,
		InvalidDomainException::class,
		InvalidLicenseException::class,
		HttpException::class,
		ModelNotFoundException::class,
		ValidationException::class,
	];

	/**
	 * Report or log an exception.
	 *
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @return void
	 */
	public function report( Exception $e )
	{
		parent::report( $e );
	}

	/**
	 * Render an exception into an HTTP response.
	 * http://www.restapitutorial.com/httpstatuscodes.html
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function render( $request, Exception $e )
	{
		if( $e instanceof AuthenticationException ) {
			return response( ['message' => 'Unauthorized', 'status' => 401], 401 );
		}
		if( $e instanceof AuthorizationException ) {
			return response( ['message' => 'Insufficient privileges to perform this action', 'status' => 403], 403 );
		}
		if( $e instanceof InvalidDomainException ) {
			return response( ['message' => 'Domain is invalid', 'status' => 401], 401 );
		}
		if( $e instanceof InvalidLicenseException ) {
			return response( ['message' => 'License is invalid', 'status' => 401], 401 );
		}
		if( $e instanceof MethodNotAllowedHttpException ) {
			return response( ['message' => 'Method Not Allowed', 'status' => 405], 405 );
		}
		if( $e instanceof NotFoundHttpException ) {
			return response( ['message' => 'The requested resource was not found', 'status' => 404], 404 );
		}
		return parent::render( $request, $e );
	}
}
