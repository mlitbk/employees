<?php

class Employee {
    public $ename = "";
    public $email = "";
    public $phone = "";

    private $table_name = 'employees';

    function __construct($name, $email, $phone) {
        $this->ename = $name;
        $this->email = $email;
        $this->phone = $phone;    
    }

    public function save() {
        $fields = array(
            'name' => $this->ename,
            'phone' => $this->phone,
            'email' => $this->email
        );
        db_insert($this->table_name)
            ->fields($fields)
            ->execute();
    }

    public static function is_valid_email($email) {
        if(filter_var($email, FILTER_VALIDATE_EMAIL))
            return true;
        return false;
    }

    public static function is_valid_phone($phone) {
        if(preg_match("/[^\d-x\(\)\.\+]/",$phone))
            return false;
        return true;
    }
}

class EmployeesList {
    private $items = array();

    private $pager_limit = 10;

    private $fields = array('name', 'phone', 'email');

    private $header;

    private $table_name = 'employees';

    function __construct($header) {
        $this->header = $header;
        $this->retrieve_employees();
    }

    public function get_items() {
        return $this->items;
    }

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
