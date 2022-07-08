<html>

<?php
    include_once('common.php');

    #if page accessed via this page's form
    if(isset($_POST['submit'])){
        #change session's previous page
        setPrev('Home');

        #go to LogIn for administration option
        if($_POST["nextPage"] == "Administration"){
            $_SESSION['Destination'] = "Admin";
            redirect('http://34.150.251.225/B3PHP_files/B3LogIn.php');
        }

        #go to other indicated page
        else{
            $_SESSION['Destination'] = "else";
            redirect('http://34.150.251.225/B3PHP_files/B3' . $_POST["nextPage"] . ".php");
        }
    }

    #if coming from another page
    else{
        #reset active user
        setUser('newUser');
    }
?>

Best Book Buy (3-B.com)<br>
Online Bookstore
<br><br>

<!-- Form for user to pick their destination -->
<form action = "B3Home.php" method = "post">

    <!-- Goes to Search Input page -->
    <input type = "radio" id = "search" name = "nextPage" value = "SearchInput">
        <label for="search">Search Only</label><br>

    <!-- Goes to Sign Up page -->
    <input type = "radio" id = "signUp" name = "nextPage" value = "SignUp">
        <label for="signUp">New Customer</label><br>

    <!-- Goes to Log In page -->
    <input type = "radio" id = "logIn" name = "nextPage" value = "LogIn">
        <label for="logIn">Returning Customer</label><br>

    <!-- Goes to Administration page -->
    <input type = "radio" id = "admin" name = "nextPage" value = "Administration">
        <label for="admin">Administrator</label><br><br>

    <input type = "submit" name = "submit" value = "Submit">
</form>

</html>