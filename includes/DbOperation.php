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
        $stmt1 = $this->con->prepare("SELECT `user-id` as id, username, email, password FROM customer WHERE email=? OR username=?");
        $stmt1->bind_param("ss", $email, $username);
        $stmt1->execute();
        $stmt1->store_result();

        if ($stmt1->num_rows == 0) {
            $stmt = $this->con->prepare("INSERT INTO customer (name, surname, email, username, password) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $surname, $email, $username, $password);
            if ($stmt->execute())
                return true;
        }
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

    function getAvailableVehicles()
    {
        $stmt = $this->con->prepare("SELECT * FROM availablevehicles");
        $stmt->execute();
        $stmt->bind_result($manufacturer, $model, $NCAP, $year, $imageurl, $fuel, $mpge, $plate, $color, $coordinates, $pricepermin, $rating);

        $customers = array();

        while ($stmt->fetch()) {
            $customer = array();
            $customer['manufacturer'] = $manufacturer;
            $customer['model'] = $model;
            $customer['NCAP'] = $NCAP;
            $customer['year'] = $year;
            $customer['imageurl'] = $imageurl;
            $customer['fuel'] = $fuel;
            $customer['mpge'] = $mpge;
            $customer['plate'] = $plate;
            $customer['color'] = $color;
            //$customer['coordinates'] = $coordinates;
            $customer['pricepermin'] = $pricepermin;
            $customer['rating'] = $rating;/**/



            array_push($customers, $customer);
        }

        return $customers;
    }

    function getLogin($email, $password)
    {
        $stmt = $this->con->prepare("SELECT `user-id` as id, username, email, password FROM customer WHERE email=? AND password=?");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $username, $email, $password);
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
}