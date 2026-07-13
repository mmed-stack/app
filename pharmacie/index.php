<!DOCTYPE html>
<html>

<head>

<title>Pharmacy Management</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:#0f0f0f;
color:white;
font-family:Arial;
}

.hero{
height:90vh;
display:flex;
justify-content:center;
align-items:center;
text-align:center;
background:linear-gradient(rgba(0,0,0,0.7),
rgba(0,0,0,0.7)),
url('https://images.unsplash.com/photo-1587854692152-cbe660dbde88?q=80&w=1200') center/cover;
}

.hero h1{
font-size:60px;
font-weight:bold;
}

.hero p{
font-size:20px;
color:#ddd;
}

.btn-custom{
padding:12px 30px;
border-radius:10px;
font-size:18px;
}

.section{
padding:80px 0;
}

.card{
background:#1c1c1c;
border:none;
border-radius:15px;
color:white;
transition:0.3s;
}

.card:hover{
transform:scale(1.05);
}

.footer{
background:black;
padding:20px;
text-align:center;
margin-top:50px;
}

</style>

</head>

<body>

<!-- Navbar -->

<nav class="navbar navbar-expand-lg navbar-dark bg-black p-3">

<div class="container">

<a class="navbar-brand text-success fw-bold" href="#">
💊 Pharmacy System
</a>

<div>

<a href="auth/login.php"
class="btn btn-success">

Connexion

</a>

</div>

</div>

</nav>

<!-- Hero Section -->

<section class="hero">

<div>

<h1>
Bienvenue dans notre pharmacie
</h1>

<p>
Système professionnel de gestion des médicaments,
stocks et ventes.
</p>

<a href="auth/login.php"
class="btn btn-success btn-custom mt-3">

Commencer

</a>

</div>

</section>

<!-- Services -->

<section class="section">

<div class="container">

<h2 class="text-center text-success mb-5">
Nos Services
</h2>

<div class="row">

<div class="col-md-4">

<div class="card p-4">

<h3>
💊 Gestion des médicaments
</h3>

<p>
Ajout, modification et suivi des médicaments.
</p>

</div>

</div>

<div class="col-md-4">

<div class="card p-4">

<h3>
📦 Gestion du stock
</h3>

<p>
Surveillance des quantités et alertes de rupture.
</p>

</div>

</div>

<div class="col-md-4">

<div class="card p-4">

<h3>
🛒 Recherche médicaments
</h3>

<p>
Recherche rapide par nom ou catégorie.
</p>

</div>

</div>

</div>

</div>

</section>

<!-- About -->

<section class="section bg-dark">

<div class="container text-center">

<h2 class="text-success mb-4">
Pourquoi ce système ?
</h2>

<p class="fs-5">

Ce système facilite la gestion commerciale
de la pharmacie avec une interface moderne,
sécurisée et rapide.

</p>

</div>

</section>

<!-- Footer -->

<div class="footer">

<p>
© 2026 Pharmacy Management System
</p>

</div>

</body>

</html>