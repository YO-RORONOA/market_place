<?php

use App\core\Application;





class M0001_initial
{
    public function up()
    {
        Application::$app->db;
    }

    public function down()
    {
        echo 'down migration'.PHP_EOL;
    }
}
