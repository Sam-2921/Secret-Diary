<?php
session_start();
$error = "";

$servername = "localhost";
$username = "samson";
$password = "samson";
$database = "secretdiary";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (array_key_exists("logout", $_GET)) {
    session_unset();
    setcookie("id", "", time() - 60 * 60);
    $_COOKIE["id"] = "";
} elseif (array_key_exists("id", $_SESSION) || array_key_exists("id", $_COOKIE)) {
 
    header("Location: login_page.php");
    exit();
}

if (array_key_exists("submit", $_POST)) {
    if (!$_POST['email']) {
        $error .= "An email address is required.<br>";
    }

    if (!$_POST['password']) {
        $error .= "A password is required.<br>";
    }

    if ($error != "") {
        $error = "<p>There were error(s) in your form!</p>" . $error;
    } else {
        $emailAddress = $conn->real_escape_string($_POST['email']);
        $password = $conn->real_escape_string($_POST['password']);
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        if ($_POST['signUp'] == '1') {
            $query = "SELECT id FROM users WHERE email = '$emailAddress' LIMIT 1";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                $error = "That email address is taken.";
            } else {
                $query = "INSERT INTO users (email, password) VALUES ('$emailAddress', '$passwordHash')";
                if ($conn->query($query)) {
                    $id = $conn->insert_id;
                    $_SESSION['id'] = $id;

                    if (isset($_POST['stayLoggedIn'])) {
                        setcookie("id", $id, time() + 60 * 60 * 24 * 365);
                    }

                    header("Location: login_page.php");
                    exit();
                } else {
                    $error .= "<p>Could not sign you up - Please try again later.</p>";
                    $error .= "<p>" . $conn->error . "</p>";
                }
            }
        } else {
            $query = "SELECT * FROM users WHERE email = '$emailAddress'";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if (password_verify($password, $row['password'])) {
                    $_SESSION['id'] = $row['id'];

                    if (isset($_POST['stayLoggedIn'])) {
                        setcookie("id", $row['id'], time() + 60 * 60 * 24 * 365);
                    }

                    header("Location: login_page.php");
                    exit();
                } else {
                    $error = "That email/password combination could not be found.";
                }
            } else {
                $error = "That email/password combination could not be found.";
            }
        }
    }
}

$conn->close();
?>

<div id="error"><?php echo $error; ?></div>

<form method="post">
    <input type="email" name="email" placeholder="Your email" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="checkbox" name="stayLoggedIn" value="1">
    <input type="hidden" name="signUp" value="1">
    <input type="submit" name="submit" value="Sign Up!">
</form>

<form method="post">
    <input type="email" name="email" placeholder="Your email" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="checkbox" name="stayLoggedIn" value="1">
    <input type="hidden" name="signUp" value="0">
    <input type="submit" name="submit" value="Log In">
</form>
