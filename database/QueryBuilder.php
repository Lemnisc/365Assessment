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
}
