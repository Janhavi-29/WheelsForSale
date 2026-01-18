<?php
include('connection.php');

// Fetch second-hand car records from the database
$sql = "SELECT * FROM secondhandcars";
$result = $conn->query($sql);

$cars = [];
while ($row = $result->fetch_assoc()) {
    $cars[] = $row;
}

echo json_encode($cars);
$conn->close();
?>
