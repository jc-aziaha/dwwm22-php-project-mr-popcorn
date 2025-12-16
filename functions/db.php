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
