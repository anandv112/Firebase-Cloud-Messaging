<?php

    require("config.php");

    if (!empty($_POST)) {

        $response = array(
            "error" => FALSE
        );

        $query = "INSERT INTO users ( fcm_registered_id, name, email, created_at ) VALUES ( :fcm_id, :name, :email, NOW() ) ";

            $query_params = array(
                ':fcm_id' => $_POST['fcm_id'],
                ':name' => $_POST['name'],
                ':email' => $_POST['email']

            );

            try {
                $stmt = $db->prepare($query);
                $result = $stmt->execute($query_params);
            }

            catch (PDOException $ex) {
                $response["error"] = TRUE;
                $response["message"] = "Database Error2. Please Try Again!";
                die(json_encode($response));
            }

            $response["error"] = FALSE;
            $response["message"] = "Register successful!";
            echo json_encode($response);
        }

    } else {
        echo 'Android Learning';
    }

?>
