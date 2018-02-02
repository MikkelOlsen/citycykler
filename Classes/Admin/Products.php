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

    public function getCategoriesList()
    {
        return $this->db->query("SELECT * FROM category");
    }

    public function getCategoryTypes()
    {
        return $this->db->query("SELECT * FROM categoryType");
    }

    public function getCatType(string $id)
    {
        return $this->db->query("SELECT categoryTypeName FROM category INNER JOIN categoryType ON categoryType = categoryTypeId WHERE categoryId = :id", [':id' => $id]);
    }

    public function getCatTypeName(string $id)
    {
        return $this->db->single("SELECT categoryTypeName FROM categoryType WHERE categoryTypeId = :id", [':id' => $id]);
    }

    public function getCat($id)
    {
        return $this->db->single("SELECT categoryId, categoryName, categoryImage, categoryType, filepath, filename, mime 
                                  FROM category 
                                  INNER JOIN media
                                  on category.categoryImage = media.mediaId
                                  WHERE categoryId = :id", [':id' => $id]);
    }

    public function newCategory(string $mediaId, array $post)
    {
        try {
            $this->db->query("INSERT INTO `category`(`categoryName`, `categoryImage`, `categoryType`) VALUES (:name, :mediaId, :type)", [':name' => $post['category'], ':mediaId' => $mediaId, ':type' => $post['categoryType']]);
            return true;
        } catch(PDOException $e){
            return false;
        }
        return false;
    }

    public function deleteCat($id)
    {
        try {
            $mediaId = $this->db->single("SELECT categoryImage FROM category WHERE categoryId = :id", [':id' => $id]);
            $this->db->query("DELETE FROM category WHERE categoryId = :id", [':id' => $id]);
            return $mediaId;
        } catch(PDOException $e) {
            return false;
        }
        return false;
    }

    public function updateCat($id, array $post)
    {
        try {
            $this->db->query("UPDATE `category` SET `categoryName`=:name, `categoryType`=:type WHERE categoryId = :id", 
                            [
                                ':name' => $post['category'],
                                ':type' => $post['categoryType'],
                                ':id' => $id
                            ]);
            return true;
        } catch(PDOException $e) {
            return false;
        }
        return false;
    }

    public function newProd(string $mediaId, array $post)
    {
        try {
            return $this->db->lastId("INSERT INTO `products`(`productTitle`, `productDesc`, `productPrice`, `fkCategory`, `fkImage`, `productModel`) 
                              VALUES (:title, :description, :price, :category, :image, :brand)", 
                              [
                                  ':title' => $post['title'],
                                  ':description' => $post['description'],
                                  ':price' => $post['price'],
                                  ':category' => $post['categoryType'],
                                  ':image' => $mediaId,
                                  ':brand' => $post['brand']
                              ]);
        } catch(PDOException $e){
            return false;
        }
        return false;
    }

    public function getProducts()
    {
        return $this->db->query("SELECT productId, productTitle, productDesc, productPrice, productModel, mediaId, filename, mime, categoryName, categoryTypeName
                                  FROM products
                                  INNER JOIN category 
                                  ON products.fkCategory = category.categoryId
                                  INNER JOIN categorytype
                                  ON category.categoryType = categorytype.categoryTypeId
                                  INNER JOIN media 
                                  ON category.categoryImage = media.mediaId");
    }

    public function insertColors($productId, $colors)
    {
        foreach($colors as $color) {
            $this->db->query("INSERT INTO productcolor (fkProduct, fkColor) VALUES (:product, :color)", [':product' => $productId, ':color' => $color]);
        }
        return true;
    }
    

}