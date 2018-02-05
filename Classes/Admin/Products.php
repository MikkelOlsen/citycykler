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
            return $this->db->lastId("INSERT INTO `products`(`productTitle`, `productDesc`, `productPrice`, `fkCategory`, `fkImage`, `productBrand`) 
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
        return $this->db->query("SELECT productId, productTitle, productDesc, productPrice, brandName, mediaId, filename, mime, categoryName, categoryTypeName
                                  FROM products
                                  INNER JOIN category 
                                  ON products.fkCategory = category.categoryId
                                  INNER JOIN categorytype
                                  ON category.categoryType = categorytype.categoryTypeId
                                  INNER JOIN productBrand 
                                  ON products.productBrand = productbrand.brandId
                                  INNER JOIN media 
                                  ON products.fkImage = media.mediaId");
    }

    public function getProductsFrontend(int $id, int $startingPos, int $prodPerPage)
    {

        $stmt = $this->db->prepare("SELECT productId, productTitle, productDesc, productPrice, brandName, mediaId, filepath, filename, mime, categoryName, categoryTypeName
                                  FROM products
                                  INNER JOIN category 
                                  ON products.fkCategory = category.categoryId
                                  INNER JOIN categorytype
                                  ON category.categoryType = categorytype.categoryTypeId
                                  INNER JOIN productBrand
                                  ON products.productBrand = productbrand.brandId
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

    public function newProducts(int $startingPos, int $prodPerPage)
    {

        $stmt = $this->db->prepare("SELECT productId, productTitle, productDesc, productPrice, brandName, mediaId, filepath, filename, mime, categoryName, categoryTypeName
                                  FROM products
                                  INNER JOIN category 
                                  ON products.fkCategory = category.categoryId
                                  INNER JOIN categorytype
                                  ON category.categoryType = categorytype.categoryTypeId
                                  INNER JOIN productBrand
                                  ON products.productBrand = productbrand.brandId
                                  INNER JOIN media 
                                  ON products.fkImage = media.mediaId
                                  ORDER BY productId DESC
                                  LIMIT :startPos, :prodPerPage");
        $stmt->bindValue(':startPos', $startingPos, PDO::PARAM_INT);
        $stmt->bindValue(':prodPerPage', $prodPerPage, PDO::PARAM_INT);
        $stmt->execute() or die(print_r($stmt->errorInfo()));
        $results = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $results;
    } 

    public function searchProducts(string $search, array $params, int $startingPos, int $prodPerPage)
    {
        $search .= 'LIMIT :starting, :limit';
        $stmt = $this->db->prepare($search);
        foreach($params as $sql => $key) {
            if($sql == ':searchWord') {
                $pdoType = PDO::PARAM_STR;
            } else {
                $pdoType = PDO::PARAM_INT;
            }
            $stmt->bindValue($sql, $key, $pdoType);
        }
        $stmt->bindValue(':starting', $startingPos, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $prodPerPage, PDO::PARAM_INT);
        $stmt->execute() or die(print_r($stmt->errorInfo()));
        $results = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $results;
    }

    public function advSearcHArray(array $post)
    {
        $sqlArray = [];

        $i = 0;
        foreach($post as $sqlKey => $sqlValue) {
            if($sqlKey == 'brand' && $sqlValue !== '') {
                $sqlArray['where'][$i] = 'AND products.productBrand = :'.$sqlKey;
            } 
            if($sqlKey == 'maxPrice' && $sqlValue !== '') {
                $sqlArray['price'] = 'AND products.productPrice < '.$sqlValue;
            }
            if($sqlKey == 'categoryType' && $sqlValue !== '') {
                $sqlArray['where'][$i] = 'AND products.fkCategory = :'.$sqlKey;
            }
            if($sqlKey == 'searchWord' && $sqlValue !== '') {
                $sqlArray['searchWord'] = "WHERE (products.productTitle LIKE CONCAT('%', :".$sqlKey.", '%'))
                                            OR (productbrand.brandName LIKE CONCAT ('%', :".$sqlKey.", '%'))
                                            OR (category.categoryName LIKE CONCAT('%', :".$sqlKey.", '%'))
                                            OR (categoryType.categoryTypeName LIKE CONCAT ('%', :".$sqlKey.", '%'))";
            }
            $i++;
        }
        return $sqlArray;
    }

    public function advSearchParams(array $post)
    {
        $paramsArray = [];

        $i = 0;
        foreach($post as $sqlKey => $sqlValue) {
            if(is_numeric($sqlValue)) {
                $sqlValue = (int) $sqlValue;
            }
            if($sqlKey == 'brand' && $sqlValue !== '') {
                $paramsArray[':'.$sqlKey] = $sqlValue;
            } 
            if($sqlKey == 'maxPrice' && $sqlValue !== '') {
                $paramsArray[':'.$sqlKey] = $sqlValue;
            }
            if($sqlKey == 'categoryType' && $sqlValue !== '') {
                $paramsArray[':'.$sqlKey] = $sqlValue;
            }
            if($sqlKey == 'searchWord' && $sqlValue !== '') {
                $paramsArray[':'.$sqlKey] = $sqlValue;
            }
            $i++;
        }
        return $paramsArray;
    }

    public function advSearchSql($post, $sqlArray)
    {
        $dynamicSql = "";
        $dynamicSql .= "SELECT productId, productTitle, productDesc, productPrice, brandName, mediaId, filepath, filename, mime, categoryName, categoryTypeName
                        FROM products
                        INNER JOIN category 
                        ON products.fkCategory = category.categoryId
                        INNER JOIN categorytype
                        ON category.categoryType = categorytype.categoryTypeId
                        INNER JOIN productBrand
                        ON products.productBrand = productbrand.brandId
                        INNER JOIN media 
                        ON products.fkImage = media.mediaId ";
        if(array_key_exists('searchWord', $sqlArray)) {
            $dynamicSql .= $sqlArray['searchWord'].' ';
        }
        if(array_key_exists('where', $sqlArray)) {
            if(array_key_exists('searchWord', $sqlArray)) {
                foreach($sqlArray['where'] as $key => $sql) {
                    $dynamicSql .= $sql.' ';
                }
            } else {
                foreach($sqlArray['where'] as $key => $sql) {
                    if($key === 0) {
                        $sql = str_replace('AND', 'WHERE', $sql);
                    }
                    $dynamicSql .= $sql.' ';
                }
            }
            
        } if(array_key_exists('price', $sqlArray)) {
            $dynamicSql .= $sqlArray['price'].' ';
        }

        
        return $dynamicSql;
    }
    

    public function getRandomProductsFrontend()
    {
        return $this->db->query("SELECT productId, productTitle, productPrice, brandName, mediaId, filepath, filename, mime, offerPrice
                                  FROM products
                                  INNER JOIN offers 
                                  ON products.productId = offers.fkProductId
                                  INNER JOIN productBrand
                                  ON products.productBrand = productbrand.brandId
                                  INNER JOIN media 
                                  ON products.fkImage = media.mediaId
                                  ORDER BY RAND()
                                  LIMIT 3");
    }

    public function getAllProductsFrontEnd($startingPos, $prodPerPage)
    {
        $stmt = $this->db->prepare("SELECT productId, productTitle, productDesc, productPrice, brandName, mediaId, filepath, filename, mime, categoryName, categoryTypeName
                                    FROM products
                                    INNER JOIN productBrand
                                    ON products.productBrand = productbrand.brandId
                                    INNER JOIN category 
                                    ON products.fkCategory = category.categoryId
                                    INNER JOIN categorytype
                                    ON category.categoryType = categorytype.categoryTypeId
                                    INNER JOIN media 
                                    ON products.fkImage = media.mediaId
                                    LIMIT :startPos, :prodPerPage");
        $stmt->bindValue(':startPos', $startingPos, PDO::PARAM_INT);
        $stmt->bindValue(':prodPerPage', $prodPerPage, PDO::PARAM_INT);
        $stmt->execute() or die(print_r($stmt->errorInfo()));
        $results = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $results;
    }

    public function getOffer($id)
    {
        return $this->db->single("SELECT offerPrice FROM offers WHERE fkProductId = :id", [':id' => $id]);
    }

    public function getProd($id)
    {
        return $this->db->single("  SELECT productId, productTitle, productDesc, productPrice, brandName, mediaId, filepath, filename, mime, fkCategory, fkImage
                                    FROM products
                                    INNER JOIN productBrand
                                    ON products.productBrand = productbrand.brandId
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
                                    `productBrand`=:brand
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

    public function newBrand(array $post)
    {
        try {
            $this->db->query("INSERT INTO productbrand(brandName) VALUES (:brand)", [':brand' => $post['brand']]);
            return true;
        } catch(PDOException $e) {
            return false;
        }
        return false;
    }

    public function getBrands()
    {
        try {
            return $this->db->query("SELECT * FROM productbrand");
        } catch(PDOException $e) {
            return false;
        }
        return false;
    }

    public function currentBrand(string $id)
    {
        return $this->db->single("SELECT * FROM productbrand WHERE brandId = :id", [':id' => $id]);
    }

    public function updateBrand(array $post, string $id)
    {
        try {
            $this->db->query("UPDATE productbrand SET brandName = :name WHERE brandId = :id", [':name' => $post['brand'], ':id' => $id]);
            return true;
        } catch(PDOException $e) {
            return false;
        }
        return false;
    }

    public function deleteBrand(string $id)
    {
        try {
            $this->db->query("DELETE FROM productbrand WHERE brandId = :id", [':id' => $id]);
            return true;
        } catch(PDOException $e) {
            return false;
        }
        return false;
    }
}
    