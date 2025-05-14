<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $message = htmlspecialchars($_POST['message'] ?? '');

    // Exemple d’enregistrement ou traitement ici...

    echo json_encode([
        'success' => true,
        'message' => 'Message received.',
        'data' => compact('name', 'email', 'message')
    ]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request']);
?>
