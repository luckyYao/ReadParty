<?php

namespace App\Http\Controllers\Front;

use DB;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Response;
use Request;
use Session;
use Validator;

class UserController extends BaseController
{	
	public function login()
	{	
        $param = Request::all();
	}

    public function reg()
    {   
        $param = Request::all();
    }

    public function logout()
    {   
        $param = Request::all();
    }
}