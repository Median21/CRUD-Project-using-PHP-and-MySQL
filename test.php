<?php
    echo "Test";

    include("idk.php");

    echo $testz;


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <link rel="stylesheet" href="CSS/headers.css">
    <title>Document</title>
</head>
<body>

    <?php include("header.php") ?>
    <input type="search" name="filter" id="filter">
    <p id="test"></p>


<script>
        $(document).ready(function () {
            $("#filter").keyup(function () {
                $.ajax({
                    url: "idk.php",
                    type: "post",
                    data: {search: $(this).val()},
                    success: function(result) {
                        console.log(result);
                    }
                })
            })
        })
    </script>

    <script src="JS/global.js"></script>
</body>
</html>