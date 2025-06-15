<?php
session_start();
include "../user/connection.php";

// Check if admin is logged in
if (!isset($_SESSION['admin_username'])) {
    header("Location: login.php");
    exit();
}

// Handle status updates
if (isset($_POST['update_status']) && isset($_POST['message_id']) && isset($_POST['status'])) {
    $message_id = mysqli_real_escape_string($conn, $_POST['message_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $update_query = "UPDATE contact_messages SET status = '$status' WHERE id = '$message_id'";
    mysqli_query($conn, $update_query);
    
    header("Location: contact_messages.php");
    exit();
}

// Handle message deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $message_id = mysqli_real_escape_string($conn, $_GET['delete']);
    
    $delete_query = "DELETE FROM contact_messages WHERE id = '$message_id'";
    mysqli_query($conn, $delete_query);
    
    header("Location: contact_messages.php");
    exit();
}

// Get all contact messages
$query = "SELECT * FROM contact_messages ORDER BY submission_date DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages - Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
            color: white;
        }
        .sidebar a {
            color: rgba(255, 255, 255, 0.8);
        }
        .sidebar a:hover {
            color: white;
            text-decoration: none;
        }
        .status-new {
            background-color: #ffc107;
            color: #000;
        }
        .status-read {
            background-color: #17a2b8;
            color: #fff;
        }
        .status-replied {
            background-color: #28a745;
            color: #fff;
        }
        .message-preview {
            max-height: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-3">
                <h4 class="text-center mb-4">Admin Panel</h4>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a href="Dashboard.php"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="customers.php"><i class="fas fa-users mr-2"></i> Customers</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="transactions.php"><i class="fas fa-exchange-alt mr-2"></i> Transactions</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="cards.php"><i class="fas fa-credit-card mr-2"></i> Cards</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="contact_messages.php" class="active"><i class="fas fa-envelope mr-2"></i> Contact Messages</a>
                    </li>
                    <li class="nav-item mt-5">
                        <a href="logout.php"><i class="fas fa-sign-out-alt mr-2"></i> Logout</a>
                    </li>
                </ul>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <h2 class="mb-4">Contact Form Messages</h2>
                
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Subject</th>
                                    <th>Message</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo date('M d, Y H:i', strtotime($row['submission_date'])); ?></td>
                                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                                        <td>
                                            <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>">
                                                <?php echo htmlspecialchars($row['email']); ?>
                                            </a>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['subject']); ?></td>
                                        <td>
                                            <div class="message-preview">
                                                <?php echo nl2br(htmlspecialchars($row['message'])); ?>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-info mt-2" data-toggle="modal" data-target="#messageModal<?php echo $row['id']; ?>">
                                                View Full Message
                                            </button>
                                        </td>
                                        <td>
                                            <span class="badge status-<?php echo $row['status']; ?>">
                                                <?php echo ucfirst($row['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <form method="post" class="d-inline">
                                                <input type="hidden" name="message_id" value="<?php echo $row['id']; ?>">
                                                <select name="status" class="form-control form-control-sm d-inline-block w-auto mr-2" onchange="this.form.submit()">
                                                    <option value="new" <?php echo $row['status'] == 'new' ? 'selected' : ''; ?>>New</option>
                                                    <option value="read" <?php echo $row['status'] == 'read' ? 'selected' : ''; ?>>Read</option>
                                                    <option value="replied" <?php echo $row['status'] == 'replied' ? 'selected' : ''; ?>>Replied</option>
                                                </select>
                                                <input type="hidden" name="update_status" value="1">
                                            </form>
                                            <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this message?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    
                                    <!-- Message Modal -->
                                    <div class="modal fade" id="messageModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="messageModalLabel">Message from <?php echo htmlspecialchars($row['name']); ?></h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>From:</strong> <?php echo htmlspecialchars($row['name']); ?> (<?php echo htmlspecialchars($row['email']); ?>)</p>
                                                    <p><strong>Subject:</strong> <?php echo htmlspecialchars($row['subject']); ?></p>
                                                    <p><strong>Date:</strong> <?php echo date('M d, Y H:i', strtotime($row['submission_date'])); ?></p>
                                                    <hr>
                                                    <div class="message-content">
                                                        <?php echo nl2br(htmlspecialchars($row['message'])); ?>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>?subject=Re: <?php echo htmlspecialchars($row['subject']); ?>" class="btn btn-primary">
                                                        <i class="fas fa-reply mr-1"></i> Reply
                                                    </a>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        No contact messages found.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html> 