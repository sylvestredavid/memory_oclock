<?php

/**
 * Cette class est le controlleur de la class Score, en gros, c'est elle qu'on appel pour les méthodes du CRUD.
 * Class ScoreController
 */
class ScoreController
{
    private $db;

    /**
     * ArticleController constructor.
     * @param $db
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Méthode de récupération des 10 meilleurs temps
     * @return array
     */
    public function getTopTen(){
        //le try catch va permettre de récupérer et d'afficher l'erreur
        try{
            $this->db;
        }
        catch(Exeption $e){
            echo 'Message erreur sql : '.$e->getMessage().'<br>';
            exit;
        }
        $scores = array();
        // Petite explication de la requête: on va selectionner les colonnes name, time et dificulty_id de la table scores, ainsi que la colonne name de la table difficulty
        // en joignant la table dificulty via la colonne id de difficulty et la colonne difficulty_id de scores, on les trie par dificulty_id du plus grand au plus petit et ensuite par time du plus petit au plus grand,
        // et on ne prend que les 10 premiers résultats
        $stmt = $this->db->prepare('SELECT scores.name, time, dificulty.name as dificulty, dificulty_id FROM scores INNER JOIN dificulty ON dificulty.id = dificulty_id order by dificulty_id DESC, time ASC LIMIT 10'); //on prépare la requête
        $stmt->execute(); // on l'envoi
        while ($row = $stmt->fetch()) { // puis on fait un mapping : on boucle sur le résultat pour créer un nouvel objet à chaque nouvelle ligne, ici on les stock ensuite dans un array qu'on retourne
            $scores[] = new Score($row['name'], $row['time'], new Dificulty($row['dificulty_id'], $row['dificulty']));
        }
        return $scores;
    }

    /**
     * Méthode d'ajout d'un score en bdd
     * @param Score $score
     * @return string
     */
    public function add(Score $score){
        // on récupère les attributs de notre objet $score
        $name = htmlspecialchars($score->getName()); // ici on utilise htmlspecialchars, une fonction de php qui convertit les caractères spéciaux en entités HTML afin d'éviter les failles xss: https://fr.wikipedia.org/wiki/Cross-site_scripting
        $time = $score->getTime();
        $dificultyId = $score->getDificulty()->getId();
        try{
            $this->db;
            $stmt = $this->db->prepare('INSERT INTO scores(name, time, dificulty_id) VALUES(:name, :time, :dificultyId)'); //on prépare la requête
            $stmt->bindParam(':name', $name); // on lui passe les paramètres nécessaires
            $stmt->bindParam(':time', $time);
            $stmt->bindParam(':dificultyId', $dificultyId);
            $stmt->execute();
            return "success"; // enfin on retourne success, il sera retourné à notre requête ajax pour savoir si l'opération s'est déroulée sans soucis et faire le nécessaire en JS
        }
        catch(Exeption $e){
            return 'Message erreur sql : '.$e->getMessage(); // en cas d'erreur le catch attrape l'exception et la renvoi, ainsi on pourra l'afficher dans la console de JS
        }
    }

}