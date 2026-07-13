<?php
include("../config/db.php");

// 1. التحقق من إرسال معرف المنتج عبر الرابط
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Produit non spécifié. <a href='home.php'>Retour</a>");
}

$product_id = mysqli_real_escape_string($conn, $_GET['id']);

// 2. جلب بيانات المنتج المحدد فقط
$sql = "SELECT * FROM productes WHERE id = '$product_id'";
$result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    die("Produit introuvable. <a href='home.php'>Retour</a>");
}

// 3. معالجة عملية الشراء عند الضغط على زر التأكيد
$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $quantite_achetee = (int)$_POST['quantite_achetee'];

    // التحقق من توفر الكمية المطلوبة
    if ($quantite_achetee <= 0) {
        $message = "<div class='alert alert-danger'>Veuillez saisir une quantité valide.</div>";
    } elseif ($quantite_achetee > $product['quantite']) {
        $message = "<div class='alert alert-danger'>Désolé, quantité insuffisante en stock !</div>";
    } else {
        // تحديث الكمية في قاعدة البيانات (خصم الكمية المشترة)
        $nouvelle_quantite = $product['quantite'] - $quantite_achetee;
        $update_sql = "UPDATE productes SET quantite = $nouvelle_quantite WHERE id = '$product_id'";
        
        if (mysqli_query($conn, $update_sql)) {
            $message = "<div class='alert alert-success'>Achat réussi ! Merci pour votre confiance.</div>";
            // تحديث البيانات المعروضة في الصفحة بعد الخصم
            $product['quantite'] = $nouvelle_quantite;
        } else {
            $message = "<div class='alert alert-danger'>Erreur lors de l'achat.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Achat de Produit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #111; color: white; }
        .card { background: #222; color: white; border: 1px solid #333; }
        .back-link { color: #aaa; text-decoration: none; font-size: 0.85rem; transition: color 0.2s; }
    </style>
</head>
<body>

<div class="container mt-5" style="max-width: 600px;">
    <a href="javascript:history.back()" class="back-link">← Retour aux résultats</a>
    
    <h2 class="text-success mt-3 mb-4">Confirmer l'achat</h2>

    <?= $message; ?>

    <div class="card p-4">
        <h4 class="text-warning mb-3"><?= htmlspecialchars($product['nom']); ?></h4>
        
        <p><strong>Catégorie:</strong> <?= htmlspecialchars($product['categorie']); ?></p>
        <p><strong>Prix unitaire:</strong> <span class="text-info fs-5"><?= $product['prix']; ?> €</span></p>
        <p><strong>En stock:</strong> <?= $product['quantite']; ?> pièces</p>
        
        <hr style="background-color: #444;">

        <?php if ($product['quantite'] > 0): ?>
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="quantite_achetee" class="form-label">Quantité à acheter :</label>
                    <input type="number" name="quantite_achetee" id="quantite_achetee" class="form-control bg-dark text-white" value="1" min="1" max="<?= $product['quantite']; ?>" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Confirmer et Payer</button>
            </form>
        <?php else: ?>
            <div class="alert alert-warning text-center">Rupture de stock (Épuisé)</div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>