<?php
include 'db.php';


$input = file_get_contents("php://input");
$data = json_decode($input, true);


if (!$data) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid or missing JSON",
        "received" => $input
    ]);
    exit;
}


$name = $data['name'] ?? null;
$email = $data['email'] ?? null;
$password = $data['password'] ?? null;
$confirmPassword = $data['confirmPassword'] ?? null;


if (!$name || !$email || !$password || !$confirmPassword) {
    echo json_encode([
        "status" => "error",
        "message" => "Missing required fields",
        "received_data" => $data
    ]);
    exit;
}


if ($password !== $confirmPassword) {
    echo json_encode(["status" => "error", "message" => "Passwords do not match"]);
    exit;
}


$check = $conn->prepare("SELECT * FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$checkResult = $check->get_result();

if ($checkResult->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "User already exists"]);
    exit;
}


$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $hashedPassword);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "User registered successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Registration failed"]);
}
?>
