<?php


include 'DBconnect.php';
$objDB = new DbConnect();
$conn = $objDB->connect();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":


        $product_id = $_GET['product_id'];
        $sql = "SELECT COUNT(bidding.product_id) as count FROM bidding WHERE product_id = :product_id";

        if (isset($sql)) {
            $stmt = $conn->prepare($sql);


            if (isset($product_id)) {
                $stmt->bindParam(':product_id', $product_id);
            }

            $stmt->execute();
            $count = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($count);
        }



        break;
}
