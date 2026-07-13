<?php

include("../config/db.php");

$id = $_GET['id'];

$sql = "DELETE FROM productes WHERE id='$id'";

mysqli_query($conn,$sql);

header("Location: products.php");

?>