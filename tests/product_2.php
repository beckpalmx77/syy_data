<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
      integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
<?php

//include("../config/connect_db.php");

try {
    $conn = new PDO("mysql:host=localhost;port=3307;dbname=foodorder", "myadmin", "myadmin");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    /* Begin Paging Info */
    $page = 1;

    if (isset($_GET['page'])) {
        $page = filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT);
    }
    $per_page = 4;
    $sqlcount = "select count(*) as total_records from food";
    $stmt = $conn->prepare($sqlcount);
    $stmt->execute();
    $row = $stmt->fetch();
    $total_records = $row['total_records'];
    $total_pages = ceil($total_records / $per_page);
    $offset = ($page - 1) * $per_page;
    /* End Paging Info */

    $sql = "select * from food limit :offset, :per_page";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['offset' => $offset, 'per_page' => $per_page]);

    echo "<table border='1' align='center'>";

    while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {

        echo "<div class='card text-center'>";
        echo "<div class='card-header'>";
        echo "</div>";
        echo "<div class='card-body'>";
        echo "<h5 class='card-title'> " . $row['foodname'] . "</h5>";
        echo "<p class='card-text'>With supporting text below as a natural lead-in to additional content.</p>";
        echo "<a href='#' class='btn btn-primary'>". $row['price'] . "</a>";
        echo "</div>";
        echo "</div>";

        //echo "<tr>";
        //echo "<td>" . $row['foodname'] . "</td>";
        //echo "<td>" . $row['price'] . "</td>";
        //echo "</tr>";
    }

    echo "</table>";

    /* Begin Navigation */
    echo "<table border='1' align='center'>";

    echo "<tr>";

    if ($page - 1 >= 1) {
        //echo "<button type='button' class='btn btn-primary'><a href=" . $_SERVER['PHP_SELF'] . "?page=" . ($page - 1) . ">Previous</a></button>";
        echo "<a href='#' class='btn btn-primary btn-lg active' role='button' aria-pressed='true'>Previous</a>";
        //echo "<td><a href=" . $_SERVER['PHP_SELF'] . "?page=" . ($page - 1) . ">Previous</a></td>";
    }

    if ($page + 1 <= $total_pages) {
        //echo "<button type='button' class='btn btn-primary'><a href=" . $_SERVER['PHP_SELF'] . "?page=" . ($page + 1) . ">Next</a></button>";
        echo "<a href='#' class='btn btn-primary btn-lg active' role='button' aria-pressed='true'>Next</a>";
        //echo "<td><a href=" . $_SERVER['PHP_SELF'] . "?page=" . ($page + 1) . ">Next</a></td>";
    }

    echo "</tr>";

    echo "</table>";
    /* End Navigation */
} catch (PDOException $e) {
    echo $e->getMessage();
}





