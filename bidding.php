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
        a.brand_name,
        a.year_model,
        a.product_condition,
        a.regular_price,
        a.starting_price,
        a.date_until,
        a.image_path,
        c.account_id,
        COUNT(b.bidding_id) as cnt,
        IFNULL(MAX(b.amount_bid), 0) as amt,
        
        a.date_until,
        IFNULL(CONCAT(c.last_name,'',c.first_name,'',LEFT(c.middle_name,1)),'No bidder') as fname
        FROM product a LEFT JOIN 
        bidding b ON a.product_id=b.product_id
        LEFT JOIN user_accounts c ON b.account_id = c.account_id
        group by a.product_id
        order by a.created_on asc";


        if (isset($sql)) {
            $stmt = $conn->prepare($sql);

            $stmt->execute();
            $bidding = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($bidding);
        }



        break;





    case "POST":
        $product = json_decode(file_get_contents('php://input'));
        $sql = "INSERT INTO product (product_id, product_name, brand_name, year_model, description, product_condition, regular_price, starting_price, date_until, image_path, created_on) 
                VALUES (null, :product_name, :brand_name, :year_model, :description, :product_condition, :regular_price, :starting_price, :date_until, :image_path, :created_on)";

        $stmt = $conn->prepare($sql);
        $created_on = date('Y-m-d');
        $stmt->bindParam(':product_name', $product->product_name);
        $stmt->bindParam(':brand_name', $product->brand_name);
        $stmt->bindParam(':year_model', $product->year_model);
        $stmt->bindParam(':description', $product->description);
        $stmt->bindParam(':product_condition', $product->product_condition);
        $stmt->bindParam(':regular_price', $product->regular_price);
        $stmt->bindParam(':starting_price', $product->starting_price);
        $stmt->bindParam(':date_until', $product->date_until);
        $stmt->bindParam(':image_path', $product->image_path);
        $stmt->bindParam(':created_on', $created_on);

        if ($stmt->execute()) {
            $response = [
                "status" => "success",
                "message" => "Product added successfully"
            ];
        } else {
            $response = [
                "status" => "error",
                "message" => "Failed to add product"
            ];
        }

        echo json_encode($response);
        break;

    case "PUT":
        $product = json_decode(file_get_contents('php://input'));

        if (isset($product->updateStatus)) {
            $sql = "UPDATE user_accounts SET is_verified = :is_verified WHERE account_id = :account_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':account_id', $product->account_id);
            $stmt->bindParam(':is_verified', $product->is_verified);
            if ($stmt->execute()) {
                $response = [
                    "status" => "success",
                    "message" => "user account status updated successfully"
                ];
            } else {
                $response = [
                    "status" => "error",
                    "message" => "Failed to update user account status"
                ];
            }
            echo json_encode($response);
            break;
        }

        $sql = "UPDATE product SET product_name = :product_name, brand_name = :brand_name, year_model = :year_model, 
                    description = :description, product_condition = :product_condition, regular_price = :regular_price, 
                    starting_price = :starting_price, date_until = :date_until, image_path = :image_path, created_on = :created_on 
                    WHERE product_id = :product_id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':product_id', $product->product_id);
        $stmt->bindParam(':product_name', $product->product_name);
        $stmt->bindParam(':brand_name', $product->brand_name);
        $stmt->bindParam(':year_model', $product->year_model);
        $stmt->bindParam(':description', $product->description);
        $stmt->bindParam(':product_condition', $product->product_condition);
        $stmt->bindParam(':regular_price', $product->regular_price);
        $stmt->bindParam(':starting_price', $product->starting_price);
        $stmt->bindParam(':date_until', $product->date_until);
        $stmt->bindParam(':image_path', $product->image_path);
        $stmt->bindParam(':created_on', $product->created_on);

        if ($stmt->execute()) {
            $response = [
                "status" => "success",
                "message" => "Product updated successfully"
            ];
        } else {
            $response = [
                "status" => "error",
                "message" => "Failed to update product"
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
