<?php

/**
 * class Employee
 */
class Employee {
    public $ename = "";
    public $email = "";
    public $phone = "";

    private $table_name = 'employees';

    private static $name_max_length = 40;
    private static $phone_max_length = 20;
    private static $email_max_length = 50;

    /**
     * Initialize an employee
     * @param name The name of the employee
     * @param email Email address
     * @param phone Phone number
     */
    function __construct($name, $phone, $email) {
        $this->ename = $name;
        $this->email = $email;
        $this->phone = $phone;    
    }

    /**
     * Save the employee in database
     */
    public function save() {
        $fields = array(
            'name' => $this->ename,
            'email' => $this->email,
            'phone' => $this->phone
        );
        db_insert($this->table_name)
            ->fields($fields)
            ->execute();
    }

    public static function is_valid_name($name) {
        $valid = true;

        if(strlen($name) > self::$name_max_length)
            $valid = false;

        return $valid;    
    }

    /**
     * Check if an email is valid
     * @param email The email to check
     * returns true if email is valid
     */
    public static function is_valid_email($email) {
        $valid = true;

        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            $valid = false;
        
        if(strlen($email) > self::$email_max_length)
            $valid = false;

        return $valid;
    }

    /**
     * Check if a phone number is valid
     * @param phone The phone number to check
     * returns true if the phone is valid
     */
    public static function is_valid_phone($phone) {
        $valid = true;

        /** 
         * Check if phone number contains
         * illegal characters
         */
        if(preg_match("/[^\d-x\(\)\.\+]/",$phone))
            $valid = false;

        if(strlen($phone) > self::$phone_max_length)
            $valid = false;
        
        return $valid;
    }
}

/**
 * class EmployeesList
 * A list of employees to be shown
 * in a table
 */
class EmployeesList {
    private $items = array();

    /**
     * How many employees to show per page
     */
    private $pager_limit = 10;

    /**
     * Fields in database to select
     */
    private $fields = array('name', 'phone', 'email');

    /**
     * The header of the table in which we
     * show the list
     */
    private $header;

    /**
     * Table name in database in which
     * the employees are stored
     */
    private $table_name = 'employees';

    /**
     * Initialize an employee list
     * @param header The header of the table
     * in which the employees are shown
     */
    function __construct($header) {
        $this->header = $header;
        $this->retrieve_employees();
    }

    /**
     * returns array of employees
     */
    public function get_items() {
        return $this->items;
    }

    /**
     * Retrieve employees from the database
     * and save in array
     */
    private function retrieve_employees() {
        $result = db_select('employees', 'e')
            ->fields('e', $this->fields)
            ->extend('TableSort')
            ->orderByHeader($this->header)
            ->extend('PagerDefault')
            ->limit($this->pager_limit)
            ->execute();
        foreach($result as $row) {
            $emp = new Employee($row->name, $row->email, $row->phone);
            $this->items[] = $emp;
        }
    }
}
