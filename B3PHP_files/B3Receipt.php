<html>
   
<style>
    table, th, td{
        border: 1px solid black;
    }
</style>

<?php
include_once('common.php');

#take user to search input page
if (isset($_POST['newSearch'])){
    redirect('http://34.150.251.225/B3PHP_files/B3SearchInput.php');
}

elseif(isset($_POST['exit'])){

    #take user to Home page
    redirect('http://34.150.251.225/B3PHP_files/B3Home.php');
}

echo "Thank you for your purchase!<br>";
db_open();
?>

<!-- Table for User Information -->
<table>
    <tr>
        <td>
            Shipping Address:<br>
            <?php
            #get user info from the database
            $addressSQL = "SELECT * FROM Users WHERE Username = '$activeUser';";
            $addressResult = mysqli_query($link, $addressSQL);
            $addressRow = mysqli_fetch_assoc($addressResult);

            #display info in table
            echo $addressRow['FirstName'] . " " . $addressRow['LastName'] . "<br>";
            echo $addressRow['Address'] . "<br>";
            echo $addressRow['City'] . "<br>";
            echo $addressRow['State'] . " " . $addressRow['ZIP'] . "<br>";
            ?>
        </td>

        <td>
            <?php

            #display Username, date, and time of purchase
            echo "UserID: " . $activeUser . "<br>";
            echo "Date: " . date("Y/m/d") . "<br>";
            echo "Time: " . date("h:i:sa") . "<br><br>";

            #display card info
            echo "Credit Card Information: <br>";
            echo $addressRow['CreditCard'] . " " . $addressRow['CardNumber'] . " " . $addressRow['CardExpiration'] . "<br>";
            ?>
        </td>

    </tr>
</table><br>

<!-- Table of Purchase made -->
<table>
    <tr>
        <th>Book Description</th>
        <th>Quantity</th>
        <th>Price</th>
    </tr>

    <?php
    #retreive cart information
    $cartSQL = "SELECT * FROM Cart, Books WHERE Cart.ISBN = Books.ISBN AND Username = '$activeUser';";
    $cartResult = mysqli_query($link, $cartSQL);
    $cartRowNum = mysqli_num_rows($cartResult);
    
    #initialize price total and book total
    $total = 0;
    $bookTotal = 0;

    #if items present in cart
    if ($cartRowNum > 0){
        while($cartRow = mysqli_fetch_assoc($cartResult)){

            #add cost and number of books to totals
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

<!-- Notify User of Shipping time -->
Shipping Note: Your order will be delivered within 5 business days.
<br><br>

<?php
#calculate tax and final total
$bookCost = $bookTotal * 2;
$fullTotal = $bookCost + $total;

#display all totals to user
echo "Subtotal: $" . $total . "<br>"; 
echo "Shipping and Handling: $" . $bookCost . ".00<br>";
echo "Total: $" . $fullTotal . "<br><br>";
?>

<!-- Button to print receipt -->
<button onclick = "printPage()">Print</button>

<!-- Form for other page destinations -->
<form method = 'post'>
    <input type = 'submit' name = 'newSearch' value = 'New Search'><br>
    <input type = 'submit' name = 'exit' value = 'Exit'>
</form>

<?php

#if reaching this page after a purchase was made
if (isset($_POST['purchase'])){
    #change user card if needed
    if($_POST['useCard'] == 'newCard'){
        #retreive submitted card info
        $newCardName = $_POST['newCardName'];
        $newCardNum = $_POST['newCardNum'];
        $newCardExp = $_POST['newCardExp'];

        #update database record
        $CardSQL = "UPDATE Users SET CreditCard = '$newCardName', CardNumber = '$newCardNum', CardExpiration = '$newCardExp' WHERE Username = '$activeUser';";
        mysqli_query($link, $CardSQL);
    }

    #retreive ISBNs from user's cart
    $shipSQL = "SELECT ISBN, Number FROM Cart;";
    $shipResult = mysqli_query($link, $shipSQL);
    
    #for each item in the user's cart
    while($shipRow = mysqli_fetch_assoc($shipResult)){
        #set retreived values to variables
        $targetISBN = $shipRow['ISBN'];
        $toRemove = $shipRow['Number'];

        #retreive corresponding stock of books in store
        $stockSQL = "SELECT ISBN, Stock FROM Books WHERE ISBN = '$targetISBN';";
        $stockResult = mysqli_query($link, $stockSQL);
        $stockRow = mysqli_fetch_assoc($stockResult);

        #calculate remaining books after purchase is made
        $initialStock = $stockRow['Stock'];
        $finalStock = $initialStock - $toRemove;

        #update record in Books for stock
        $outSQL = "UPDATE Books SET Stock = '$finalStock' WHERE ISBN = '$targetISBN';";
        mysqli_query($link, $outSQL);
    }

    #add receipt to shipment database
    if (floatval($fullTotal) > 0){
        #determine time of purchase
        $currMonth = date('m');
        $currYear = date('Y');
        $currTime = date("h:i:sa");

        #insert data into Shipments record
        $shipmentSQL = "INSERT INTO Shipments (Username, Time, Month, Year, Total) VALUES (?, ?, ?, ?, ?);";
        $shipmentSTMT = mysqli_prepare($link, $shipmentSQL);
        mysqli_stmt_bind_param($shipmentSTMT, 'sssss', $activeUser, $currTime, $currMonth, $currYear, $fullTotal);
        mysqli_stmt_execute($shipmentSTMT);

        mysqli_stmt_close($shipmentSTMT);
    }

    #clear user cart
    $removeSQL = "DELETE FROM Cart WHERE Username = '$activeUser';";
    mysqli_query($link, $removeSQL);
}

db_close();
?>

<script>
    //print function for print button
    function printPage(){
        window.print();
    }
</script>

</html>