<?php
session_start();

if(!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../auth/login_admin.php");
    exit();
}

include("../includes/header.php");
?>



<div class="container mt-4">

    <!-- Welcome bar -->
    <div class="welcome-bar">
        <span>Bienvenue, <strong><?= htmlspecialchars($_SESSION['name']) ?></strong> — Administrateur</span>
        <a href="../auth/logout.php" class="logout-link">⎋ Déconnexion</a>
    </div>

    <h1 class="dashboard-title">📊 Dashboard Admin</h1>

    <div class="row g-4">

        <div class="col-md-4">
            <div class="stat-card">
                <span class="icon">💊</span>
                <h3>Produits</h3>
                <a href="products.php" class="btn btn-success w-100">
                    Voir Produits
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card">
                <span class="icon">➕</span>
                <h3>Ajouter Produit</h3>
                <a href="add_product.php" class="btn btn-success w-100">
                    Ajouter
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card">
                <span class="icon">📦</span>
                <h3>Stock</h3>
                <a href="stock.php" class="btn btn-success w-100">
                    Voir Stock
                </a>
            </div>
        </div>

    </div>

</div>

<?php include("../includes/footer.php"); ?>