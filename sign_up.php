<?php
session_start();
if ($_GET['user']){
    if ($_SESSION['email'] !== $_GET['user']){
        session_destroy();
    }
    echo "<script>window.location.href = 'sign_up.php';</script>";
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
    <title>Sign Up</title>
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
     <a style="font-size: 15px;color: white">Sign Up</a>
</nav>
<div class="container">
    <br>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <header class="card-header">
                    <h4 class="card-title mt-2">Sign Up</h4>
                </header>
                <article class="card-body">
                    <form method="post" action="main.php" onsubmit=handleSubmit(this) autocomplete="off">
                        <div class="form-row">
                            <div class="col form-group" data-col="First_name">
                                <label>First name </label>
                                <input  type="text" class="form-control" data-type="First_name" placeholder="">
                                <span style="display:none;"></span>
                            </div> <!-- form-group end.// -->
                            <div class="col form-group" data-col="Last_name">
                                <label>Last name</label>
                                <input type="text" class="form-control" data-type="Last_name" placeholder=" ">
                                <span style="display:none;"></span>
                            </div> <!-- form-group end.// -->
                        </div> <!-- form-row end.// -->
                        <div class="form-group" data-col="email">
                            <label>Email address</label>
                            <input class="form-control" type="text" data-type="email" autocomplete="off">
                            <span style="display:none;"></span>
                        </div>
                        <div class="form-group" data-col="password">
                            <label>Create password</label>
                            <input class="form-control" data-type="password" type="password" autocomplete="off">
                            <span style="display:none;"></span>
                        </div> <!-- form-group end.// -->
                        <div class="form-group" data-col="re-pass">
                            <label>Retype password</label>
                            <input class="form-control" data-type="re-pass" type="password" autocomplete="off">
                            <span style="display:none;"></span>
                        </div> <!-- form-group end.// -->
                        <div class="form-group">
                            <button type="submit"  class="btn btn-primary btn-block"> Sign Up  </button>
                        </div> <!-- form-group// -->
                        <small class="text-muted">By clicking the 'Sign Up' button, you confirm that you accept our <br> Terms of use and Privacy Policy.</small>
                    </form>
                </article> <!-- card-body end .// -->
                <div class="border-top card-body text-center">Have an account? <a href=".">Sign In</a></div>
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