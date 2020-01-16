<?php

class DbOperation
{
    //Database connection link
    private $con;

    //Class constructor
    function __construct()
    {
        //Getting the DbConnect.php file
        require_once dirname(__FILE__) . '/DbConnect.php';

        //Creating a DbConnect object to connect to the database
        $db = new DbConnect();

        //Initializing our connection link of this class
        //by calling the method connect of DbConnect class
        $this->con = $db->connect();
    }

    /*
    * The create operation
    * When this method is called a new record is created in the database
    */
    function createCustomer($name, $surname, $email, $username, $password)
    {
        $stmt = $this->con->prepare("INSERT INTO customer (name, surname, email, username, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name,$surname, $email, $username, $password);
        if ($stmt->execute())
            return true;
        return false;
    }

    /*
    * The read operation
    * When this method is called it is returning all the existing record of the database
    */
    function getCustomers()
    {
        $stmt = $this->con->prepare("SELECT `user-id` as id, name FROM customer");
        $stmt->execute();
        $stmt->bind_result($id, $name);

        $customers = array();

        while ($stmt->fetch()) {
            $customer = array();
            $customer['id'] = $id;
            $customer['name'] = $name;


            array_push($customers, $customer);
        }

        return $customers;
    }

    function getLogin($email, $password)
    {
        $stmt = $this->con->prepare("SELECT `user-id` as id, username, email, password FROM customer WHERE email=? AND password=?");
        $stmt->bind_param( "ss",$email, $password);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows > 0) {
            $stmt->bind_result($id,$username, $email, $password);
            while ($stmt->fetch()) {
                $customer = array();
                $customer['id'] = $id;
                $customer['username'] = $username;
                $customer['email'] = $email;
                $customer['password'] = $password;
            }

            return $customer;
        }

        return NULL;
    }

    /*
    * The update operation
    * When this method is called the record with the given id is updated with the new given values
    */
    function updateHero($id, $name, $realname, $rating, $teamaffiliation)
    {
        $stmt = $this->con->prepare("UPDATE heroes SET name = ?, realname = ?, rating = ?, teamaffiliation = ? WHERE id = ?");
        $stmt->bind_param("ssisi", $name, $realname, $rating, $teamaffiliation, $id);
        if ($stmt->execute())
            return true;
        return false;
    }


    /*
    * The delete operation
    * When this method is called record is deleted for the given id
    */
    function deleteHero($id)
    {
        $stmt = $this->con->prepare("DELETE FROM heroes WHERE id = ? ");
        $stmt->bind_param("i", $id);
        if ($stmt->execute())
            return true;

        return false;
    }
}