<?php

namespace Swarovsky\Core\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialGoogleAccount extends AdvancedModel
{
    protected $fillable = ['user_id', 'provider_user_id', 'provider'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
