<?php
// Enable CORS if needed
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// For preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Include database connection
include "conn.php";

// Get the HTTP method
$method = $_SERVER['REQUEST_METHOD'];

// Function to sanitize input
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Function to handle errors
function sendError($message, $code = 400) {
    http_response_code($code);
    echo json_encode(['status' => 'error', 'message' => $message]);
    exit;
}

// Function to send success response
function sendResponse($data, $code = 200) {
    http_response_code($code);
    echo json_encode(['status' => 'success', 'data' => $data]);
    exit;
}

// Handle different HTTP methods
switch($method) {
    case 'GET':
        // Fetch all notes
        try {
            $stmt = $conn->prepare("SELECT * FROM notes ORDER BY tstamp DESC");
            $stmt->execute();
            $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Format the response
            $formattedNotes = [];
            foreach ($notes as $note) {
                $formattedNotes[] = [
                    'id' => $note['id'],
                    'title' => htmlspecialchars_decode($note['title']),
                    'description' => htmlspecialchars_decode($note['description']),
                    'tstamp' => $note['tstamp']
                ];
            }
            
            sendResponse(['notes' => $formattedNotes]);
        } catch (PDOException $e) {
            sendError("Error fetching notes: " . $e->getMessage());
        }
        break;

    case 'POST':
        try {
            // Get raw input data
            $data = json_decode(file_get_contents("php://input"), true);
            
            if (!isset($data['title']) || !isset($data['description'])) {
                sendError("Title and description are required");
            }
            
            $title = sanitize($data['title']);
            $description = sanitize($data['description']);
            
            $stmt = $conn->prepare("INSERT INTO notes (title, description, tstamp) VALUES (?, ?, NOW())");
            if (!$stmt->execute([$title, $description])) {
                sendError("Failed to insert note");
            }
            
            // Get the inserted note
            $noteId = $conn->lastInsertId();
            $stmt = $conn->prepare("SELECT * FROM notes WHERE id = ?");
            $stmt->execute([$noteId]);
            $newNote = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$newNote) {
                sendError("Failed to retrieve inserted note");
            }
            
            sendResponse([
                'message' => 'Note added successfully',
                'note' => [
                    'id' => $newNote['id'],
                    'title' => htmlspecialchars_decode($newNote['title']),
                    'description' => htmlspecialchars_decode($newNote['description']),
                    'tstamp' => $newNote['tstamp']
                ]
            ]);
        } catch (PDOException $e) {
            sendError("Error adding note: " . $e->getMessage());
        }
        break;

    case 'PUT':
        try {
            if (!isset($_GET['id'])) {
                sendError("Note ID is required");
            }
            
            // Get raw input data
            $input = file_get_contents("php://input");
            $data = json_decode($input, true);
            
            if (!$data) {
                sendError("Invalid JSON data received: " . $input);
            }
            
            if (!isset($data['title']) || !isset($data['description'])) {
                sendError("Title and description are required");
            }
            
            $id = sanitize($_GET['id']);
            $title = sanitize($data['title']);
            $description = sanitize($data['description']);
            
            $stmt = $conn->prepare("UPDATE notes SET title = ?, description = ?, tstamp = NOW() WHERE id = ?");
            if (!$stmt->execute([$title, $description, $id])) {
                sendError("Failed to update note");
            }
            
            if ($stmt->rowCount() === 0) {
                sendError("Note not found", 404);
            }
            
            // Get the updated note
            $stmt = $conn->prepare("SELECT * FROM notes WHERE id = ?");
            $stmt->execute([$id]);
            $updatedNote = $stmt->fetch(PDO::FETCH_ASSOC);
            
            sendResponse([
                'message' => 'Note updated successfully',
                'note' => [
                    'id' => $updatedNote['id'],
                    'title' => htmlspecialchars_decode($updatedNote['title']),
                    'description' => htmlspecialchars_decode($updatedNote['description']),
                    'tstamp' => $updatedNote['tstamp']
                ]
            ]);
        } catch (PDOException $e) {
            sendError("Error updating note: " . $e->getMessage());
        }
        break;

    case 'DELETE':
        try {
            if (!isset($_GET['id'])) {
                sendError("Note ID is required");
            }
            
            $id = sanitize($_GET['id']);
            
            $stmt = $conn->prepare("DELETE FROM notes WHERE id = ?");
            $stmt->execute([$id]);
            
            if ($stmt->rowCount() === 0) {
                sendError("Note not found", 404);
            }
            
            sendResponse(['message' => 'Note deleted successfully', 'id' => $id]);
        } catch (PDOException $e) {
            sendError("Error deleting note: " . $e->getMessage());
        }
        break;

    default:
        sendError("Method not allowed", 405);
        break;
}
?>