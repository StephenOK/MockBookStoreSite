<html>

<script>
    //determines if the search bar has any content or not
    function checkFilled(){
        //get search bar info
        var x = document.forms['searchIn']['userSearch'].value;

        //if data is empty, notify user and halt form
        if (x == '' || x == null){
            alert("Please input a search.");
            return false;
        }
    }
</script>

<?php
    include_once('common.php');
    setPrev('SearchIn');

    //redirect to user's cart if user pushed that button
    if(isset($_POST['cart'])){
        redirect('http://34.150.251.225/B3PHP_files/B3CartReview.php');
    }

    //returns user to home page if user pushed that button
    elseif(isset($_POST['exit'])){

        db_open();

        //remove all cart items for the current user
        $exitQuery = "DELETE FROM Cart WHERE Username = '$activeUser';";
        mysqli_query($link, $exitQuery);
    
        db_close();

        //take user to Home page
        redirect('http://34.150.251.225/B3PHP_files/B3Home.php');
    }
?>

<!-- Form for user to create a search -->
<form name = 'searchIn' action = 'B3SearchOutput.php' onsubmit = 'return checkFilled()' method = 'post'>

    <!-- Desired text from user -->
    Search for <input type = 'text' name = 'userSearch'><br>
    <br>

    <!-- Checkbox list for search categories -->
    <input type = 'checkbox' id = 'anywhere' name = 'attribute' value = '*' checked = 'checked' onclick = 'fixEverywhere()'>
        <label for='anywhere'>Keyword Anywhere</label><br>

    <input type = 'checkbox' id = 'title' name = 'attribute1' value = 'Title' onclick = 'fixAnywhere()'>
        <label for='title'>Title</label><br>

    <input type = 'checkbox' id = 'author' name = 'attribute2' value = 'Author' onclick = 'fixAnywhere()'>
        <label for='author'>Author</label><br>

    <input type = 'checkbox' id = 'publisher' name = 'attribute3' value = 'Publisher' onclick = 'fixAnywhere()'>
        <label for='publisher'>Publisher</label><br>

    <input type = 'checkbox' id = 'ISBN' name = 'attribute4' value = 'ISBN' onclick = 'fixAnywhere()'>
        <label for='ISBN'>ISBN</label><br>
    <br>
      
    <!-- Dropdown list for selection of genres -->
    <select id = 'Genre' name = 'Genre'>
        <option value = '*'>All Genres</option>

        <?php
            db_open();
            #retreive all genres available in store
            $genreSQL = "SELECT DISTINCT Genre FROM Books;";
            $genreResult = mysqli_query($link, $genreSQL);
            $genreRowNum = mysqli_num_rows($genreResult);

            #create a dropdown option for each one
            if($genreRowNum > 0){
                while($genreRow = mysqli_fetch_assoc($genreResult)){
                    $genreName = $genreRow['Genre'];
                    echo "<option value = '$genreName'>$genreName</option>";
                }
            }
            db_close();
        ?>
    </select>
    <br>

    <!-- Submission Button -->
    <input type = 'submit' name = 'search' value = 'Search'><br>
  </form>

  <!-- Form for other destinations from this page -->
  <form action = 'B3SearchInput.php' method = 'post'>
    <input type = 'submit' name = 'cart' value = 'Manage Cart'><br>
    <input type = 'submit' name = 'exit' value = 'Exit'><br>

  </form>

  <script>
    //find all checkboxes for the category list
    var anywhere = document.getElementById("anywhere");
    var title = document.getElementById("title");
    var author = document.getElementById("author");
    var publisher = document.getElementById("publisher");
    var ISBN = document.getElementById("ISBN");

    //uncheck anywhere checkbox if any other box is checked
    function fixAnywhere(){
        if (anywhere.checked == true){
            anywhere.checked = false;
        }
    }

    //uncheck all other boxes if anywhere box is checked
    function fixEverywhere(){
        title.checked = false;
        author.checked = false;
        publisher.checked = false;
        ISBN.checked = false;
    }

  </script>

</html>