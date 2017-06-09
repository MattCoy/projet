<?php
//connexion à la base
require_once('connBdd.php');

/*

get all slides from slider table
returns array of slides

*/
function getSlides($bdd){
	$reponse = $bdd->prepare('SELECT * FROM slider');
	$reponse->execute();
	return $reponse->fetchAll();

}
