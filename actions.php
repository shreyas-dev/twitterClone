<?php
 include("functions.php");
    if ($_GET['action'] == "loginSignup")
    {
        $error = "";
        if (!$_POST['email']) 
        {
            $error = "An email address is required.";
        } 
        else if (!$_POST['password']) 
        {
            
            $error = "A password is required";
            
        } 
        else if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) 
        {
  
            $error = "Please enter a valid email address.";
            
        }
        if ($error != "") {
            
            echo $error;
            exit();
            
        }
         if ($_POST['loginActive'] == "0") 
        {
            $query = "SELECT * FROM users WHERE email = '". mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
            $result = mysqli_query($link, $query);
            if (mysqli_num_rows($result) > 0) 
                $error = "That email address is already taken.";
            else{
                $query="INSERT INTO users (email,password) VALUES ('".mysqli_real_escape_string($link,$_POST['email'] )."','".mysqli_real_escape_string($link, md5($_POST['password']))."')";
                if(mysqli_query($link, $query)){
                    $_SESSION['id']=mysqli_insert_id($link);
                    echo "1";
                }
                else
                {
                    $error= 'Sorry Couldn\'t create try again later' ;
                }
            }
        }
        else
        {
            $pass=md5($_POST['password']);
            $stmt=mysqli_prepare($link, "SELECT * FROM users WHERE email=? AND password=?");
            mysqli_stmt_bind_param($stmt, "ss", $_POST['email'],$pass);
            $result=mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $id, $users,$password);
            
            if($row=mysqli_stmt_fetch($stmt)){
                $_SESSION['id']=$id;
                echo 1;
            }
            else {
                $error="Incorrect Email/Password combination";
            }
        }
        if($error!=""){
            echo $error;
            exit();
        }
    }
    if ($_GET['action'] == 'toggleFollow') {
        
        $query = "SELECT * FROM isFollowing WHERE follower = ". mysqli_real_escape_string($link, $_SESSION['id'])." AND isFollowing = ". mysqli_real_escape_string($link, $_POST['userId'])." LIMIT 1";
            $result = mysqli_query($link, $query);
            if (mysqli_num_rows($result) > 0) {
                
                $row = mysqli_fetch_assoc($result);
                
                mysqli_query($link, "DELETE FROM isFollowing WHERE id = ". mysqli_real_escape_string($link, $row['id'])." LIMIT 1");
                
                echo "1";
                  
            } else {
                
                mysqli_query($link, "INSERT INTO isFollowing (follower, isFollowing) VALUES (". mysqli_real_escape_string($link, $_SESSION['id']).", ". mysqli_real_escape_string($link, $_POST['userId']).")");
                
                echo "2";
                
                
            }
        
    }
    if($_GET['action']=='postTweet'){
        if(!$_POST['tweetContent']){
            echo "Your Tweet is Empty";
        }
        else if(strlen($_POST['tweetContent'])>140){
            echo "Your Tweet is tooo long";
        }
        else {
             mysqli_query($link, "INSERT INTO tweets (`tweet`, `userid` , `date`) VALUES ('". mysqli_real_escape_string($link, $_POST['tweetContent'])."',". mysqli_real_escape_string($link, $_SESSION['id']).", NOW())");
             echo "1";
        }
    }
?>