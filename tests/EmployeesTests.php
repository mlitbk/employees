<?php

/**
 * tests for employee class
 * to run: phpunit EmployeeTests.php
 */

require_once '../Employees.php';
class EmployeesTests extends PHPUnit_Framework_TestCase {
    protected $object;

    protected function setUp() {
        $this->object = new Employee("Michael","123456","mlitbk@gmail.com");
    }

    protected function tearDown() {
    }

    public function testVariables() {
        $this->assertSame($this->object->ename,"Michael");
        $this->assertSame($this->object->phone,"123456");
        $this->assertSame($this->object->email,"mlitbk@gmail.com");
    }

    public function testEmailValidations() {
        $this->assertTrue(Employee::is_valid_email($this->object->email));
        $invalid_emails = array(
            "","123","savdsa","dsa@","@dsa","dsa@da");
        foreach($invalid_emails as $em) {
            $this->object->email = $em;
            $this->assertFalse(Employee::is_valid_email($this->object->email));
        }
    }

    public function checkPhoneValidation() {
        $this->assertTrue(Employee::is_valid_phone($this->object->phone));
        $invalid_phones = array(
            "","a","dsada","213r4312");
        foreach($invalid_phones as $ip) {
            $this->object->phone = $ip;
            $this->assertFalse(Employee::is_valid_phone($this->object->ip));
        }
    }

    public function testLongInput() {
        $input = str_repeat("a",51);
        $this->assertFalse(Employee::is_valid_email($input));
        $this->assertFalse(Employee::is_valid_phone($input));
        $this->assertFalse(Employee::is_valid_name($input));
    }
}

?>
