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
            $_SESSION['id']=$id;
        	if($row=mysqli_stmt_fetch($stmt)){
        		
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
?>