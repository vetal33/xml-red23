<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Routing\Controller as BaseController;

class HomeController extends BaseController
{

    public function __construct()
    {
        //$this->middleware('role:user');
    }
    public function index()
    {
        $userCount = User::all()->count();

        return view('admin.index', compact('userCount'));
    }

}
