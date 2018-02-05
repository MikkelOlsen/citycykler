<?php

class Pagination extends \PDO
{

    private $db = null;
    
    public function __construct(DB $db) 
    {
        $this->db = $db;
    }

    public function paging($recordsPerPage)
    {
        $startingPostion = 0;
        if(isset($_GET['side_tal'])) {
            $startingPostion = ($_GET['side_tal']-1)*$recordsPerPage;
        }
        return $startingPostion;
    }

    public function pagingLink($query, $recordsPerPage, $params)
    {
        $category = '';
        if(isset($_GET['kategori'])) {
            $category = '&kategori='.$_GET['kategori'];
        }
        
        $stmt = $this->db->query($query, $params);

        $totalAmount = sizeof($stmt);

        if($totalAmount > 3) {
            echo '<table id="data" class="pagination">';
            echo '<tr><td>';
            $totalPages = ceil($totalAmount/$recordsPerPage);
            $currentPage = 1;
            if(isset($_GET['side_tal'])) {
                $currentPage = $_GET['side_tal'];
            }       
            for($i = 1; $i <= $totalPages; $i++) {
                if($i == $currentPage) {
                    echo '<a href="?p=produktliste'.$category.'&side_tal='.$i.'" style="border:none;cursor:default">'.$i.'</a>&nbsp;&nbsp;';
                } else {
                    echo '<a href="?p=produktliste'.$category.'&side_tal='.$i.'">'.$i.'</a>&nbsp;&nbsp;';
                }
            }
            
        }
        echo '</td></tr>';
        echo '</table>';
        
    }
    

}