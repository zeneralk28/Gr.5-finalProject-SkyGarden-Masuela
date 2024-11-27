<?php 

session_start();

include 'connect.php'; //include our database

//used post method and check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //contents of the form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
}

//check if the field is not empty
if (empty($username) || empty($email) || empty($password)) {
    echo "All fields are required";
    exit;
}

//check if email or username already exists in database
$sql = "SELECT * FROM users WHERE username = ? OR email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0){
    //username or email alrd exist
    echo "Username or email is already taken";
} else {
    //hashed the password first before storing to be more secure
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    //insert values into database
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        //created acc successfully
        echo "Created successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}

$stmt->close();
$conn->close();

?>
