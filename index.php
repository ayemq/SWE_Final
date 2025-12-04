<?php
require_once 'config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT id, username, password, judge_name FROM judges WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $judge = $result->fetch_assoc();
        
        if ($judge['password'] === $password) {
            $_SESSION['judge_id'] = $judge['id'];
            $_SESSION['judge_name'] = $judge['judge_name'];
            $_SESSION['username'] = $judge['username'];
            
            $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $log_stmt = $conn->prepare("INSERT INTO login_logs (judge_id, ip_address) VALUES (?, ?)");
            $log_stmt->bind_param("is", $judge['id'], $ip_address);
            $log_stmt->execute();
            
            if ($username === 'admin') {
                header('Location: database.php');
            } else {
                header('Location: judgecard.php');
            }
            exit();
        } else {
            $error = 'Invalid username or password';
        }
    } else {
        $error = 'Invalid username or password';
    }
    
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Judge Login - Computer Science Project</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="login-card">
            <h1>Judge Login</h1>
            <h2>Project Evaluation</h2>
            
            <?php if ($error): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required autofocus>
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn-primary">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
