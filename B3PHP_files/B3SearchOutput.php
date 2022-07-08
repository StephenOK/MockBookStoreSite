<html>

<!-- Style for displayed table -->
<style>
    table, td{
        border: 1px solid black;
    }
</style>

<?php

include_once("common.php");

setPrev('SearchOut');

#if user added an item to their cart
if (isset($_POST['cartItem'])){
    db_open();

    #get inputs for specific book
    $bookKey = $_POST['isbn'];
    $bookPrice = $_POST['price'];

    #get inputs for number, date, and time
    $defaultNum = "01";
    date_default_timezone_set("America/New_York");
    $day = date("Y/m/d");
    $time = date("h:i:sa");

    #execute input sql statement for cart input
    $addStatement = "INSERT INTO Cart (ISBN, Username, Number, Day, Time, Total) VALUES (?, ?, ?, ?, ?, ?);";
    $addStmt = mysqli_prepare($link, $addStatement);
    mysqli_stmt_bind_param($addStmt, "ssssss", $bookKey, $activeUser, $defaultNum, $day, $time, $bookPrice);
    mysqli_stmt_execute($addStmt);

    mysqli_stmt_close($addStmt);

    db_close();
}

#user wants to go to the checkout page
if(isset($_POST['checkout'])){
    #go to Sign up page if user is not logged in
    if ($activeUser == 'newUser'){
        redirect('http://34.150.251.225/B3PHP_files/B3SignUp.php');
    }

    #go to Checkout page if user is logged in
    else{
        redirect('http://34.150.251.225/B3PHP_files/B3Checkout.php');
    }
}

#user wants to manage their cart
elseif(isset($_POST['cart'])){
    redirect('http://34.150.251.225/B3PHP_files/B3CartReview.php');
}

#user wants to make a new search
elseif(isset($_POST['newSearch'])){
    redirect('http://34.150.251.225/B3PHP_files/B3SearchInput.php');
}

#user wants to log out to the home page
elseif(isset($_POST['exit'])){

    db_open();

    #remove objects from user's cart
    $exitQuery = "DELETE FROM Cart WHERE Username = '$activeUser';";
    mysqli_query($link, $exitQuery);

    db_close();

    #return user to Home
    redirect('http://34.150.251.225/B3PHP_files/B3Home.php');
}

db_open();

#determine number of objects in user's cart
$cartSQL = "SELECT count(*) FROM Cart WHERE Username = '$activeUser';";
$cartResult = mysqli_query($link, $cartSQL);
$cartRow = mysqli_fetch_assoc($cartResult);

#set total to variable
$cartAmount = $cartRow['count(*)'];

#display number of items in cart
echo "Your shopping cart has " . $cartAmount . " item(s).<br><br>";

#if coming back from review page
if(isset($_POST['reviewBack']) || isset($_POST['cartItem'])){
    #use previously held search query
    $searchSQL = $_SESSION['query'];
}

#if first time using this search
elseif(isset($_POST['search'])){
    
    #determine if all categories are options
    $attribute = $_POST['attribute'];

    #delimit inputted criteria with user's commas
    $searchCriteria = explode(",", $_POST['userSearch']);

    #start search by selecting Books table and having more than 0 available items
    $searchSQL = "SELECT * FROM Books WHERE Stock > 0 AND (";

    #if all criteria are options
    if ($attribute == '*'){
        #create an array of all possible categories
        $Categories = Array('Title', 'Author', 'Publisher', 'ISBN');

        #set first loop indicator
        $firstLoop = true;

        #loop through each category
        foreach($Categories as $Slot){

            #loop through each user input
            foreach ($searchCriteria as $piece){
                #remove spaces at the ends of the user's input
                $piece = trim($piece);

                #if first search
                if ($firstLoop){
                    #add to SQL query a search for the user's input
                    $searchSQL = $searchSQL . " $Slot LIKE '%$piece%'";

                    #indicate first search has passed
                    $firstLoop = false;
                }

                #later searches
                else{
                    #add to SQL query a search for the user's input
                    $searchSQL = $searchSQL . " OR $Slot LIKE '%$piece%'";
                }
            }
        }
    }

    #if specific criteria are indicated
    else{
        #create an array made of nulls and the selected items
        $specified = array($_POST['attribute1'], $_POST['attribute2'], $_POST['attribute3'], $_POST['attribute4']);

        #set first loop indicator
        $firstLoop = true;

        #loop through each user input
        foreach ($searchCriteria as $piece){
            #remove spaces at ends of users inputs
            $piece = trim($piece);

            #loop through each category
            foreach($specified as $attribute){
                #if it is a valid attribute
                if($attribute != null){
                    #if first search
                    if ($firstLoop){
                        #add to SQL query a search for the user's input
                        $searchSQL = $searchSQL . " $attribute LIKE '%$piece%'";
                        #indicate first search has passed
                        $firstLoop = false;
                    }

                    #later searches
                    else{
                        #add to SQL query a search for the user's input
                        $searchSQL = $searchSQL . " OR $attribute LIKE '%$piece%'";
                    }
                }
            }
        }
    }

    #close attribute searches for user inputs
    $searchSQL = $searchSQL . ")";

    #if user indicated a genre
    if ($_POST['Genre'] != '*'){
        #input genre check into SQL statement
        $genre = $_POST['Genre'];
        $searchSQL = $searchSQL . " and Genre = '$genre'";
    }

    #close SQL statement
    $searchSQL = $searchSQL . ";";
}

#record completed query as session variable
$_SESSION['query'] = $searchSQL;

#get results from database
$searchResult = mysqli_query($link, $searchSQL);
$searchTable = mysqli_num_rows($searchResult);

#start results display
echo "<table>";

#if results found
if ($searchTable > 0){
    #for each row
    while($searchRow = mysqli_fetch_assoc($searchResult)){
        ?>
        <tr>
            <td>

                <?php
                #isolate book's individual ISBN and price
                $ISBN = $searchRow['ISBN'];
                $price = $searchRow['Price'];

                #initialize button's active state
                $activeButton = null;

                #check if book is in cart
                $checkQuery = "SELECT ISBN FROM Cart WHERE ISBN = '$ISBN' AND Username = '$activeUser'";
                $checkResult = mysqli_query($link, $checkQuery);
                $checkTable = mysqli_num_rows($checkResult);

                #if book is found in cart
                if ($checkTable > 0){
                    $activeButton = 'disabled';
                }
                
                #create forms for adding item to the cart and accessing the reviews for the book
                echo "<form method = 'post'>
                        <input type = 'hidden' name = 'isbn' value = '$ISBN'>
                        <input type = 'hidden' name = 'price' value = '$price'>
                        <input type = 'submit' name = 'cartItem' value = 'Add to Cart' $activeButton>
                      </form>

                <form action = 'B3Reviews.php' method = 'post'>
                    <input type = 'hidden' name = 'isbn' value = '$ISBN'>
                    <input type = 'submit' name = 'reviewButton' value = 'Reviews'>
                </form>";
                ?>

            </td>
                          
            <td>
                    
        <?php

        #display book properties
        echo $searchRow['Title'];
        echo "<br>
                    
        Author: ";
        echo $searchRow['Author'];
        echo "<br>
                    
        Publisher: ";
        echo $searchRow['Publisher'];
        echo "<br>
                    
        ISBN: ";
        echo $searchRow['ISBN'];
        echo "<br>
                    
        Price: ";
        echo $searchRow['Price'];
        echo "</td></tr>";
    }
}            

#close table
echo "</table>";

db_close();
?>
    <!-- Form for user to access other pages -->
    <form action = 'B3SearchOutput.php' method = 'post'>
        <input type = 'submit' name = 'checkout' value = 'Proceed to Checkout'>
        <input type = 'submit' name = 'cart' value = 'Manage Cart'>
        <input type = 'submit' name = 'newSearch' value = 'New Search'>
        <input type = 'submit' name = 'exit' value = 'Exit'>
    </form>

</html>