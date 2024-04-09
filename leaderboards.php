<?php


include 'DBconnect.php';
$objDB = new DbConnect();
$conn = $objDB->connect();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":

        if (isset($_GET['product_id'])) {
            $product_id = $_GET['product_id'];
            $sql = "SELECT
                    a.product_name,
                    a.image_path,
                    b.createdOn AS date_created,
                    b.amount_bid AS amt,
                    IFNULL(CONCAT(c.last_name,' ',c.first_name,' ', LEFT(c.middle_name,1)),'No bidder') AS fname
                    FROM product a LEFT JOIN 
                    bidding b ON a.product_id=b.product_id
                    LEFT JOIN user_accounts c ON b.account_id = c.account_id
                    WHERE b.product_id= :product_id
                    ORDER BY b.amount_bid DESC";

            if (isset($sql)) {
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':product_id', $product_id);

                $stmt->execute();
                $leader = $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo json_encode($leader);
            }
        }



        break;
}
