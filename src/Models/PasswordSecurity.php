<?php

namespace Swarovsky\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PasswordSecurity
 * @package Swarovsky\Core\Models
 * @property int $id
 * @property int $user_id
 * @property User $user
 * @property bool $google2fa_enable
 * @property string $google2fa_secret len: 255
 */
class PasswordSecurity extends Model
{
    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
