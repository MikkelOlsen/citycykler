<?php

class ImageHandler extends \PDO {

    private $db = null;
    
    public function __construct(DB $db) 
    {
        $this->db = $db;
    }

    public function imageHandler(array $files, array $options = [] )
    {
        try {
            if(!file_exists($options['path'])) {
                mkdir($options['path'], 0777, true);
            }
            $validExts = $options['validExts'];
            $width = $options['sizes']['width'];
            $height = $options['sizes']['height'];
            $ext = strtolower(pathinfo($files['name'], PATHINFO_EXTENSION));
            if(in_array($ext, $validExts)) {
                list($w, $h) = getimagesize($files['tmp_name']);
                /* calculate new image size with ratio */
                $ratio = max($width/$w, $height/$h);
                $h = ceil($height / $ratio);
                $x = ($w - $width / $ratio) / 2;
                $w = ceil($width / $ratio);
                $fileName = $width.'x'.$height.'_'.$files['name'];
                $without_extension = substr($fileName, 0, strrpos($fileName, '.'.$ext));
                $path = $options['path'].'/'.$fileName;
                /* read binary data from image file */
                $imgString = file_get_contents($files['tmp_name']);
                /* create image from string */
                $image = imagecreatefromstring($imgString);
                $tmp = imagecreatetruecolor($width, $height);
                imagecopyresampled($tmp, $image,
                    0, 0,
                    $x, 0,
                    $width, $height,
                    $w, $h);
                /* Save image */
                switch ($files['type']) {
                    case 'image/jpeg':
                    imagejpeg($tmp, $path, 100);
                    break;
                    case 'image/png':
                    imagepng($tmp, $path, 0);
                    break;
                    case 'image/gif':
                    imagegif($tmp, $path);
                    break;
                    default:
                    exit;
                    break;
                }
                $returnArray = array(
                    'filePath' => $options['path'],
                    'fileName' => $without_extension,
                    'mime' => $ext
                );
                if(isset($options['create']) && $options['create'] == true) {
                    return $this->imageUploader($returnArray);
                } else {
                    return $returnArray;
                }
                /* cleanup memory */
                imagedestroy($image);
                imagedestroy($tmp);
            } 
            else {
                echo 'not valid';
            }
        
        } catch(PDOException $e) {

        }
    }

    public function imageUploader(array $infoArray)
    {
        return $this->db->lastId("INSERT INTO `media`(`filepath`, `filename`, `mime`) VALUES (:filepath, :filename, :mime)", [':filepath' => $infoArray['filePath'], ':filename' => $infoArray['fileName'], ':mime' => $infoArray['mime']]);
    }

    public function unlinkImage($mediaId, $delete = false)
    {
        try {
            $infoArray = $this->db->single("SELECT * FROM media WHERE mediaId = :id", [':id' => $mediaId]);
            if($delete == true) {
                $this->db->query("DELETE FROM media WHERE mediaId = :id", [':id' => $mediaId]);
            }
            var_dump($infoArray);
            if(file_exists($infoArray->filepath.'/'.$infoArray->filename.'.'.$infoArray->mime)) {
                unlink($infoArray->filepath.'/'.$infoArray->filename.'.'.$infoArray->mime);
            }
            return true;
        } catch(PDOException $e) {
            return false;
        }
        return false;
    }

    public function updateImg(array $files, array $options = [])
    {
       $infoArray = $this->imageHandler($files, $options);
    //    echo '<br><br>test<br>';
    //    var_dump($infoArray);
    //    echo $infoArray['filepath'];
         if($this->unlinkImage($options['mediaId']) == true) {
           try {
            $this->db->query("UPDATE `media` SET `filepath`=:path, `filename`=:name, `mime`=:mime WHERE mediaId = :id", 
            [
                ':path' => $infoArray['filePath'],
                ':name' => $infoArray['fileName'],
                ':mime' => $infoArray['mime'],
                ':id' => $options['mediaId']
            ]);
            return true;
           } catch(PDOExcetption $e) {
               return false;
           }
           return false;
       }
    }
}