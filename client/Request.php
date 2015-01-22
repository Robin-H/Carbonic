<?php

class Request
{
    private static $url;
    private static $config;

    private $controller;
    private $method;
    private $args = array(array());

    public function __construct($config)
    {
        // Set current URL
        self::$url = filter_input(INPUT_GET, '_seo_url');
        self::$config = $config;

        $this->setController();
        $this->setMethod();
    }

    public function process()
    {
        if (method_exists($this->controller, $this->method)) {
            call_user_func_array(array($this->controller, $this->method), $this->args);
        }
        else {
            throw new Exception("{$this->controller}::{$this->method} was not found");
        }
    }

    private function setController()
    {
        // Default to 'Page'
        $this->controller = 'PageController';


    }

    private function setMethod()
    {
        // Default to 'main'
        $this->method = 'main';
    }

    public static function getURL()
    {
        return self::$url;
    }

    public static function getConfig()
    {
        return self::$config;
    }
}

?>