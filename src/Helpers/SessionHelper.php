<?php
namespace Swarovsky\Core\Helpers;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

class SessionHelper {

    public static function add_message(string $message, $type = 'default'): void
    {
        $errors = Session::get('errors', new ViewErrorBag);
        if (! $errors instanceof ViewErrorBag) {
            $errors = new ViewErrorBag;
        }
        $bag = $errors->getBags()['default'] ?? new MessageBag;
        $bag->add($type, $message);
        Session::flash(
            'errors', $errors->put('default', $bag)
        );
    }

}
