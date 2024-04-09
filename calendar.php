<?php


include 'DBconnect.php';
$objDB = new DbConnect();
$conn = $objDB->connect();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":

        $sql = "SELECT calendar_id AS id, calendar_title AS title, start, end, allDay FROM calendar";


        if (isset($sql)) {
            $stmt = $conn->prepare($sql);

            $stmt->execute();
            $calendar = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($calendar);
        }


        break;

    case "POST":
        $calendar = json_decode(file_get_contents('php://input'));
        $sql = "INSERT INTO calendar (calendar_id, calendar_title, start, end, allDay, account_id) 
                VALUES (null, :calendar_title, :start, :end, :allDay, :account_id)";
        $stmt = $conn->prepare($sql);
        $created_at = date('Y-m-d H:i:s');
        $stmt->bindParam(':calendar_title', $calendar->calendar_title);
        $stmt->bindParam(':start', $calendar->start);
        $stmt->bindParam(':end', $calendar->end);
        $stmt->bindParam(':allDay', $calendar->allDay);
        $stmt->bindParam(':account_id', $calendar->account_id);



        // $stmt->bindParam(':created_at', $created_at);

        if ($stmt->execute()) {
            $response = [
                "status" => "success",
                "message" => "calendar created successfully"
            ];
        } else {
            $response = [
                "status" => "error",
                "message" => "calendar creation failed"
            ];
        }

        echo json_encode($response);
        break;

    case "PUT":
        $calendar = json_decode(file_get_contents('php://input'));
        $sql = "UPDATE calendar SET calendar_title= :calendar_title, start=:start, end=:end, allDay=:allDay 
                WHERE calendar_id = :calendar_id";
        $stmt = $conn->prepare($sql);
        $updated_at = date('Y-m-d');
        $stmt->bindParam(':calendar_id', $calendar->calendar_id);
        $stmt->bindParam(':calendar_title', $calendar->calendar_title);
        $stmt->bindParam(':start', $calendar->start);
        $stmt->bindParam(':end', $calendar->end);
        $stmt->bindParam(':allDay', $calendar->allDay);

        if ($stmt->execute()) {

            $response = [
                "status" => "success",
                "message" => "User updated successfully"
            ];
        } else {
            $response = [
                "status" => "error",
                "message" => "User update failed"
            ];
        }

        echo json_encode($response);
        break;

    case "DELETE":
        $sql = "DELETE FROM calendar WHERE calendar_id = :id";
        $path = explode('/', $_SERVER['REQUEST_URI']);

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $path[3]);

        if ($stmt->execute()) {
            $response = [
                "status" => "success",
                "message" => "User deleted successfully"
            ];
        } else {
            $response = [
                "status" => "error",
                "message" => "User deletion failed"
            ];
        }
}
