<?php


include 'DBconnect.php';
$objDB = new DbConnect();
$conn = $objDB->connect();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":

        $sql = "SELECT 
                    a.product_id,
                    a.date_until,
                    MAX(b.amount_bid) AS max_bid_for_product,
                    (
                        SELECT MAX(amount_bid)
                        FROM bidding
                    ) AS max_bid_overall,
                    MAX(b.amount_bid) - (
                        SELECT MAX(amount_bid)
                        FROM bidding
                    ) AS outbid,
                    a.product_name,
                    IFNULL(
                        CONCAT(c.last_name, '', c.first_name, '', LEFT(c.middle_name, 1)),
                        'No bidder'
                    ) AS fname,
                    CASE
                        WHEN MAX(b.amount_bid) - (
                            SELECT MAX(amount_bid)
                            FROM bidding
                        ) = 0 THEN 1
                        ELSE 0
                    END AS statuss
                FROM
                    product a
                INNER JOIN
                    bidding b ON a.product_id = b.product_id
                INNER JOIN
                    user_accounts c ON b.account_id = c.account_id
                WHERE b.account_id = :account_id
                GROUP BY
            a.product_id, a.product_name";

        if (isset($sql)) {
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':account_id', $_GET['account_id']);

            $stmt->execute();
            $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($history);
        }


        break;
}
