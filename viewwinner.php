<?php


include 'DBconnect.php';
$objDB = new DbConnect();
$conn = $objDB->connect();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":

        if (isset($_GET['account_id'])) {
            $account_id = $_GET['account_id'];
            $sql = "SELECT account_id, username, first_name, last_name, middle_name, address, email_address, phone_number, id_image, is_verified FROM user_accounts WHERE account_id= :account_id";

            if (isset($sql)) {
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':account_id', $account_id);

                $stmt->execute();
                $account = $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo json_encode($account);
            }
        }



        break;
}
