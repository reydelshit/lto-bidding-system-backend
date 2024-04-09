<?php


include 'DBconnect.php';
$objDB = new DbConnect();
$conn = $objDB->connect();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":


        $sql = "SELECT product.product_name, user_accounts.first_name, user_accounts.last_name, payment.account_id, payment.status, payment.payment_id, payment.amount, payment.proof_image FROM payment
		INNER JOIN product ON product.product_id = payment.product_id INNER JOIN user_accounts ON user_accounts.account_id = payment.account_id ";


        if (isset($sql)) {
            $stmt = $conn->prepare($sql);


            $stmt->execute();
            $product = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($product);
        }



        break;


    case "POST":
        $payment = json_decode(file_get_contents('php://input'));
        $sql = "INSERT INTO payment (payment_id, proof_image, product_id, account_id, created_at) 
                    VALUES (null, :proof_image, :product_id, :account_id, :created_at)";

        $stmt = $conn->prepare($sql);
        $created_at = date('Y-m-d H:i:s');
        $stmt->bindParam(':proof_image', $payment->proof_image);
        $stmt->bindParam(':product_id', $payment->product_id);
        $stmt->bindParam(':account_id', $payment->account_id);
        $stmt->bindParam(':created_at', $created_at);

        if ($stmt->execute()) {
            $response = [
                "status" => "success",
                "message" => "Payment added successfully"
            ];
        } else {
            $response = [
                "status" => "error",
                "message" => "Failed to add payment"
            ];
        }

        echo json_encode($response);
        break;

    case "PUT":
        $payment = json_decode(file_get_contents('php://input'));

        $sql = "UPDATE payment SET status = :status
                    WHERE payment_id = :payment_id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':payment_id', $payment->payment_id);
        $stmt->bindParam(':status', $payment->status);


        if ($stmt->execute()) {
            $response = [
                "status" => "success",
                "message" => "payment updated successfully"
            ];
        } else {
            $response = [
                "status" => "error",
                "message" => "Failed to update payment"
            ];
        }

        echo json_encode($response);
        break;

    case "DELETE":
        $product = json_decode(file_get_contents('php://input'));
        $sql = "DELETE FROM product WHERE product_id = :product_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':product_id', $product->product_id);

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
