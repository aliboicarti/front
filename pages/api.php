<?php
ob_clean();
ini_set('display_errors', 0);
error_reporting(0);

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

// Database configuration
$host = "localhost";
$dbname = "my_website";
$username = "dev"; 
$password = "gfOIAMqPklJvFpul";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed: " . $e->getMessage()]);
    exit;
}

// Handle requests
$method = $_SERVER['REQUEST_METHOD'];

// FIX: Check if PATH_INFO exists, otherwise use empty string
$pathInfo = $_SERVER['PATH_INFO'] ?? '';
$request = explode('/', trim($pathInfo, '/'));

// FIX: Filter out empty strings from the array
$request = array_values(array_filter($request));

// Check if we have a valid endpoint
if (empty($request) || empty($request[0])) {
    http_response_code(404);
    echo json_encode(["error" => "Endpoint not found"]);
    exit;
}

// Routing for /posts
if ($request[0] === 'posts') {
    switch ($method) {
        case 'GET':
            if (isset($request[1]) && is_numeric($request[1])) {
                $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
                $stmt->execute([$request[1]]);
                $post = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode($post ?: ["error" => "Post not found"]);
            } else {
                $stmt = $pdo->query("SELECT * FROM posts");
                $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($posts);
            }
            break;
        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);
            if (!isset($data['title']) || !isset($data['content'])) {
                http_response_code(400);
                echo json_encode(["error" => "Title and content are required"]);
                exit;
            }
            $stmt = $pdo->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
            $stmt->execute([$data['title'], $data['content']]);
            http_response_code(201);
            echo json_encode(["id" => $pdo->lastInsertId(), "title" => $data['title'], "content" => $data['content']]);
            break;
        case 'PUT':
            if (!isset($request[1]) || !is_numeric($request[1])) {
                http_response_code(400);
                echo json_encode(["error" => "Invalid post ID"]);
                exit;
            }
            $data = json_decode(file_get_contents("php://input"), true);
            if (!isset($data['title']) || !isset($data['content'])) {
                http_response_code(400);
                echo json_encode(["error" => "Title and content are required"]);
                exit;
            }
            $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
            $stmt->execute([$data['title'], $data['content'], $request[1]]);
            if ($stmt->rowCount() > 0) {
                echo json_encode(["message" => "Post updated"]);
            } else {
                http_response_code(404);
                echo json_encode(["error" => "Post not found"]);
            }
            break;
        case 'DELETE':
            if (!isset($request[1]) || !is_numeric($request[1])) {
                http_response_code(400);
                echo json_encode(["error" => "Invalid post ID"]);
                exit;
            }
            $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
            $stmt->execute([$request[1]]);
            if ($stmt->rowCount() > 0) {
                echo json_encode(["message" => "Post deleted"]);
            } else {
                http_response_code(404);
                echo json_encode(["error" => "Post not found"]);
            }
            break;
        default:
            http_response_code(405);
            echo json_encode(["error" => "Method not allowed"]);
            break;
    }
}
// Routing for /orders
elseif ($request[0] === 'orders') {
    switch ($method) {
        case 'GET':
            if (isset($request[1]) && is_numeric($request[1])) {
                $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
                $stmt->execute([$request[1]]);
                $order = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode($order ?: ["error" => "Order not found"]);
            } else {
                $stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
                $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($orders);
            }
            break;
        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);
            if (!isset($data['name']) || !isset($data['phone']) || !isset($data['description'])) {
                http_response_code(400);
                echo json_encode(["error" => "Name, phone, and description are required"]);
                exit;
            }
            $stmt = $pdo->prepare("INSERT INTO orders (name, phone, address, email, description) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['name'],
                $data['phone'],
                $data['address'] ?? null,
                $data['email'] ?? null,
                $data['description']
            ]);
            http_response_code(201);
            echo json_encode([
                "id" => $pdo->lastInsertId(),
                "name" => $data['name'],
                "phone" => $data['phone'],
                "address" => $data['address'] ?? null,
                "email" => $data['email'] ?? null,
                "description" => $data['description'],
                "message" => "Order created"
            ]);
            break;
        case 'PUT':
            if (!isset($request[1]) || !is_numeric($request[1])) {
                http_response_code(400);
                echo json_encode(["error" => "Invalid order ID"]);
                exit;
            }
            $data = json_decode(file_get_contents("php://input"), true);
            if (!isset($data['name']) || !isset($data['phone']) || !isset($data['description'])) {
                http_response_code(400);
                echo json_encode(["error" => "Name, phone, and description are required"]);
                exit;
            }
            $stmt = $pdo->prepare("UPDATE orders SET name = ?, phone = ?, address = ?, email = ?, description = ? WHERE id = ?");
            $stmt->execute([
                $data['name'],
                $data['phone'],
                $data['address'] ?? null,
                $data['email'] ?? null,
                $data['description'],
                $request[1]
            ]);
            if ($stmt->rowCount() > 0) {
                echo json_encode(["message" => "Order updated"]);
            } else {
                http_response_code(404);
                echo json_encode(["error" => "Order not found"]);
            }
            break;
        case 'DELETE':
            if (!isset($request[1]) || !is_numeric($request[1])) {
                http_response_code(400);
                echo json_encode(["error" => "Invalid order ID"]);
                exit;
            }
            $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
            $stmt->execute([$request[1]]);
            if ($stmt->rowCount() > 0) {
                echo json_encode(["message" => "Order deleted"]);
            } else {
                http_response_code(404);
                echo json_encode(["error" => "Order not found"]);
            }
            break;
        default:
            http_response_code(405);
            echo json_encode(["error" => "Method not allowed"]);
            break;
    }
} else {
    http_response_code(404);
    echo json_encode(["error" => "Endpoint not found"]);
}
?>