<?php

class Autoload
{
    private static $classLocations;

    public static function loadClass($className)
    {
        // Request class is needed for loadFileLocationsFromCache
        if (empty(self::$classLocations)) {
            if ($className == 'Request') {
                include_once(__DIR__ . '/client/Request.php');
                return;
            }
            else {
                self::loadFileLocationsFromCache();
            }
        }

        if (!isset(self::$classLocations[$className]) || !is_file(Request::getConfig()->getRoot() . self::$classLocations[$className])) {
            self::findFileForClass($className);
        }
        
        if (isset(self::$classLocations[$className])) {
            include_once(Request::getConfig()->getRoot() . self::$classLocations[$className]);
        }
        else {
            // Class could not be found
            throw new Exception("Could not load $className");
        }
    }

    private static function findFileForClass($className)
    {
        $possibleClassLocations = array(
            '/app/controller/',
            '/app/model/',
            '/carbon/client/',
            '/carbon/'
        );

        $classFileName = $className  . '.php';

        foreach ($possibleClassLocations as $possibleClassLocation) {
            if (is_file(Request::getConfig()->getRoot() . $possibleClassLocation . $classFileName)) {
                // Class found, add it to the stack
                self::$classLocations[$className] = $possibleClassLocation . $classFileName;

                // Stop
                return;
            }
        }
    }

    private static function loadFileLocationsFromCache()
    {
        self::$classLocations = $classLocations = array();

        // Check if cache file is present
        if (is_file(Request::getConfig()->getRoot() . '/cache/classlocations.cache')) {
            $classLocations = @unserialize(file_get_contents(Request::getConfig()->getRoot() . '/cache/classlocations.cache'));
        }

        // Check if the cache file contains valid data 
        if (is_array($classLocations)) {
            foreach ($classLocations as $className => $classLocation) {
                if (preg_match('/^([a-z0-9]+)$/i', $className) && 
                    preg_match('/^([a-z0-9\/\-\.]+)$/i', $className)) {
                    self::$classLocations[$className] = $classLocation;
                }
            }
        }
    }

    private static function saveFileLocationsToCache()
    {
        // Remove any existing file
        @unlink(Request::getConfig()->getRoot() . '/cache/classlocations.cache');

        if (is_writable(Request::getConfig()->getRoot() . '/cache/')) {
            $f = fopen(Request::getConfig()->getRoot() . '/cache/classlocations.cache', 'w+');
            fwrite($f, serialize(self::$classLocations));
        }
    }
}

?>