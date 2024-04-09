<?php


include 'DBconnect.php';
$objDB = new DbConnect();
$conn = $objDB->connect();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":

        $sql = "SELECT
                    a.product_id
                    FROM
                    product a
                    INNER JOIN
                    bidding b ON a.product_id = b.product_id
                    INNER JOIN
                    user_accounts c ON b.account_id = c.account_id
                    WHERE a.date_until < NOW()
                    GROUP BY
                    a.product_id, a.product_name";

        if (isset($sql)) {
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $winners = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($winners);
        }




        break;
}
