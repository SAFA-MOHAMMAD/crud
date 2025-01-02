<?php 
require "./connection.php";

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $query="SELECT * FROM crud WHERE id='$id'";
    $res=mysqli_query($con,$query);
    if(mysqli_num_rows($res)>0){
    $row=mysqli_fetch_assoc($res);
 }
 else{
    $row="";
 }
 if(isset($_POST['update'])){
     $id= $_POST['id'];
     $username= $_POST['name'];
     $email= $_POST['email'];
     $password= $_POST['pass'];
     $query ="UPDATE crud SET  name='$username',email='$email',pass='$password' WHERE id='$id'";
     $res=mysqli_query($con,$query);
     if($res){
         echo 'user updated';
         header('location:users.php');
     }
     else{
         echo 'user not updated';

     }
    
 }
    }

?>