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

    public function pagingLink($query, $recordsPerPage, $params, $getParam, $pageLink)
    {
        $category = '';
        if(isset($_GET['kategori'])) {
            $category = '&kategori='.$_GET['kategori'];
        }
        // foreach($_GET as $get => $value) { if($get !== 'p' && $get !== 'side_tal') { echo 'get: '.$get.' value: '.$value.'<br>'; } }
        
        $stmt = $this->db->query($query, $params);
        
        $totalAmount = sizeof($stmt);
        if($totalAmount > 2) {
            echo '<table id="data" class="pagination">';
            echo '<tr><td>';
            $totalPages = ceil($totalAmount/$recordsPerPage);
            $currentPage = 1;
            if(isset($_GET['side_tal'])) {
                $currentPage = $_GET['side_tal'];
            }       
            for($i = 1; $i <= $totalPages; $i++) {
                if($i == $currentPage) {
                    $link = '<a href="'.$pageLink.'';
                    foreach($getParam as $get => $value) { 
                        if($get !== 'p' && $get !== 'side_tal') { 
                            $link .= '&'.$get.'='.$value; 
                        } 
                    }
                    $link .= '&side_tal='.$i.'" style="border:none;cursor:default">'.$i.'</a>&nbsp;&nbsp;';
                    echo $link;
                } else {
                    $link = '<a href="'.$pageLink.'';
                    foreach($getParam as $get => $value) { 
                        if($get !== 'p' && $get !== 'side_tal') { 
                            $link .= '&'.$get.'='.$value; 
                        } 
                    }
                    $link .= '&side_tal='.$i.'">'.$i.'</a>&nbsp;&nbsp;';
                    echo $link;
                }
            }
            
        }
        echo '</td></tr>';
        echo '</table>';
        
    }
    

}