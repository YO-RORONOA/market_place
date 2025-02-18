<?php
/**
 * User: TheCodeholic
 * Date: 7/8/2020
 * Time: 8:43 AM
 */

namespace App\controllers;

use App\core\Application;
use App\core\Controller;
use App\core\Request;


/**
 * Class SiteController
 *
 * @author  Zura Sekhniashvili <zurasekhniashvili@gmail.com>
 * @package app\controllers
 */
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