<?php
  #initialize session
  session_start();

  #create server variables
  $db_server = null;
  $db_username = "root";
  $db_password = "B29h0IdRd!";
  $db_name = "B3Database";

  #initialize session variables
  $activeUser;
  $prevPage;

  #if previous session active
  if(isset($_SESSION['activeUser'])){
    #maintain active user variable
    $activeUser = $_SESSION['activeUser'];
    $_SESSION['activeUser'] = $activeUser;

    #maintain previous page variable
    $prevPage = $_SESSION['prevPage'];
  }

  #if new session
  else{
    #set initial values
    $activeUser = 'newUser';
    $prevPage = 'newPage';

    #set values in session
    $_SESSION['activeUser'] = $activeUser;
    $_SESSION['prevPage'] = $prevPage;
  }

  #Open a connection
  function db_open()  {
    global $link, $db_server, $db_name, $db_username, $db_password;
    $link = mysqli_connect($db_server, $db_username, $db_password, $db_name)
                or  die("Could not connect: " . mysqli_connect_error());
  }

  #Close a connection
  function db_close() {
    global $link;
    mysqli_close($link);
  }

  #take user to indicated page
  function redirect($url){
    ob_start();
    header('Location: ' .$url);
    ob_end_flush();
    die();
  }

  #change active user in session
  function setUser($loggedUser){
    $activeUser = $loggedUser;
    $_SESSION['activeUser'] = $activeUser;
  }

  #change previous page in session
  function setPrev($previous){
    $prevPage = $previous;
    $_SESSION['prevPage'] = $prevPage;
  }
  ?>