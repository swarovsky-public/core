<?php

namespace Swarovsky\Core\Facades;

use Illuminate\Support\Facades\Facade;

class FormFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'lb-uikit-3-forms';
    }
}
