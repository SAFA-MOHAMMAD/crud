<?php
// Database connection settings
$host = 'localhost';
$user = 'root';
$password = 'd28m07y2024';
$dbname = 'crudsystem';

// Connect to the database
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]));
}

// Set headers for JSON response
header('Content-Type: application/json');

// Determine the action from the request
$action = $_GET['action'] ?? null;

switch ($action) {
    case 'getStudents':
        getStudents($conn);
        break;
    case 'getStudent':
        getStudent($conn);
        break;
    case 'updateStudent':
        updateStudent($conn);
        break;
    case 'createStudent':
        createStudent($conn);
        break;
    case 'signUpAdmin':
        signUpAdmin($conn);
        break;
    case 'adminLogin':
        adminLogin($conn);
        break;
    case 'deleteStudent':
        deleteStudent($conn);
        break;
    case 'loginStudent':
        loginStudent($conn);
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
}

// Function to retrieve all students
function getStudents($conn)
{
    $query = "SELECT * FROM students";
    $result = $conn->query($query);

    if ($result) {
        $students = $result->fetch_all(MYSQLI_ASSOC);//coveret it to an multi-demition array
        echo json_encode(['status' => 'success', 'data' => $students]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to retrieve students: ' . $conn->error]);
    }
}


function getStudent($conn)
{
    if (isset($_GET['id'])) {
        $id = $conn->real_escape_string($_GET['id']);//super gloabl array gets data from URL
        $query = "SELECT * FROM students WHERE id = $id";
        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
            $student = $result->fetch_assoc();
            echo json_encode(['status' => 'success', 'data' => $student]);//send the data to the browser
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Student not found']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid student ID']);
    }
}


// Function to update a student
function updateStudent($conn)
{
    $data = json_decode(file_get_contents('php://input'), true);//to read the data from the request body

    if (isset($data['id'], $data['name'], $data['email'], $data['department'], $data['password'])) {
        $id = $conn->real_escape_string($data['id']);
        $name = $conn->real_escape_string($data['name']);
        $email = $conn->real_escape_string($data['email']);
        $department = $conn->real_escape_string($data['department']);
        $password = $conn->real_escape_string($data['password']);

        $query = "UPDATE students SET name='$name', email='$email', department='$department', password='$password' WHERE id=$id";
        if ($conn->query($query)) {
            echo json_encode(['status' => 'success', 'message' => 'Student updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update student: ' . $conn->error]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input data']);
    }
}


// Function to create a new student
function createStudent($conn)
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['name'], $data['email'], $data['password'])) {
        $name = $conn->real_escape_string($data['name']);
        $email = $conn->real_escape_string($data['email']);
        $department = $conn->real_escape_string($data['department']);
        $password = $conn->real_escape_string($data['password']);

        $query = "INSERT INTO students (name, email, department , password) VALUES ('$name', '$email', '$department' , '$password')";
        if ($conn->query($query)) {
            echo json_encode(['status' => 'success', 'message' => 'Student created successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to create student: ' . $conn->error]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input data']);
    }
}

function signUpAdmin($conn) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['name'], $data['email'], $data['department'], $data['password'])) {
        $name = $conn->real_escape_string($data['name']);
        $email = $conn->real_escape_string($data['email']);
        $department = $conn->real_escape_string($data['department']);
        $password = $conn->real_escape_string($data['password']);

        // Check if the email already exists
        $checkQuery = "SELECT * FROM admins WHERE email = '$email'";
        $checkResult = $conn->query($checkQuery);

        if ($checkResult && $checkResult->num_rows > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Email already exists']);
        } else {
            // Hash the password before storing it
            //$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert new admin record into the admins table
            $insertQuery = "INSERT INTO admins (name, email, password) VALUES ('$name', '$email', '$password')";
            if ($conn->query($insertQuery)) {
                echo json_encode(['status' => 'success', 'message' => 'Admin created successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to create admin: ' . $conn->error]);
            }
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
    }
}
function adminLogin($conn) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['email'], $data['password'])) {
        $email = $conn->real_escape_string($data['email']);
        $password = $conn->real_escape_string($data['password']);

        // Check if the admin exists in the database
        $checkQuery = "SELECT * FROM admins WHERE email = '$email' AND password = '$password'";
        $checkResult = $conn->query($checkQuery);

        if ($checkResult && $checkResult->num_rows > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Login successful']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid email or password']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
    }
}


// Function to delete a student
function deleteStudent($conn)
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['id'])) {
        $id = $conn->real_escape_string($data['id']);//prevent attacks
        $query = "DELETE FROM students WHERE id=$id";
        if ($conn->query($query)) {
            echo json_encode(['status' => 'success', 'message' => 'Student deleted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete student: ' . $conn->error]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input data']);
    }
}
function loginStudent($conn) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['id'], $data['name'], $data['password'])) {
        $id = $conn->real_escape_string($data['id']);
        $name = $conn->real_escape_string($data['name']);
        $password = $conn->real_escape_string($data['password']); // Fixed typo from "passwordword" to "password"

        // Query to check if student exists with matching id, name, and password
        $query = "SELECT id, name, password FROM students WHERE id = '$id' AND name = '$name' AND password = '$password'";
        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
            $student = $result->fetch_assoc();
            echo json_encode(['status' => 'success', 'student' => $student]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid credentials']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Missing login data']);
    }
}


// Close the database connection
$conn->close();
?>
