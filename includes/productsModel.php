<?php
//connexion à la base
require_once('connBdd.php');

/*

get all slides from slider table
returns array of slides

*/
function getProducts($bdd){
	$reponse = $bdd->prepare('SELECT * FROM products');
	$reponse->execute();
	return $reponse->fetchAll();
}

function getNProducts($bdd, $n){
	$reponse = $bdd->prepare('SELECT * FROM products LIMIT :limit');
	$reponse->bindValue(':limit', $n, PDO::PARAM_INT);
	$reponse->execute();
	return $reponse->fetchAll();
}
