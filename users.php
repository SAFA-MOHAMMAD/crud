<?php
require './connection.php';
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $query="DELETE FROM crud WHERE id='$id'";
    $res=mysqli_query($con,$query);
    if($res){
        header('location:users.php');
    }
}
?>
