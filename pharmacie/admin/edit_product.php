<?php

include("../config/db.php");
include("../includes/header.php");

$id = $_GET['id'];

$sql = "SELECT * FROM productes WHERE id='$id'";

$result = mysqli_query($conn,$sql);

$row = mysqli_fetch_assoc($result);

if(isset($_POST['update'])){

$nom = $_POST['nom'];
$categorie = $_POST['categorie'];
$prix = $_POST['prix'];
$quantite = $_POST['quantite'];
$expiration = $_POST['expiration'];

$update = "UPDATE productes SET

nom='$nom',
categorie='$categorie',
prix='$prix',
quantite='$quantite',
expiration='$expiration'

WHERE id='$id'";

mysqli_query($conn,$update);

header("Location: products.php");

}

?>

<div class="card p-4">

<h2>Modifier Produit</h2>

<form method="POST">

<input type="text"
name="nom"
value="<?= $row['nom']; ?>"
class="form-control mb-3">

<input type="text"
name="categorie"
value="<?= $row['categorie']; ?>"
class="form-control mb-3">

<input type="number"
name="prix"
value="<?= $row['prix']; ?>"
class="form-control mb-3">

<input type="number"
name="quantite"
value="<?= $row['quantite']; ?>"
class="form-control mb-3">

<input type="date"
name="expiration"
value="<?= $row['expiration']; ?>"
class="form-control mb-3">

<button name="update"
class="btn btn-success">

Modifier

</button>

</form>

</div>

<?php include("../includes/footer.php"); ?>