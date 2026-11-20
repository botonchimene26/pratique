<?php
function estAnagramme($s1, $s2) {
    // Nettoyage : minuscules, suppression des espaces
    $s1 = strtolower(str_replace(' ', '', $s1));
    $s2 = strtolower(str_replace(' ', '', $s2));

    // Si longueurs différentes, ce n'est pas un anagramme
    if (strlen($s1) !== strlen($s2)) {
        return false;
    }

    // On transforme en tableau, on trie, on compare
    $t1 = str_split($s1);
    $t2 = str_split($s2);
    sort($t1);
    sort($t2);

    return $t1 === $t2;
}

function estValide($chaine) {
    // Uniquement lettres, sans chiffres ni caractères spéciaux
    return preg_match("/^[a-zA-Z]+$/", $chaine);
}
?>