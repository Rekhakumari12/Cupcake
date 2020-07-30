<?php
session_start();
$localhost = "localhost";
$user = "root";
$pass = "";
$db = "test_db";
$con = new mysqli($localhost,$user,$pass,$db);
if($con->connect_error){
    die("Connection Error".$con->connect_error);
}
// if connection succeesfull
else{
    // for sign up
    if(isset($_POST['signup'])){
        if($_REQUEST['username'] && $_REQUEST['email']){
            $username = $_REQUEST['username'];
            $email = $_REQUEST['email'];
            $password = $_REQUEST['password'];
            $cpassword =$_REQUEST['cpassword'];
            // checking password 
            if($password === $cpassword){
                $str_pass = password_hash($password,PASSWORD_BCRYPT);
            }
            else{
                echo "<script>alert('Password should be same')</script>" ;
            }
            // to fetch email if exist
            $query1 = "SELECT username from register WHERE email='$email' ";
            $result = $con->prepare($query1);
            $result->bind_result($username);
            $result->execute();
            $result->store_result();
            if($result->num_rows>0){
                echo "<script>alert('Email already exists')</script>" ;
                $result->close();
                ?>
                <meta http-equiv="refresh" content="1,url=register.html">            
                <?php
                }
            else{
                $query2 = "INSERT INTO `register`(`username`, `email`, `pwd`) VALUES (?, ?, ?) ";
                $result=$con->prepare($query2);
                if($result){
                    $result->bind_param('sss',$username,$email,$str_pass);
                    $result->execute();
                    echo "<script>alert('Data inserted!')</script>" ;
                    ?>
                    <meta http-equiv="refresh" content="2,url=register.html">            
                    <?php
                    $result->close();
                }
            }
        }
    
    else{
        echo "<script>alert('Fill all fields')</script>" ;
        ?>
        <meta http-equiv="refresh" content="2,url=register.html">            
        <?php
    }
 }
//  for login
 else{
    if($_REQUEST['password'] && $_REQUEST['email']){
        $email = $_REQUEST['email'];
        $password = $_REQUEST['password'];
        $auth_query="SELECT * FROM register WHERE email='$email'";
        $result=$con->prepare($auth_query);
        $result->bind_result($id,$username,$email,$pwd);//want to access these field
        $result->execute();
        $result->store_result();
        if($result->fetch()){
            $_SESSION['username']=$username;
            header("location:index.php");
        }
        else{
            echo "<script>alert('Enter valid Email and Password')</script>";
        }
        $result->close();

    }
  }
} 
$con->close();

?>



