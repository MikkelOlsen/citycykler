<?php

class User extends \PDO
{

    private $db = null;
    
    public function __construct(DB $db) 
    {
        $this->db = $db;
    }

    public function login(array $post)
    {
        $user = $this->db->single("SELECT * FROM users WHERE username = :username OR userEmail = :username", [':username' => $post['username']]);
        if(sizeof($user) == 1) {
            if(password_verify($post['pass'], $user->password)) {
                $_SESSION['user']['username'] = $user->username;
                $_SESSION['user']['fullname'] = $user->fullname;
                $_SESSION['user']['useremail'] = $user->userEmail;
                return true;
            }
        }
        return false;
    }

    public function loginCheck($session)
    {
        $user = $this->db->single("SELECT * FROM users WHERE username = :username OR userEmail = :username", [':username' => $session['username']]);
        if(sizeof($user) == 1) {
            return true;
        }
        return false;
    }
    

}