<?php
session_start();

    
?>
<?php
    $title = "Liste des films";
    $description = "Découvrez la liste complète de mes films : notes, commentaires et fiches détaillées. Répertoire cinéma mis à jour régulièrement.";
    $keywords = "Cinéma, répertoire, film, dwwm22";
?>
<?php include_once __DIR__ . "/../partials/head.php"; ?>

    <?php include_once __DIR__ . "/../partials/nav.php"; ?>

        <!-- Main: Le contenu spécifique à cette page -->
        <main class="container">
            <h1 class="text-center my-3 display-5">Liste des films</h1>

            <div class="d-flex justify-content-end align-items-center my-3">
                <a href="/create.php" class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i> 
                    Ajouter film
                </a>
            </div>

            <?php if(isset($_SESSION['success']) && !empty($_SESSION['success'])) : ?>  
                <!-- Affichage du message flash -->
                <div class="text-center alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['success']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif ?>
            
        </main>

    <?php include_once __DIR__ . "/../partials/footer.php"; ?>

<?php include_once __DIR__ . "/../partials/foot.php"; ?>