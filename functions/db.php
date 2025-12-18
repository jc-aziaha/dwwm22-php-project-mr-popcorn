<?php

    /**
     * Cette fonction permet d'établir une connexion avec la base de données.
     *
     * @return PDO
     */
    function connectToDb(): PDO {

        $dsnDb = 'mysql:dbname=mr-popcorn;host=127.0.0.1;port=3306';
        $userDb = 'root';
        $passwordDb = '';

        try {
            $db = new PDO($dsnDb, $userDb, $passwordDb);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $exception) {
            die("Connection to database failed: " . $exception->getMessage());
        }

        return $db;
    }


    /**
     * Cette fonction permet d'insérer un nouveau film en base de données.
     *
     * @param null|float $ratingRounded
     * @param array $data
     * 
     * @return void
     */
    function insertFilm(null|float $ratingRounded, array $data = []): void {

        // Etablissons une connexion à la base de données.
        $db = connectToDb();

        // Préparons la requête à executer
        try {
            $req = $db->prepare("INSERT INTO film (title, rating, comment, created_at, updated_at) VALUES (:title, :rating, :comment, now(), now() )");
    
            // Passons à la requête, les données necessaires
            $req->bindValue(":title", $data['title']);
            $req->bindValue(":rating", $ratingRounded);
            $req->bindValue(":comment", $data['comment']);
    
            // Exécutons la requête
            $req->execute();
            
            // Fermons le curseur, c'est à dire la connexion à la base de données.
            $req->closeCursor();
        } catch (\PDOException $exception) {
            throw $exception;
        }
    }


    /**
     * Cette fonction permet de récupérer tous les films de la base de données.
     *
     * @return array
     */
    function getFilms(): array {
        $db = connectToDb();

        try {
            $req = $db->prepare("SELECT * FROM film ORDER BY created_at DESC");
            $req->execute();
            $films = $req->fetchAll();
            $req->closeCursor(); // Non obligatoire.
        } catch (\PDOException $exception) {
            throw $exception;
        }

        return $films;
    }


    /**
     * Cette fonction permet de récupérer un film en fonction de l'identifiant renseigné.
     *
     * @param integer $filmId
     * 
     * @return false|array
     */
    function getFilm(int $filmId): false|array {
        $db = connectToDb();

        try {
            $req = $db->prepare("SELECT * FROM film WHERE id=:id");
            $req->bindValue(":id", $filmId);

            $req->execute();
            $film = $req->fetch();
            $req->closeCursor();
        } catch (\PDOException $exception) {
            throw $exception;
        }

        return $film;
    }


    /**
     * Cette fonction permet de mettre à jour un film dans la base de données.
     *
     * @param null|float $ratingRounded
     * @param integer $filmId
     * @param array $data
     * 
     * @return void
     */
    function updateFilm(null|float $ratingRounded, int $filmId, array $data = []): void {
        $db = connectToDb();

        try {
            $req = $db->prepare("UPDATE film SET title=:title, rating=:rating, comment=:comment, updated_at=now() WHERE id=:id");
    
            $req->bindValue(":title", $data['title']);
            $req->bindValue(":rating", $ratingRounded);
            $req->bindValue(":comment", $data['comment']);
            $req->bindValue(":id", $filmId);
    
            $req->execute();
            $req->closeCursor();
        } catch (\PDOException $exception) {
            throw $exception;
        }
    }


    /**
     * Cette fonction permet de supprimer le film dans la base de données.
     *
     * @param integer $filmId
     * 
     * @return void
     */
    function deleteFilm(int $filmId): void {
        $db = connectToDb();

        try {
            $req = $db->prepare("DELETE FROM film WHERE id=:id");
            $req->bindValue(":id", $filmId);
            $req->execute();
            $req->closeCursor();
        } catch (\PDOException $exception) {
            throw $exception;
        }
    }