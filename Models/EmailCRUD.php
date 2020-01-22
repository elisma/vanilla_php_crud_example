<?php

namespace Models;
include_once '../Utils/Database.php';

use PDO;
use Utils\Database;

class EmailCRUD
{
    // Connection instance
    private $connection;

    public function __construct() {
        // INCLUDING DATABASE AND MAKING OBJECT
        $db_connection = new Database();
        $this->connection = $db_connection->get_connection();
    }

    public function update() {
        parse_str(file_get_contents('php://input'), $update_vars);

        //looking for id in PUT parameters
        if (isset($update_vars['id']) && $update_vars['id']) {
            $email_id = $update_vars['id'];

            //GET EMAIL BY ID FROM DATABASE
            $get_email = "SELECT * FROM `emails` WHERE id=:email_id";
            $get_stmt = $this->connection->prepare($get_email);
            $get_stmt->bindValue(':email_id', $email_id, PDO::PARAM_INT);
            $get_stmt->execute();

            //CHECK WHETHER THERE IS ANY EMAIL IN OUR DATABASE
            if ($get_stmt->rowCount() == 0) return FALSE;

            // FETCH EMAIL FROM DATBASE
            $row = $get_stmt->fetch(PDO::FETCH_ASSOC);

            // CHECK, IF NEW UPDATE REQUEST DATA IS AVAILABLE THEN SET IT OTHERWISE SET OLD DATA
            $email = isset($update_vars['email']) ? $update_vars['email'] : $row['email'];

            $update_query = "UPDATE `emails` SET email = :email
                             WHERE id = :email_id";

            $update_stmt = $this->connection->prepare($update_query);

            // DATA BINDING AND REMOVE SPECIAL CHARS AND REMOVE TAGS
            $update_stmt->bindValue(':email', htmlspecialchars(strip_tags($email)), PDO::PARAM_STR);
            $update_stmt->bindValue(':email_id', $email_id, PDO::PARAM_INT);


            if ($update_stmt->execute()) {
                return 'Email Updated';
            }
        }
        return FALSE;
    }

    public function read() {

        // CHECK GET ID PARAMETER OR NOT
        $email_id = FALSE;
        if (isset($_GET['id'])) {
            //IF HAS ID PARAMETER
            $email_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        }


        $query = " SELECT e.*
                     FROM emails as e";
// --------Prepare a where clause in case there is an id in GET
        if ($email_id) $query = $query . "WHERE ph.id=:email_id";
        $request = $this->connection->prepare($query);
        $request->bindValue(':email_id', $email_id, PDO::PARAM_INT);
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
        if (isset($_POST['profile_id']) && $_POST['profile_id'] && isset($_POST['email']) && $_POST['email']) {
            // check if there is a profile that matches
            if (!$this->_check_if_profile_exists($_POST['profile_id'])) return FALSE;
            // CHECK DATA VALUE IS EMPTY OR NOT
            $insert_query = "INSERT INTO `emails`(profile_id,email) VALUES(:profile_id,:email)";
            $insert_stmt = $this->connection->prepare($insert_query);
            // DATA BINDING
            $insert_stmt->bindValue(':profile_id', htmlspecialchars(strip_tags($_POST['profile_id'])), PDO::PARAM_INT);
            $insert_stmt->bindValue(':email', htmlspecialchars(strip_tags($_POST['email'])), PDO::PARAM_STR);
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
        if (isset($delete_vars['id']) && $email_id = $delete_vars['id']) {
            //GET EMAIL BY ID FROM DATABASE
            $get_email = "SELECT * FROM `emails` WHERE id=:email_id";
            $get_stmt = $this->connection->prepare($get_email);
            $get_stmt->bindValue(':email_id', $delete_vars['id'], PDO::PARAM_INT);
            $get_stmt->execute();
            //CHECK WHETHER THERE IS ANY EAMIL IN OUR DATABASE
            if ($get_stmt->rowCount() == 0) return FALSE;

            //DELETE EMAIL BY ID FROM DATABASE
            $delete_email = "DELETE FROM `emails` WHERE id=:email_id";
            $delete_email_stmt = $this->connection->prepare($delete_email);
            $delete_email_stmt->bindValue(':email_id', $email_id, PDO::PARAM_INT);
            if ($delete_email_stmt->execute()) {
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