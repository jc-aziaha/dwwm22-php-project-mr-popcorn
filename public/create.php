<?php
session_start();

    // var_dump($_SERVER); 
    // die();

    /*
     * ----------------------------------------------------------------
     * Traitement des données provenant du formulaire 
     * ---------------------------------------------------------------- 
     */

    // 1. Si les données du formulaire sont envoyées via la méthode POST,
    if ( $_SERVER['REQUEST_METHOD'] === "POST" ) {
    
        // Alors,
        // 2. Protéger le serveur contre les failles de sécurité
        // 2a. Les failles de type csrf
        if ( 
            !isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) ||
            empty($_POST['csrf_token'])  || empty($_SESSION['csrf_token'])  ||
            $_POST['csrf_token'] !== $_SESSION['csrf_token']
        ) {
            // Effectuer une redirection vers la page de laquelle proviennent les informations
            // Puis arrêter l'exécution du script.
            header('Location: create.php');
            die();
        }
        unset($_SESSION['csrf_token']);
        unset($_POST['csrf_token']);

        
        // 2b. Les robots spameurs
        // Si le pot de miel n'existe pas ou qu'il n'est pas vide,
        if ( !isset($_POST['honey_pot']) || !empty($_POST['honey_pot']) ) {
            // Effectuer une redirection vers la page de laquelle proviennent les informations
            // Puis arrêter l'exécution du script.
            header('Location: create.php');
            die();
        }
        unset($_POST['honey_pot']);
        
        // 3. Procéder à la validation des données du formulaire
        $formErrors = [];

        // Si le titre est déclaré et différent de null
        if (isset($_POST['title'])) {
            $title = trim($_POST['title']);

            if ("" === $title) {
                $formErrors['title'] = "Le titre est obligatoire.";
            } else if( mb_strlen($title) > 255 ) {
                $formErrors['title'] = "Le titre ne doit pas dépasser 255 caractères.";
            }
        }

        // Note
        if (isset($_POST['rating']) && $_POST['rating'] !== "") {
            $rating = trim($_POST['rating']); 

            if ( !is_numeric($rating) ) {
                $formErrors['rating'] = "La note doit être un nombre.";
            } else if ( floatval($rating) < 0 || floatval($rating) > 5 ) {
                $formErrors['rating'] = "La note doit être comprise entre 0 et 5.";
            }
        }

        // Comment
        if (isset($_POST['comment']) && $_POST['comment'] !== "") {
            $comment = trim($_POST['comment']); 

            if ( mb_strlen($comment) > 1000 ) {
                $formErrors['comment'] = "Le commentaire ne doit pas dépasser 1000 caractères.";
            }
        }

        // 4. S'il existe au moins une erreur détectée par le système,
        if ( count($formErrors) > 0 ) {
            // Alors,
            // 4a. Sauvegarder les messages d'erreurs en session, pour affichage à l'écran de l'utilisateur
            $_SESSION['form_errors'] = $formErrors;

            // 4b. Sauvegarder les anciennes données provenant du formulaire en session

            // 4c. Effectuer une redirection vers la page de laquelle proviennent les informations
            // Puis arrêter l'exécution du script.
            header('Location: create.php');
            die();
        }

        die('Continuer la partie');


        // 5. Dans le cas contraire,
        // 5a. Arrondir la note à un chiffre après la virgule,

        // 6. Etablir une connexion avec la base de données

        // 7. Effectuer la requête d'insertion du nouveau film dans la table prévue (film)

        // 8. Générer le message flash de succès

        // 9. Effectuer une redirection vers la page listant les films ajoutés (index.php)
        // Puis arrêter l'exécution du script.
    }

    // Générons et sauvegardons le jéton de sécurité en session
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>
<?php
    $title = "Nouveau film";
    $description = "Ajout d'un nouveau film";
    $keywords = "Cinema, répertoire, ajout, nouveau, film, dwwm22";
?>
<?php include_once __DIR__ . "/../partials/head.php"; ?>

    <?php include_once __DIR__ . "/../partials/nav.php"; ?>

        <!-- Main: Le contenu spécifique à cette page -->
        <main class="container">
            <h1 class="text-center my-3 display-5">Nouveau film</h1>

            <!-- Formulaire d'ajout d'un nouveau film -->
            <div class="container mt-3">
                <div class="row">
                    <div class="col-md-8 col-lg-4 mx-auto p-4 bg-white shadow rounded">

                        <?php if(isset($_SESSION['form_errors']) && !empty($_SESSION['form_errors'])) : ?>
                            <div class="alert alert-danger" role="alert">
                                <ul>
                                    <?php foreach($_SESSION['form_errors'] as $error) : ?>
                                        <li><?= $error; ?></li>
                                    <?php endforeach ?>
                                    <?php unset($_SESSION['form_errors']); ?>
                                </ul>
                            </div>
                        <?php endif ?>

                        <form method="post">
                            <div class="mb-3">
                                <label for="title">Titre <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control" autofocus required>
                            </div>
                            <div class="mb-3">
                                <label for="rating">Note / 5</label>
                                <input type="number" min="0" max="5" step="0.5" inputmode="decimal" name="rating" id="rating" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="comment">Laissez un commentaire</label>
                                <textarea name="comment" id="comment" class="form-control" rows="4"></textarea>
                            </div>
                            <input type="hidden" name="csrf_token" value="<?=$_SESSION['csrf_token'];?>">
                            <input type="hidden" name="honey_pot" value="">
                            <div>
                                <input formnovalidate type="submit" value="Ajouter" class="w-100 btn btn-primary shadow">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>

    <?php include_once __DIR__ . "/../partials/footer.php"; ?>

<?php include_once __DIR__ . "/../partials/foot.php"; ?>