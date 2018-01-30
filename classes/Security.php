<?php

class Security extends \PDO
{

    private $db = null;
    
    public function __construct(DB $db) 
    {
        $this->db = $db;
    }

    
    public function secGetMethod(string $method)
    {
        return (filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_SPECIAL_CHARS) === strtoupper($method)) ? true : false;
    }    

    public function secGetInputArray(string $input)
    {
        return filter_input_array(strtoupper($input), FILTER_SANITIZE_SPECIAL_CHARS);
    }
}
