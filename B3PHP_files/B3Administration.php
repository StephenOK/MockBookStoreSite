<html>

Administration Page<br><br>

<?php
include_once("common.php");

echo "Number of Users: ";

db_open();

#retreive number of users on record
$userCountSQL = "SELECT count(*) FROM Users;";
$userCountResult = mysqli_query($link, $userCountSQL);
$userCountRow = mysqli_fetch_assoc($userCountResult);

#display number of users
echo $userCountRow['count(*)'] . "<br><br>";

echo "Books by Category:<br>";

#retreive number of books by genre
$bookGenreSQL = "SELECT Genre, count(*) FROM Books GROUP BY Genre;";
$bookGenreResult = mysqli_query($link, $bookGenreSQL);
$bookGenreRowNum = mysqli_num_rows($bookGenreResult);

#display information from database
if($bookGenreRowNum > 0){
    while($bookGenreRow = mysqli_fetch_assoc($bookGenreResult)){
        echo $bookGenreRow['Genre'] . ": " . $bookGenreRow['count(*)'] . "<br>";
    }
}
echo "<br>";

echo "Sales This Year:<br>";

#determine current year and month
$currentYear = date("Y");
$currentMonth = floatval(date('m'));

#retreive sales total for the year
$totalSQL = "SELECT SUM(Total) FROM Shipments WHERE Year = '$currentYear';";
$totalResult = mysqli_query($link, $totalSQL);
$totalRow = mysqli_fetch_assoc($totalResult);

#calculate average sales per month
$average = round(floatval($totalRow['SUM(Total)'])/$currentMonth, 2);

#display values
echo "Average Monthly Sales: $" . $average . "<br>";

echo "Sales by Month:<br>";

#retreive monthly totals
$monthlySQL = "SELECT Month, SUM(Total) FROM Shipments WHERE Year = '$currentYear' GROUP BY Month;";
$monthlyResult = mysqli_query($link, $monthlySQL);
$monthlyRowNum = mysqli_num_rows($bookGenreResult);

#display monthly totals
if($monthlyRowNum > 0){
    while($monthlyRow = mysqli_fetch_assoc($monthlyResult)){
        echo $monthlyRow['Month'] . ": $" . $monthlyRow['SUM(Total)'] . "<br>";
    }
}
echo "<br>";

echo "Books and Number of Reviews:<br>";

#retreive book titles and isbns
$TitleSQL = "SELECT ISBN, Title FROM Books;";
$TitleResult = mysqli_query($link, $TitleSQL);
$TitleRowNum = mysqli_num_rows($TitleResult);

if($TitleRowNum > 0){
    while($bookReviewsRow = mysqli_fetch_assoc($TitleResult)){
        $thisISBN = $bookReviewsRow['ISBN'];

        #retrieve number of reviews for each book
        $ISBNCountSQL = "SELECT count(*) FROM Reviews WHERE ISBN = '$thisISBN';";
        $ISBNCountResult = mysqli_query($link, $ISBNCountSQL);
        $ISBNCountRow = mysqli_fetch_assoc($ISBNCountResult);

        #display review totals
        echo $bookReviewsRow['Title'] . ": " . $ISBNCountRow['count(*)'] . "<br>";
    }
}
echo "<br><br>";
db_close();
?>

<!-- Form to exit back to Home page -->
<form action = "B3Home.php" method = "post">
    <input type = 'submit' name = 'leaveAdmin' value = 'Exit'>
</form>

</html>