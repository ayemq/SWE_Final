<?php
require_once 'config.php';

if (!isset($_SESSION['judge_id']) || $_SESSION['username'] === 'admin') {
    header('Location: index.php');
    exit();
}

$judge_name = $_SESSION['judge_name'] ?? 'Judge';
?>
<!DOCTYPE html>
<html lang="en">
<br><br><br>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Judge Scoring Card - Computer Science Project</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="judge-card">
            <h1>Judge Card</h1>
            
            <form id="scoringForm" method="POST" action="submit.php">
                <div class="form-section">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="group_members">Group Members:</label>
                            <input type="text" id="group_members" name="group_members" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="group_number">Group Number:</label>
                            <input type="text" id="group_number" name="group_number" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="project_title">Project Title:</label>
                        <input type="text" id="project_title" name="project_title" required>
                    </div>
                </div>
                
                <div class="scoring-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Criteria</th>
                                <th>Developing (0-10)</th>
                                <th>Accomplished (11-15)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Articulate requirements</td>
                                <td>
                                    <select name="articulate_requirements_dev" class="score-dropdown developing">
                                        <option value="">Select</option>
                                        <option value="0">0</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="articulate_requirements_acc" class="score-dropdown accomplished">
                                        <option value="">Select</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Choose appropriate tools and methods for each task</td>
                                <td>
                                    <select name="choose_tools_methods_dev" class="score-dropdown developing">
                                        <option value="">Select</option>
                                        <option value="0">0</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="choose_tools_methods_acc" class="score-dropdown accomplished">
                                        <option value="">Select</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Give clear and coherent oral presentation</td>
                                <td>
                                    <select name="oral_presentation_dev" class="score-dropdown developing">
                                        <option value="">Select</option>
                                        <option value="0">0</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="oral_presentation_acc" class="score-dropdown accomplished">
                                        <option value="">Select</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Functioned well as a team</td>
                                <td>
                                    <select name="team_function_dev" class="score-dropdown developing">
                                        <option value="">Select</option>
                                        <option value="0">0</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="team_function_acc" class="score-dropdown accomplished">
                                        <option value="">Select</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="total-row">
                                <td><strong>Total</strong></td>
                                <td colspan="2" id="totalScore"><strong>0</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="form-section">
                    <div class="form-group">
                        <label for="judge_name_field">Judge's name:</label>
                        <input type="text" id="judge_name_field" name="judge_name" value="<?php echo htmlspecialchars($judge_name); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="comments">Comments:</label>
                        <textarea id="comments" name="comments" rows="4" placeholder="Enter your comments here..."></textarea>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Submit</button>
                    <a href="logout.php" class="btn-secondary">Logout</a>
                    <br><br>
                    <a href="database.php" class="btn-primary" style="text-decoration: none;">Access Scores in Database</a>

                </div>
            </form>
        </div>
    </div>
    
    <script>
        const form = document.getElementById('scoringForm');
        const totalScoreElement = document.getElementById('totalScore');
        
        function calculateTotal() {
            const criteria = [
                {dev: 'articulate_requirements_dev', acc: 'articulate_requirements_acc'},
                {dev: 'choose_tools_methods_dev', acc: 'choose_tools_methods_acc'},
                {dev: 'oral_presentation_dev', acc: 'oral_presentation_acc'},
                {dev: 'team_function_dev', acc: 'team_function_acc'}
            ];
            let total = 0;
            
            criteria.forEach(criterion => {
                const devSelect = form.querySelector(`select[name="${criterion.dev}"]`);
                const accSelect = form.querySelector(`select[name="${criterion.acc}"]`);
                
                if (devSelect && devSelect.value) {
                    total += parseInt(devSelect.value);
                } else if (accSelect && accSelect.value) {
                    total += parseInt(accSelect.value);
                }
            });
            
            totalScoreElement.innerHTML = `<strong>${total}</strong>`;
        }
        
        function setupDropdowns() {
            const criteria = [
                {dev: 'articulate_requirements_dev', acc: 'articulate_requirements_acc'},
                {dev: 'choose_tools_methods_dev', acc: 'choose_tools_methods_acc'},
                {dev: 'oral_presentation_dev', acc: 'oral_presentation_acc'},
                {dev: 'team_function_dev', acc: 'team_function_acc'}
            ];
            
            criteria.forEach(criterion => {
                const devSelect = form.querySelector(`select[name="${criterion.dev}"]`);
                const accSelect = form.querySelector(`select[name="${criterion.acc}"]`);
                
                devSelect.addEventListener('change', function() {
                    if (this.value) {
                        accSelect.value = '';
                    }
                    calculateTotal();
                });
                
                accSelect.addEventListener('change', function() {
                    if (this.value) {
                        devSelect.value = '';
                    }
                    calculateTotal();
                });
            });
        }
        
        setupDropdowns();
    </script>
</body>
</html>
