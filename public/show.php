<?php
session_start();

    require_once __DIR__ . "/../functions/db.php";
    require_once __DIR__ . "/../functions/helpers.php";

    // 1. Si l'identifiant du film envoyé dans la barre d'url n'existe pas,
    if ( !isset($_GET['film_id']) || empty($_GET['film_id']) ) {
        // Alors, effectuer une redirection vers la page index
        // Puis, arrêter l'exécution du script.
        header("Location: index.php");
        die();
    }
        
    // Dans le cas contraire,
    // 2. Récupérer l'identifiant du film depuis la barre d'url ($_GET),
    // Protéger le serveur contre les failles xss,
    // Convertir l'identifiant en entier.
    $filmId = (int) htmlspecialchars($_GET['film_id']);


    // 3. Etablir une connexion avec la base de données
    // Afin de vérifier si l'identifiant correspond à un film qui existe vraiment
    // Puis récupérer le film.
    $film = getFilm($filmId);

    // 4. Si le film n'existe pas,
    if ( false === $film) {
        // Alors, effectuer une redirection vers la page index
        // Puis, arrêter l'exécution du script.
        header("Location: index.php");
        die();        
    }

    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>
<?php
    $title = "Les détails de ce film";
    $description = "Les détails de ce film";
    $keywords = "Cinema, répertoire, lire, film, dwwm22";
?>
<?php include_once __DIR__ . "/../partials/head.php"; ?>

    <?php include_once __DIR__ . "/../partials/nav.php"; ?>

        <!-- Main: Le contenu spécifique à cette page -->
        <main class="container">
            <h1 class="text-center my-3 display-5">Les détails de ce film</h1>

            <p class="text-center my-4">
                <small>
                    Ajouté le <?= (new DateTime($film['created_at']))->format('d/m/Y \à H:i:s'); ?>
                </small>
                <br>
                <small>
                    <?php if(isset($film['updated_at']) && !empty($film['updated_at'])) : ?>
                        Modifié le <?= (new DateTime($film['updated_at']))->format('d/m/Y \à H:i:s'); ?>
                    <?php endif ?>
                </small>
            </p>

            <div class="container">
                <div class="row">
                    <div class="col-md-6 mx-auto">
                        <article class="film-card bg-white p-4 rounded shadow mb-4">
                            <h2>Titre: <?= htmlspecialchars($film['title']); ?></h2>
                            <p>Note: <?= isset($film['rating']) && $film['rating'] !== "" ? displayStars((float) htmlspecialchars($film['rating'])) : 'Non renseignée'; ?></p>
                            <p>Commentaire: <?= isset($film['comment']) && $film['comment'] !== "" ? htmlspecialchars($film['comment']) : 'Non renseigné'; ?></p>
                            <hr>
                            <div class="d-flex justify-content-start align-items-center gap-2">
                                <a href="edit.php?film_id=<?= htmlspecialchars($film['id']); ?>" class="btn btn-sm btn-secondary">Modifier</a>
                                <form action="/delete.php" method="post">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
                                    <input type="hidden" name="honey_pot" value="">
                                    <input type="hidden" name="film_id" value="<?= htmlspecialchars($film['id']); ?>">
                                    <input type="submit" class="btn btn-sm btn-danger" value="Supprimer" onclick="return confirm('Vous êtes sur de supprimer ce film');">
                                </form>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </main>

    <?php include_once __DIR__ . "/../partials/footer.php"; ?>

<?php include_once __DIR__ . "/../partials/foot.php"; ?>
