<?php

namespace Swarovsky\Core\Traits;

use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

trait ConfirmsPasswords
{
    use RedirectsUsers;

    public function showConfirmForm(): View
    {
        return view('swarovsky-core::auth.passwords.confirm');
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function confirm(Request $request) # php 8 required  : RedirectResponse|Response
    {
        $request->validate($this->rules(), $this->validationErrorMessages());

        $this->resetPasswordConfirmationTimeout($request);

        return $request->wantsJson()
                    ? new Response('', 204)
                    : redirect()->intended($this->redirectPath());
    }


    protected function resetPasswordConfirmationTimeout(Request $request): void
    {
        $request->session()->put('auth.password_confirmed_at', time());
    }

    protected function rules(): array
    {
        return [
            'password' => 'required|password',
        ];
    }

    protected function validationErrorMessages(): array
    {
        return [];
    }
}
