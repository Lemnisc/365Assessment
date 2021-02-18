<?php

class QueryBuilder
{
    protected $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function selectAll($table)
    {
        $statement = $this->pdo->prepare("select * from {$table}");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_CLASS);
    }

    public function selectColumns($table, $columns)
    {
        $sql = sprintf('select %s from %s', implode(', ', array_values($columns)), $table);
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_CLASS);
        } catch (Exception $error) {
            die('Could not select from database. Error: ' . $error->getMessage());
        }
    }

    public function getMaxFromColumn($table, $column)
    {
        $sql = sprintf('select max(%s) as max_value from %s', $column, $table);
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->execute();
            $max_value = $statement->fetch(PDO::FETCH_ASSOC)['max_value'];
            return $max_value;
        } catch (Exception $error) {
            die('Could not select from database. Error: ' . $error->getMessage());
        }
    }

    public function insert($table, $parameters)
    {
        $sql = sprintf(
            'insert into %s (%s) values (%s)',
            $table,
            implode(', ', array_keys($parameters)),
            ':' . implode(', :', array_keys($parameters))
        );
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->execute($parameters);
        } catch (Exception $error) {
            die('Could not insert into database. Error: ' . $error->getMessage());
        }
    }

    public function getWhereKeyIsValue($table, $key, $value)
    {
        $sql = sprintf('select * from %s where %s = "%s"', $table, $key, $value);
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_CLASS);
        } catch (Exception $error) {
            die('Could not get from database. Error: ' . $error->getMessage());
        }
    }

    public function deleteByColumnValue($table, $column, $value)
    {
        $sql = sprintf('delete from %s where %s = "%s"', $table, $column, $value);
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->execute();
        } catch (Exception $error) {
            die('Could not delete from database. Error: ' . $error->getMessage());
        }
    }
    
    public function getColumnsWhereKeyIsValue($table, $key, $value, $columns)
    {
        $sql = sprintf('select %s from %s where %s = "%s"', implode(', ', array_values($columns)), $table, $key, $value);
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $error) {
            die('Could not get from database. Error: ' . $error->getMessage());
        }
    }
}
