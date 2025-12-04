<?php
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['judge_id'])) {
    header('Location: index.php');
    exit();
}

$is_admin = ($_SESSION['username'] ?? '') === 'admin';

$conn = getDBConnection();

$detailed_query = "SELECT s.*, 
    (SELECT AVG(total_score) FROM scores WHERE group_number = s.group_number) as avg_total
    FROM scores s 
    ORDER BY s.group_number, s.submitted_at DESC";
$detailed_result = $conn->query($detailed_query);

$log_query = "SELECT l.*, j.judge_name, j.username 
             FROM login_logs l 
             JOIN judges j ON l.judge_id = j.id 
             ORDER BY l.login_time DESC 
             LIMIT 50";
$log_result = $conn->query($log_query);
?>
<!DOCTYPE html>
<html lang="en">
<br><br><br><br><br>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Results</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="admin-panel">
            <div class="admin-header">
                <h1><?php echo $is_admin ? 'Administrator Panel' : 'Results'; ?></h1>
                <div>
                    <a href="judgecard.php" class="btn-secondary">Back to Scoring</a>
                    <a href="logout.php" class="btn-secondary">Logout</a>
                </div>
            </div>
            
            <h2>Detailed Scores</h2>
            <div class="results-table-container">
                <table class="results-table">
                    <thead>
                        <tr>
                            <th>Judge Name</th>
                            <th>Group Number</th>
                            <th>Group Members</th>
                            <th>Project Title</th>
                            <th>Articulate</th>
                            <th>Tools</th>
                            <th>Presentation</th>
                            <th>Team</th>
                            <th>Average Total</th>
                            <th>Judge Total</th>
                            <th>Comments</th>
                            <th>Submitted At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($detailed_result && $detailed_result->num_rows > 0): ?>
                            <?php while ($row = $detailed_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['judge_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['group_number']); ?></td>
                                    <td><?php echo htmlspecialchars($row['group_members']); ?></td>
                                    <td><?php echo htmlspecialchars($row['project_title']); ?></td>
                                    <td><?php echo $row['articulate_requirements']; ?></td>
                                    <td><?php echo $row['choose_tools_methods']; ?></td>
                                    <td><?php echo $row['oral_presentation']; ?></td>
                                    <td><?php echo $row['team_function']; ?></td>
                                    <td><strong><?php echo number_format($row['avg_total'], 2); ?></strong></td>
                                    <td><strong><?php echo $row['total_score']; ?></strong></td>
                                    <td><?php echo htmlspecialchars($row['comments']); ?></td>
                                    <td><?php echo date('Y-m-d H:i:s', strtotime($row['submitted_at'])); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="12" class="no-data">No detailed scores available.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if ($is_admin): ?>
            <h2>Login Logs</h2>
            <div class="results-table-container">
                <table class="results-table">
                    <thead>
                        <tr>
                            <th>Judge</th>
                            <th>Username</th>
                            <th>Login Time</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($log_result && $log_result->num_rows > 0): ?>
                            <?php while ($row = $log_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['judge_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td><?php echo date('Y-m-d H:i:s', strtotime($row['login_time'])); ?></td>
                                    <td><?php echo htmlspecialchars($row['ip_address']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="no-data">No login logs available.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php $conn->close(); ?>
</body>
</html>
