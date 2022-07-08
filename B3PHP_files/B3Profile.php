<html>

<?php
include_once("common.php");
echo "Update Your Profile<br><br>";

db_open();

#update to user's page is requested
if (isset($_POST['update'])){
    #gather inputted user info
    $newPass = $_POST['PIN'];
    $newFirst = $_POST['FName'];
    $newLast = $_POST['LName'];
    $newStreet = $_POST['address'];
    $newCity = $_POST['city'];
    $newState = $_POST['state'];
    $newZIP = $_POST['zip'];

    #gather inputted card info
    $newCard = $_POST['cardName'];
    $newCNum = $_POST['cardNum'];
    $newCExp = $_POST['cardExp'];
    
    #begin forming SQL request
    $changeSQL = "UPDATE Users SET ";

    #if user inputted new password
    if ($newPass != null || $newPass != ''){
        $changeSQL = $changeSQL . "Password = '$newPass', ";
    }

    #complete user request and submit to database
    $changeSQL = $changeSQL . "FirstName = '$newFirst', LastName = '$newLast', Address = '$newStreet', City = '$newCity', State = '$newState', ZIP = '$newZIP', 
                                CreditCard = '$newCard', CardNumber = '$newCNum', CardExpiration = '$newCExp' WHERE Username = '$activeUser';";
    mysqli_query($link, $changeSQL);

    #send user back to Checkout page
    redirect('http://34.150.251.225/B3PHP_files/B3Checkout.php');
}

#retreive user's current information
$infoSQL = "SELECT * FROM Users WHERE Username = '$activeUser';";
$infoResult = mysqli_query($link, $infoSQL);
$infoRow = mysqli_fetch_assoc($infoResult);

#display username
echo "Username: " . $infoRow['Username'] . "<br>";
?>

<!-- Form of user's info and changes they want to make -->
<form method = 'post' onsubmit = 'return validSubmission()'>
<?php
    #set user's current information to variables
    $FName = $infoRow['FirstName'];
    $LName = $infoRow['LastName'];
    $address = $infoRow['Address'];
    $city = $infoRow['City'];
    $state = $infoRow['State'];
    $ZIP = $infoRow['ZIP'];
    $CardName = $infoRow['CreditCard'];
    $CardNum = $infoRow['CardNumber'];
    $CardExp = $infoRow['CardExpiration'];

    #display user's information
    echo "New PIN: <input type = 'password' id = 'PIN' name = 'PIN' maxlength = '15'> ";
    echo "Retype PIN: <input type = 'password' id = 'PINConfirm' name = 'PINConfirm' maxlength = '15'>  (Leave blank for no change)<br>";

    echo "First Name: <input type = 'text' id = 'FName' name = 'FName' value = '$FName' maxlength = '15'><br>";
    echo "Last Name: <input type = 'text' id = 'LName' name = 'LName' value = '$LName' maxlength = '15'><br>";
    
    echo "Address: <input type = 'text' id = 'address' name = 'address' value = '$address' maxlength = '40'><br>";
    echo "City: <input type = 'text' id = 'city' name = 'city' value = '$city' maxlength = '20'><br>";
?>
    State: 
    <select id = "state" name = "state">

        <option value = "AL" <?php if($state == 'AL'){echo 'selected';} ?>>AL</option>
        <option value = "AK" <?php if($state == 'AK'){echo 'selected';} ?>>AK</option>
        <option value = "AR" <?php if($state == 'AR'){echo 'selected';} ?>>AR</option>
        <option value = "AZ" <?php if($state == 'AZ'){echo 'selected';} ?>>AZ</option>
        <option value = "CA" <?php if($state == 'CA'){echo 'selected';} ?>>CA</option>
        <option value = "CO" <?php if($state == 'CO'){echo 'selected';} ?>>CO</option>
        <option value = "CT" <?php if($state == 'CT'){echo 'selected';} ?>>CT</option>
        <option value = "DE" <?php if($state == 'DE'){echo 'selected';} ?>>DE</option>
        <option value = "FL" <?php if($state == 'FL'){echo 'selected';} ?>>FL</option>
        <option value = "GA" <?php if($state == 'GA'){echo 'selected';} ?>>GA</option>

        <option value = "HI" <?php if($state == 'HI'){echo 'selected';} ?>>HI</option>
        <option value = "ID" <?php if($state == 'ID'){echo 'selected';} ?>>ID</option>
        <option value = "IA" <?php if($state == 'IA'){echo 'selected';} ?>>IA</option>
        <option value = "IL" <?php if($state == 'IL'){echo 'selected';} ?>>IL</option>
        <option value = "IN" <?php if($state == 'IN'){echo 'selected';} ?>>IN</option>
        <option value = "KS" <?php if($state == 'KS'){echo 'selected';} ?>>KS</option>
        <option value = "KY" <?php if($state == 'KY'){echo 'selected';} ?>>KY</option>
        <option value = "LA" <?php if($state == 'LA'){echo 'selected';} ?>>LA</option>
        <option value = "MA" <?php if($state == 'MA'){echo 'selected';} ?>>MA</option>
        <option value = "MD" <?php if($state == 'MD'){echo 'selected';} ?>>MD</option>

        <option value = "ME" <?php if($state == 'ME'){echo 'selected';} ?>>ME</option>
        <option value = "MI" <?php if($state == 'MI'){echo 'selected';} ?>>MI</option>
        <option value = "MN" <?php if($state == 'MN'){echo 'selected';} ?>>MN</option>
        <option value = "MO" <?php if($state == 'MO'){echo 'selected';} ?>>MO</option>
        <option value = "MS" <?php if($state == 'MS'){echo 'selected';} ?>>MS</option>
        <option value = "MT" <?php if($state == 'MT'){echo 'selected';} ?>>MT</option>
        <option value = "NC" <?php if($state == 'NC'){echo 'selected';} ?>>NC</option>
        <option value = "ND" <?php if($state == 'ND'){echo 'selected';} ?>>ND</option>
        <option value = "NE" <?php if($state == 'NE'){echo 'selected';} ?>>NE</option>
        <option value = "NH" <?php if($state == 'NH'){echo 'selected';} ?>>NH</option>

        <option value = "NJ" <?php if($state == 'NJ'){echo 'selected';} ?>>NJ</option>
        <option value = "NM" <?php if($state == 'NM'){echo 'selected';} ?>>NM</option>
        <option value = "NV" <?php if($state == 'NV'){echo 'selected';} ?>>NV</option>
        <option value = "NY" <?php if($state == 'NY'){echo 'selected';} ?>>NY</option>
        <option value = "OH" <?php if($state == 'OH'){echo 'selected';} ?>>OH</option>
        <option value = "OK" <?php if($state == 'OK'){echo 'selected';} ?>>OK</option>
        <option value = "OR" <?php if($state == 'OR'){echo 'selected';} ?>>OR</option>
        <option value = "PA" <?php if($state == 'PA'){echo 'selected';} ?>>PA</option>
        <option value = "RI" <?php if($state == 'RI'){echo 'selected';} ?>>RI</option>
        <option value = "SC" <?php if($state == 'SC'){echo 'selected';} ?>>SC</option>

        <option value = "SD" <?php if($state == 'SD'){echo 'selected';} ?>>SD</option>
        <option value = "TN" <?php if($state == 'TN'){echo 'selected';} ?>>TN</option>
        <option value = "TX" <?php if($state == 'TX'){echo 'selected';} ?>>TX</option>
        <option value = "UT" <?php if($state == 'UT'){echo 'selected';} ?>>UT</option>
        <option value = "VA" <?php if($state == 'VA'){echo 'selected';} ?>>VA</option>
        <option value = "VT" <?php if($state == 'VT'){echo 'selected';} ?>>VT</option>
        <option value = "WA" <?php if($state == 'WA'){echo 'selected';} ?>>WA</option>
        <option value = "WI" <?php if($state == 'WI'){echo 'selected';} ?>>WI</option>
        <option value = "WV" <?php if($state == 'WV'){echo 'selected';} ?>>WV</option>
        <option value = "WY" <?php if($state == 'WY'){echo 'selected';} ?>>WY</option>

    </select>

    <?php
    echo "ZIP: <input type = 'text' id = 'zip' name = 'zip' value = '$ZIP' maxlength = '6' onkeypress = 'return checkInput(event.charCode)'><br><br>";

    echo "Credit Card: <input type = 'text' id = 'cardName' name = 'cardName' value = '$CardName' maxlength = '20'>";
    echo "<input type = 'text' id = 'cardNum' name = 'cardNum' value = '$CardNum' minlength = '16' maxlength = '16' onkeypress = 'return checkInput(event.charCode)'><br>";
    echo "Card Expiration: <input type = 'text' id = 'cardExp' name = 'cardExp' value = '$CardExp' maxlength = '5' pattern='\d{2}/\d{2}'> (mm/yy)<br>";
    ?>

    <input type = 'submit' name = 'update' value = 'Update'>
</form>

<!-- Form to return to Checkout without making any changes -->
<form action = 'B3Checkout.php' method = post>
    <input type = 'submit' name = 'cancel' value = 'Cancel'>
</form>

<?php
db_close();
?>

<script>
    //checks if user's input is numeric
    function checkInput(input){
        return input >= 48 && input <= 57;
    }

    //verifies that user's info is valid for the database
    function validSubmission(){
        //retreive all form inputs
        var pass1 = document.getElementById('PIN').value;
        var pass2 = document.getElementById('PINConfirm').value;

        var fName = document.getElementById('FName').value;
        var lName = document.getElementById('LName').value;
        var street = document.getElementById('address').value;
        var city = document.getElementById('city').value;
        var ZIP = document.getElementById('zip').value;

        var cardName = document.getElementById('cardName').value;
        var cardNum = document.getElementById('cardNum').value;
        var cardExp = document.getElementById('cardExp').value;

        //if any element is not filled
        if (fName == '' || lName == '' || street == '' || city == '' || ZIP == ''){
            alert("Necessary elements are empty.");
            return false;
        }

        //if passwords don't match
        else if(pass1 != pass2){
            alert("Passwords do not match!");
            return false;
        }

        //if partial credit card is inputted
        else if (cardName == '' || cardNum == '' || cardExp == ''){
            if (cardName != '' || cardNum != '' || cardExp != ''){
                alert("Partial credit cards are not accepted!");
                return false;
            }
        }

        //if month in expiration date is not valid
        else if (cardExp != ''){
            var month = parseInt(cardExp.substring(0, 2));

            if (month > 12 || month < 1){
                alert("Invalid month input for Expiration Date!");
                return false;
            }
        }
    }
</script>

</html>