<?php

include("../config/db.php");

$search = $_GET['search'];

$sql = "SELECT * FROM productes

WHERE nom LIKE '%$search%'

OR categorie LIKE '%$search%'";

$result = mysqli_query($conn,$sql);

?>

<!DOCTYPE html>
<html>

<head>

<title>Résultat</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:#111;
color:white;
}

.table{
color:white;
}
.back-link {
    color: #aaa;
    text-decoration: none;
    font-size: 0.85rem;
    transition: color 0.2s;
}


</style>

</head>

<body>

<div class="container mt-5">
<a href="home.php" class="back-link">← Retour au Home</a>
<h2 class="text-success mb-4">
Résultat Recherche
</h2>

<table class="table table-bordered">

<tr>

<th>Nom</th>
<th>Catégorie</th>
<th>Prix</th>
<th>Quantité</th>
<th>Action</th>
</tr>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?= $row['nom']; ?></td>
<td><?= $row['categorie']; ?></td>
<td><?= $row['prix']; ?></td>
<td><?= $row['quantite']; ?></td>
<td> <a href="achate.php?id=<?=$row['id']?>" class="btn btn-sm btn-success">Achete</a></td>
</tr>

<?php } ?>

</table>

</div>

</body>

</html>