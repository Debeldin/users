<?php
/**
 * User API
 */
/**
 * simple autoloader for the project
 * This autoloader will load classes from the project directory structure
 */
spl_autoload_register(function ($class) {
    // Base directory for the project
    $basedir = __DIR__ . '/..';
    // Replace namespace separators with directory separators
    $file = $basedir . '/' . str_replace('\\', '/', $class) . '.php';
    // If the file exists, require it
    if (file_exists($file)) {
        require_once $file;
    }
});

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

$method = $_SERVER['REQUEST_METHOD'];


if ($method === 'OPTIONS') {
    exit;
}

try {
    $db = new \database\Database();
    $pdo = $db->getPdo();
    $user = new \users\User($pdo);
    // Get the JSON input for POST/PUT requests
    $input = json_decode(file_get_contents('php://input'), true);

    switch ($method) {
        case 'GET':
            $result = "";
            if (isset($_GET['search'])) {
                $searchterm = $_GET['search'];
                $result = $user->searchUser($searchterm);
            }
            echo json_encode($result);
            break;

        case 'POST':
            if ($input) {
                $firstname = $input['first_name'] ?? '';
                $lastname = $input['last_name'] ?? '';
                $email = $input['email'] ?? '';
                $birthdate = $input['birthdate'] ?? '';
                $user->addUser($firstname, $lastname, $email, $birthdate);
                echo json_encode(["message" => "User added successfully"]);
            } else {
                http_response_code(400);
                echo json_encode(["message" => "Invalid input for POST request"]);
            }
            break;

        case 'PUT':
            if (isset($_GET['id']) && $input) {
                $id = (int)$_GET['id'];
                $firstname = $input['first_name'] ?? '';
                $lastname = $input['last_name'] ?? '';
                $email = $input['email'] ?? '';
                $birthdate = $input['birthdate'] ?? '';
                $user->updateUser($id, $firstname, $lastname, $email, $birthdate);
                echo json_encode(["message" => "User updated successfully"]);
            } else {
                http_response_code(400);
                echo json_encode(["message" => "Invalid input for PUT request"]);
            }
            break;

        case 'DELETE':
            if (isset($_GET['id'])) {
                $id = (int)$_GET['id'];
                $user->deleteUser($id);
                echo json_encode(["message" => "User deleted successfully"]);
            } else {
                http_response_code(400);
                echo json_encode(["message" => "Invalid input for DELETE request"]);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(["message" => "Invalid request method"]);
            break;
    }
} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "An unexpected error occurred: " . $e->getMessage()]);
}
