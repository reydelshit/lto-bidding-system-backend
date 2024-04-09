<?php


include 'DBconnect.php';
$objDB = new DbConnect();
$conn = $objDB->connect();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":

        $sql = "SELECT 
                MAX(b.amount_bid) AS max_bid_for_product,
                a.date_until,
                payment.status AS payment_status,
                a.product_name,
                CASE
                    WHEN a.date_until >= NOW() THEN 0
                    WHEN (
                        SELECT account_id
                        FROM bidding
                        WHERE product_id = a.product_id
                        ORDER BY amount_bid DESC
                        LIMIT 1
                    ) = 11 THEN 1
                    ELSE 2
                END AS status
            FROM
                product a
            INNER JOIN
                bidding b ON a.product_id = b.product_id
            INNER JOIN
                user_accounts c ON b.account_id = c.account_id
            LEFT JOIN
                payment ON payment.product_id = b.product_id
            WHERE
                b.account_id = :account_id
            GROUP BY
                a.product_id, a.date_until, payment.status, a.product_name;";

        if (isset($sql)) {
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':account_id', $_GET['account_id']);

            $stmt->execute();
            $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($history);
        }



        break;
}
