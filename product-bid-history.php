<?php


include 'DBconnect.php';
$objDB = new DbConnect();
$conn = $objDB->connect();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":

        if (isset($_GET['bided_products'])) {
            $sql = "SELECT product.product_name, product.image_path, bidding.product_id, bidding.bidding_id, MAX( bidding.amount_bid) AS amount_bid, bidding.createdOn FROM bidding INNER JOIN product ON product.product_id = bidding.product_id WHERE bidding.account_id = :account_id GROUP BY bidding.product_id";

            if (isset($sql)) {
                $stmt = $conn->prepare($sql);

                $stmt->bindParam(':account_id', $_GET['account_id']);

                $stmt->execute();
                $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo json_encode($history);
            }
        }

        if (isset($_GET['bid_logs'])) {
            $sql = "SELECT bidding.account_id, user_accounts.username,  product.product_name, product.image_path, bidding.bidding_id,  bidding.amount_bid , bidding.createdOn FROM bidding INNER JOIN product ON product.product_id = bidding.product_id INNER JOIN user_accounts ON user_accounts.account_id = bidding.account_id WHERE bidding.product_id = :product_id";

            if (isset($sql)) {
                $stmt = $conn->prepare($sql);

                $stmt->bindParam(':product_id', $_GET['product_id']);

                $stmt->execute();
                $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo json_encode($history);
            }
        }




        break;
}
