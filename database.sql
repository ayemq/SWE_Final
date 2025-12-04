-- Create database
CREATE DATABASE IF NOT EXISTS judging_system;
USE judging_system;

-- Table for storing judge login credentials
CREATE TABLE IF NOT EXISTS judges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    judge_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for storing login sessions
CREATE TABLE IF NOT EXISTS login_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judge_id INT NOT NULL,
    login_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    FOREIGN KEY (judge_id) REFERENCES judges(id)
);

-- Table for storing scores
CREATE TABLE IF NOT EXISTS scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judge_id INT NOT NULL,
    judge_name VARCHAR(100) NOT NULL,
    group_members TEXT,
    group_number VARCHAR(50),
    project_title TEXT,
    articulate_requirements INT NOT NULL,
    choose_tools_methods INT NOT NULL,
    oral_presentation INT NOT NULL,
    team_function INT NOT NULL,
    total_score INT NOT NULL,
    comments TEXT,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (judge_id) REFERENCES judges(id)
);

-- Insert default judge credentials (password is 'judge123' for all)
INSERT INTO judges (username, password, judge_name) VALUES
('judge1', 'judge123', 'Judge 1'),
('judge2', 'judge123', 'Judge 2'),
('judge3', 'judge123', 'Judge 3'),
('judge4', 'judge123', 'Judge 4')
ON DUPLICATE KEY UPDATE username=username;

-- Create admin user (username: admin, password: admin123)
INSERT INTO judges (username, password, judge_name) VALUES
('admin', 'admin123', 'Administrator')
ON DUPLICATE KEY UPDATE username=username;
