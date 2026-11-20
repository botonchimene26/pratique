<?php
require 'config.php';
$erreur = "";
if (isset($_POST['inscrire'])) {
    $pseudo = trim($_POST['pseudo']);
    $mdp = $_POST['mot_de_passe'];
    $confirm = $_POST['confirmation'];

    if (empty($pseudo) || empty($mdp)) {
        $erreur = "Tous les champs sont obligatoires.";
    } elseif ($mdp !== $confirm) {
        $erreur = "Les mots de passe ne correspondent pas.";
    } else {
        // Vérifier disponibilité du pseudo
        $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE pseudo = ?");
        $stmt->execute([$pseudo]);
        if ($stmt->fetch()) {
            $erreur = "Ce pseudonyme est déjà pris.";
        } else {
            // Hachage sécurisé du mot de passe
            $hash = password_hash($mdp, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO utilisateurs (pseudo, mot_de_passe) VALUES (?, ?)");
            $stmt->execute([$pseudo, $hash]);
            header("Location: connexion.php?success=1");
            exit;
        }
    }
}
?>
<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"><title>Inscription</title></head>
<body>
<h2>Inscription</h2>
<?php if($erreur) echo "<p style='color:red'>$erreur</p>"; ?>
<form method="POST">
  Pseudonyme : <br><input type="text" name="pseudo" required><br><br>
  Mot de passe : <br><input type="password" name="mot_de_passe" required><br><br>
  Confirmation : <br><input type="password" name="confirmation" required><br><br>
  <button type="submit" name="inscrire">S'inscrire</button>
</form>
<a href="index.php">Retour</a>
</body></html>