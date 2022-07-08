<html>

<?php

echo "Log In:<br>";

include_once('common.php');
db_open();

#initialize error message
$logError = null;

#if user submitted credentials
if (isset($_POST['logInSubmit'])){

    #retreive $_POST submissions
    $UserSubmit = $_POST['checkUser'];
    $PINSubmit = $_POST['checkPIN'];
    
    #if user selected Returning Customer 
    if($_SESSION['Destination'] == 'else'){
        #get data from Users table
        $CheckSQL = "SELECT Username, PIN FROM Users WHERE Username = '$UserSubmit';";
    }
    #if user selected Administration
    else{
        #get data from Administrators table
        $CheckSQL = "SELECT Username, AdminPIN FROM Administrators WHERE Username = '$UserSubmit';";
    }
    
    #retreive record from database
    $CheckResult = mysqli_query($link, $CheckSQL);
    $CheckRows = mysqli_num_rows($CheckResult);

    #if no user found
    if ($CheckRows == 0){
        $logError = "Username not found!";
    }

    else{
        #retreive stored password
        $CheckRow = mysqli_fetch_assoc($CheckResult);
        $PINConfirm = $CheckRow['PIN'];

        #if failure in first check, search for AdminPIN
        if ($PINConfirm == null){
            $PINConfirm = $CheckRow['AdminPIN'];
        }

        #if password does not match record
        if($PINConfirm != $PINSubmit){
            $logError = "Password is incorrect!";
        }

        else{
            #log in user
            setUser($UserSubmit);
            setPrev("LogIn");

            #redirect to SearchInput for normal log in
            if($_SESSION['Destination'] == 'else'){
                redirect('http://34.150.251.225/B3PHP_files/B3SearchInput.php');
            }

            #redirect to Administration
            else{
                redirect('http://34.150.251.225/B3PHP_files/B3Administration.php');
            }
        }
    }
}

db_close();
?>

<!-- Form for user log in -->
<form method = 'post'>
    Username: <input type = 'text' name = 'checkUser'><br>
    Password: <input type = 'password' name = 'checkPIN'><br>

<?php
#display error if one given
if ($logError != null){
    echo $logError;
}
?>

    <br>
    <input type = 'submit' name = 'logInSubmit' value = 'Log In'><br>
</form>

<!-- Return to Home page -->
<form action = 'B3SearchInput.php' method = 'post'>
    <input type = 'submit' name = 'cancel' value = 'Cancel'>
</form>

</html>