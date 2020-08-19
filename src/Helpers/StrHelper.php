<?php

namespace Swarovsky\Core\Helpers;

use Illuminate\Support\Str;
use Swarovsky\Core\Models\AdvancedModel;

class StrHelper
{
    public const SpecialWords = [
        'oculus'
    ];

    public static function lower(string $value): string
    {
        return mb_strtolower($value, 'UTF-8');
    }


    public static function singular(string $word): string
    {

        if (in_array($word, self::SpecialWords, true)) {
            return $word;
        }
        return Str::singular($word);

    }

    public static function plural(string $word): string
    {
        if (in_array($word, self::SpecialWords, true)) {
            return $word;
        }
        return Str::plural($word);
    }

    public static function getClassModelName(AdvancedModel $model): string
    {
        return self::plural(Str::camel(class_basename($model)));
    }
}
