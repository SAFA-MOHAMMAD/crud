<?php
   
   $con= mysqli_connect("localhost","root","d28m07y2024","crudsystem");
   if (!$con) {
       die("Connection failed: " . mysqli_connect_error());
   }

   $res= mysqli_query($con,"select * from crud");
   if (!$res) {
       die("Query failed: " . mysqli_error($con));
   }
       ?>