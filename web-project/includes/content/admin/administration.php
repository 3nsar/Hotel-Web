<?php 
    // Überprüfen ob Sitzung schon gestartet hat
    if (empty($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: /web-project/index.php"); // Zurück zu index.php, wenn nicht eingeloggt
        exit();
    }

    // Very that user is an admin
    if ($_SESSION['is_admin'] !== 1) {
        echo "Not authorized.";
        var_dump($_SESSION['is_admin']); // Outputs the value and type
        exit();
    }

    // Abrufen der Benutzerdaten aus der Datenbank
    $query = "SELECT user_id, gender, firstName, lastName, username, email, active, created_at FROM users";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container my-5 min-vh-100">
    <h2>All Clients</h2>
    <table class="table">
       <thead>
        <tr>
            <th>ID</th>
            <th>Gender</th>
            <th>FirstName</th>
            <th>LastName</th>
            <th>Username</th>
            <th>Email</th>
            <th>Created At</th>
            <th>Action</th>
            <th>Active</th>
        </tr>
       </thead>
       <tbody>
        <?php 
        foreach ($result as $row) {
            // Umgekehrte Logik: 0 = "Active", 1 = "Inactive"
            $btnClass = $row['active'] == 1 ? 'btn-danger' : 'btn-success';
            $statusText = $row['active'] == 1 ? 'Inactive' : 'Active';
            
            echo "<tr>
                <td>$row[user_id]</td>
                <td>$row[gender]</td>
                <td>$row[firstName]</td>
                <td>$row[lastName]</td>
                <td>$row[username]</td>
                <td>$row[email]</td>
                <td>$row[created_at]</td>
                <td>
                    <a class='btn btn-primary btn-sm' href='/web-project/includes/content/admin/edit.php?user_id=$row[user_id]'>Edit</a>
                    <a class='btn btn-danger btn-sm' href='/web-project/includes/content/admin/delete.php?user_id=$row[user_id]'>Delete</a>
                </td>
                <td>
                    <a href='/web-project/includes/content/admin/toggle_active.php?user_id={$row['user_id']}&current_status={$row['active']}' 
                       class='btn btn-sm {$btnClass}'>
                        {$statusText}
                    </a>
                </td>
            </tr>";
        }
        ?>
       </tbody>
    </table>
</div>