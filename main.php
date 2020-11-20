<?php
session_start();
include "config.php";
if (!isset($_GET['key']) && !isset($_POST['action'])) {
   if ("".explode("/", $_SERVER['REQUEST_URI'])[2]=="main.php"){
        echo "<script>window.location.href = '.';</script>";
    }
}
class main
{
    private $sql;
    private $conn;
    public $response;
    private $data;
 public function __construct($servername,$username,$password)
 {
     try {
         $this->conn = new PDO("mysql:host=$servername", $username, $password);
         $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

         $this->sql = "CREATE DATABASE IF NOT EXISTS projectDB";
         $this->conn->exec($this->sql);
         $this->sql = "use projectDB";
         $this->conn->exec($this->sql);
         $this->sql = "CREATE TABLE user(
  id int(6) NOT NULL AUTO_INCREMENT,
  f_name varchar(50) NOT NULL,
  l_name varchar(50) NOT NULL,
  email varchar(50) DEFAULT NULL,
  password varchar(100) DEFAULT NULL,
  created varchar(100) NOT NULL,
  status varchar(50) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY email (email)
  );
  
  CREATE TABLE payment(
  id int(6) NOT NULL AUTO_INCREMENT,
  debt varchar(50) NOT NULL,
  payers varchar(5000) NOT NULL,
  status varchar(50) DEFAULT NULL,
  created varchar(100) NOT NULL,
  end_date varchar(100) NOT NULL,
  creator_id int(6) NOT NULL,
  PRIMARY KEY (id)
  ) ;
";
         $this->conn->exec($this->sql);
     }catch (PDOException $e){}
 }
    private function mail($email,$value){
       if (file_exists("mail/config.txt")){
          $username = explode("\n",file_get_contents("mail/config.txt"))[0];
          $password =base64_decode(explode("\n",file_get_contents("mail/config.txt"))[1]);
       }
       if ($value ==="verification"){
           $subject = "Sign Up Verification";
           $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
           $body = "<h1 style='color: green'>Register Successfuly</h1><br> Your Email: " . "$email" . "<br><p>Please Click here to confirm your details.</p><br>" . "<a href=".$actual_link."?key=".base64_encode($email)." target='_parent'>Click Here</a></div>";
       }else{
           $subject = "Notifications";
           $actual_link = explode("main.php",(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]")[0];
                 if ($value[1]){
                     if ($value[0]==='add-no'){
                        $actual_link=$actual_link."sign_up.php";
                     }
            $body="<p>".$_SESSION['email']."  user asks you to pay ".$value[1]."$</p><br><p>Click on the link below to see more details</p><br><a href='".$actual_link."?user=".$email."'>Click here</a>";
                 }
                 if ($value==="paidMy"){
                     $body="<p>From:".$_SESSION['email']."</p><br><p>No more to follow the link and amount already paid and closed the account</p>";
                 }
                 if ($value==="paidOther"){
                     $body="<p>".$email[1]." Paid your ".$email[2]."$</p>";
                     $email=$email[0];
                 }
                 if ($value==="Reject"){
                     $body="<p>".$email[1]." Refused to pay your ".$email[2]."$</p>";
                     $email=$email[0];
                 }
       }
      require_once("mail/phpmailer/PHPMailerAutoload.php");
      $mail = new PHPMailer;
      $mail->CharSet = 'utf-8';
      $mail->isSMTP();
      $mail->Host = 'smtp.mail.ru';
      $mail->SMTPAuth = true;
      $mail->Username = $username;
      $mail->Password = $password;
      $mail->SMTPSecure = 'ssl';
      $mail->Port = 465;
      $mail->setFrom($username);
      $mail->addAddress($email);
      $mail->isHTML(true);
      $mail->Subject = $subject ;
      $mail->Body    =  $body;
      $mail->AltBody = '';
        if(!$mail->send()) {
            return false;
        } else{
            return true;
        }
    }
    private function test_email($email){
     $this->sql = "SELECT * FROM user WHERE email = '{$email}'";
     foreach ($this->conn->query($this->sql) as $row) {
         return [$row['id'],$row['password'],$row['status']];
     }
     }
 public function verification($email){
     $this->sql = "UPDATE user SET status='approved' WHERE email = '{$email}'";
     return $this->conn->exec($this->sql);
 }
 public function register($f_name,$l_name,$email,$pass){
     if ($this->test_email($email)){
         $this->response=["response"=>"error","message"=>"That email address already exists"];
     }else{
         if ($this->mail($email,"verification")){
             $pass = md5($pass);
             $this->sql = "insert into `user`(f_name,l_name,email,password,created,status)values('{$f_name}','{$l_name}','{$email}', '{$pass}',NOW(),'pending')";
             if($this->conn->exec($this->sql)){
                 $this->response=["response"=>"success","message"=>"Please check your email and confirm the request "];
             }else{
                 $this->response=["response"=>"error","message"=>"Please check the details."];
             }
         }else{
                 $this->response=["response"=>"error","message"=>"Your email address does not exist"];
         }
     }
     return $this->response;
 }
 public function login($email,$pass){
    $this->data = $this->test_email($email);
    if ($this->data){
        if ($this->data[1] === md5($pass) &&  $this->data[2]==="approved"){
            $_SESSION['id'] = $this->data[0];
            $_SESSION['email'] = $email;
            $this->response=["response"=>"success","message"=>""];
        }else{
            $this->response=["response"=>"error","message"=>"The data is incorrect, please check."];
        }
    }else{
        $this->response=["response"=>"error","message"=>"The data is incorrect, please check."];
    }
    return $this->response;
 }
 private function getCreator($id){
    $this->sql ="SELECT email FROM user WHERE id='{$id}' LIMIT 1";
     foreach ($this->conn->query($this->sql) as $row) {
        return $row['email'];
     }
 }
 public function addPayment($end_date,$debt,$payers){
     $id=$_SESSION['id'];
       $array = [];
       $amount =0;
     foreach (json_decode($payers) as $payments){
         if ($payments->user_email !== $_SESSION['email']) {
                if (!$this->mail($payments->user_email, [$this->test_email($payments->user_email)?'add-yes':'add-no', $payments->user_debt])){
                    $this->response=["response"=>"error","message"=>"Your e-mail does not exist"];
                }else{
                    array_push($array,$payments);
                    $amount=$amount+$payments->user_debt;
                    if ( $this->response['response'] !=="error"){
                        $this->response=["response"=>"success","message"=>""];
                    }
                }
         }else{
             $this->response=["response"=>"error","message"=>"The data is incorrect, please check."];
         }
     }
     if (count($array) ===0){
         $this->response=["response"=>"error","message"=>"The data is incorrect, please check."];
     }else{
         $length = count($array);
         if (intval($debt)-$amount !== 0){
             array_push($array,['user_debt'=>intval($debt)-$amount,'user_email'=>$_SESSION['email'],'status'=>'unpaid']);
         }
         $array=json_encode($array);
         $this->sql = "insert into `payment`(debt,payers,status,created,end_date,creator_id)values('{$debt}','{$array}','unpaid',NOW(),'{$end_date}','{$id}')";
         if ($this->conn->exec($this->sql)){
             $this->response=["response"=>"success","message"=>$length,'array'=>$array];
         }else{
             $this->response=["response"=>"error","message"=>"The data is incorrect, please check."];
         }
     }
     return $this->response;
 }
 public function UpdatePayment($end_date,$debt,$payers,$id,$added){
     $amount=0;
    if (count($added) && $added[0]){
        for ($i=0;$i<count($added);$i++){
            $value =[$this->test_email(json_encode(json_decode($payers)[$added[$i]]->user_email))?"add-yes":"add-no",json_decode($payers)[$added[$i]]->user_debt];
            if (!$this->mail(json_decode($payers)[$added[$i]]->user_email,$value)){
                $this->response=["response"=>"error","message"=>"Your e-mail does not exist"];
            }else{
                if ( $this->response['response'] !=="error"){
                    $this->response=["response"=>"success","message"=>""];
                }
            }
        }
    }else{
        $this->response=["response"=>"success","message"=>""];
    }
    if ($this->response['response'] ==="success"){
        $payers=json_decode($payers);
        for($i=0;$i<count($payers);$i++){
            $amount=$amount+intval($payers[$i]->user_debt);
        }
        $amount=intval($debt)-$amount;
        if ($amount !== 0){
            array_push($payers,['user_debt'=>$amount,'user_email'=>$_SESSION['email'],'status'=>'unpaid']);
        }
        $length=count($payers);
        $payers=json_encode($payers);
        $this->sql = "UPDATE payment SET payers ='{$payers}' WHERE id = '{$id}'";
        if($this->conn->exec($this->sql)){
            $this->response=["response"=>"success","message"=>$length,'array'=>$payers];
        }else{
            $this->response=["response"=>"success","message"=>$length,'array'=>$payers];
        }
    }
    return $this->response;
 }
 public function getYourTicket(){
     $array=[];
     $this->sql = "SELECT * FROM payment WHERE creator_id = '{$_SESSION['id']}'";
     foreach ($this->conn->query($this->sql) as $row) {
         array_push($array,[$row['id'],$row['debt'],$row['status'],$row['end_date'],$row['created']]);
     }
     return $array;
 }
 public function getAllUserTicket(){
     $array=[];
     $this->sql = "SELECT * FROM payment WHERE creator_id !='".$_SESSION['id']."' and  payers  LIKE '%".$_SESSION['email']."%'";
     foreach ($this->conn->query($this->sql) as $row) {
       array_push($array,[$row['id'],array_filter(json_decode($row['payers']),function ($item){if ($item->user_email==$_SESSION['email']){return true;}}),$row['end_date'],$this->getCreator($row['creator_id']),$row['created']]);
     }
     return $array;
 }
 public function getPayment($id){
     $this->sql = "SELECT * FROM payment WHERE id = '{$id}'";
     foreach ($this->conn->query($this->sql) as $row) {
        return ['created'=>$row['created'],'amount'=>$row['debt'],'date'=>$row['end_date'],'payers'=>$row['payers'],'creator'=>$row['creator_id']];
     }
 }
 public function PayMy($id,$ajax){
     $array=[];
     $payers =json_decode($this->getPayment($id)['payers']);
    foreach ( $payers as $payment){
        if ($payment->status ==="reject"){
            array_push($array,['user_debt'=>$payment->user_debt,'user_email'=>$payment->user_email,'status'=>'reject']);
        }else{
            array_push($array,['user_debt'=>$payment->user_debt,'user_email'=>$payment->user_email,'status'=>'paid']);
        }
       if ($ajax === true){
           if ($payment->user_email !== $_SESSION['email'] && $payment->status !== 'paid' && $payment->status !== 'reject' ){
               $this->mail($payment->user_email,'paidMy');
           }
       }
    }
    $array=json_encode($array);
    $this->sql = "UPDATE payment SET status='paid',payers='{$array}' WHERE id = '{$id}'";
     if($this->conn->exec($this->sql)){
         return true;
     }else{
         return false;
     }
 }
 public function PayOther($id,$index,$creator){
     $payment=$this->getPayment($id);
     $payers=json_decode($payment['payers']);
     $this->mail([$creator,$payers[$index]->user_email,$payers[$index]->user_debt],'paidOther');
     $amount=intval($payment['amount'])-intval($payers[$index]->user_debt);
     $payers[$index]->status="paid";
     $payers=json_encode($payers);
     if ($amount===0){
         if ($this->PayMy($id,false)){
             $this->sql = "UPDATE payment SET payers='{$payers}' WHERE id = '{$id}'";
             if($this->conn->exec($this->sql)){
                 return true;
             }else{
                 return false;
             }
         }
     }else{
         $this->sql = "UPDATE payment SET debt='{$amount}',payers='{$payers}' WHERE id = '{$id}'";
         if($this->conn->exec($this->sql)){
             return true;
         }else{
             return false;
         }
     }
 }
 public function Reject($id,$index,$creator){
     $payment=$this->getPayment($id);
     $payers=json_decode($payment['payers']);
     $this->mail([$creator,$payers[$index]->user_email,$payers[$index]->user_debt],'Reject');
     $payers[$index]->status="reject";
     $payers=json_encode($payers);
     $this->sql = "UPDATE payment SET payers='{$payers}' WHERE id = '{$id}'";
     if($this->conn->exec($this->sql)){
         return true;
     }else{
         return false;
     }
 }
}
$main = new main($hostname[0],$hostname[1],$hostname[2]);
if (isset($_POST['action']) && $_POST['action'] == 'register') {
    echo json_encode($main->register("{$_POST['f_name']}","{$_POST['l_name']}","{$_POST['email']}","{$_POST['password']}"));
}
if (isset($_POST['action']) && $_POST['action'] == 'login') {
    echo json_encode($main->login("{$_POST['email']}","{$_POST['password']}"));
}
if(isset($_GET['key'])){
    $res = $main->verification(base64_decode($_GET['key']));
    $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/".explode("/",$_SERVER[REQUEST_URI])[1]."/";
    echo "<script>window.location.href = '{$link}';</script>" ;
}
if (isset($_POST['action']) && $_POST['action'] == 'logout') {
    session_destroy();
    echo true;
}
if (isset($_POST['action']) && $_POST['action']=='addPayment'){
   echo json_encode($main->addPayment(date("Y-m-d H:i:s", strtotime($_POST['end_date'])),$_POST['debt'],$_POST['payers']));
}
$your_ticket = $main->getYourTicket();
$other_ticket = $main->getAllUserTicket();
$others = [];
for ($i=0;$i<count($other_ticket);$i++){
   for ($j=0;$j<count(array_keys($other_ticket[$i][1]));$j++){
          array_push($others,[$other_ticket[$i][0],$other_ticket[$i][1][array_keys($other_ticket[$i][1])[$j]]->user_debt,$other_ticket[$i][3],explode(" ",$other_ticket[$i][2])[0],$other_ticket[$i][4],$other_ticket[$i][1][array_keys($other_ticket[$i][1])[$j]]->status,array_keys($other_ticket[$i][1])[$j]]);
   }
}
if (isset($_POST['action']) && $_POST['action']==="GetPayment"){
    echo json_encode($main->getPayment($_POST['id']));
}
if (isset($_POST['action']) && $_POST['action']=='UpdatePayment'){
    echo  json_encode($main->UpdatePayment(date("Y-m-d H:i:s", strtotime($_POST['end_date'])),$_POST['debt'],$_POST['payers'],$_POST['id'],explode(",",$_POST['added'])));
}
if (isset($_POST['action']) && $_POST['action']==="PayMy"){
   echo  json_encode($main->PayMy($_POST['id'],true));
}
if (isset($_POST['action']) && $_POST['action']==="PayOther"){
   echo json_encode($main->PayOther($_POST['id'],$_POST['index'],$_POST['creator']));
}
if (isset($_POST['action']) && $_POST['action']==="reject"){
    echo json_encode($main->Reject($_POST['id'],$_POST['index'],$_POST['creator']));
}