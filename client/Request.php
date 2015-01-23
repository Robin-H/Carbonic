<?php

class Request
{
    private static $url;
    private static $config;

    private $controller;
    private $method = 'main';
    private $args = array();

    public function __construct($config)
    {
        // Set current URL
        self::$url = filter_input(INPUT_GET, '_seo_url');
        self::$config = $config;

        $this->setController();
    }

    public function process()
    {
        if (method_exists($this->controller, $this->method)) {
            call_user_func_array(array($this->controller, $this->method), $this->args);
        }
        else {
            throw new Exception("{$this->controller} was not found");
        }
    }

    private function setController()
    {
        // Get default from config
        $this->controller = self::$config->getDefaultController();

        if (!empty(self::$url)) {
            $urlChunks = explode("/", self::$url);
            if (sizeof($urlChunks) > 0) {
                $controller = ucfirst(array_shift($urlChunks)) . 'Controller';

                if (class_exists($controller)) {
                    $this->controller = $controller;
                    $this->args = $urlChunks;
                }
                else {
                    $this->args = explode("/", self::$url);
                }
            }
        }
    }

    private function setMethod($method)
    {
        $this->method = $method;
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