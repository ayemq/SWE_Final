<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config.php';

if (!isset($_SESSION['judge_id']) || $_SESSION['username'] === 'admin') {
    header('Location: index.php');
    exit();
}

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judge_id = $_SESSION['judge_id'];
    $judge_name = trim($_POST['judge_name'] ?? '');
    $group_members = trim($_POST['group_members'] ?? '');
    $group_number = trim($_POST['group_number'] ?? '');
    $project_title = trim($_POST['project_title'] ?? '');
    $comments = trim($_POST['comments'] ?? '');
    
    $articulate_requirements = 0;
    if (!empty($_POST['articulate_requirements_dev'])) {
        $articulate_requirements = intval($_POST['articulate_requirements_dev']);
    } elseif (!empty($_POST['articulate_requirements_acc'])) {
        $articulate_requirements = intval($_POST['articulate_requirements_acc']);
    }
    
    $choose_tools_methods = 0;
    if (!empty($_POST['choose_tools_methods_dev'])) {
        $choose_tools_methods = intval($_POST['choose_tools_methods_dev']);
    } elseif (!empty($_POST['choose_tools_methods_acc'])) {
        $choose_tools_methods = intval($_POST['choose_tools_methods_acc']);
    }
    
    $oral_presentation = 0;
    if (!empty($_POST['oral_presentation_dev'])) {
        $oral_presentation = intval($_POST['oral_presentation_dev']);
    } elseif (!empty($_POST['oral_presentation_acc'])) {
        $oral_presentation = intval($_POST['oral_presentation_acc']);
    }
    
    $team_function = 0;
    if (!empty($_POST['team_function_dev'])) {
        $team_function = intval($_POST['team_function_dev']);
    } elseif (!empty($_POST['team_function_acc'])) {
        $team_function = intval($_POST['team_function_acc']);
    }
    
    $has_articulate = !empty($_POST['articulate_requirements_dev']) || !empty($_POST['articulate_requirements_acc']);
    $has_tools = !empty($_POST['choose_tools_methods_dev']) || !empty($_POST['choose_tools_methods_acc']);
    $has_presentation = !empty($_POST['oral_presentation_dev']) || !empty($_POST['oral_presentation_acc']);
    $has_team = !empty($_POST['team_function_dev']) || !empty($_POST['team_function_acc']);
    
    if (empty($group_members) || empty($group_number) || empty($project_title) || empty($judge_name) ||
        !$has_articulate || !$has_tools || !$has_presentation || !$has_team) {
        $error = 'Please fill in all required fields and select a score for each criterion.';
    } else {
        $total_score = $articulate_requirements + $choose_tools_methods + 
                      $oral_presentation + $team_function;
        
        $scores = [
            $articulate_requirements,
            $choose_tools_methods,
            $oral_presentation,
            $team_function
        ];
        
        $valid = true;
        foreach ($scores as $score) {
            if ($score < 0 || $score > 15) {
                $valid = false;
                break;
            }
        }
        
        if ($valid) {
            $conn = getDBConnection();
            
            $check_stmt = $conn->prepare("SELECT id FROM scores WHERE judge_id = ? AND group_number = ?");
            $check_stmt->bind_param("is", $judge_id, $group_number);
            $check_stmt->execute();
            $existing = $check_stmt->get_result();
            
            if ($existing->num_rows > 0) {
                $update_stmt = $conn->prepare("UPDATE scores SET 
                    judge_name = ?, group_members = ?, project_title = ?,
                    articulate_requirements = ?, choose_tools_methods = ?,
                    oral_presentation = ?, team_function = ?, total_score = ?,
                    comments = ?, submitted_at = CURRENT_TIMESTAMP
                    WHERE judge_id = ? AND group_number = ?");
                $update_stmt->bind_param("sssiiiiiissi", 
                    $judge_name, $group_members, $project_title,
                    $articulate_requirements, $choose_tools_methods,
                    $oral_presentation, $team_function, $total_score,
                    $comments, $judge_id, $group_number);
                
                if ($update_stmt->execute()) {
                    $success = true;
                } else {
                    $error = 'Error updating score: ' . $conn->error;
                }
                $update_stmt->close();
            } else {
                $insert_stmt = $conn->prepare("INSERT INTO scores 
                    (judge_id, judge_name, group_members, group_number, project_title,
                     articulate_requirements, choose_tools_methods, oral_presentation,
                     team_function, total_score, comments) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $insert_stmt->bind_param("issssiiiiis", 
                    $judge_id, $judge_name, $group_members, $group_number, $project_title,
                    $articulate_requirements, $choose_tools_methods,
                    $oral_presentation, $team_function, $total_score, $comments);
                
                if ($insert_stmt->execute()) {
                    $success = true;
                } else {
                    $error = 'Error saving score: ' . $conn->error;
                }
                $insert_stmt->close();
            }
            
            $check_stmt->close();
            $conn->close();
        } else {
            $error = 'Invalid score values. Please select valid scores.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Result</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="result-card">
            <?php if ($success): ?>
                <div class="success-message">
                    <h2>✓ Score Submitted Successfully!</h2>
                    <p>Your evaluation has been recorded in the database.</p>
                    <div class="result-actions">
                        <a href="judgecard.php" class="btn-primary">Submit Another Evaluation</a>
                        <a href="logout.php" class="btn-secondary">Logout</a>
                        <br><br>
                        <a href="database.php" class="btn-primary" style="text-decoration: none;">Access Database</a>

                    </div>
                </div>
            <?php else: ?>
                <div class="error-message">
                    <h2>✗ Submission Failed</h2>
                    <p><?php echo htmlspecialchars($error); ?></p>
                    <div class="result-actions">
                        <a href="judgecard.php" class="btn-primary">Go Back</a>
                        <a href="logout.php" class="btn-secondary">Logout</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
