<html>

<!-- Style for displayed table -->
<style>
    table, th, td{
        border: 1px solid black;
    }
</style>

<?php
include_once("common.php");

setPrev('Cart');

#if user wants to proceed to checkout
if(isset($_POST['checkout'])){
    #if user is not logged in
    if ($activeUser == 'newUser'){
        #user is sent to sign up page
        redirect('http://34.150.251.225/B3PHP_files/B3SignUp.php');
    }
    #if user is logged in
    else{
        #user is sent to checkout
        redirect('http://34.150.251.225/B3PHP_files/B3Checkout.php');
    }
}

#if user makes a new search
elseif(isset($_POST['newSearch'])){
    #user is sent to search input
    redirect('http://34.150.251.225/B3PHP_files/B3SearchInput.php');
}

#if user wants to exit
elseif(isset($_POST['exit'])){

    db_open();

    #user's items are removed from cart
    $exitQuery = "DELETE FROM Cart WHERE Username = '$activeUser';";
    mysqli_query($link, $exitQuery);

    db_close();

    #user is sent to Home page
    redirect('http://34.150.251.225/B3PHP_files/B3Home.php');
}

#if user wants to remove an item from their cart
if(isset($_POST['delete'])){
    #get the isbn of the book
    $target = $_POST['isbn'];

    #send deletion statement to database
    db_open();

    $deleteQuery = "DELETE FROM Cart WHERE ISBN = '$target' AND Username = '$activeUser';";
    mysqli_query($link, $deleteQuery);

    db_close();
}

#if user wants to update their cart total
if(isset($_POST['update'])){
    #initialize current loop number and max loops needed
    $loopAt = 1;
    $loops = $_POST['loops'];
    
    db_open();

    while($loops >= $loopAt){
        #generate names of $_POST objects
        $nameLoop = 'isbn' . $loopAt;
        $priceLoop = 'amount' . $loopAt;
        
        #retreive $_POST objects
        $currName = $_POST[$nameLoop];
        $currNum = $_POST[$priceLoop];

        #retreive book's price
        $originalSQL = "SELECT Price FROM Books WHERE ISBN = '$currName';";
        $originalResult = mysqli_query($link, $originalSQL);
        $originalRow = mysqli_fetch_assoc($originalResult);

        #recalculate cost for user's item(s)
        $originalPrice = $originalRow['Price'];
        $currPrice = $originalPrice * $currNum;

        #reflect change in database
        $updateSQL = "UPDATE Cart SET Number = '$currNum', Total = '$currPrice' WHERE ISBN = '$currName' AND Username = '$activeUser';";
        mysqli_query($link, $updateSQL);

        #increment loop
        $loopAt = $loopAt + 1;
    }

    db_close();
}

echo $activeUser . "'s Cart";

db_open();

#retreive cart and corresponding book data
$cartQuery = "SELECT * FROM Cart, Books WHERE Cart.ISBN = Books.ISBN AND Username = '$activeUser';";
$cartResult = mysqli_query($link, $cartQuery);
$cartTable = mysqli_num_rows($cartResult);

#table for user's cart contents
echo "<table>";

if ($cartTable > 0){
    ?>

    <!-- Table Headers -->
    <tr>
        <th>Remove</th>
        <th>Book Description</th>
        <th>Quantity</th>
        <th>Price</th>
    </tr>
    <?php

    #initialize cart total and number of cart rows
    $total = 0;
    $loopNum = 0;

    #for each item in the cart
    while($cartRow = mysqli_fetch_assoc($cartResult)){
        
        #retreive book information and number of books in cart
        $ISBN = $cartRow['ISBN'];
        $amount = $cartRow['Number'];
        $indPrice = $cartRow['Total'];
        $bookStock = $cartRow['Stock'];

        #increment loop and create corresponding labels
        $loopNum = $loopNum + 1;
        $loopName = 'isbn' . $loopNum;
        $loopAmount = 'amount' . $loopNum;
        $loopStock = 'stock' . $loopNum;

        #create hidden variables for payment form with book's ISBN and stock
        echo "<input type = 'hidden' name = '$loopName' value = '$ISBN' form = 'payment'>
              <input type = 'hidden' id = '$loopStock' value = '$bookStock' form = 'payment'>";

        #increase cart total
        $total = $total + $indPrice;

        #create table row
        #lead with delete button and hidden ISBN value
        echo "<tr>
                
                <td>
                    <form method = 'post'>
                        <input type = 'hidden' name = 'isbn' value = '$ISBN'>
                        <input type = 'submit' name = 'delete' value = 'Delete Item'>
                    </form>
                </td>

                <td>";
                
                #display book's title, author, and individual price
                echo $cartRow['Title'];

                echo "<br>By ";
                echo $cartRow['Author'];

                echo "<br>Price: $";
                echo $cartRow['Price'];

            #display input for number of books user wants
          echo "</td>

                <td>
                    <input type = 'number' id = '$loopAmount' name = '$loopAmount' value = '$amount' min = '1' form = 'payment'>
                </td>

                <td>";
                #display total for individual row
                    echo "$" . $indPrice;
          echo "</td>

              </tr>";
        
    }
} 

echo "</table>";

echo "<br><br>";
echo "SubTotal: " . $total;

db_close();
?>


<br><br>

<!-- Form for cart update -->
<form id = 'payment' method = 'post' onsubmit = 'return checkStock()'>
<?php

#inputs for number of rows and submission button
echo "<input type = 'hidden' id = 'loops' name = 'loops' value = '$loopNum'>
      <input type = 'submit' name = 'update' value = 'Recalculate Payment'>
    </form>";
?>

<br><br>

<!-- Form for user to access other pages -->
<form method = 'post'>
    <input type = 'submit' name = 'checkout' value = 'Proceed to Checkout'>
    <input type = 'submit' name = 'newSearch' value = 'New Search'>
    <input type = 'submit' name = 'exit' value = 'Exit'>
</form>

<script>
    //Function used in recalculation form to check if the store's stock of books can supply the customer's order
    function checkStock(){
        //retreive number of rows and initialize current row pointer
        var loopNum = document.getElementById('loops').value;
        var loopAt = 1;

        //look through each individual row
        while(loopNum >= loopAt){
            //retreive book's number in store
            var heldID = "stock" + loopAt + '';
            var heldString = document.getElementById(heldID).value;
            var held = parseInt(heldString);

            //retreive book's number in cart
            var requestID = "amount" + loopAt + '';
            var requestString = document.getElementById(requestID).value;
            var request = parseInt(requestString);

            //check that store can supply order
            if (request > held){
                //notify user if unable
                alert('Too many books asked for. Please correct the amounts.');
                return false;
            }

            //increment to next row
            loopAt = loopAt + 1;
        }
    }

</script>

</html>