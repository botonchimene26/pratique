<?php
require 'config.php';
require 'fonctions.php';

$message = "";
$classe = "";
$chaine1 = "";
$chaine2 = "";

if (isset($_POST['verifier'])) {
    $chaine1 = trim($_POST['string1'] ?? '');
    $chaine2 = trim($_POST['string2'] ?? '');

    if (empty($chaine1) || empty($chaine2)) {
        $message = "Veuillez remplir les deux champs.";
        $classe = "erreur";
    } elseif (!estValide($chaine1) || !estValide($chaine2)) {
        $message = "Caractères non autorisés : utilisez uniquement des lettres.";
        $classe = "erreur";
    } else {
        $isAnagramme = estAnagramme($chaine1, $chaine2);
        $resultat = $isAnagramme ? 'OUI' : 'NON';
        $classe = $isAnagramme ? 'succes' : 'echec';
        $message = $isAnagramme
            ? "✅ Ce sont des anagrammes !"
            : "❌ Ce ne sont pas des anagrammes.";

        // Enregistrement en base
        $user_id = $_SESSION['user_id'] ?? null;
        $stmt = $pdo->prepare("INSERT INTO verifications (utilisateur_id, chaine1, chaine2, resultat) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $chaine1, $chaine2, $resultat]);
    }
}

// Récupération de l'historique : personnel si connecté, sinon tout
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM verifications WHERE utilisateur_id = ? ORDER BY date_verification DESC");
    $stmt->execute([$_SESSION['user_id']]);
} else {
    $stmt = $pdo->query("SELECT * FROM verifications ORDER BY date_verification DESC LIMIT 20");
}
$historique = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Vérificateur d'anagrammes</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>

<h2>Vérificateur d'anagrammes</h2>

<?php if(isset($_SESSION['pseudo'])): ?>
  <p>Connecté en tant que <b><?= htmlspecialchars($_SESSION['pseudo']) ?></b> | <a href="deconnexion.php">Déconnexion</a></p>
<?php else: ?>
  <p><a href="inscription.php">Inscription</a> | <a href="connexion.php">Connexion</a></p>
<?php endif; ?>

<?php if($message): ?><p class="<?= $classe ?>"><?= $message ?></p><?php endif; ?>

<form method="POST">
  <label>String 1</label><br>
  <input type="text" name="string1" value="<?= htmlspecialchars($chaine1) ?>" class="<?= $classe ?>" placeholder="Ex: anagram"><br>
  <label>String 2</label><br>
  <input type="text" name="string2" value="<?= htmlspecialchars($chaine2) ?>" class="<?= $classe ?>" placeholder="Ex: nagaram"><br><br>
  <button type="submit" name="verifier">Soumettre</button>
</form>

<h3>Historique des vérifications</h3>
<table>
  <tr><th>String 1</th><th>String 2</th><th>Résultat</th><th>Date</th></tr>
  <?php foreach($historique as $h): ?>
  <tr>
    <td><?= htmlspecialchars($h['chaine1']) ?></td>
    <td><?= htmlspecialchars($h['chaine2']) ?></td>
    <td><?= $h['resultat'] == 'OUI' ? '✅ Oui' : '❌ Non' ?></td>
    <td><?= $h['date_verification'] ?></td>
  </tr>
  <?php endforeach; ?>
</table>

</body>
</html>