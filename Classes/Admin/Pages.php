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
        return $this->db->single("SELECT pageName, pageText, filepath, filename, mime FROM pagecontent INNER JOIN media on mediaId = pageImage");
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

    public function siteSettings()
    {
        return $this->db->single("SELECT * FROM sitesettings");
    }
    
    public function updateSettings(array $post)
    {
        $this->db->query("UPDATE sitesettings SET siteTitle = :title, street = :street, zipcode = :zip, city = :city, phone = :phone, fax = :fax, email = :email", 
        [
            ':title' => $post['title'],
            ':street' => $post['street'],
            ':zip' => $post['zip'],
            ':city' => $post['city'],
            ':phone' => $post['phone'],
            ':fax' => $post['fax'],
            ':email' => $post['email']
        ]); 
        return true;
    }
    

}