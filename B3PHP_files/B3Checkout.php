<html>

<style>
    table, th, td{
        border: 1px solid black;
    }
</style>

<?php
include_once('common.php');

echo "Checkout";

db_open();

#retreive user information
$userSQL = "SELECT * FROM Users WHERE Username = '$activeUser';";
$userResult = mysqli_query($link, $userSQL);
$userInfo = mysqli_fetch_assoc($userResult);

?>

<!-- Table for user's information -->
<table>
    <tr>
        <td>
            Shipping Address:<br>
            <?php
            #display user's information
            echo $userInfo['FirstName'] . " " . $userInfo['LastName'] . "<br>";
            echo $userInfo['Address'] . "<br>";
            echo $userInfo['City'] . "<br>";
            echo $userInfo['State'] . " " . $userInfo['ZIP'] . "<br>";
            ?>
        </td>

        <td>
            <!-- Form for user's payment information -->
            <form action = 'B3Receipt.php' method = 'post' id = 'purchase' onsubmit = 'return checkNewCard()'>

                <!-- User's current information -->
                <input type = 'radio' id = 'existingCard' name = 'useCard' value = 'oldCard' checked = 'checked'>
                    <label for = 'existingCard'>Use Card on File:</label><br>
                
                <?php
                #retreive user's active card
                $userCard = $userInfo['CreditCard'];
                $userNum = $userInfo['CardNumber'];
                $userExp = $userInfo['CardExpiration'];
                echo "<input type = 'hidden' id = 'checkExist' value = '$userCard'>";

                #display card info if record exists
                if ($userCard != null){
                    echo $userCard;
                    echo " ";
                    echo $userInfo['CardNumber'];
                    echo " ";
                    echo $userInfo['CardExpiration'];
                }
                ?>
                <br><br>
                
                <!-- New Card Input -->
                <input type = 'radio' id = 'newCard' name = 'useCard' value = 'newCard'>
                    <label for = 'newCard'>New Credit Card:</label><br>

                <!-- Form for new card information -->
                <input type = 'text' id = 'newCardName' name = 'newCardName' maxlength = '20' placeholder = 'Card Type'><br>
                <input type = 'text' id = 'newCardNum' name = 'newCardNum' minlength = "16" maxlength = "16" onkeypress = 'return checkInput(event.charCode)' placeholder = 'Card Number'><br>
                <input type = 'text' id = 'newCardExp' name = 'newCardExp' maxlength = "5" pattern="\d{2}/\d{2}" placeholder = 'Expiration (mm/yy)'>
            </form>
        </td>
    </tr>
</table>
<br>

<!-- Table for cart items -->
<table>

    <!-- Table headers -->
    <tr>
        <th>Book Description</th>
        <th>Quantity</th>
        <th>Price</th>
    </tr>

    <?php
    #Retreive information on user's cart
    $cartSQL = "SELECT * FROM Cart, Books WHERE Cart.ISBN = Books.ISBN AND Username = '$activeUser';";
    $cartResult = mysqli_query($link, $cartSQL);
    $cartRowNum = mysqli_num_rows($cartResult);
    
    #instantiate book totals and number of books in cart
    $total = 0;
    $bookTotal = 0;

    #for each unique isbn
    if ($cartRowNum > 0){
        while($cartRow = mysqli_fetch_assoc($cartResult)){

            #increase cart total and book number
            $total = $total + $cartRow['Total'];
            $bookTotal = $bookTotal + $cartRow['Number'];

            echo "<tr>

                    <td>";
                        #display book information
                        echo $cartRow['Title'];

                        echo "<br>By ";
                        echo $cartRow['Author'];

                        echo "<br>Price: $";
                        echo $cartRow['Price'];
              echo "</td>
                
                    <td>";
                        #display number of books in cart
                        echo $cartRow['Number'];
              echo "</td>
                
                    <td>";
                        #display cost of row
                        echo "$";
                        echo $cartRow['Total'];
              echo "</td>";

            echo "</tr>";
        }
    }
    ?>
</table><br>

<!-- Notify user of shipping time -->
Shipping Note: Your order will be delivered within 5 business days.
<br><br>

<?php
#determine tax and total
$bookCost = $bookTotal * 2;
$fullTotal = $bookCost + $total;

#display all totals
echo "Subtotal: $" . $total . "<br>"; 
echo "Shipping and Handling: $" . $bookCost . ".00<br>";
echo "Total: $" . $fullTotal . "<br><br>";
?>

<!-- Forms for access to other pages -->
<form action = 'B3SearchInput.php' method = 'post'>
    <input type = 'submit' name = 'cancel' value = 'Cancel'>
</form>

<form action = 'B3Profile.php' method = 'post'>
    <input type = 'submit' name = 'profile' value = 'Update Profile'>
</form>

<!-- Purchase submission button -->
<input type = 'submit' name = 'purchase' value = 'BUY IT!' form = 'purchase'>


<?php
db_close();
?>

<script>
    //check for numeric input
    function checkInput(input){
        return input >= 48 && input <= 57;
    }

    //checks for valid card input
    function checkNewCard(){
        //get user's card input
        var userChoice = document.querySelector('input[name = "useCard"]:checked').value;
        
        //user wants to use a new card
        if (userChoice == "newCard"){
            //get card elements
            var cardName = document.getElementById('newCardName').value;
            var cardNum = document.getElementById('newCardNum').value;
            var cardExp = document.getElementById('newCardExp').value;

            //if any empty fields
            if (cardName == '' || cardNum == '' || cardExp == ''){
                alert("Please fill out all credit card information.");
                return false;
            }

            //retreive inputed month
            var month = parseInt(cardExp.substring(0, 2));
    
            //check for valid month input
            if (month > 12 || month < 1){
                alert("Invalid month input for Expiration Date!");
                return false;
            }
        }

        //user wants to use existing card
        else{
            //retrieve card information
            var cardName = document.getElementById('checkExist').value;

            //if no card on file, notify user
            if(cardName == null || cardName == ""){
                alert("No card on record. Please input a card.");
                return false;
            }
        }
    }
</script>

</html>