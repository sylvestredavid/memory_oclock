let cards;
let nbCardFind = 0;
let nbCartTurn = 0;
let turnedCard1;
let time = 0;
let end = false;
let dificulty;
let nbCardToFind;
let fruitsEasy = ['banane','pomme_verte', 'raisin', 'pasteque', 'peche_violette', 'poire', 'cerises', 'framboise', 'mangue', 'cerises_jaunes',
    'banane','pomme_verte', 'raisin', 'pasteque', 'peche_violette', 'poire', 'cerises', 'framboise', 'mangue', 'cerises_jaunes'];

let fruitsHard = ['pomme_rouge', 'banane', 'citron_vert', 'grenade', 'citron_jaune', 'fraise',
    'pomme_verte', 'raisin', 'pasteque', 'poire', 'cerises', 'framboise', 'mangue', 'cerises_jaunes',
    'pomme_rouge', 'banane', 'citron_vert', 'grenade', 'citron_jaune', 'fraise',
    'pomme_verte', 'raisin', 'pasteque', 'poire', 'cerises', 'framboise', 'mangue', 'cerises_jaunes'];

let fruitsExtrem = ['pomme_rouge', 'banane', 'orange', 'citron_vert', 'grenade', 'peche', 'citron_jaune', 'fraise',
    'pomme_verte', 'brugnon', 'raisin', 'pasteque', 'peche_violette', 'poire', 'cerises', 'framboise', 'mangue', 'cerises_jaunes',
    'pomme_rouge', 'banane', 'orange', 'citron_vert', 'grenade', 'peche', 'citron_jaune', 'fraise',
    'pomme_verte', 'brugnon', 'raisin', 'pasteque', 'peche_violette', 'poire', 'cerises', 'framboise', 'mangue', 'cerises_jaunes'];

/**
 * fonction qui sera appelée lors du click sur le bouton jouer du popup
 * elle enlève le popup en lui mettant un display a none
 * et lance le jeu... let's go!
 */
function play(niveau) {
    dificulty = niveau;
    $("#timer").css("visibility", "visible"); //on fait apparaitre le timer
    $("#popup").css("display", "none"); // et disparaitre le popup
    timer();
    generateCards();
    returnCard();
}

/**
 * fonction qui affiche le timer et qui arrête le jeu si on a pas trouvé toutes les cartes avant la fin du temps imparti (3min)
 */
function timer() {
    const jauge = $("#jauge");
    // on crée un interval qui va exécuter le code à l'intérieur (handler) suivant un intervalle spécifié (100ms pour ici)
    let timerInterval = setInterval(() => {
        if (time < 180) { // si la variable time est inférieur à 180 (donc 3minutes)
            if (!end) { // si le jeu n'est pas fini
                //on incrémente la variable time de 0.1 (car toutes les 0.1secondes) et on fait avancer la jauge en calculant le pourcentage suivant time
                time += 0.1;
                jauge.width((time * 100 / 180) + "%");
                if (time >= 60 && time < 120) { // si time est supérieur à 60 et inférieur à 90, on passe la jauge en orange... on a encore le temps...
                    jauge.css("background-color", "orange")
                }else if (time >= 120) { // sinon si time est supérieur à 120, on passe la jauge en rouge... viiiiiiiiiite!!!!
                    jauge.css("background-color", "red")
                }
            } else { // si end est à true, le jeu est fini on supprime le setInterval
                clearInterval(timerInterval)
            }
        } else { // si time est supérieur ou égale à 180, on a perdu, on supprime le setInterval et on affiche un popup pour rejouer
            clearInterval(timerInterval);
            if (confirm('Perdu... \n Rejouer?')) {
                location.reload();
            }
            end = true;
        }
    }, 100)
}

/**
 * fonction qui va créer les cartes, on commence par mélanger le tableau contenant le nom des fruits
 * puis on parcourt le tableau pour créer des divs et on leur donne une class suivant l'entrée du tableau en cours... c'est ce qui va permettre d'afficher le bon fruit
 * et de vérifier si les 2 cartes retournées sont identiques
 */
function generateCards() {
    // on crée une nouvelle variable qui sera égale a un des 3 tableaux de fruits suivant la difficultée
    let fruits;
    if(dificulty === 1) {
        fruits = fruitsEasy;
    } else if(dificulty === 2) {
        fruits = fruitsHard;
    } else {
        fruits = fruitsExtrem
    }
    nbCardToFind = fruits.length / 2;
    fruits.sort(function () {
        return Math.random() - .5 // là, je ne vais pas mentir, j'ai trouvé cette fonction avec google^^, c'est aussi ça le métier de dev, trouver des ressources sur le net : https://www.hakharien.fr/article-array-shuffle-js
    });
    fruits.forEach(f => {
        $("#cards_container").append($(`<div class="card ${f} hidden"></div>`))
    });
    cards = $(".card"); //on finit par récupérer toutes les cartes dans un tableau
}

/**
 * fonction qui retourne les cartes si elles ne le sont pas déjà
 */
function returnCard() {
    for (let card of cards) {
        $(card).click(function () {
            if ($(card).hasClass('hidden') && nbCartTurn < 2 && !end) { // si la carte a la class hidden, et que le nombre de carte retournée est inférieur à 2 (afin de ne pas retourner plus de 2 cartes par tour)
                // on incrémente le nombre de carte retournées et on enlève la class hidden de la carte afin de la révéler
                nbCartTurn++;
                $(card).removeClass('hidden');
                if (nbCartTurn === 2) { // si le nombre de carte retournée est égale à 2, on vérifie que les cartes sont identiques
                    verifCards(card)
                } else { // sinon on stock la carte dans une variable afin de faire les vérifications au prochain click
                    turnedCard1 = card;
                }
            }
        })
    }
}

/**
 * fonction qui va vérifier si les 2 cartes sont identiques
 * @param card
 */
function verifCards(card) {
    if (turnedCard1.classList[1] === card.classList[1]) { // pour vérifier, on utilise la fonction classList qui va retourner sous forme de tableau les class de l'élément, la class qui nous intéresse est à la position 1
        nbCartTurn = 0;
        nbCardFind++; // on incrémente le nombre de cartes trouvée, puis on vérifie si il est égale à a nbCardToFind (nombre de cartes / 2), dans ce cas c'est gagné!
        if (nbCardFind === nbCardToFind) {
            // on attend 20ms (pour laisser la dernière carte se retourner) puis on affiche un prompt demandant le nom de l'user, afin d'enregistrer son score
            setTimeout(() => {
                let name = prompt('Vous avez trouvé toutes les cartes en ' + secondToMinutes(time) + '\n enregistrez votre temps.');
                saveScore(name);
                end = true;
            }, 20)
        }
    } else { // si les 2 class ne sont pas identiques, on remet la class hidden aux 2 cartes, on utilise un setTimeOut afin de laisser le temps de voir les cartes
        setTimeout(() => {
            $(turnedCard1).addClass('hidden');
            $(card).addClass('hidden');
            nbCartTurn = 0;
        }, 800)
    }
}

/**
 * fonction pour enregistrer le score en bdd
 * @param name
 */
function saveScore(name) {
    //on crée une requête AJAX de type POST
    $.post({
        url: "./process/sendScore.php", //le fichier de destination
        data : {
            name: name,
            time: Math.floor(time),
            dificulty: dificulty
        }, // les données qu'on envoie
        dataType : 'text', // le type de données RECUES de php, ici text suffis car on a juste à vérifier si on a reçus "success"
        success: function(res){ // et la fonction à réaliser une fois la requête réussie
            if(res === "success") {
                alert('Votre score a bien été enregistré.')
            } else {
                console.log(res)
            }
        }
    })
}

/**
 * fonction qui transforme un int en un temps formaté "mm min ss sec"
 * @param time
 * @returns {string}
 */
function secondToMinutes(time) {
    let minutes = Math.floor(time / 60);
    let seconds = Math.floor(time - minutes * 60);
    return `${minutes} min ${seconds} sec`
}