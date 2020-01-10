<?php

class Connection
{

    static private $instance = NULL;

    private function __construct()
    {
    }

    static function getPdoInstance()
    {
        if(self::$instance == NULL){
            $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
            $conn->exec("set names utf8");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance = $conn;
        }
        return self::$instance;
    }
}