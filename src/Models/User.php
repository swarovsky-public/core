<?php

namespace Swarovsky\Core\Models;



use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Swarovsky\Core\Helpers\StrHelper;

/**
 * Class User
 * @package Swarovsky\Core\Models\User
 * @property $name
 * @property $email
 * @property $email_verified_at
 * @property $password
 * @property $remember_token
 * @property $created_at
 * @property $updated_at
 */
class User extends Authenticatable  implements MustVerifyEmail
{
    use Notifiable, HasRoles;

    protected $fillable = [
        'id','name', 'email', 'password', 'email_verified_at'
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function passwordSecurity(): HasOne
    {
        return $this->hasOne(PasswordSecurity::class);
    }

    public function isAllowed(string $requestPermission): bool
    {
        foreach($this->roles as $role){
            if(StrHelper::lower($role->name) === 'super admin'){
                return true;
            }

            foreach ($role->permissions as $permission) {
                if (strtolower($permission->name) === strtolower($requestPermission)) {
                    return true;
                }
            }
        }

        $permissions = $this->getPermissionsViaRoles();
        foreach ($permissions as $permission) {
            if (strtolower($permission->name) === strtolower($requestPermission)) {
                return true;
            }
        }

        return false;
    }




    public static function isGoogle(int $id): bool
    {
        return SocialGoogleAccount::where('user_id', '=', $id)->first() !== null;
    }

    public static function isFacebook(int $id): bool
    {
        return SocialFacebookAccount::where('user_id', '=', $id)->first() !== null;
    }

}
