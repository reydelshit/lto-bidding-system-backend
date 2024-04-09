<?php


include 'DBconnect.php';
$objDB = new DbConnect();
$conn = $objDB->connect();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":

        if (isset($_GET['account_id'])) {
            $account_id = $_GET['account_id'];
            $sql = "SELECT reviews.feedback_rating, reviews.description, product.product_name, product.image_path, user_accounts.first_name, user_accounts.last_name FROM reviews 
                    INNER JOIN product ON product.product_id = reviews.product_id INNER JOIN user_accounts ON user_accounts.account_id = reviews.account_id WHERE reviews.account_id = :account_id";
        }


        if (isset($sql)) {
            $stmt = $conn->prepare($sql);

            if (isset($account_id)) {
                $stmt->bindParam(':account_id', $account_id);
            }

            $stmt->execute();
            $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($reviews);
        }


        break;

    case "POST":
        $feedback = json_decode(file_get_contents('php://input'));
        $sql = "INSERT INTO reviews (description, feedback_rating, feedback_date, product_id, account_id) 
                VALUES (:description, :feedback_rating, :feedback_date, :product_id, :account_id)";
        $stmt = $conn->prepare($sql);
        $feedback_date = date('Y-m-d');
        $stmt->bindParam(':description', $feedback->feedback_description);
        $stmt->bindParam(':feedback_rating', $feedback->feedback_rating);
        $stmt->bindParam(':feedback_date', $feedback_date);
        $stmt->bindParam(':product_id', $feedback->product_id);
        $stmt->bindParam(':account_id', $feedback->account_id);


        if ($stmt->execute()) {
            $response = [
                "status" => "success",
                "message" => "User created feedback successfully"
            ];
        } else {
            $response = [
                "status" => "error",
                "message" => "User creation feedback failed"
            ];
        }

        echo json_encode($response);
        break;
}
