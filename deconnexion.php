<?php
session_start();
// La déconnexion détruit UNIQUEMENT la session.
// Elle ne supprime PAS l'utilisateur de la base de données.
session_unset();
session_destroy();
header("Location: index.php");
exit;
?>