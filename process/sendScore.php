<?php
//on commence par récupérer les class et fichiers dont on aura besoin
require("../models/Score.php");
require('../models/Dificulty.php');
require("../controllers/ScoreController.php");
require('../utils/constantes.php');

//on se connecte à notre base de donnée à l'aide d'un objet PDO, on utilise les constantes pour une meilleur maintenance
$dbh = new PDO('mysql: host='.DB_HOST.'; port='.DB_PORT.'; dbname='.DB_NAME.'; charset=UTF8', USER_DB, MDP_DB);

//on récupère les données envoyées par notre requête ajax, grâce à la variable globale $_POST
$name = $_POST['name'];
$time = $_POST['time'];
$dificultyId = $_POST['dificulty'];

//on instancie un nouvel objet ScoreController auquel on passe notre instance de bdd, c'est lui qui a les fonctions permettant de récupérer ou ajouter des scores
$scoreCtrl = new ScoreController($dbh);
//on peut alors faire appel à la méthode add de $scoreCtrl et renvoyer ce qu’elle retourne à notre requete ajax avec echo
echo $scoreCtrl->add(new Score($name, $time, new Dificulty($dificultyId, ""))); // Je met une string vide pour le nom car on s'en servira pas.
