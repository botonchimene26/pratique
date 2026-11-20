<?php
require 'config.php';
$erreur = "";
if (isset($_POST['connecter'])) {
    $pseudo = trim($_POST['pseudo']);
    $mdp = $_POST['mot_de_passe'];

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE pseudo = ?");
    $stmt->execute([$pseudo]);
    $user = $stmt->fetch();

    if ($user && password_verify($mdp, $user['mot_de_passe'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['pseudo'] = $user['pseudo'];
        header("Location: index.php");
        exit;
    } else {
        $erreur = "Pseudonyme ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"><title>Connexion</title></head>
<body>
<h2>Connexion</h2>
<?php if($erreur) echo "<p style='color:red'>$erreur</p>"; ?>
<?php if(isset($_GET['success'])) echo "<p style='color:green'>Inscription réussie, connectez-vous.</p>"; ?>
<form method="POST">
  Pseudonyme : <br><input type="text" name="pseudo" required><br><br>
  Mot de passe : <br><input type="password" name="mot_de_passe" required><br><br>
  <button type="submit" name="connecter">Se connecter</button>
</form>
<a href="index.php">Retour</a>
</body>
</html>