<?php
session_start();
require __DIR__ . '/../config.php';

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $motDePasse = $_POST['mot_de_passe'] ?? '';
    if (password_verify($motDePasse, ADMIN_HASH)) {
        session_regenerate_id(true);
        $_SESSION['admin'] = true;
        header('Location: horaires.php');
        exit;
    }

    $erreur = 'Mot de passe incorrect.';
}
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Connexion – Administration</title>
    <link rel="stylesheet" href="/style.css" />
</head>

<body>
    <main>
        <section>
            <h1>Espace administrateur</h1>

            <?php if ($erreur !== ''): ?>
                <p class="erreur">
                    <?= htmlspecialchars($erreur) ?></p>
            <?php endif; ?>
            <form method="post">
                <label for="mot_de_passe">Mot de passe</label>
                <input
                    type="password"
                    id="mot_de_passe"
                    name="mot_de_passe"
                    required />
                <button type="submit" class="bouton">Se connecter</button>
            </form>
        </section>
    </main>
</body>

</html>