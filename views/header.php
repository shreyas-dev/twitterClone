<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- Bootstrap CSS -->
   <link href='https://fonts.googleapis.com/css?family=Yanone+Kaffeesatz' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-y3tfxAZXuh4HwSYylfB+J125MxIs6mR5FOHamPBG064zB+AFeWH94NdvaCBm8qnd" crossorigin="anonymous">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet"> 
      <title>Shreyas B</title>
      <link rel="stylesheet" href="style.css">
  </head>
  <body>
      
      <nav class="navbar navbar-light bg-faded">
  <a class="navbar-brand" href="index.php">Twitter</a>
  <ul class="nav navbar-nav">
    <li class="nav-item">
      <a class="nav-link" href="?page=timeline">Your timeline</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="?page=yourtweets">Your tweets</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="?page=publicprofiles">Public Profiles</a>
    </li>
  </ul>
  <div class="form-inline pull-xs-right">
      
      <?php if (@($_SESSION['id'])) { ?>
      <i class='glyphicon glyphicons-4-user'></i><span>
      <?php $query = @("SELECT email FROM users WHERE id = ". mysqli_real_escape_string($link, $_SESSION['id'])); $result=mysqli_query($link,$query); while($row=mysqli_fetch_assoc($result)){
       echo $row['email'];
        }?></span>
        <a class="btn btn-primary-outline" href="?function=logout">Logout</a>
      <?php } else { ?>
    <button class="btn btn-primary-outline" data-toggle="modal" data-target="#myModal">Login/Signup</button>
      
      <?php } ?>
  </div>
</nav>