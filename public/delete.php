<?php
session_start();

    require_once __DIR__ . "/../functions/db.php";

    // 1. Si la méthode d'envoi n'est pas POST,
    if ( "POST" !== $_SERVER['REQUEST_METHOD'] ) {
        // Alors, effectuer une redirection vers la page d'accueil
        //  Puis arrêter l'exécution du script.
        header("Location: index.php");
        die();
    }

    
    // 2. Dans le cas contraire,
    // 2a. Récupérer et valider le jéton de sécurité contre les failles de type CSRF.
    if ( 
        !isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) ||
        empty($_POST['csrf_token'])  || empty($_SESSION['csrf_token'])  ||
        $_POST['csrf_token'] !== $_SESSION['csrf_token']
    ) {
        // Effectuer une redirection vers la page de laquelle proviennent les informations
        // Puis arrêter l'exécution du script.
        header("Location: index.php");
        die();
    }
    unset($_SESSION['csrf_token']);
    unset($_POST['csrf_token']);

        
    // 8b. Les robots spameurs
    // Si le pot de miel n'existe pas ou qu'il n'est pas vide,
    if ( !isset($_POST['honey_pot']) || !empty($_POST['honey_pot']) ) {
        // Effectuer une redirection vers la page de laquelle proviennent les informations
        // Puis arrêter l'exécution du script.
        header("Location: index.php");
        die();
    }
    unset($_POST['honey_pot']);  

    // 3. Récupérer l'identifiant du film
    $filmId = (int) htmlspecialchars($_POST['film_id']);

    // 4. Etablir une connexion avec la base de données
    // Puis, récupérer le film correspondant à cet identifiant.
    $film = getFilm($filmId);

    // 5. Si le film n'existe pas
    if ( false === $film ) {
        // Alors, effectuer une redirection vers la page d'accueil
        //  Puis arrêter l'exécution du script.
        header("Location: index.php");
        die();
    }

    // 6. Dans le cas contraire, 
    // Effectuer la requête de suppression du film
    deleteFilm($filmId);

    // 7. Générer le message flash de succès
    $_SESSION['success'] =  "Le film <strong>{$film['title']}</strong> a été supprimé avec succès.";
    // $_SESSION['success'] =  "Le film a été supprimé avec succès.";

    // 8. Effectuer une redirection vers la page d'accueil
    //  Puis arrêter l'exécution du script.
    header('Location: index.php');
    die();


