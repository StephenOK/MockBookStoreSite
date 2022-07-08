<html>

<?php
include_once("common.php");

#instantiate error text
$ErrorText = null;

#if attempt to create user is made
if(isset($_POST['newUser'])){

    #check for null inputs
    if (empty($_POST["username"]) ||
        empty($_POST["pass1"])||
        empty($_POST["pass2"])||
        empty($_POST["fName"])||
        empty($_POST["lName"])||
        empty($_POST["address"])||
        empty($_POST["city"])||
        empty($_POST["state"])||
        empty($_POST["zip"]))
        {
        #notify user of failure
        $ErrorText = 'Required information is missing!';
    }
    
    #check for matching password inputs
    elseif($_POST["pass1"] != $_POST["pass2"]) {
        $ErrorText = 'Passwords do not match!';
    }
    
    else { 
        #retreive submitted information       
        $Username = $_POST["username"];
        $Password = $_POST["pass1"];
        $PassCheck = $_POST["pass2"];
        $FirstName = $_POST["fName"];
        $LastName = $_POST["lName"];
        $Address = $_POST["address"];
        $City = $_POST["city"];
        $State = $_POST["state"];
        $ZIP = $_POST["zip"];
        $CreditCard = $_POST["card"];
        $CardNum = $_POST["cNum"];
        $CardExp = $_POST["cExp"];
  
        db_open();
  
        #check for existing username
        $UsernameCheck = "SELECT Username FROM Users WHERE Username = '$Username';";
        $UCheckRes = mysqli_query($link, $UsernameCheck);
        $UCheckRows = mysqli_num_rows($UCheckRes);

        #notify user of existing name
        if ($UCheckRows > 0){
            $ErrorText = 'Username already taken!';
        }

        else{
            #if credit card information provided
            if ($CreditCard != ''){
                $SQL_Input = "INSERT INTO Users (
                    Username,
                    PIN,
                    FirstName,
                    LastName,
                    Address,
                    City,
                    State,  
                    ZIP,
                    CreditCard,
                    CardNumber,
                    CardExpiration) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
            }

            #if credit card information not provided
            else{
                $SQL_Input = "INSERT INTO Users (
                    Username,
                    PIN,
                    FirstName,
                    LastName,
                    Address,
                    City,
                    State,  
                    ZIP) VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
            }

            #prepare statement
            $stmt = mysqli_prepare($link, $SQL_Input);
  
            #bind parameters for information with card
            if ($CreditCard != ''){
                mysqli_stmt_bind_param($stmt, "sssssssssss", $Username, $Password, $FirstName, $LastName, $Address, $City, 
                                        $State, $ZIP, $CreditCard, $CardNum, $CardExp);
            }

            #bind parameters for information without card
            else{
                mysqli_stmt_bind_param($stmt, "ssssssss", $Username, $Password, $FirstName, $LastName, $Address, $City, $State, 
                                        $ZIP);
            }

            #commit statement to database
            mysqli_stmt_execute($stmt);
  
            #retreive affected rows
            $affected_rows = mysqli_stmt_affected_rows($stmt);
            
            mysqli_stmt_close($stmt);
            
            #if change was made
            if ($affected_rows == 1){
                #set active user to new person
                setUser($Username);

                #go to Search page if coming from Home
                if ($_SESSION['prevPage'] == 'Home'){
                    $_SESSION['prevPage'] = 'SignUp';
                    redirect('http://34.150.251.225/B3PHP_files/B3SearchInput.php');
                }

                else{
                    $_SESSION['prevPage'] = 'SignUp';

                    #set any carted books to new user's cart
                    $fixSQL = "UPDATE Cart SET Username = '$Username' WHERE Username = 'newUser';";
                    mysqli_query($link, $fixSQL);

                    #go to Checkout page if coming from anywhere else
                    redirect('http://34.150.251.225/B3PHP_files/B3Checkout.php');
                }
            }
   
            #other SQL error occured
            else{
                $ErrorText = 'Error Occured';
            }
            
            echo '<br>';
        }
        
        db_close();
    }
  }
?>

Make a new account:<br>

<!-- Form for new user submission -->
<form action = "B3SignUp.php" method = "post" onsubmit = "return checkCard()">

    Username: <input type = "text" name = "username" maxlength = "15"><br>
    Password: <input type = "password" name = "pass1" maxlength = "15"><br>
    Re-type Password: <input type = "password" name = "pass2" maxlength = "15"><br>
    First Name: <input type = "text" name = "fName" maxlength = "15"><br>
    Last Name: <input type = "text" name = "lName" maxlength = "15"><br>
    Address: <input type = "text" name = "address" maxlength = "40"><br>
    City: <input type = "text" name = "city" maxlength = "20"><br>

    State:
        <select id = "state" name = "state">
            <option value = "">--Select State--</option>    

            <option value = "AL">AL</option>
            <option value = "AK">AK</option>
            <option value = "AR">AR</option>
            <option value = "AZ">AZ</option>
            <option value = "CA">CA</option>
            <option value = "CO">CO</option>
            <option value = "CT">CT</option>
            <option value = "DE">DE</option>
            <option value = "FL">FL</option>
            <option value = "GA">GA</option>

            <option value = "HI">HI</option>
            <option value = "ID">ID</option>
            <option value = "IA">IA</option>
            <option value = "IL">IL</option>
            <option value = "IN">IN</option>
            <option value = "KS">KS</option>
            <option value = "KY">KY</option>
            <option value = "LA">LA</option>
            <option value = "MA">MA</option>
            <option value = "MD">MD</option>

            <option value = "ME">ME</option>
            <option value = "MI">MI</option>
            <option value = "MN">MN</option>
            <option value = "MO">MO</option>
            <option value = "MS">MS</option>
            <option value = "MT">MT</option>
            <option value = "NC">NC</option>
            <option value = "ND">ND</option>
            <option value = "NE">NE</option>
            <option value = "NH">NH</option>

            <option value = "NJ">NJ</option>
            <option value = "NM">NM</option>
            <option value = "NV">NV</option>
            <option value = "NY">NY</option>
            <option value = "OH">OH</option>
            <option value = "OK">OK</option>
            <option value = "OR">OR</option>
            <option value = "PA">PA</option>
            <option value = "RI">RI</option>
            <option value = "SC">SC</option>

            <option value = "SD">SD</option>
            <option value = "TN">TN</option>
            <option value = "TX">TX</option>
            <option value = "UT">UT</option>
            <option value = "VA">VA</option>
            <option value = "VT">VT</option>
            <option value = "WA">WA</option>
            <option value = "WI">WI</option>
            <option value = "WV">WV</option>
            <option value = "WY">WY</option>

        </select><br>

    ZIP: <input type = "text" name = "zip" maxlength = "6" onkeypress = 'return checkInput(event.charCode)'><br>

    Credit Card: <input type = "text" id = "card" name = "card" maxlength = "20"><br>
    Credit Card Number: <input type = "text" id = "cNum" name = "cNum" minlength = "16" maxlength = "16" onkeypress = 'return checkInput(event.charCode)'><br>

    Expiration Date: <input type = "month" id = "cExp" name = "cExp" maxlength = "5" pattern="\d{2}/\d{2}"> (mm/yy) <br>

<?php
#display ErrorText if needed
if ($ErrorText != null){
    echo $ErrorText;
}
?>

<br>
    <input type = "hidden" name = prevSite value = "$previousSite">
    <input type = "submit" name = "newUser" value = "Submit">
</form>

<!-- Form for user to stop input-->
<form action = 'B3Message.php' method = 'post'>
    <input type = "submit" name = "notIn" value = "Don't Register">
</form>

<script>
    //checks if input is a number
    function checkInput(input){
        return input >= 48 && input <= 57;
    }

    //checks for valid card information
    function checkCard(){
        //retreive required information
        var cardName = document.getElementById("card").value;
        var cardNum = document.getElementById("cNum").value;
        var cardExp = document.getElementById("cExp").value;

        //if any card inputs are empty
        if (cardName == '' || cardNum == '' || cardExp == ''){
            //if any card inputs are not empty
            if (cardName != '' || cardNum != '' || cardExp != ''){
                //notify user of error
                alert("Partial credit cards are not accepted!");
                return false;
            }
        }

        //if value given for cardExp
        else if (cardExp != ''){
            //extract desired section of information
            var month = parseInt(cardExp.substring(0, 2));

            //check for valid month
            if (month > 12 || month < 1){
                alert("Invalid month input for Expiration Date!");
                return false;
            }
        }
    }
</script>

</html>