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
                                  ON category.categoryImage = media.mediaId
                                  ORDER BY categoryType ASC");
    }

    public function getCategoriesList()
    {
        return $this->db->query("SELECT * FROM category ORDER BY categoryType ASC");
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

    public function getCatName(string $id)
    {
        return $this->db->single("SELECT categoryName FROM category WHERE categoryId = :id", [':id' => $id]);
    }

    public function getCat($id)
    {
        return $this->db->single("SELECT categoryId, categoryName, categoryImage, categoryType, filepath, filename, mime 
                                  FROM category 
                                  INNER JOIN media
                                  on category.categoryImage = media.mediaId
                                  WHERE categoryId = :id", [':id' => $id]);
    }

    public function getCategoryFrontend($id)
    {
        return $this->db->query("SELECT categoryId, categoryName, categoryImage, categoryType, filepath, filename, mime 
                                  FROM category 
                                  INNER JOIN media
                                  on category.categoryImage = media.mediaId
                                  WHERE categoryType = :id", [':id' => $id]);
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

    public function offerHandler($id, $price)
    {
        $currentOffer = $this->getOffer($id);
        if(sizeof($currentOffer) > 0) {
            if(!empty($price)) {
                $this->db->query("UPDATE offers SET offerPrice = :price WHERE fkProductId = :id",[':price' => $price, ':id' => $id]);
                return true;
            } else {
                $this->db->query("DELETE FROM offers WHERE fkProductId = :id", [':id' => $id]);
                return true;
            }
        } else {
            if(!empty($price)) {
                $this->db->query("INSERT INTO offers (fkProductId, offerPrice) VALUES (:id, :price)",
                [
                    ':id' => $id, 
                    ':price' => $price
                ]);
                return true;
            } else {
            }
        }
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
                                  ON products.fkImage = media.mediaId");
    }

    public function getProductsFrontend(int $id, int $startingPos, int $prodPerPage)
    {

        $stmt = $this->db->prepare("SELECT productId, productTitle, productDesc, productPrice, productModel, mediaId, filepath, filename, mime, categoryName, categoryTypeName
                                  FROM products
                                  INNER JOIN category 
                                  ON products.fkCategory = category.categoryId
                                  INNER JOIN categorytype
                                  ON category.categoryType = categorytype.categoryTypeId
                                  INNER JOIN media 
                                  ON products.fkImage = media.mediaId
                                  WHERE fkCategory = :id
                                  LIMIT :startPos, :prodPerPage");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':startPos', $startingPos, PDO::PARAM_INT);
        $stmt->bindValue(':prodPerPage', $prodPerPage, PDO::PARAM_INT);
        $stmt->execute() or die(print_r($stmt->errorInfo()));
        $results = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $results;
    } 

    public function searchProducts(string $search, int $startingPos, int $prodPerPage)
    {
        $stmt = $this->db->prepare("SELECT productId, productTitle, productDesc, productPrice, productModel, mediaId, filepath, filename, mime, categoryName, categoryTypeName
                                  FROM products
                                  INNER JOIN category 
                                  ON products.fkCategory = category.categoryId
                                  INNER JOIN categorytype
                                  ON category.categoryType = categorytype.categoryTypeId
                                  INNER JOIN media 
                                  ON products.fkImage = media.mediaId
                                  WHERE (products.productTitle LIKE CONCAT('%', :search, '%')
                                  OR products.productModel LIKE CONCAT ('%', :search, '%'))
                                  OR (category.categoryName LIKE CONCAT('%', :search, '%'))
                                  OR (categoryType.categoryTypeName LIKE CONCAT ('%', :search, '%'))
                                  LIMIT :starting, :limit");
        $stmt->bindValue(':search', $search, PDO::PARAM_STR);
        $stmt->bindValue(':starting', $startingPos, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $prodPerPage, PDO::PARAM_INT);
        $stmt->execute() or die(print_r($stmt->errorInfo()));
        $results = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $results;
    }

    public function getRandomProductsFrontend()
    {
        return $this->db->query("SELECT productId, productTitle, productPrice, productModel, mediaId, filepath, filename, mime, offerPrice
                                  FROM products
                                  INNER JOIN offers 
                                  ON products.productId = offers.fkProductId
                                  INNER JOIN media 
                                  ON products.fkImage = media.mediaId
                                  ORDER BY RAND()
                                  LIMIT 3");
    }

    public function getOffer($id)
    {
        return $this->db->single("SELECT offerPrice FROM offers WHERE fkProductId = :id", [':id' => $id]);
    }

    public function getProd($id)
    {
        return $this->db->single("  SELECT productId, productTitle, productDesc, productPrice, productModel, mediaId, filepath, filename, mime, fkCategory, fkImage
                                    FROM products
                                    INNER JOIN category 
                                    ON products.fkCategory = category.categoryId
                                    INNER JOIN categorytype
                                    ON category.categoryType = categorytype.categoryTypeId
                                    INNER JOIN media 
                                    ON products.fkImage = media.mediaId
                                    WHERE productId = :id", [':id' => $id]);
    }

    public function getColors($id) : array
    {
        return $this->db->query("SELECT * FROM productcolor WHERE fkProduct = :id", [':id' => $id]);
    }

    public function getBlobs($id)
    {
        $sql = "SELECT * FROM colors WHERE colorId = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $stmt->bindColumn(1, $id);
        $stmt->bindColumn(2, $color);
        $stmt->bindColumn(3, $mime);
        $stmt->bindColumn(4, $data, PDO::PARAM_LOB);
        
        $fetch = $stmt->fetch();
        return $fetch;
    }

    public function insertColors($productId, $colors)
    {
        foreach($colors as $color) {
            $this->db->query("INSERT INTO productcolor (fkProduct, fkColor) VALUES (:product, :color)", [':product' => $productId, ':color' => $color]);
        }
        return true;
    }

    public function deleteProd($id)
    {
        try{
            $mediaId = $this->db->single("SELECT fkImage FROM products WHERE productId = :id", [':id' => $id]);
            $this->db->query("DELETE FROM productcolor WHERE fkProduct = :id", [':id' => $id]);
            $this->db->query("DELETE FROM products WHERE productId = :id", [':id' => $id]);
            return $mediaId;
        } catch(PDOException $e) {
            return false;
        }
        return false;
    }

    public function editColorHandler(array $adding, array $deleting, $id)
    {
        if(sizeof($adding) > 0) {
            foreach($adding as $add) {
                $this->db->query("INSERT INTO productcolor (fkProduct, fkColor) VALUES (:product, :color)", [':product' => $id, ':color' => $add]);
            }
        }

        if(sizeof($deleting) > 0) {
            foreach($deleting as $delete) {
                $this->db->query("DELETE FROM productcolor WHERE fkProduct = :product AND fkColor = :color", [':product' => $id, ':color' => $delete]);
            }
        }
        return true;
    }

    public function editProd($id, array $post)
    {
        try {
            $this->db->query("UPDATE `products` 
                                SET `productTitle`=:title,
                                    `productDesc`=:desc,
                                    `productPrice`=:price,
                                    `fkCategory`=:category,
                                    `productModel`=:brand
                                WHERE productId = :id",
                            [
                                ':title' => $post['title'],
                                ':desc' => $post['description'],
                                ':price' => $post['price'],
                                ':category' => $post['categoryType'],
                                ':brand' => $post['brand'],
                                ':id' => $id
                            ]);
            return true;
        } catch(PDOException $e) {
            return false;
        }
        return false;
    }
    

}