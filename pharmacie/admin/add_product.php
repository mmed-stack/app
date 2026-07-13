<?php
session_start();

if(!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../auth/login_admin.php");
    exit();
}

include("../config/db.php");
include("../includes/header.php");

$message = "";
$message_type = "";

if(isset($_POST['save'])){

    $nom        = mysqli_real_escape_string($conn, trim($_POST['nom']));
    $categorie  = mysqli_real_escape_string($conn, trim($_POST['categorie']));
    $prix       = mysqli_real_escape_string($conn, $_POST['prix']);
    $quantite   = mysqli_real_escape_string($conn, $_POST['quantite']);
    $expiration = mysqli_real_escape_string($conn, $_POST['expiration']);

    // Validation
    if(empty($nom) || empty($categorie) || empty($prix) || empty($quantite)){
        $message      = "Veuillez remplir tous les champs obligatoires.";
        $message_type = "danger";

    } elseif($prix <= 0) {
        $message      = "Le prix doit être supérieur à 0.";
        $message_type = "danger";

    } elseif($quantite < 0) {
        $message      = "La quantité ne peut pas être négative.";
        $message_type = "danger";

    } else {
        $sql = "INSERT INTO productes (nom, categorie, prix, quantite, expiration)
                VALUES ('$nom', '$categorie', '$prix', '$quantite', '$expiration')";

        if(mysqli_query($conn, $sql)){
            $message      = "✅ Produit \"" . htmlspecialchars($nom) . "\" ajouté avec succès !";
            $message_type = "success";
        } else {
            $message      = "❌ Erreur lors de l'ajout. Réessayez.";
            $message_type = "danger";
        }
    }
}
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

input, select {
    background: #222 !important;
    color: white !important;
    border: 1px solid #333 !important;
    border-radius: 10px !important;
}

input:focus, select:focus {
    border-color: #28a745 !important;
    box-shadow: 0 0 0 2px rgba(40,167,69,0.15) !important;
}

input::placeholder {
    color: #555 !important;
}

select option {
    background: #222;
}

.page-title {
    color: #28a745;
    font-weight: bold;
    border-bottom: 2px solid #28a745;
    padding-bottom: 10px;
    margin-bottom: 25px;
}

.field-label {
    font-size: 0.8rem;
    color: #aaa;
    margin-bottom: 5px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.btn-save {
    background: #28a745;
    border: none;
    border-radius: 10px;
    color: white;
    padding: 10px 30px;
    font-weight: bold;
    transition: all 0.2s;
    width: 100%;
}

.btn-save:hover {
    background: #218838;
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(40,167,69,0.3);
}

.btn-cancel {
    display: block;
    text-align: center;
    margin-top: 10px;
    color: #aaa;
    text-decoration: none;
    font-size: 0.85rem;
    transition: color 0.2s;
}

.btn-cancel:hover { color: #dc3545; }

.back-link {
    color: #aaa;
    text-decoration: none;
    font-size: 0.85rem;
    transition: color 0.2s;
}

.back-link:hover { color: #28a745; }
</style>

<div class="container mt-4" style="max-width: 580px;">

    <a href="dashboard.php" class="back-link">← Retour au Dashboard</a>

    <div class="card p-4 mt-3">

        <h2 class="page-title">➕ Ajouter un Produit</h2>

        <?php if($message != ""): ?>
            <div class="alert alert-<?= $message_type ?> rounded-3">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST" novalidate>

            <div class="mb-3">
                <label class="field-label">Nom du produit *</label>
                <input type="text"
                       name="nom"
                       class="form-control"
                       placeholder="Ex: Paracétamol 500mg"
                       value="<?= isset($_POST['nom']) && $message_type=='danger' ? htmlspecialchars($_POST['nom']) : '' ?>"
                       required>
            </div>

            <div class="mb-3">
                <label class="field-label">Catégorie *</label>
                <select name="categorie" class="form-control">
                    <option value="">-- Choisir une catégorie --</option>
                    <?php
                    $categories = ['Antibiotique','Antidouleur','Vitamine','Sirop','Crème','Injection','Autre'];
                    foreach($categories as $cat):
                        $selected = (isset($_POST['categorie']) && $_POST['categorie'] == $cat) ? 'selected' : '';
                    ?>
                        <option value="<?= $cat ?>" <?= $selected ?>><?= $cat ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="row">
                <div class="col-6 mb-3">
                    <label class="field-label">Prix </label>
                    <input type="number"
                           name="prix"
                           class="form-control"
                           placeholder="0.00"
                           
                           value="<?= isset($_POST['prix']) && $message_type=='danger' ? htmlspecialchars($_POST['prix']) : '' ?>"
                           required>
                </div>

                <div class="col-6 mb-3">
                    <label class="field-label">Quantité *</label>
                    <input type="number"
                           name="quantite"
                           class="form-control"
                           placeholder="0"
                           min="0"
                           value="<?= isset($_POST['quantite']) && $message_type=='danger' ? htmlspecialchars($_POST['quantite']) : '' ?>"
                           required>
                </div>
            </div>

            <div class="mb-4">
                <label class="field-label">Date d'expiration</label>
                <input type="date"
                       name="expiration"
                       class="form-control"
                       value="<?= isset($_POST['expiration']) && $message_type=='danger' ? htmlspecialchars($_POST['expiration']) : '' ?>">
            </div>

            <button type="submit" name="save" class="btn-save">
                💾 Enregistrer le produit
            </button>

            <a href="products.php" class="btn-cancel">Annuler</a>

        </form>

    </div>
</div>

<?php include("../includes/footer.php"); ?>