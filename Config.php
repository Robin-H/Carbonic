<?php

class Config
{
    private $root;
    private $autoloader;

    private $dbHost;
    private $dbUsername;
    private $dbPassword;
    private $dbName;

    private $defaultController;

    public function __construct($root)
    {
        $this->root = $root;
        $this->setAutoloader();

        $this->dbHost = 'localhost';
        $this->dbUsername = '';
        $this->dbPassword = '';
        $this->dbName = '';

        $this->defaultController = 'PageController';
    }

    private function setAutoloader($autoloader = null)
    {
        if (!is_callable($autoloader)) {
            include($this->root . '/carbon/Autoload.php');
            $autoloader = array('Autoload', 'loadClass');
        }

        spl_autoload_register($autoloader);
    }

    public function getRoot()
    {
        return $this->root;
    }

    public function getDefaultController()
    {
        return $this->defaultController;
    }
}

?>