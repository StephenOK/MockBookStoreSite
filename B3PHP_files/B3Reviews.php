<html>

<!-- Style for displayed table -->
<style>
    table, td{
        border: 1px solid black;
    }
</style>

<?php
include_once("common.php");

#set previous page
setPrev('Reviews');

db_open();

#if coming from search output page
if(isset($_POST['reviewButton'])){
  #retreive book's ISBN
  $BookISBN = $_POST['isbn'];

  #retreive title of desired book
  $TitleSQL = "SELECT Title FROM Books WHERE ISBN = $BookISBN;";
  $TitleRes = mysqli_query($link, $TitleSQL);
  $TitleRow = mysqli_fetch_assoc($TitleRes);

  #create page title for book
  echo "Reviews for ";
  echo $TitleRow['Title'];

  echo "<br><br>";

  #retreive reviews for specified book
  $sql = "SELECT Review FROM Reviews WHERE ISBN = $BookISBN;";
  $result = mysqli_query($link, $sql);
  $resultCheck = mysqli_num_rows($result);

  #if user reviews are found
  if ($resultCheck > 0){
  
    #initiate table
    echo "<table>";
  
    #display each review in a new row
    while ($row = mysqli_fetch_assoc($result)){
      echo "<tr><td><br>";
        echo $row['Review'];
        echo "<br><br></td></tr>";
    }  
  
    #close table
    echo "</table>";
  }

  #if no reviews are found
  else{
    echo "No reviews posted yet.<br><br>";
  }

  db_close();
}
?>

<!-- Form for returning to the Search Output page -->
<form action = 'B3SearchOutput.php' method = 'post'>
  <input type = 'submit' name = 'reviewBack' value = 'Done'>
</form>

</html>