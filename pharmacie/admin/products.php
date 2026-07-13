<?php
session_start();

if(!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../auth/login_admin.php");
    exit();
}

include("../config/db.php");
include("../includes/header.php");

$sql = "SELECT * FROM productes ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
$total = mysqli_num_rows($result);
?>

<style>
body {
    background: #111;
    color: white;
}

.card {
    background: #1e1e1e;
    border: none;
    border-radius: 15px;
}

.table {
    color: white;
}

input {
    background: #222 !important;
    color: white !important;
    border: none !important;
}

.page-title {
    color: #28a745;
    font-weight: bold;
    border-bottom: 2px solid #28a745;
    padding-bottom: 10px;
    margin-bottom: 25px;
}

.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 10px;
}

.badge-total {
    background: #1e1e1e;
    color: #28a745;
    border: 1px solid #28a745;
    border-radius: 20px;
    padding: 5px 15px;
    font-size: 0.85rem;
}

.search-box {
    background: #1e1e1e !important;
    color: white !important;
    border: 1px solid #333 !important;
    border-radius: 10px !important;
    padding: 8px 15px !important;
    width: 220px;
}

.search-box:focus {
    border-color: #28a745 !important;
    outline: none !important;
    box-shadow: 0 0 0 2px rgba(40,167,69,0.15) !important;
}

.table-dark-custom {
    background: #1e1e1e;
    border-radius: 15px;
    overflow: hidden;
}

.table-dark-custom table {
    margin: 0;
    color: white;
}

.table-dark-custom thead th {
    background: #252525;
    color: #28a745;
    border-color: #333;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 14px 16px;
}

.table-dark-custom tbody td {
    border-color: #2a2a2a;
    padding: 12px 16px;
    vertical-align: middle;
}

.table-dark-custom tbody tr:hover {
    background: #252525;
}

/* Stock badges */
.stock-ok   { color: #28a745; font-weight: bold; }
.stock-low  { color: #ffc107; font-weight: bold; }
.stock-out  { color: #dc3545; font-weight: bold; }

/* Expiration */
.exp-ok      { color: #aaa; }
.exp-soon    { color: #ffc107; font-weight: bold; }
.exp-expired { color: #dc3545; font-weight: bold; }

.btn-edit {
    background: transparent;
    border: 1px solid #ffc107;
    color: #ffc107;
    border-radius: 8px;
    padding: 4px 12px;
    font-size: 0.8rem;
    text-decoration: none;
    transition: all 0.2s;
    margin-right: 4px;
}
.btn-edit:hover {
    background: #ffc107;
    color: #111;
}

.btn-delete {
    background: transparent;
    border: 1px solid #dc3545;
    color: #dc3545;
    border-radius: 8px;
    padding: 4px 12px;
    font-size: 0.8rem;
    text-decoration: none;
    transition: all 0.2s;
}
.btn-delete:hover {
    background: #dc3545;
    color: white;
}

.empty-state {
    text-align: center;
    padding: 50px;
    color: #555;
}

.back-link {
    color: #aaa;
    text-decoration: none;
    font-size: 0.85rem;
    transition: color 0.2s;
}
.back-link:hover { color: #28a745; }
</style>

<div class="container mt-4">

    <a href="dashboard.php" class="back-link">← Retour au Dashboard</a>

    <h2 class="page-title mt-3">💊 Liste des Produits</h2>

    <div class="top-bar">
        <span class="badge-total">Total : <?= $total ?> produit(s)</span>
        <div style="display:flex; gap:10px; align-items:center;">
            <input type="text"
                   id="searchInput"
                   class="search-box"
                   placeholder="🔍 Rechercher...">
            <a href="add_product.php" class="btn btn-success" style="border-radius:10px;">
                + Ajouter
            </a>
        </div>
    </div>

    <div class="table-dark-custom">
        <table class="table table-bordered mb-0" id="productsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prix (DA)</th>
                    <th>Quantité</th>
                    <th>Expiration</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

            <?php if($total == 0): ?>
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <p style="font-size:2rem;">📦</p>
                            <p>Aucun produit trouvé.</p>
                            <a href="add_product.php" class="btn btn-success mt-2">Ajouter un produit</a>
                        </div>
                    </td>
                </tr>
            <?php else: ?>

            <?php while($row = mysqli_fetch_assoc($result)):
                // Stock color
                $qty = (int)$row['quantite'];
                if($qty == 0)       $stockClass = 'stock-out';
                elseif($qty <= 5)   $stockClass = 'stock-low';
                else                $stockClass = 'stock-ok';

                // Expiration color
                $expClass = 'exp-ok';
                if(!empty($row['expiration'])){
                    $today     = new DateTime();
                    $expDate   = new DateTime($row['expiration']);
                    $diffDays  = (int)$today->diff($expDate)->format('%r%a');
                    if($diffDays < 0)        $expClass = 'exp-expired';
                    elseif($diffDays <= 30)  $expClass = 'exp-soon';
                }
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['nom']) ?></td>
                    <td><?= number_format($row['prix'], 2) ?> DA</td>
                    <td class="<?= $stockClass ?>">
                        <?= htmlspecialchars($row['quantite']) ?>
                        <?php if($qty == 0) echo ' ⚠️';
                              elseif($qty <= 5) echo ' ⚠'; ?>
                    </td>
                    <td class="<?= $expClass ?>">
                        <?= htmlspecialchars($row['expiration']) ?>
                        <?php if($expClass == 'exp-expired') echo ' ❌';
                              elseif($expClass == 'exp-soon') echo ' ⚠'; ?>
                    </td>
                    <td>
                        <a href="edit_product.php?id=<?= $row['id'] ?>" class="btn-edit">✏ Modifier</a>
                        <a href="delete_product.php?id=<?= $row['id'] ?>"
                           class="btn-delete"
                           onclick="return confirm('Supprimer ce produit ?')">🗑 Supprimer</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            <?php endif; ?>

            </tbody>
        </table>
    </div>

</div>

<script>
// Live search
document.getElementById('searchInput').addEventListener('keyup', function(){
    const val = this.value.toLowerCase();
    const rows = document.querySelectorAll('#productsTable tbody tr');
    rows.forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
    });
});
</script>

<?php include("../includes/footer.php"); ?>