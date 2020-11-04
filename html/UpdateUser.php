<?php
class UpdateUser {

    public $username;
    public $current_password;
    public $new_password;
    public $new_password_check;
    public $mail;
    public $sex;
    public $birthdate;
    public $birthdate_year;
    public $birthdate_month;
    public $birthdate_day;
    public $height;
    public $shoe_size;

    function __construct($username = '', $current_password = '', $new_password = '', $new_password_check = '', $mail = '', $sex = 0, $birthdate = '', $birthdate_year = 0, $birthdate_month = 0, $birthdate_day = 0, $height = 0, $show_size = 0) {
            
        $this->username = $username;
        $this->current_password = $current_password;
        $this->new_password = $new_password;
        $this->new_password_check = $new_password_check;
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
