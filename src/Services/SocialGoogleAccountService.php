<?php

namespace Swarovsky\Core\Services;

use Illuminate\Support\Facades\Date;
use Laravel\Socialite\Contracts\User as ProviderUser;
use Swarovsky\Core\Models\SocialGoogleAccount;
use Swarovsky\Core\Models\User;

class SocialGoogleAccountService
{
    public function createOrGetUser(ProviderUser $providerUser){
        $account = SocialGoogleAccount::whereProvider('google')
            ->whereProviderUserId($providerUser->getId())
            ->first();

        if($account !== null){
            return $account->user;
        }
        $account = new SocialGoogleAccount([
            'provider_user_id' => $providerUser->getId(),
            'provider' => 'google'
        ]);

        $user = User::whereEmail($providerUser->getEmail())->first();

        if($user === null){
            $user = User::create([
                'email' => $providerUser->getEmail(),
                'name' => $providerUser->getName(),
                'avatar' => $providerUser->getAvatar(),
                'password' => md5(random_int(1,10000)),
                'email_verified_at' => Date::now()
            ]);
        }
        $user->syncRoles('user');
        $account->user()->associate($user);
        $account->save();
        return $user;
    }
}
