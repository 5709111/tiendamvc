<?php

class Session
{
    private $login = false;
    private $user;
    private $cartTotal;

    public function __construct()
    {
        session_start();

        if ( isset($_SESSION['user'])) {
            $this->user = $_SESSION['user'];
            $this->login = true;
            $_SESSION['is_Admin']=$this->userIsAdmin();
            if (isset($this->user->id)) {
                $_SESSION['cartTotal'] = $this->cartTotal();
                $this->cartTotal = $_SESSION['cartTotal'];
            }
        } else {
            unset($this->user);
            $this->login = false;
        }
    }

    public function login($user)
    {
        $this->user = $user;
        $_SESSION['user'] = $user;
        $this->login = true;
    }

    public function logout()
    {
        unset($_SESSION['user']);
        unset($this->user);
        session_destroy();
        $this->login = false;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getUserId()
    {
        return $this->user->id ?? null;
    }

    public function cartTotal()
    {
        $db = MySQLdb::getInstance()->getDatabase();

        $sql = 'SELECT sum(p.price * c.quantity) - sum(c.discount) + sum(c.send) as total FROM carts as c, products as p WHERE c.user_id=:user_id AND c.product_id=p.id AND c.state=0';
        $query = $db->prepare($sql);
        $query->execute([':user_id' => $this->getUserId()]);
        $data = $query->fetch(PDO::FETCH_OBJ);
        //$db->close();

        return ($data->total ?? 0);
    }
    public function userIsAdmin(): bool
    {
        $user = $this->getUser();
        $db = Mysqldb::getInstance()->getDatabase();

        $sql = 'SELECT * FROM admins WHERE email=:email';
        $query = $db->prepare($sql);
        $query->execute([':email' => $user->email ?? $user['user']]);

        unset($db);

        return $query->rowCount() === 1;
    }
}