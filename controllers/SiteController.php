<?php


namespace App\controllers;

use App\core\Application;
use App\core\Controller;
use App\core\Request;



class SiteController extends Controller
{
    public function home()
    {
        return $this->render('home');
    }

    public function login()
    {
        $this->setLayout('auth');
        return $this->render('login');
    }

    public function contact()
    {
        return $this->render('contact');
    }

    public function handleContact(Request $request)
    {

        $body = $request->getbody();
        var_dump($body);
    }
}