<?php
class NewUser {

    public $username;
    public $password;
    public $password_check;
    public $mail;
    public $sex;
    public $birthdate;
    public $birthdate_year;
    public $birthdate_month;
    public $birthdate_day;
    public $height;
    public $shoe_size;

    function __construct($username = '', $password = '', $password_check = '', $mail = '', $sex = 0, $birthdate = '', $birthdate_year = '', $birthdate_month = '', $birthdate_day = '', $height = 0, $show_size = 0) {
            
        $this->username = $username;
        $this->password = $password;
        $this->password_check = $password_check;
        $this->mail = $mail;
        $this->sex = $sex;
        $this->birthdate = $birthdate;
        $this->birthdate_year = $birthdate_year;
        $this->birthdate_month = $birthdate_month;
        $this->birthdate_day = $birthdate_day;
        $this->height = $height;
        $this->shoe_size = $show_size;       
        
    }
}
