<?php
//connexion à la base
require_once('connBdd.php');

/*

get all products from products table
returns array of products

*/
function getProducts($bdd){
	$reponse = $bdd->prepare('SELECT * FROM products');
	$reponse->execute();
	return $reponse->fetchAll();
}


/*

get n products from products table
param
	$n INT
returns array of products

*/
function getNProducts($bdd, $n){
	$reponse = $bdd->prepare('SELECT * FROM products LIMIT :limit');
	$reponse->bindValue(':limit', $n, PDO::PARAM_INT);
	$reponse->execute();
	return $reponse->fetchAll();
}



/*

get products matching search query from products table
param
	$search STR
	$order 0 or 1
returns array of products

*/
function getSearchProducts($bdd, $search, $order = 0){
	$query = 'SELECT * FROM products WHERE name LIKE :search ORDER BY price';
	if($order){
		$query .= ' DESC';
	}
	$reponse = $bdd->prepare($query);
	$reponse->bindValue(':search', '%' . $search . '%', PDO::PARAM_INT);
	$reponse->execute();
	return $reponse->fetchAll();

}
