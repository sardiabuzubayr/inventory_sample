<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Session\Session;

class AuthController extends Controller{
    public function __construct(){
        $session = new Session();
        $this->session = (object)$session->get('login');
    }
}
