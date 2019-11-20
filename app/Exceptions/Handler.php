<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use ReflectionObject;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * @var array
     */
    const EXCEPTIONS = [
        'AuthenticationException' => ['Unauthorized', 401],
        'AuthorizationException' => ['Insufficient privileges to perform this action', 403],
        'DomainExistsException' => ['Domain already exists', 403],
        'DomainLicenseException' => ['License domain is invalid', 401],
        'DomainLimitReachedException' => ['Domain limit reached for this license', 403],
        'ExpiredLicenseException' => ['License has expired', 401],
        'InvalidDomainException' => ['Domain is invalid', 401],
        'InvalidLicenseException' => ['License is invalid', 401],
        'InvalidSoftwareException' => ['Software is invalid', 401],
        'MethodNotAllowedHttpException' => ['Method Not Allowed', 405],
        'NotFoundHttpException' => ['The requested resource was not found', 404],
        'RevokedLicenseException' => ['License has been revoked', 401],
        'SoftwareLicenseException' => ['License software is invalid', 401],
        'ValidationException' => ['Validation failed', 422],
    ];

    /**
     * A list of the exception types that should not be reported.
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        DomainExistsException::class,
        DomainLimitReachedException::class,
        InvalidDomainException::class,
        InvalidLicenseException::class,
        InvalidSoftwareException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     * http://www.restapitutorial.com/httpstatuscodes.html.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        $exception = (new ReflectionObject($e))->getShortName();
        if (!array_key_exists($exception, static::EXCEPTIONS)) {
            return parent::render($request, $e);
        }
        $licenseException = ucfirst(urlencode($e->getMessage())).'LicenseException';
        if (array_key_exists($licenseException, static::EXCEPTIONS)) {
            $exception = $licenseException;
        }
        $value = static::EXCEPTIONS[$exception];
        $data = ['message' => $value[0], 'status' => $value[1]];
        if (method_exists($this, $method = sprintf('custom%sData', $exception))) {
            $data = $this->$method($e, $data);
        }
        return response()->json($data, $value[1]);
    }

    /**
     * @return array
     */
    protected function customValidationExceptionData(Exception $e, array $data)
    {
        $data['errors'] = $e->validator->errors();
        return $data;
    }
}
