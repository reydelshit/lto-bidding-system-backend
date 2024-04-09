<?php


include 'DBconnect.php';
$objDB = new DbConnect();
$conn = $objDB->connect();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":

        if (isset($_GET['products'])) {
            $sql = "SELECT DATE_FORMAT(product.created_on, '%M') AS name, COUNT(*) AS total
            FROM product
            GROUP BY MONTH(product.created_on)";

            if (isset($sql)) {
                $stmt = $conn->prepare($sql);

                $stmt->execute();
                $account = $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo json_encode($account);
            }
        }


        if (isset($_GET['bidders'])) {
            $sql = "SELECT DATE_FORMAT(user_accounts.created_on, '%M') AS name, COUNT(*) AS total
            FROM user_accounts
            GROUP BY MONTH(user_accounts.created_on)";

            if (isset($sql)) {
                $stmt = $conn->prepare($sql);

                $stmt->execute();
                $account = $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo json_encode($account);
            }
        }



        break;
}
