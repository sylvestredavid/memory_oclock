<?php
    //on commence par récupérer les class et fichiers dont on aura besoin
    require('models/Score.php');
    require('models/Dificulty.php');
    require('controllers/ScoreController.php');
    require('utils/constantes.php');

    //on appelle notre base de donnée à l'aide d'un objet PDO, on utilise les constantes pour une meilleur maintenance
    $dbh = new PDO('mysql: host='.DB_HOST.'; dbname='.DB_NAME.'; charset=UTF8', USER_DB, MDP_DB);

    //on instancie un nouvel objet ScoreController auquel on passe notre instance de bdd, c'est lui qui a les fonctions permettant de récupérer ou ajouter des scores
    $scoreCtrl = new ScoreController($dbh);
    //on peut alors faire appel à la méthode getTopTen de $scoreCtrl et stocker ce qu'elle retourne dans une variable
    $scores = $scoreCtrl->getTopTen();
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="views/img/logooclock.ico" /> <!-- sert à changer l'icône de l'onglet... juste pour le plaisir ^^ -->
    <link rel="stylesheet" href="views/css/style.css">
    <title>memory o'clock</title>
</head>
    <body>
        <!--Début container jeu-->
        <div class="jeux_container">
            <div class="flex wrap" id="cards_container">
                <!-- on ne met rien ici, ce sera généré par la fonction generateCards de javascript -->
            </div>
            <div class="timer" id="timer">
                <div class="jauge" id="jauge"></div>
            </div>
        </div>
        <!--Fin container jeu-->
        <!--Début popup de score visible au début de la partie-->
        <div class="score_popup" id="popup">
            <div class="score_popup_body">
                <h1>Bienvenue sur le memory d'o'clock</h1>
                <p>Voici les meilleurs scores :</p>
                <table>
                    <thead>
                        <tr>
                            <th>Rang</th>
                            <th>Nom</th>
                            <th>Temps</th>
                            <th>Niveau</th>
                        </tr>
                    </thead>
                    <tbody>
                    <!--
                        ici j'affiche mes données à l'aide de la syntaxe alternative, au lieu d'avoir un gros bloc de php avec des echo dedans, ça rend le code plus claire,
                        voici un article qui explique tout ça: https://www.hakharien.fr/article-better-coding-php
                    -->
                    <?php foreach ($scores as $rang => $score): ?> <!--on fait un foreach sur notre variable $score qui n'est autre qu'un array, avec le foreach on va parcourir tous les éléments (foreach = pour chacun cqfd^^)-->
                        <tr>
                            <td>
                                <p><?= $rang + 1 ?></p> <!--pour le rang, je n'ai qu'à récupérer l'index de l'élément et rajouter 1, et oui un array commence à l'index 0 (si on n'a pas défini nous même les index)-->
                            </td>
                            <td>
                                <p><?= $score->getName() ?></p> <!--j'affiche le nom avec le getter getName, attributs privé oblige...-->
                            </td>
                            <td>
                                <p><?= gmdate("i:s", $score->getTime()) ?></p> <!--et là, plutôt que de réinventer la roue, je transforme mon temps qui est en secondes en minutes:secondes grâce à gmdate: https://www.php.net/manual/fr/function.gmdate.php-->
                            </td>
                            <td>
                                <p><?= $score->getDificulty()->getName() ?></p>
                            </td>
                        </tr>
                    <?php endforeach; ?> <!--j'oublie pas de fermer mon foreach!-->
                    </tbody>
                </table>
                <div class="btn_container">
                    <p>Choisissez votre difficultée :</p>
                </div>
                <div class="flex around">
                    <button class="btn btn_easy" id="play" onclick="play(1)">Facile</button> <!--onclick="play()" permet d'appeler la fonction play du JS lors d'un click sur le bouton -->
                    <button class="btn btn_hard" id="play" onclick="play(2)">Difficile</button>
                    <button class="btn btn_extrem" id="play" onclick="play(3)">Extreme</button>
                </div>
            </div>
        </div>
        <!--Fin popup de score visible au début de la partie-->
        <script
                src="https://code.jquery.com/jquery-3.4.1.js"
                integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
                crossorigin="anonymous"></script>
        <script type="text/javascript" src="views/js/script.js"></script>
    </body>
</html>