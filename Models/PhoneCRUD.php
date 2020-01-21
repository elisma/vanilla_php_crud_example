<?php


namespace Models;

include_once '../Utils/Database.php';

use PDO;
use Utils\Database;

class PhoneCRUD
{


    // Connection instance
    private $connection;

    // table name


    public function __construct() {
        // INCLUDING DATABASE AND MAKING OBJECT
        $db_connection = new Database();
        $this->connection = $db_connection->get_connection();
    }

    //C
    public function update() {


        parse_str(file_get_contents('php://input'), $update_vars);

//lloking for id in PUT parameters
        if (isset($update_vars['id']) && $update_vars['id']) {
            $phone_id = $update_vars['id'];

            //GET PHONE BY ID FROM DATABASE
            $get_phone = "SELECT * FROM `phones` WHERE id=:phone_id";
            $get_stmt = $this->connection->prepare($get_phone);
            $get_stmt->bindValue(':phone_id', $phone_id, PDO::PARAM_INT);
            $get_stmt->execute();

            //CHECK WHETHER THERE IS ANY PHONE IN OUR DATABASE
            if ($get_stmt->rowCount() == 0) return FALSE;

            // FETCH PHONE FROM DATBASE
            $row = $get_stmt->fetch(PDO::FETCH_ASSOC);

            // CHECK, IF NEW UPDATE REQUEST DATA IS AVAILABLE THEN SET IT OTHERWISE SET OLD DATA
            $phone = isset($update_vars['phone']) ? $update_vars['phone'] : $row['phone'];

            $update_query = "UPDATE `phones` SET phone = :phone
        WHERE id = :phone_id";

            $update_stmt = $this->connection->prepare($update_query);

            // DATA BINDING AND REMOVE SPECIAL CHARS AND REMOVE TAGS
            $update_stmt->bindValue(':phone', htmlspecialchars(strip_tags($phone)), PDO::PARAM_STR);
            $update_stmt->bindValue(':phone_id', $phone_id, PDO::PARAM_INT);


            if ($update_stmt->execute()) {
                return 'Phone Updated';
            }

        }
        return FALSE;

    }
    public function read() {

        // CHECK GET ID PARAMETER OR NOT
        $phone_id = FALSE;
        if (isset($_GET['id'])) {
            //IF HAS ID PARAMETER
            $phone_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        }

// --------Prepare a where clause in case there is an id in GET


        $query = " SELECT ph.*,
     FROM phones as ph";
        if($phone_id) $query= $query."WHERE ph.id=:phone_id";
        $request = $this->connection->prepare($query);

        $request->bindValue(':phone_id', $phone_id, PDO::PARAM_INT);
        $request->execute();
        $data = array();

//CHECK WHETHER THERE IS ANY PROFILE IN OUR DATABASE
        if ($request->rowCount() > 0) {


            while ($row = $request->fetch(PDO::FETCH_ASSOC)) {


                array_push($data, $row);
            }


        }
        return $data;
    }


    //U
    public function create() {

        if (isset($_POST['profile_id']) && $_POST['profile_id'] && isset($_POST['phone']) && $_POST['phone']) {
            // check if there is a profile that matches
            if(!$this->_check_if_profile_exists($_POST['profile_id'])) return FALSE;


            // CHECK DATA VALUE IS EMPTY OR NOT

            $insert_query = "INSERT INTO `phones`(profile_id,phone) VALUES(:profile_id,:phone)";

            $insert_stmt = $this->connection->prepare($insert_query);
            // DATA BINDING
            $insert_stmt->bindValue(':profile_id', htmlspecialchars(strip_tags($_POST['profile_id'])), PDO::PARAM_INT);
            $insert_stmt->bindValue(':phone', htmlspecialchars(strip_tags($_POST['phone'])), PDO::PARAM_STR);

            if ($insert_stmt->execute()) {

                return $this->connection->lastInsertId();
            }

        }

        return FALSE;
    }

    //D
    public function delete() {

        parse_str(file_get_contents('php://input'), $delete_vars);

//CHECKING, IF ID AVAILABLE ON $data
        if (isset($delete_vars['id']) && $phone_id = $delete_vars['id']) {

            //GET PHONE BY ID FROM DATABASE
            $get_phone = "SELECT * FROM `phones` WHERE id=:phone_id";
            $get_stmt = $this->connection->prepare($get_phone);
            $get_stmt->bindValue(':phone_id', $delete_vars['id'], PDO::PARAM_INT);
            $get_stmt->execute();

            //CHECK WHETHER THERE IS ANY PHONE IN OUR DATABASE
            if ($get_stmt->rowCount() == 0) return FALSE;

            //DELETE PHONE BY ID FROM DATABASE
            $delete_phone = "DELETE FROM `phones` WHERE id=:phone_id";
            $delete_phone_stmt = $this->connection->prepare($delete_phone);
            $delete_phone_stmt->bindValue(':phone_id', $phone_id, PDO::PARAM_INT);

            if ($delete_phone_stmt->execute()) {
                return TRUE;
            }


        }
        return FALSE;

    }

    private function _check_if_profile_exists($profile_id) {

        //GET PROFILE BY ID FROM DATABASE
        $get_profile = "SELECT * FROM `profiles` WHERE id=:profile_id";
        $get_stmt = $this->connection->prepare($get_profile);
        $get_stmt->bindValue(':profile_id', $profile_id, PDO::PARAM_INT);
        $get_stmt->execute();

        //CHECK WHETHER THERE IS ANY PROFILE IN OUR DATABASE
        return $get_stmt->rowCount();


    }
}