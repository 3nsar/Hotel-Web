<?php 
require_once 'config/config_session.inc.php';
require_once 'config/dbaccess.php';

// Überprüfen, ob Sitzung schon gestartet ist
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php"); // Zurück zu index.php, wenn nicht eingeloggt
    exit();
}

// Create connection
$connection = new mysqli($host, $dbusername, $dbpassword, $dbname);

// Überprüfen auf Verbindungsfehler
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Benutzerdaten des derzeit eingeloggenen Benutzers abrufen
$user_id = $_SESSION['user_id']; // Angenommen, die User-ID wird in der Sitzung gespeichert
$query = "SELECT user_id, gender, firstName, lastName, username, email, created_at FROM users WHERE user_id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

// Buchungen des aktuell eingeloggten Benutzers abrufen
// $bookingsQuery = "SELECT booking_id, start_date, end_date, fk_user_id FROM rooms WHERE fk_user_id = ?";
// $bookingsStmt = $connection->prepare($bookingsQuery);
// $bookingsStmt->bind_param("i", $user_id);
// $bookingsStmt->execute();
// $bookingsResult = $bookingsStmt->get_result();
// ?>

<div class="container my-5 vh-100">
    <h2>Mein Profil</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Gender</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Created At</th>
                <th>Action</th> <!-- Neue Spalte für Aktionen -->
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo htmlspecialchars($result['gender']); ?></td>
                <td><?php echo htmlspecialchars($result['firstName']); ?></td>
                <td><?php echo htmlspecialchars($result['lastName']); ?></td>
                <td><?php echo htmlspecialchars($result['username']); ?></td>
                <td><?php echo htmlspecialchars($result['email']); ?></td>
                <td><?php echo htmlspecialchars($result['created_at']); ?></td>
                <td>
                    <a href="/web-project/includes/content/editUser.php?user_id=<?php echo $result['user_id']; ?>" class="btn btn-primary">Edit</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>


<?php 
// Schließen der Verbindungen
$stmt->close();
//$bookingsStmt->close();
$connection->close(); 
?>
