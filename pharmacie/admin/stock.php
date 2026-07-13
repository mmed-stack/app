<?php
session_start();

if(!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../auth/login_admin.php");
    exit();
}

include("../config/db.php");
include("../includes/header.php");

$sql    = "SELECT * FROM productes ORDER BY quantite ASC";
$result = mysqli_query($conn, $sql);
$total  = mysqli_num_rows($result);

// Compteurs pour les stats
$out = $low = $ok = $expired = 0;
$rows = [];
$today = new DateTime();

while($row = mysqli_fetch_assoc($result)){
    $qty = (int)$row['quantite'];
    if($qty == 0)      $out++;
    elseif($qty <= 5)  $low++;
    else               $ok++;

    if(!empty($row['expiration'])){
        $exp = new DateTime($row['expiration']);
        if($exp < $today) $expired++;
    }

    $rows[] = $row;
}
?>

<style>
body { background: #111; color: white; }

.card { background: #1e1e1e; border: none; border-radius: 15px; }

input {
    background: #222 !important;
    color: white !important;
    border: 1px solid #333 !important;
    border-radius: 10px !important;
}

input:focus {
    border-color: #28a745 !important;
    box-shadow: 0 0 0 2px rgba(40,167,69,0.15) !important;
}

.page-title {
    color: #28a745;
    font-weight: bold;
    border-bottom: 2px solid #28a745;
    padding-bottom: 10px;
    margin-bottom: 25px;
}

/* Stat cards */
.stat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
    margin-bottom: 25px;
}

.stat-box {
    background: #1e1e1e;
    border-radius: 12px;
    padding: 18px;
    text-align: center;
    border-top: 3px solid transparent;
}

.stat-box.green  { border-color: #28a745; }
.stat-box.yellow { border-color: #ffc107; }
.stat-box.red    { border-color: #dc3545; }
.stat-box.orange { border-color: #fd7e14; }

.stat-box .num {
    font-size: 2rem;
    font-weight: bold;
    display: block;
}

.stat-box .lbl {
    font-size: 0.72rem;
    color: #777;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.stat-box.green  .num { color: #28a745; }
.stat-box.yellow .num { color: #ffc107; }
.stat-box.red    .num { color: #dc3545; }
.stat-box.orange .num { color: #fd7e14; }

/* Toolbar */
.toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    flex-wrap: wrap;
    gap: 10px;
}

.filter-btns { display: flex; gap: 8px; flex-wrap: wrap; }

.filter-btn {
    background: #1e1e1e;
    border: 1px solid #333;
    color: #aaa;
    border-radius: 20px;
    padding: 5px 14px;
    font-size: 0.78rem;
    cursor: pointer;
    transition: all 0.2s;
}

.filter-btn:hover, .filter-btn.active {
    border-color: #28a745;
    color: #28a745;
}

.search-box {
    padding: 7px 14px !important;
    width: 200px;
}

/* Table */
.table-wrap {
    background: #1e1e1e;
    border-radius: 15px;
    overflow: hidden;
}

.table-wrap table {
    margin: 0;
    color: white;
}

.table-wrap thead th {
    background: #252525;
    color: #28a745;
    border-color: #333;
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 14px 16px;
}

.table-wrap tbody td {
    border-color: #2a2a2a;
    padding: 12px 16px;
    vertical-align: middle;
}

.table-wrap tbody tr:hover { background: #252525; }

/* Badges statut */
.badge-ok      { background: rgba(40,167,69,0.15);  color: #28a745; border: 1px solid #28a745; border-radius: 20px; padding: 3px 12px; font-size: 0.78rem; }
.badge-low     { background: rgba(255,193,7,0.15);   color: #ffc107; border: 1px solid #ffc107; border-radius: 20px; padding: 3px 12px; font-size: 0.78rem; }
.badge-out     { background: rgba(220,53,69,0.15);   color: #dc3545; border: 1px solid #dc3545; border-radius: 20px; padding: 3px 12px; font-size: 0.78rem; }

/* Expiration */
.exp-ok      { color: #aaa; }
.exp-soon    { color: #ffc107; font-weight: bold; }
.exp-expired { color: #dc3545; font-weight: bold; }

/* Progress bar quantité */
.qty-bar {
    height: 5px;
    border-radius: 5px;
    background: #333;
    margin-top: 5px;
}
.qty-fill {
    height: 100%;
    border-radius: 5px;
    transition: width 0.4s;
}

.back-link { color: #aaa; text-decoration: none; font-size: 0.85rem; }
.back-link:hover { color: #28a745; }
</style>

<div class="container mt-4">

    <a href="dashboard.php" class="back-link">← Retour au Dashboard</a>

    <h2 class="page-title mt-3">📦 Gestion du Stock</h2>

    <!-- Stats -->
    <div class="stat-grid">
        <div class="stat-box green">
            <span class="num"><?= $ok ?></span>
            <span class="lbl">✅ Disponible</span>
        </div>
        <div class="stat-box yellow">
            <span class="num"><?= $low ?></span>
            <span class="lbl">⚠ Stock faible</span>
        </div>
        <div class="stat-box red">
            <span class="num"><?= $out ?></span>
            <span class="lbl">❌ Épuisé</span>
        </div>
        <div class="stat-box orange">
            <span class="num"><?= $expired ?></span>
            <span class="lbl">🕒 Expiré</span>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="toolbar">
        <div class="filter-btns">
            <button class="filter-btn active" onclick="filterTable('all', this)">Tous (<?= $total ?>)</button>
            <button class="filter-btn" onclick="filterTable('ok', this)">Disponible</button>
            <button class="filter-btn" onclick="filterTable('low', this)">Stock faible</button>
            <button class="filter-btn" onclick="filterTable('out', this)">Épuisé</button>
            <button class="filter-btn" onclick="filterTable('expired', this)">Expiré</button>
        </div>
        <input type="text" id="searchInput" class="search-box" placeholder="🔍 Rechercher...">
    </div>

    <!-- Table -->
    <div class="table-wrap">
        <table class="table table-bordered mb-0" id="stockTable">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Catégorie</th>
                    <th>Prix (DA)</th>
                    <th>Quantité</th>
                    <th>Expiration</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($rows as $row):
                $qty = (int)$row['quantite'];

                // Statut stock
                if($qty == 0)      { $statusKey = 'out';  $badge = '<span class="badge-out">❌ Épuisé</span>'; }
                elseif($qty <= 5)  { $statusKey = 'low';  $badge = '<span class="badge-low">⚠ Stock faible</span>'; }
                else               { $statusKey = 'ok';   $badge = '<span class="badge-ok">✅ Disponible</span>'; }

                // Expiration
                $expKey   = 'ok';
                $expClass = 'exp-ok';
                $expLabel = htmlspecialchars($row['expiration'] ?? '-');
                if(!empty($row['expiration'])){
                    $expDate  = new DateTime($row['expiration']);
                    $diffDays = (int)$today->diff($expDate)->format('%r%a');
                    if($diffDays < 0){
                        $expClass = 'exp-expired';
                        $expLabel .= ' ❌';
                        $expKey   = 'expired';
                    } elseif($diffDays <= 30){
                        $expClass = 'exp-soon';
                        $expLabel .= ' ⚠';
                    }
                }

                // Barre quantité (max visuel = 100)
                $barPct   = min(100, ($qty / 100) * 100);
                $barColor = $qty == 0 ? '#dc3545' : ($qty <= 5 ? '#ffc107' : '#28a745');
            ?>
                <tr data-status="<?= $statusKey ?>" data-exp="<?= $expKey ?>">
                    <td><strong><?= htmlspecialchars($row['nom']) ?></strong></td>
                    <td><span style="color:#aaa;font-size:0.85rem;"><?= htmlspecialchars($row['categorie'] ?? '-') ?></span></td>
                    <td><?= number_format($row['prix'], 2) ?> DA</td>
                    <td>
                        <?= $qty ?>
                        <div class="qty-bar">
                            <div class="qty-fill" style="width:<?= $barPct ?>%; background:<?= $barColor ?>;"></div>
                        </div>
                    </td>
                    <td class="<?= $expClass ?>"><?= $expLabel ?></td>
                    <td><?= $badge ?></td>
                    <td>
                        <a href="edit_product.php?id=<?= $row['id'] ?>"
                           style="color:#ffc107; font-size:0.85rem; text-decoration:none;">
                            ✏ Modifier
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<script>
function filterTable(type, btn){
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    document.querySelectorAll('#stockTable tbody tr').forEach(row => {
        if(type === 'all'){
            row.style.display = '';
        } else if(type === 'expired'){
            row.style.display = row.dataset.exp === 'expired' ? '' : 'none';
        } else {
            row.style.display = row.dataset.status === type ? '' : 'none';
        }
    });
}

document.getElementById('searchInput').addEventListener('keyup', function(){
    const val = this.value.toLowerCase();
    document.querySelectorAll('#stockTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
    });
});
</script>

<?php include("../includes/footer.php"); ?>