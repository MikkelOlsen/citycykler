<?php

class Products extends \PDO
{

    private $db = null;
    
    public function __construct(DB $db) 
    {
        $this->db = $db;
    }

    public function getCategories()
    {
        return $this->db->query("SELECT categoryId, categoryName, categoryTypeName, mediaId, filename, mime
                                  FROM category 
                                  INNER JOIN categorytype
                                  ON category.categoryType = categorytype.categoryTypeId
                                  INNER JOIN media 
                                  ON category.categoryImage = media.mediaId");
    }

    public function getCategoryTypes()
    {
        return $this->db->query("SELECT * FROM categoryType");
    }
    

}