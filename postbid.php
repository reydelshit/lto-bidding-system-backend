<?php


include 'DBconnect.php';
$objDB = new DbConnect();
$conn = $objDB->connect();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":


        if (isset($_GET['user_id'])) {
            $user_id = $_GET['user_id'];
            $sql = "SELECT * FROM product WHERE user_id = :user_id";
        }

        if (isset($_GET['product_id'])) {
            $product_id_spe = $_GET['product_id'];
            $sql = "SELECT * FROM product WHERE product_id = :product_id";
        }


        if (!isset($_GET['product_id']) && !isset($_GET['user_id'])) {
            $sql = "SELECT * FROM product ORDER BY product_id DESC ";
        }


        if (isset($sql)) {
            $stmt = $conn->prepare($sql);

            if (isset($product_id_spe)) {
                $stmt->bindParam(':product_id', $product_id_spe);
            }

            if (isset($user_id)) {
                $stmt->bindParam(':user_id', $user_id);
            }

            $stmt->execute();
            $product = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($product);
        }



        break;


    case "POST":
        $bidding = json_decode(file_get_contents('php://input'));
        $sql = "INSERT INTO bidding (bidding_id, product_id, account_id, amount_bid, createdOn) 
                    VALUES (null, :product_id, :account_id, :amount_bid, :createdOn)";

        $stmt = $conn->prepare($sql);
        $createdOn = date('Y-m-d H:i:s');
        $stmt->bindParam(':product_id', $bidding->product_id);
        $stmt->bindParam(':account_id', $bidding->account_id);
        $stmt->bindParam(':amount_bid', $bidding->amount_bid);
        $stmt->bindParam(':createdOn', $createdOn);

        if ($stmt->execute()) {
            $response = [
                "status" => "success",
                "message" => "Bidding added successfully"
            ];
        } else {
            $response = [
                "status" => "error",
                "message" => "Failed to add bidding"
            ];
        }

        echo json_encode($response);
        break;
}
