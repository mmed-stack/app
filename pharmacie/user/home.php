<?php

session_start();

if(!isset($_SESSION['role'])){

header("Location: ../auth/login.php");

}

?>

<!DOCTYPE html>
<html>

<head>

<title>User Home</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:#111;
color:white;
}

.card{
background:#1e1e1e;
border:none;
border-radius:15px;
}
.logout-link {
    color: #dc3545;
    text-decoration: none;
    font-size: 0.85rem;
    border: 1px solid #dc3545;
    padding: 5px 12px;
    border-radius: 8px;
    transition: all 0.2s;
}
.logout-link:hover {
    background: #dc3545;
    color: white;
}



</style>

</head>

<body>

<div class="container mt-5">

<div class="card p-5 text-center">
 <a href="../auth/logout.php" class="logout-link">⎋ Déconnexion</a>
<h1 class="text-success">
Bienvenue
<?= $_SESSION['name']; ?>
💊
</h1>

<p>
Rechercher un médicament facilement.
</p>

<a href="search.php"
class="btn btn-success">

Rechercher Médicament

</a>

</div>

</div>

</body>

</html>