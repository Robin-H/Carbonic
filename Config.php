<?php

class Config
{
    private $root;
    private $autoloader;

    private $dbType;
    private $dbHost;
    private $dbUsername;
    private $dbPassword;
    private $dbName;

    private $defaultController;

    public function __construct($root)
    {
        $this->root = $root;
        $this->setAutoloader();

        $this->dbType = 'sqlite';
        $this->dbName = 'app.db';

        $this->defaultController = 'PageController';
    }

    private function setAutoloader($autoloader = null)
    {
        if (!is_callable($autoloader)) {
            include($this->root . '/carbonic/Autoload.php');
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

    public function getDBType()
    {
        return $this->dbType;
    }

    public function getDBHost()
    {
        return $this->dbHost;
    }

    public function getDBUsername()
    {
        return $this->dbUsername;
    }

    public function getDBPassword()
    {
        return $this->dbPassword;
    }

    public function getDBName()
    {
        return $this->dbName;
    }
}

?>