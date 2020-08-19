<?php

namespace Swarovsky\Core\Support;

use PragmaRX\Google2FALaravel\Events\EmptyOneTimePasswordReceived;
use PragmaRX\Google2FALaravel\Events\LoginFailed;
use PragmaRX\Google2FALaravel\Events\LoginSucceeded;
use PragmaRX\Google2FALaravel\Exceptions\InvalidOneTimePassword;
use PragmaRX\Google2FALaravel\Exceptions\InvalidSecretKey;
use PragmaRX\Google2FALaravel\Google2FA;
use PragmaRX\Google2FALaravel\Support\Constants;
use PragmaRX\Google2FALaravel\Support\ErrorBag;
use PragmaRX\Google2FALaravel\Support\Input;
use Swarovsky\Core\Traits\Response;


class Google2FAAuthenticator extends Google2FA
{
    use ErrorBag;
    use Input;
    use Response;
    protected string $password;

    public function boot($request): Google2FA
    {
        parent::boot($request);

        return $this;
    }

    public function bootStateless($request): Google2FA
    {
        $this->boot($request);

        $this->setStateless();

        return $this;
    }

    private function fireLoginEvent($succeeded)
    {
        event(
            $succeeded
                ? new LoginSucceeded($this->getUser())
                : new LoginFailed($this->getUser())
        );

        return $succeeded;
    }


    protected function getOneTimePassword()
    {
        $password = $this->getInputOneTimePassword();

        if (blank($password)) {
            event(new EmptyOneTimePasswordReceived());

            if ($this->config('throw_exceptions', true)) {
                throw new InvalidOneTimePassword(config('google2fa.error_messages.cannot_be_empty'));
            }
        }

        return $password;
    }

    public function isAuthenticated(): bool
    {
        return $this->canPassWithoutCheckingOTP() || ($this->checkOTP() === Constants::OTP_VALID);
    }

    protected function canPassWithoutCheckingOTP(): bool
    {
        if($this->getUser()->passwordSecurity === null) {
            return true;
        }
        return
            !$this->getUser()->passwordSecurity->google2fa_enable ||
            !$this->isEnabled() ||
            $this->noUserIsAuthenticated() ||
            $this->twoFactorAuthStillValid();
    }


    protected function checkOTP()
    {
        if (!$this->inputHasOneTimePassword() || empty($this->getInputOneTimePassword())) {
            return Constants::OTP_EMPTY;
        }

        $isValid = $this->verifyOneTimePassword();

        if ($isValid) {
            $this->login();
            $this->fireLoginEvent($isValid);

            return Constants::OTP_VALID;
        }

        $this->fireLoginEvent($isValid);

        return Constants::OTP_INVALID;
    }

    protected function verifyOneTimePassword()
    {
        return $this->verifyAndStoreOneTimePassword($this->getOneTimePassword());
    }

    protected function getGoogle2FASecretKey()
    {
        $secret = $this->getUser()->passwordSecurity->{$this->config('otp_secret_column')};

        if (blank($secret)) {
            throw new InvalidSecretKey('Secret key cannot be empty.');
        }

        return $secret;
    }


}
