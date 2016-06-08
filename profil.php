<?php

if(!isset($_SESSION['membre'])) { // si la session membre n'existe pas
    header('location:' . URL .'?page=connexion'); // redirection vers la page de connexion
} 
// affichage des coordonnées :
$content .= '<article><p>Bonjour <strong>' . $_SESSION['membre']['pseudo'] . '</strong></p>';
$content .= '<p>votre email est: ' . $_SESSION['membre']['email'] . '</p>';
$content .= '<p> votre civilité est: ' . $_SESSION['membre']['civilite'] . '</p>';
$content .= '<p> votre nom est: ' . $_SESSION['membre']['nom'] . '</p>';
$content .= '<p> votre prenom est: ' . $_SESSION['membre']['prenom'] . '</p></article>';