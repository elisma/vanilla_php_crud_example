<?php


namespace Models;

include_once '../Utils/Database.php';

use PDO;
use stdClass;
use Utils\Database;

class ProfileCRUD
{


    // Connection instance
    private $connection;

    // table name
    private $table_name = "Profiles";


    public function __construct()
    {
        // INCLUDING DATABASE AND MAKING OBJECT
        $db_connection = new Database();
        $this->connection = $db_connection->get_connection();
    }

    //C
    public function update()
    {


        parse_str( file_get_contents('php://input'),$update_vars);

//lloking for id in PUT parameters
        if (isset($update_vars['id']) && $update_vars['id']) {

            $profile_id = $update_vars['id'];

            //GET POST BY ID FROM DATABASE
            $get_post = "SELECT * FROM `profiles` WHERE id=:profile_id";
            $get_stmt = $this->connection->prepare($get_post);
            $get_stmt->bindValue(':profile_id', $profile_id, PDO::PARAM_INT);
            $get_stmt->execute();

            //CHECK WHETHER THERE IS ANY POST IN OUR DATABASE
            if ($get_stmt->rowCount() == 0) return FALSE;
            // FETCH POST FROM DATBASE
            $row = $get_stmt->fetch(PDO::FETCH_ASSOC);

            // CHECK, IF NEW UPDATE REQUEST DATA IS AVAILABLE THEN SET IT OTHERWISE SET OLD DATA
            $first_names = isset($update_vars['first_names']) ? $update_vars['first_names'] : $row['first_names'];
            $surnames = isset($update_vars['surnames']) ? $update_vars['surnames'] : $row['surnames'];

            $update_query = "UPDATE `profiles` SET first_names = :first_names, surnames = :surnames
        WHERE id = :profile_id";

            $update_stmt = $this->connection->prepare($update_query);

            // DATA BINDING AND REMOVE SPECIAL CHARS AND REMOVE TAGS
            $update_stmt->bindValue(':first_names', htmlspecialchars(strip_tags($first_names)), PDO::PARAM_STR);
            $update_stmt->bindValue(':surnames', htmlspecialchars(strip_tags($surnames)), PDO::PARAM_STR);
            $update_stmt->bindValue(':profile_id', $profile_id, PDO::PARAM_INT);


            if ($update_stmt->execute()) {
                return 'Profile Updated';
            }

        }
        return FALSE;

    }

    //R
    public function read()
    {

        // CHECK GET ID PARAMETER OR NOT
        $profile_id = FALSE;
        if (isset($_GET['id'])) {
            //IF HAS ID PARAMETER
            $profile_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        }

// --------Prepare a where clause in case there is an id in GET
        $where_clause = ($profile_id) ? " WHERE id='$profile_id'" : "";


        $query = " SELECT p.*,
          GROUP_CONCAT(DISTINCT ph.phone SEPARATOR ', ') as phones,
          GROUP_CONCAT( DISTINCT e.email SEPARATOR ', ') as emails
     FROM profiles as p
LEFT JOIN emails as e  ON p.id = e.profile_id
LEFT JOIN phones as ph  ON p.id = ph.profile_id
 GROUP BY p.id
 ";

        $request = $this->connection->prepare($query);

        $request->execute();
        $data = array();

//CHECK WHETHER THERE IS ANY POST IN OUR DATABASE
        if ($request->rowCount() > 0) {


            while ($row = $request->fetch(PDO::FETCH_ASSOC)) {

                $p = new Profile($row['id'], $row['first_names'], $row['surnames'], $row['phones'], $row['emails']);

                array_push($data, $p);
            }


        }
        return $data;
    }

    //U
    public function create()
    {



// TODO: i havent tested thiss!! finish it
        if (isset($_POST['first_names']) && $_POST['first_names'] && isset($_POST['surnames']) && $_POST['surnames']) {
            // CHECK DATA VALUE IS EMPTY OR NOT

                $insert_query = "INSERT INTO `profiles`(first_names,surnames) VALUES(:first_names,:surnames)";

                $insert_stmt = $this->connection->prepare($insert_query);
                // DATA BINDING
                $insert_stmt->bindValue(':first_names', htmlspecialchars(strip_tags($_POST['first_names'])), PDO::PARAM_STR);
                $insert_stmt->bindValue(':surnames', htmlspecialchars(strip_tags($_POST['surnames'])), PDO::PARAM_STR);

                if ($insert_stmt->execute()) {
                    return 'Data Inserted Successfully';
                }

        }

        return FALSE;
    }

    //D
    public function delete()
    {

        parse_str( file_get_contents('php://input'),$delete_vars);

//CHECKING, IF ID AVAILABLE ON $data
        if (isset( $delete_vars['id']) && $profile_id = $delete_vars['id']) {

            //DELETE POST BY ID FROM DATABASE
            $delete_post = "DELETE FROM `profiles` WHERE id=:profile_id";
            $delete_post_stmt = $this->connection->prepare($delete_post);
            $delete_post_stmt->bindValue(':profile_id', $profile_id, PDO::PARAM_INT);

            if ($delete_post_stmt->execute()) {
                return TRUE;
            }


        }
        return FALSE;

    }
}