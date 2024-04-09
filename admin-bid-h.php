<?php


include 'DBconnect.php';
$objDB = new DbConnect();
$conn = $objDB->connect();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":

        if (isset($_GET['bid_admin'])) {
            $sql = "SELECT 
            p.*,
            COALESCE(bid_count, 0) AS num_bids
        FROM 
            product p
        LEFT JOIN 
            (SELECT 
                 product_id, 
                 COUNT(*) AS bid_count
             FROM 
                 bidding
             GROUP BY 
                 product_id) AS bid_counts ON p.product_id = bid_counts.product_id;";

            if (isset($sql)) {
                $stmt = $conn->prepare($sql);


                $stmt->execute();
                $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo json_encode($history);
            }
        }





        break;
}
