<?php

class Blob extends \PDO {
    private $db = null;
    
    public function __construct(DB $db) 
    {
        $this->db = $db;
    }

    public function insertBlob($filePath, $mime)
    {
        $blob = fopen($filePath, 'rb');
    
        $sql = "INSERT INTO `colors`(`colorName`, `colorMime`, `colorData`) VALUES (:color, :mime, :data)";
        $stmt = $this->db->prepare($sql);
 
        $stmt->bindParam(':color', $_POST['color']);
        $stmt->bindParam(':mime', $mime);
        $stmt->bindParam(':data', $blob, PDO::PARAM_LOB);
        
        $this->db->beginTransaction();
        $stmt->execute();
        $this->db->commit();
        var_dump($stmt);
    }

    public function selectBlob() {
 
        $sql = "SELECT *
                FROM colors";
 
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $stmt->bindColumn(1, $id);
        $stmt->bindColumn(2, $color);
        $stmt->bindColumn(3, $mime);
        $stmt->bindColumn(4, $data, PDO::PARAM_LOB);
 
        $fetches = $stmt->fetchAll();
        $i = 0;

        foreach($fetches as $fetch) {
            $blob[$i] = array(
                'color' => $fetch['colorName'],
                'data' => $fetch['colorData'],
                'mime' => $fetch['colorMime'],
                'id' => $fetch['colorId']
            );
            $i++;
        }
        return $blob;
    }
}