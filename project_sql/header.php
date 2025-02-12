<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" contnt="ie=edge">
    <title>Secret Diary</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" 
          rel="stylesheet" 
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" 
          crossorigin="anonymous">

  
    <style type="text/css">
        html {
            background: url('diary.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100%;
        }

        body {
            background: none;
            height: 100%;
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
<nav class="navbar navbar-light bg-faded navbar-fixed-top">
    <a class="navbar-brand" href="#">Secret Diary</a>

    <div class="pull-xs-right">
        <a href="secret_diary.php?logout=1">
            <button class="btn btn-dark">Logout</button>
        </a>
    </div>
</nav>
<?php


if (array_key_exists("id", $_COOKIE)) {
    $_SESSION['id'] = $_COOKIE['id'];
}

if (array_key_exists("id", $_SESSION)) {
    
} else {
    header("Location: secret_diary.php");
}
?>
