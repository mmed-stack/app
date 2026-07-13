<!DOCTYPE html>
<html>

<head>

<title>Recherche</title>

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

<div class="card p-4">
<a href="home.php" class="back-link">← Retour au Home</a>
<h2 class="text-success">
Recherche Médicament
</h2>

<form action="result.php" method="GET">

<input type="text"
name="search"
class="form-control mb-3"
placeholder="Nom ou catégorie">

<button class="btn btn-success">

Rechercher

</button>

</form>

</div>

</div>

</body>

</html>