<?php

include("../config/db.php");

if(isset($_POST['register'])){

$name=$_POST['name'];
$email=$_POST['email'];
$password=$_POST['password'];

$sql="INSERT INTO useres
(name,email,password,role)

VALUES(
'$name',
'$email',
'$password',
'user'
)";

mysqli_query($conn,$sql);

header("Location: login.php");

}
?>

<!DOCTYPE html>
<html>

<head>

<title>Register</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-dark">

<div class="container mt-5">

<div class="card p-4 col-md-4 mx-auto">

<h2 class="text-success text-center">
Créer un compte
</h2>

<form method="POST">

<input type="text"
name="name"
class="form-control mb-3"
placeholder="Nom">

<input type="email"
name="email"
class="form-control mb-3"
placeholder="Email">

<input type="password"
name="password"
class="form-control mb-3"
placeholder="Mot de passe">

<button name="register"
class="btn btn-success w-100">

S'inscrire

</button>

</form>

<br>

<a href="login.php"
class="text-light">

Déjà un compte ?

</a>

</div>

</div>

</body>
</html>