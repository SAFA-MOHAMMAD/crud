<!-- 
require "./connection.php";
if(isset($_POST['buttonsubmit'])){
    $id= $_POST['id'];
    $username= $_POST['name'];
    $password= $_POST['pass'];
    $query="SELECT id FROM crud WHERE name='$username' and pass='$password'";
    $res=mysqli_query($con,$query);
    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        session_start();
        $_SESSION['id'] = $row['id'];
        header('location:users.php');
    } else {
        echo "User not found";
    }
    
} -->
