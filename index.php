<?php
session_start();
if ($_GET['user']){
    if ($_SESSION['email'] !== $_GET['user']){
        session_destroy();
    }
    echo "<script>window.location.href = '.';</script>";
}else{
    if ($_SESSION["id"]){
        echo "<script>window.location.href = 'dashboard.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign In</title>
    <!-- Font-->
    <link rel="stylesheet" href="style/font.min.css">
    <!-- Font Icon -->
    <link rel="stylesheet" href="fonts/material-icon/css/material-design-iconic-font.min.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="style/bootstrap.css">
    <link rel="stylesheet" href="style/bootstrap.min.css">
    <!-- Main css -->
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-primary">
    <a style="font-size: 15px;color: white">Sign In</a>
</nav>
<div class="container">
    <br>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <header class="card-header">
                    <h4 class="card-title mt-2">Sign In</h4>
                </header>
                <article class="card-body">
                    <form method="post" action="main.php" onsubmit=handleSubmit(this) autocomplete="off">
                        <div class="form-group" data-col="email">
                            <label>Email address</label>
                            <input data-type="email" class="form-control"  type="text">
                            <span style="display:none;"></span>
                        </div> <!-- form-group end.// -->
                        <div class="form-group" data-col="password">
                            <label>Password</label>
                            <input class="form-control" data-type="password" type="password">
                            <span style="display:none;"></span>
                        </div> <!-- form-group end.// -->
                        <div class="form-group">
                            <button type="submit"  class="btn btn-primary btn-block"> Sign In </button>
                        </div> <!-- form-group// -->
                        <small class="text-muted">By clicking the 'Sign In' button, you confirm that you accept our <br> Terms of use and Privacy Policy.</small>
                    </form>
                </article> <!-- card-body end .// -->
                <div class="border-top card-body text-center">Create an account <a href="sign_up.php"> Sign Up</a></div>
            </div> <!-- card.// -->
        </div> <!-- col.//-->

    </div> <!-- row.//-->
</div>

<!--container end.//-->
<div id="alert" style="display: none"></div>
<br><br>
<script src="scripts/jquery.min.js"></script>
<script src="scripts/popper.js"></script>
<script src="scripts/bootstrap.min.js"></script>
<script src="scripts/validation.min.js"></script>
<script src="scripts/validation.js"></script>
</body>
</html>