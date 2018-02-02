<?php

class Pages extends \PDO
{

    private $db = null;
    
    public function __construct(DB $db) 
    {
        $this->db = $db;
    }

    public function getPageData()
    {
        return $this->db->single("SELECT * FROM pagecontent");
    }
    

    public function updatePage(string $pageText)
    {
        try {
            $this->db->query("UPDATE pagecontent SET pageText = :pageText", [ ':pageText' => $pageText]);
            return true;
        } catch(PDOException $e) {
            return false;
        }
        return false;
    }
    

}