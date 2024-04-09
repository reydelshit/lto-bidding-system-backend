<?php


include 'DBconnect.php';
$objDB = new DbConnect();
$conn = $objDB->connect();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":


        $account_id = $_GET['account_id'];
        $sql = "SELECT * FROM vip WHERE account_id = :account_id";

        if (isset($sql)) {
            $stmt = $conn->prepare($sql);


            if (isset($account_id)) {
                $stmt->bindParam(':account_id', $account_id);
            }

            $stmt->execute();
            $vip = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($vip);
        }



        break;


    case "POST":
        $bidding = json_decode(file_get_contents('php://input'));
        $sql = "INSERT INTO vip (vip_id, account_id, created_at) 
                    VALUES (null, :account_id, :created_at)";

        $stmt = $conn->prepare($sql);
        $created_at = date('Y-m-d H:i:s');
        $stmt->bindParam(':account_id', $bidding->account_id);
        $stmt->bindParam(':created_at', $created_at);

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

    case "DELETE":
        $vips = json_decode(file_get_contents('php://input'));
        $sql = "DELETE FROM vip WHERE account_id = :account_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':account_id', $vips->account_id);

        if ($stmt->execute()) {
            $response = [
                "status" => "success",
                "message" => "product deleted successfully"
            ];
        } else {
            $response = [
                "status" => "error",
                "message" => "product delete failed"
            ];
        }

        echo json_encode($response);
        break;
}
