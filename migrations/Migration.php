<?php

namespace App\core\migrations;


abstract class Migration
{
    abstract public function up();
    abstract public function down();

}