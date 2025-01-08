<?php
session_start();
$page_title = "Profile";
require_once '../includes/_head.php';
require_once '../classes/database.class.php';
require_once '../classes/account.class.php';

// Initialize Account class
$accountObj = new Account();

// Get all users and store in $account variable
try {
    $db = new Database();
    $conn = $db->connect();
    
    $query = "SELECT 
                id,
                first_name,
                last_name,
                username,
                role,
                is_admin
              FROM account 
              ORDER BY id";
              
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $account = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    $account = false;
    $error = "Database error: " . $e->getMessage();
}
?>

<body id="dashboard">
    <div class="wrapper">
        <?php
        require_once '../includes/_topnav.php';
        require_once '../includes/_sidebar.php';
        ?>
        <div class="content-page">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">User Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="userTable">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Username</th>
                                                <th>Role</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($account) { // Now $account is properly defined
                                                foreach ($account as $user) {
                                                    echo "<tr>";
                                                    echo "<td>" . htmlspecialchars($user['id']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($user['first_name']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($user['last_name']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($user['username']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($user['role']) . "</td>";
                                                    echo "<td>" . ($user['is_admin'] ? 'Active' : 'Inactive') . "</td>"; // Assuming is_admin indicates status
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='6'>No users found</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once '../includes/_footer.php'; ?>
</body>
</html>
