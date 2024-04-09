<?php


include 'DBconnect.php';
$objDB = new DbConnect();
$conn = $objDB->connect();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":

        $sql = "SELECT
        a.product_id,
        a.product_name,
        a.is_vip,
        a.available_slot,
        a.regular_price,
        a.starting_price,
        a.date_until,
        a.image_path,
        COUNT(b.bidding_id) AS cnt,
        IFNULL(MAX(b.amount_bid), 0) AS amt,
        a.date_until,
        IFNULL(CONCAT(c.last_name,'',c.first_name,'',LEFT(c.middle_name,1)),'No bidder') AS fname
        FROM product a LEFT JOIN 
        bidding b ON a.product_id=b.product_id
        LEFT JOIN user_accounts c ON b.account_id = c.account_id
        WHERE DATE(a.date_until) >= DATE(now())
        GROUP BY a.product_id
        ORDER BY a.created_on ASC";

        if (isset($sql)) {
            $stmt = $conn->prepare($sql);

            $stmt->execute();
            $product = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($product);
        }



        break;
}
