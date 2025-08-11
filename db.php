<? 
$servername = "localhost";
$username = "root";
$password = "#almcore2007#";
$database = "ikoopreg";

// Check connection
$conn = new mysqli($servername, $username, $password,$database);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
?>