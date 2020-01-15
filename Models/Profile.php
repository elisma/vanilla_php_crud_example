<?php


namespace Models;

// just a base class representing
class Profile
{



    // table columns
    public $id;
    public $first_names;
    public $sur_names;
    public  $phones;
    public $emails;


    public function __construct($id, $first_names, $sur_names, $phones, $emails)
    {
        $this->id = $id;
        $this->first_names = $first_names;
        $this->sur_names = $sur_names;
        $this->phones = explode(',',$phones);
        $this->emails = explode(',',$emails);
    }



}