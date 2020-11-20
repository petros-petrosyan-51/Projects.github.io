<?php
session_start();
if (!$_SESSION["id"]){
    echo "<script>window.location.href = '.';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Split Payment</title>
    <!-- Font-->
    <link rel="stylesheet" href="style/font.min.css">
    <!-- Font Icon -->
    <link rel="stylesheet" href="fonts/material-icon/css/material-design-iconic-font.min.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="style/bootstrap.css">
    <link rel="stylesheet" href="style/bootstrap.min.css">
    <!-- Main css -->
    <link rel="stylesheet" href="style/style.css">
    <script src="scripts/jquery.min.js"></script>
    <script type="text/javascript" src="scripts/bootstrap-datepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="style/bootstrap-datepicker.css" >
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-primary d-flex">
    <div class="p-2">
        <button type="button" onclick="window.location.href = 'dashboard.php'"  class="btn btn-primary"><a href="dashboard.php" style="color: white;text-decoration: none"><h4><i class="zmdi zmdi-arrow-left"></i>  Back To Dashboard</h4></a></button>
    </div>
    <div class="p-2">
        <button type="button" data-close="true"  class="btn btn-primary"><h4>Create Split Payment</h4></button>
    </div>
    <div class="ml-auto p-2" ><button type="button" id="logout" class="btn btn-primary"><h4>Logout</h4></button></div>
</nav>
<div class="container" id="payment">
   <div class="card">
       <div class="card-header d-flex">
           <label  class="col-form-label p-2">Create Split Payment</label>
           <button   type="button" class="close ml-auto p-2 " data-close="true" aria-label="Close">
               <span aria-hidden="true">&times;</span>
           </button>
       </div>
       <div class="card-body">
           <form>
               <div style="width: 96.5%;margin: auto">
                   <div class="form-group row">
                       <label for="staticEmail" class="col-sm-2 col-form-label">Your Email Address</label>
                       <div class="col-sm-10">
                           <input style="width: 100%" type="text" readonly class="form-control" id="staticEmail" value="<?php echo $_SESSION['email'] ?>">
                       </div>
                   </div>
                   <div class="form-group row">
                       <label for="amount" class="col-sm-2 col-form-label">Amount</label>
                       <div class="input-group col">
                           <input type="text" id="amount" class="form-control" placeholder="0000$" aria-label="Amount (to the nearest dollar)">
                       </div>
                   </div>
                   <div class="form-group row dates">
                           <label for="deadline" class="col-sm-2 col-form-label">Deadline</label>
                       <div class="input-group col">
                           <input type="text"  class="form-control" id="deadline" name="event_date" placeholder="YYYY-MM-DD" autocomplete="off" >
                       </div>
                   </div>
               </div>
           </form>
       </div>
       <div class="card-footer card-header d-flex">
           <div class="p-2">
               <label  class="col-form-label">Invite a user</label>
           </div>
           <div class="ml-auto p-2">
               <button  type="button" id="add" class="btn btn-primary px-3"><i class="zmdi zmdi-account-add"></i> Add</button>
           </div>
       </div>
       <div class="container append">
           <button style="display:none;" type="button" id="send" class="btn btn-primary">Send</button>
           <div id="append">
           </div>
           <div style="width: 20%;margin: auto;height: 150px;display: none"  class="loader"><img style="height: 100%" src="images/giphy.gif"></div>
       </div>
   </div>
</div>
<div id="alert" style="display: none"></div>
<script src="scripts/main.js"></script>
<script src="scripts/validation.min.js"></script>
<script src="scripts/validation.js"></script>
<script src="scripts/create_split_payment.js"></script>
</body>
</html>

