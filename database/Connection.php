<?php

class Connection
{
    public static function make($config)
    {
        try {
            return new PDO(
                $config['connection'].';dbname='.$config['name'],
                $config['username'],
                $config['password'],
                $config['options']
            );
        } catch (PDOException $error) {
            die(nl2br("The connection to the database could not be made. This is what went wrong: \n \n" . $error->getMessage()));
        }
    }
}
