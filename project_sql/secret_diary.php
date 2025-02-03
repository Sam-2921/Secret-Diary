<?php
session_start();
$error = "";

// Database connection setup
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

if (isset($_GET['logout'])) {
    session_unset();
    setcookie("id", "", time() - 60 * 60);
    $_COOKIE["id"] = "";
    header("Location: secret_diary.php");
    exit();
}

if (array_key_exists("id", $_SESSION) || array_key_exists("id", $_COOKIE)) {
    header("Location: loggedin.php");
    exit();
}

if (array_key_exists("submit", $_POST)) {
    if (!$_POST['email']) {
        $error .= "An email address is required.<br>";
    }

    if (!$_POST['password']) {
        $error .= "A password is required.<br>";
    }

    if ($error == "") {
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

                    header("Location: loggedin.php");
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

                    header("Location: loggedin.php");
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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secret Diary</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <style>
        html {
            background: url('diary.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100%;
        }

        body {
            background: none;
            height: 100%;
            align-items: center;
            justify-content: center;
        }

        .container {
            text-align: center;
            width: 480px;
        }

        #logInForm {
            display: none;
        }

        .toggleForms {
            font-weight: bold;
            color: white;
        }

        #diary {
            width: 100%;
            height: 100%;
        }

        #homePageContainer {
            margin-top: 200px;
        }

        .container-fluid {
            height: 100%;
        }
    </style>
</head>
<body>
    <div class="container" id="homePageContainer">
        <h1>Secret Diary</h1>

        <p>Store your thoughts permanently and securely</p>
        <div id="error">
            <?php if ($error != "") : ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
        </div>

 
        <form method="post" id="signUpForm">
            <p>Interested? Sign up now!</p>
            <fieldset class="form-group">
                <input type="email" name="email" class="form-control" placeholder="Your email" required><br>
            </fieldset>

            <fieldset class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </fieldset>

            <fieldset class="checkbox">
                Stay Logged In:
                <input type="checkbox" name="stayLoggedIn" value="1">
            </fieldset>

            <input type="hidden" name="signUp" value="1">
            <input type="submit" name="submit" class="btn btn-success" value="Sign Up!">
            <p><a class="toggleForms">Log In </a></p>
        </form>


        <form method="post" id="logInForm">
            <p>Log in using your username and password</p>
            <fieldset class="form-group">
                <input type="email" name="email" class="form-control" placeholder="Your email" required><br>
            </fieldset>

            <fieldset class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </fieldset>

            <fieldset class="checkbox">
                Stay Logged In:
                <input type="checkbox" name="stayLoggedIn" value="1">
            </fieldset>

            <input type="hidden" name="signUp" value="0">
            <input type="submit" name="submit" class="btn btn-success" value="Log In">
            <p><a class="toggleForms">Sign up</a></p>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(".toggleForms").click(function () {
            $("#signUpForm").toggle();
            $("#logInForm").toggle();
        });
    </script>
</body>
</html>
