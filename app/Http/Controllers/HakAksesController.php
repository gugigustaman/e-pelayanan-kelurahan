<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class HakAksesController extends Controller
{
    public function login() {
    	return view('hakakses.login');
    }

    public function webLogin() {
    	
    }

    public function apiLogin() {
    	
    }

    public function check() {

    }

    public function logout() {
        
    }
}
