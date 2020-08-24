<?php

namespace Swarovsky\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;


class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('advanced_permission:can see admin ui');
    }


    public function dashboard(): Renderable
    {
        return view('pages.admin.dashboard');
    }

}
