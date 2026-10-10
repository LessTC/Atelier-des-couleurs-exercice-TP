<?php
session_start();

// Sécurité : si pas de connexion via formulaire, on renvoie vers le formulaire // 
if (empty($_SESSION['admin'])) {
    header('Location: connexion.php');
    exit;
}
// Sécurité : jeton CSRF // 
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

$fichier = __DIR__ . '/../horaires.json';
$horaires = json_decode(file_get_contents($fichier), true);

$message = '';
$erreurs = [];

// Sécurité : vérification que c'est l'admin qui a bien envoyé la requête //
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
        http_response_code(403);
        exit('Requête refusée.');
    }

    $format = '/^([01]\d|2[0-3]):[0-5]\d$/';

    foreach ($horaires as $i => $jour) {
        $ferme = isset($_POST['ferme'][$i]);
        $ouverture = $_POST['ouverture'][$i] ?? '';
        $fermeture = $_POST['fermeture'][$i] ?? '';

        if ($ferme) {
            $horaires[$i]['ouverture'] = null;
            $horaires[$i]['fermeture'] = null;
            continue;
        }

        if (!preg_match($format, $ouverture) || !preg_match($format, $fermeture)) {
            $erreurs[] = $jour['jour'] . ' : heures invalides.';
        } elseif ($ouverture >= $fermeture) {
            $erreurs[] = $jour['jour'] . " : l'ouverture doit être avant la fermeture.";
        } else {
            $horaires[$i]['ouverture'] = $ouverture;
            $horaires[$i]['fermeture'] = $fermeture;
        }
    }
    if (empty($erreurs)) {
        $json = json_encode($horaires, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($fichier, $json);
        $message = 'Les horaires ont été mis à jour.';
    }
}
?>



<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Horaires – Administration</title>
    <link rel="stylesheet" href="../style.css" />
</head>

<body>
    <main>
        <section>
            <h1>Modifier les horaires</h1>
            <p>
                <a href="../index.html">Voir le site</a> –
                <a href="deconnexion.php">Se déconnecter</a>
            </p>

            <?php if ($message !== ''): ?>
                <p class="succes"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

            <?php foreach ($erreurs as $erreur): ?>
                <p class="erreur"><?= htmlspecialchars($erreur) ?></p>
            <?php endforeach; ?>

            <form method="post">
                <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>" />

                <?php foreach ($horaires as $i => $jour): ?>
                    <fieldset>
                        <legend><?= htmlspecialchars($jour['jour']) ?></legend>

                        <label>
                            Ouverture
                            <input type="time" name="ouverture[<?= $i ?>]"
                                value="<?= htmlspecialchars($jour['ouverture'] ?? '') ?>" />
                        </label>

                        <label>
                            Fermeture
                            <input type="time" name="fermeture[<?= $i ?>]"
                                value="<?= htmlspecialchars($jour['fermeture'] ?? '') ?>" />
                        </label>

                        <label>
                            <input type="checkbox" name="ferme[<?= $i ?>]"
                                <?= $jour['ouverture'] === null ? 'checked' : '' ?> />
                            Fermé
                        </label>
                    </fieldset>
                <?php endforeach; ?>

                    <br/>
                <button type="submit" class="bouton">Enregistrer</button>
            </form>
        </section>
    </main>
</body>

</html>