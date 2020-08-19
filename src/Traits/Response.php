<?php

namespace Swarovsky\Core\Traits;

use Illuminate\Http\JsonResponse as IlluminateJsonResponse;
use Illuminate\Http\Response as IlluminateHtmlResponse;
use Illuminate\View\View;
use PragmaRX\Google2FALaravel\Events\OneTimePasswordRequested;
use PragmaRX\Google2FALaravel\Events\OneTimePasswordRequested53;
use PragmaRX\Google2FALaravel\Support\Constants;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

trait Response
{
    protected function makeJsonResponse($statusCode): IlluminateJsonResponse
    {
        return new IlluminateJsonResponse(
            $this->getErrorBagForStatusCode($statusCode),
            $statusCode
        );
    }

    protected function makeStatusCode(): int
    {
        if ($this->checkOTP() === Constants::OTP_VALID || $this->getRequest()->isMethod('get')) {
            return SymfonyResponse::HTTP_OK;
        }

        if ($this->checkOTP() === Constants::OTP_EMPTY) {
            return SymfonyResponse::HTTP_BAD_REQUEST;
        }

        return SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY;
    }

    protected function makeHtmlResponse($statusCode): IlluminateHtmlResponse
    {
        $view = $this->getView();

        if ($statusCode !== SymfonyResponse::HTTP_OK) {
            $view->withErrors($this->getErrorBagForStatusCode($statusCode));
        }

        return new IlluminateHtmlResponse($view, $statusCode);
    }

    /**
     * Create a response to request the OTP.
     *
     * @return IlluminateHtmlResponse|IlluminateJsonResponse
     */
    public function makeRequestOneTimePasswordResponse()
    {
        event(
            app()->version() < '5.4'
                ? new OneTimePasswordRequested53($this->getUser())
                : new OneTimePasswordRequested($this->getUser())
        );

        $expectJson = app()->version() < '5.4'
            ? $this->getRequest()->wantsJson()
            : $this->getRequest()->expectsJson();

        return $expectJson
            ? $this->makeJsonResponse($this->makeStatusCode())
            : $this->makeHtmlResponse($this->makeStatusCode());
    }

    private function getView(): View
    {
        $config = require(dirname(__DIR__, 1).'/config/google2fa.php');
        return view( $config['view']);
    }

    abstract protected function getErrorBagForStatusCode($statusCode);

    abstract protected function inputHasOneTimePassword();

    abstract public function checkOTP();

    abstract protected function getUser();

    abstract public function getRequest();

    abstract protected function config($string, $children = []);
}
