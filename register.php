<?php
// Turn off error display in production, log errors instead
error_reporting(E_ALL);
ini_set('display_errors', 0); // Disable error display in production
ini_set('log_errors', 1);     // Enable error logging
ini_set('error_log', '/path/to/error_log'); // Specify error log path

$servername = "localhost";
$username = "root";
$password = "Tanay@123";
$dbname = "ers";

// Create connection with error handling
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    die("Connection failed. Please try again later.");
}

// CSRF token generation and validation
session_start();
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$team_registered = false;
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token.");
    }

    // Sanitize user inputs
    $team_name = htmlspecialchars($_POST['teamName']);
    $team_leader_name = htmlspecialchars($_POST['teamLeaderName']);
    $team_leader_roll = htmlspecialchars($_POST['teamLeaderRoll']);
    $team_leader_phone = htmlspecialchars($_POST['teamLeaderPhone']);
    $member1_name = isset($_POST['member1Name']) ? htmlspecialchars($_POST['member1Name']) : null;
    $member1_roll = isset($_POST['member1Roll']) ? htmlspecialchars($_POST['member1Roll']) : null;
    $member2_name = isset($_POST['member2Name']) ? htmlspecialchars($_POST['member2Name']) : null;
    $member2_roll = isset($_POST['member2Roll']) ? htmlspecialchars($_POST['member2Roll']) : null;
    $member3_name = isset($_POST['member3Name']) ? htmlspecialchars($_POST['member3Name']) : null;
    $member3_roll = isset($_POST['member3Roll']) ? htmlspecialchars($_POST['member3Roll']) : null;
    $member4_name = isset($_POST['member4Name']) ? htmlspecialchars($_POST['member4Name']) : null;
    $member4_roll = isset($_POST['member4Roll']) ? htmlspecialchars($_POST['member4Roll']) : null;

    // Input validation
    if (empty($team_name) || empty($team_leader_name) || empty($team_leader_roll) || empty($team_leader_phone)) {
        $error_message = "Please fill all required fields.";
    } else {
        // Prepare statements to check if team name or leader roll already exists
        $stmt_check_team = $conn->prepare("SELECT * FROM roborushreg WHERE team_name = ?");
        $stmt_check_team->bind_param("s", $team_name);
        $stmt_check_team->execute();
        $result_check_team = $stmt_check_team->get_result();

        $stmt_check_leader = $conn->prepare("SELECT * FROM roborushreg WHERE leader_roll = ?");
        $stmt_check_leader->bind_param("s", $team_leader_roll);
        $stmt_check_leader->execute();
        $result_check_leader = $stmt_check_leader->get_result();

        // Check if team name or leader roll is already registered
        if ($result_check_team->num_rows > 0) {
            $error_message = "Team name '$team_name' is already registered!";
        } elseif ($result_check_leader->num_rows > 0) {
            $error_message = "Team leader with roll number '$team_leader_roll' has already registered!";
        } else {
            // Insert new team registration using prepared statements
            $stmt_insert = $conn->prepare("INSERT INTO roborushreg (
                team_name, leader_name, leader_roll, leader_phone,
                member1_name, member1_roll, member2_name, member2_roll,
                member3_name, member3_roll, member4_name, member4_roll
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            $stmt_insert->bind_param("ssssssssssss", $team_name, $team_leader_name, $team_leader_roll, $team_leader_phone,
                $member1_name, $member1_roll, $member2_name, $member2_roll,
                $member3_name, $member3_roll, $member4_name, $member4_roll);

            if ($stmt_insert->execute()) {
                $team_registered = true;
            } else {
                $error_message = "Error executing query: " . $stmt_insert->error;
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoboRush 3.0 Registration</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <div class="header1">
        <div class="Header">
            <a href="index.html" class="logolink">
                <img src="logo.jpg.jpg" id="logo">
            </a>
            <div class="menu-container">
                <div class="hamburger" onclick="toggleMenu()">
                    <div class="line"></div>
                    <div class="line"></div>
                    <div class="line"></div>
                </div>
                <div id="sideMenu" class="side-menu">
                    <br><br>
                    <a href="#">Team</a>
                    <a href="project.html">Projects</a>
                    <a href="competitions.html" style="text-decoration:none">Competitions</a>
                    <a href="#">Sessions</a>
                    <a href="gallery.html">Gallery</a>
                    <a href="#">Alumni</a>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <?php if ($team_registered): ?>
            <center><div class="success-message">
                Team registered successfully! <br>
                <a href="competitions.html" class="go-back-link">Go back to Competitions</a>
            </div>
            </center>
        <?php else: ?>
            <h1 class="registertitle">ROBORUSH 3.0 Registration</h1>
            
            <?php if (!empty($error_message)) { ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php } ?>

            <form class="registration-form" action="register.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <label for="teamName">Team Name</label>
                <input type="text" id="teamName" name="teamName" placeholder="Enter Team Name" required>

                <label for="teamLeaderName">Team Leader's Name</label>
                <input type="text" id="teamLeaderName" name="teamLeaderName" placeholder="Enter Team Leader's Name" required>

                <label for="teamLeaderRoll">Team Leader's Roll Number</label>
                <input type="text" id="teamLeaderRoll" name="teamLeaderRoll" placeholder="Enter Roll Number" required>

                <label for="teamLeaderPhone">Team Leader's Mobile Number</label>
                <input type="tel" id="teamLeaderPhone" name="teamLeaderPhone" placeholder="Enter Mobile Number" required pattern="^[0-9]{10}$" title="Mobile number must be exactly 10 digits long" required>

                <h2>Team Members</h2>

                <label for="member1Name">Team Member 1 Name</label>
                <input type="text" id="member1Name" name="member1Name" placeholder="Enter Name">

                <label for="member1Roll">Team Member 1 Roll Number</label>
                <input type="text" id="member1Roll" name="member1Roll" placeholder="Enter Roll Number">

                <label for="member2Name">Team Member 2 Name</label>
                <input type="text" id="member2Name" name="member2Name" placeholder="Enter Name">

                <label for="member2Roll">Team Member 2 Roll Number</label>
                <input type="text" id="member2Roll" name="member2Roll" placeholder="Enter Roll Number">

                <label for="member3Name">Team Member 3 Name</label>
                <input type="text" id="member3Name" name="member3Name" placeholder="Enter Name">

                <label for="member3Roll">Team Member 3 Roll Number</label>
                <input type="text" id="member3Roll" name="member3Roll" placeholder="Enter Roll Number">

                <label for="member4Name">Team Member 4 Name</label>
                <input type="text" id="member4Name" name="member4Name" placeholder="Enter Name">

                <label for="member4Roll">Team Member 4 Roll Number</label>
                <input type="text" id="member4Roll" name="member4Roll" placeholder="Enter Roll Number">

                <input class="registerbut" type="submit" value="Submit">
            </form>
        <?php endif; ?>
    </div>    
</body>
</html>
