<?php
session_start();
include 'main.php';
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
    <title>Dashboard</title>
    <!-- Font-->
    <link rel="stylesheet" href="style/font.min.css">
    <!-- Font Icon -->
    <link rel="stylesheet" href="fonts/material-icon/css/material-design-iconic-font.min.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="style/bootstrap.css">
    <link rel="stylesheet" href="style/bootstrap.min.css">
    <script src="scripts/jquery-3.3.1.slim.min.js"></script>
    <script src="scripts/popper.js" ></script>
    <script src="scripts/bootstrap.min.js" ></script>
    <script src="scripts/jquery.min.js"></script>
    <link rel="stylesheet" href="style/datatTables.min.css">
    <script src="scripts/dataTables.min.js" ></script>
    <!-- Main css -->
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-primary d-flex">
   <div class="p-2"><button type="button" onclick="window.location.href = 'split_payment.php'" class="btn btn-primary"><a style="color: white;text-decoration: none" href="split_payment.php"><h4>Split Payment</h4></a></button></div>
   <div class="ml-auto p-2" ><button type="button" id="logout" class="btn btn-primary"><h4>Logout</h4></button></div>
</nav>
<div class="container">
   <div class="tables">
       <h2 align="center">My proposals </h2>
       <br>
       <table class="table table-fluid" id="myTable1">
           <thead>
           <tr><th>Amount</th><th>Deadline</th><th>Created Date</th><th>Action</th></tr>
           </thead>
           <tbody>
           <?php
           for ($i=0;$i<count($your_ticket);$i++){
               if ($your_ticket[$i][2]!=="paid"){
                   echo "<tr data-id='{$your_ticket[$i][0]}'><td>".$your_ticket[$i][1]."$</td><td>".explode(" ",$your_ticket[$i][3])[0]."</td>
<td>".$your_ticket[$i][4]."</td><td><div>
<button data-id='{$your_ticket[$i][0]}' data-open='modal1'  data-toggle=\"modal\" data-target=\"#exampleModal\" type=\"button\" class=\"btn btn-success\">Pay</button>
<button data-id='{$your_ticket[$i][0]}' onclick='window.location.href = \"split_payment.php?open=\"+window.btoa(this.dataset.id)' style='margin-left: 10px' type=\"button\" class=\"btn btn-primary\">Edit</button>
</div></td></tr>";
               }else{
                   echo "<tr data-id='{$your_ticket[$i][0]}'><td>".$your_ticket[$i][1]."$</td><td>".explode(" ",$your_ticket[$i][3])[0]."</td>
<td>".$your_ticket[$i][4]."</td><td><div>
<button disabled type=\"button\" class=\"btn btn-success\">Pay</button>
<button disabled style='margin-left: 10px' type=\"button\" class=\"btn btn-primary\">Edit</button>
</div></td></tr>";
               }
           }
           ?>
           </tbody>
       </table>
   </div>
    <div class="tables">
    <h2 align="center">Other proposals </h2>
    <br>
    <table class="table table-fluid" id="myTable2">
        <thead>
        <tr><th>Amount</th><th>Creator</th><th>Deadline</th><th>Created Date</th><th>Action</th></tr>
        </thead>
        <tbody>
        <?php
        for ($i=0;$i<count($others);$i++){
          if ($others[$i][5]==="unpaid"){
              echo "<tr data-index='{$others[$i][6]}' data-id='{$others[$i][0]}'><td>".$others[$i][1]."$</td><td>".$others[$i][2]."</td><td>".$others[$i][3]."</td><td>".$others[$i][4]."</td><td>
<button  data-creator='{$others[$i][2]}' data-index='{$others[$i][6]}' data-id='{$others[$i][0]}' type=\"button\" data-open='modal2' data-toggle=\"modal\" data-target=\"#exampleModal\" class=\"btn btn-success\">Pay</button>
<button data-click='reject' data-creator='{$others[$i][2]}' data-id='{$others[$i][0]}' data-index='{$others[$i][6]}' style='margin-left: 10px' type=\"button\" class=\"btn btn-primary\">Reject</button>
</td></tr>";
          }else{
              if ($others[$i][5]==="reject"){
                  echo "<tr ><td>".$others[$i][1]."$</td><td>".$others[$i][2]."</td><td>".$others[$i][3]."</td><td>".$others[$i][4]."</td><td>
<button disabled style='margin-left: 10px' type=\"button\" class=\"btn btn-danger\">Denied</button>
</td></tr>";
              }else{
                  echo "<tr ><td>".$others[$i][1]."$</td><td>".$others[$i][2]."</td><td>".$others[$i][3]."</td><td>".$others[$i][4]."</td><td>
<button disabled class=\"btn btn-success\">Pay</button>
<button disabled style='margin-left: 10px' type=\"button\" class=\"btn btn-primary\">Reject</button>
</td></tr>";
              }
          }
        }
        ?>
        </tbody>
    </table>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Payment Field</h5>
                </div>
                <div class="modal-body">
                    <p id="modalText"></p>
                </div>
                <div class="modal-footer">
                    <button id="pay_now" type="button" class="btn btn-success">Pay Now</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="scripts/main.js"></script>
</body>
</html>
