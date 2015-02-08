<?php

class Model
{
    private $pdo;
    private $isDefaultPDO = false;

    final protected function setPDO($type, $name, $host, $username, $password)
    {   
        switch ($type) {
            case 'mysql':
                $dsn = 'mysql:dbname=' . $name . ';host=' . $host;
                $this->pdo = new PDO($dsn, $username, $password);
            break;

            default:
                $dsn = 'sqlite:' . Request::getConfig()->getRoot() . '/carbonic/db/' . $name;
                $this->pdo = new PDO($dsn);
            break;
        }

        $this->isDefaultPDO = false;
    }

    final protected function setDefaultPDO()
    {
        if ($this->isDefaultPDO) {
            return;
        }

        $type     = Request::getConfig()->getDBType();
        $host     = Request::getConfig()->getDBHost();
        $username = Request::getConfig()->getDBUsername();
        $password = Request::getConfig()->getDBPassword();
        $name     = Request::getConfig()->getDBName();

        $this->setPDO($type, $name, $host, $username, $password);
        $this->isDefaultPDO = true;
    }

    protected function query($queryString, $params = array())
    {
        // Make sure we have a working PDO connection
        $this->setDefaultPDO();

        $queryString = str_replace('PREFIX_', Request::getConfig()->getTablePrefix(), $queryString);
        $statement = $this->pdo->prepare($queryString, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        
        foreach ($params as $param => $value) {
            $statement->bindParam(':' . $param, $value);
        }

        if ($statement->execute()) {
            return $statement;
        }
        else {
            $errorMessage = $statement->errorInfo();
            if (isset($errorMessage[2])) {
                $errorMessage = $errorMessage[2];
            }
            elseif (isset($errorMessage[1])) {
                $errorMessage = "MySQL Error: " . $errorMessage[1];
            }
            else {
                $errorMessage = "An error occurred with: $queryString";
            }

            throw new Exception($errorMessage);
        }
    }

    protected function exists($table, $column, $value)
    {
        // Make sure we have a working PDO connection
        $this->setDefaultPDO();

        $result = $this->query("SELECT $column FROM PREFIX_$table WHERE $column = :value LIMIT 1", array('value' => $value));
        return ($result->rowCount() > 0);
    }

    protected function save($table, $properties, $id = null)
    {
        // Make sure we have a working PDO connection
        $this->setDefaultPDO();

        $fields = $params = array();

        foreach ($properties as $property => $value) {
            $fields[] = $property . " = :$property";
        }

        if (empty($id) || !is_array($id)) {
            // Add
            $this->query("INSERT INTO PREFIX_{$table} SET $fields", $properties);

            return $this->pdo->lastInsertId();
        }
        else {
            // Add ID-field
            $primaryKey = key($id);
            $params['primaryKeyValue'] = $id[$primaryKey];

            // Update
            $this->query("UPDATE PREFIX_{$table} SET $fields WHERE $primaryKey = :primaryKeyValue", $properties);

            return $id[$primaryKey];
        }
    }
}

?>