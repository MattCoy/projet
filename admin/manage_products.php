<?php session_start();
//connect to bdd
require_once('../includes/connBdd.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Admin - products</title>
	<!-- Theme style  -->
	<link rel="stylesheet" href="../css/style.css">
	<!-- Icomoon Icon Fonts-->
	<link rel="stylesheet" href="../css/icomoon.css">
	<!-- Bootstrap  -->
	<link rel="stylesheet" href="../css/bootstrap.css">
	<!-- Modernizr JS -->
	<script src="../js/modernizr-2.6.2.min.js"></script>
	<!-- FOR IE9 below -->
	<!--[if lt IE 9]>
	<script src="js/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<?php
	include_once('includes/menu_admin.php');
	?>
	<div class="container">
		<h1>Admin - Gestion des produits</h1>
		<div class="row">

		</div>
		<div class="row">
			<h2>Ajout d'un nouveau produit</h2>
			<div class="col-md-6">
				<?php
				//si les variables existent, que l'email est valide et que le mot de passe et le pseudo ne sont pas vides
				if(isset($_POST['name']) AND isset($_POST['price']) AND isset($_POST['idCategory']) AND isset($_POST['available']) AND isset($_FILES['picture'])  AND !empty($_POST['name']) AND !empty($_POST['price']) AND !empty($_POST['idCategory']) ){

					$maxfilesize = 5242880; //1 Mo
					if($_FILES['picture']['error'] === 0 AND $_FILES['picture']['size'] < $maxfilesize){
						//pas d'erreur et le fichier n'est pas trop volumineux
						//on teste l'extension
						$extensions_autorisees = array('jpg', 'jpeg', 'png', 'gif');
						$fileInfo = pathinfo($_FILES['picture']['name']);
						$extension = $fileInfo['extension'];
						if(in_array($extension, $extensions_autorisees)){
							//extension valide
							echo 'c\'est bon';
							//transférer définitivement le fichier sur le serveur
							//on renomme le fichier
							$nom = md5(uniqid(rand(), true));
							//création de la miniature
							//on crée une copie de l'image
							//test de l'extension
							if($extension == 'jpg' OR $extension == 'jpeg'){
								//jpeg ou pjg
								$newImage = imagecreatefromjpeg($_FILES['picture']['tmp_name']);	
							}
							elseif($extension == 'png'){
								//png
								$newImage = imagecreatefrompng($_FILES['picture']['tmp_name']);
							}
							else{
								//fichier gif
								$newImage = imagecreatefromgif($_FILES['picture']['tmp_name']);
							}
							
							//largeur
							$imageWidth = imagesx($newImage);
							//hauteur
							$imageHeight = imagesy($newImage);

							//echo $imageWidth;
							//je décide de la largeur des miniatures
							$newWidth = 350;
							//on calcule la nouvelle hauteur
							$newHeight = ($imageHeight * $newWidth) / $imageWidth;

							//on crée la nouvelle image
							$miniature = imagecreatetruecolor($newWidth, $newHeight);
							if($extension == 'png'){
								imagesavealpha($miniature, true);
								$white = imagecolorallocate($miniature, 255, 255, 255);
								// On rend l'arrière-plan transparent
								imagecolortransparent($miniature, $white);
							}

							imagecopyresampled($miniature, $newImage, 0, 0, 0, 0, $newWidth, $newHeight, $imageWidth, $imageHeight);
							

							//on teste l'extension
							if($extension == 'jpg' OR $extension == 'jpeg'){
								imagejpeg($miniature, '../uploads/thumbnails/' . $nom . '.' . $extension);
							}
							elseif($extension == 'png'){
								imagepng($miniature, '../uploads/thumbnails/' . $nom . '.' . $extension);
							}
							else{
								imagegif($miniature, '../uploads/thumbnails/' . $nom . '.' . $extension);
							}

							move_uploaded_file($_FILES['picture']['tmp_name'], '../uploads/'.$nom.'.'.$extension);
							//on enregistre les infos dans la base de données
							$reponse = $bdd->prepare('INSERT INTO products (name, price, idCategory, available, creation_date, picture) VALUES (:name, :price, :idCategory, :available, NOW(), :picture)');
							$reponse->bindValue(':name', htmlspecialchars($_POST['name']), PDO::PARAM_STR);
							$reponse->bindValue(':price', htmlspecialchars($_POST['price']), PDO::PARAM_STR);
							$reponse->bindValue(':idCategory', htmlspecialchars($_POST['idCategory']), PDO::PARAM_INT);
							$reponse->bindValue(':available', htmlspecialchars($_POST['available']), PDO::PARAM_STR);
							$reponse->bindValue(':picture', $nom . '.' .$extension, PDO::PARAM_STR);
							if($reponse->execute()){
								?>
								<div class="alert alert-success">
									<strong>Success!</strong> Produit ajouté
								</div>
								<?php
							}
							else{
								?>
								<div class="alert alert-danger">
									<strong>Error!</strong> problème lors de l'insertion dans la bdd
								</div>
								<?php
							}

						}
						else{
							//extension non autorisée
							echo 'pas bonne extension';
						}
					}
					else{//problème:
						if($_FILES['picture']['error'] > 0){
							//erreur lors du transfert
							echo 'erreur de transfert';
						}
						else{
							//fichier trop volumineux
							echo 'fichier trop gros';
						}
						echo 'c\'est pas bon';
					}


				}

				//récupération des catégories pour générer la liste déroulante
				$reponseCat = $bdd->prepare('SELECT * FROM categories');
				$reponseCat->execute();
				$categories = $reponseCat->fetchAll();
				?>

				<form method="POST" action="manage_products.php" enctype="multipart/form-data">
					<div class="form-group">
						<label>Nom du produit</label>
						<input type="text" name="name" class="form-control">
					</div>
					<div class="form-group">
						<label>Prix</label>
						<input type="text" name="price" class="form-control">
					</div>
					<div class="form-group">
						<label>Disponible en boutique</label>
						<select name="available" class="form-control">
							<option value="1">oui</option>
							<option value="0">non</option>
						</select>
					</div>
					<div class="form-group">
						<label>Catégorie de produit</label>
						<select name="idCategory" class="form-control">
							<?php
							foreach($categories as $categorie){
								echo '<option value="' . $categorie['ID'] . '">' . $categorie['title'] . '</option>';
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label>Photo</label>
						<input type="file" name="picture" class="form-control">
					</div>
					<button type="submit" class="btn btn-default">Envoyer</button>
				</form>
			</div>
			<div class="col-md-6">
				<h2>Liste des utilisateurs</h2>
				<?php
				//si on souhaite supprimer un produit
				if(isset($_GET['delete']) AND isset($_GET['IDProduct']) AND ctype_digit($_GET['IDProduct'])){
					//on récupère d'abord le nom du fichier image pour pouvoir les effacer
					$reponse = $bdd->prepare('SELECT picture FROM products WHERE ID = :id');
					$reponse->bindValue(':id', $_GET['IDProduct'], PDO::PARAM_INT);
					$reponse->execute();
					$picture = $reponse->fetch();
					//requête de suppression
					$reponse = $bdd->prepare('DELETE FROM products WHERE ID = :id');
					$reponse->bindValue(':id', $_GET['IDProduct'], PDO::PARAM_INT);
					if($reponse->execute()){
						//produit supprimé de la bdd, on efface les fichiers image
						unlink('../uploads/' . $picture['picture']);
						unlink('../uploads/thumbnails/' . $picture['picture']);
						?>
						<div class="alert alert-success">
							<strong>Success!</strong> Produit supprimé
						</div>
						<?php
					}
					else{
						?>
						<div class="alert alert-success">
							<strong>Error!</strong> produit non suprimé
						</div>
						<?php	

					}
				}
				//récupération de tout les produits du site
				$reponse = $bdd->prepare('SELECT products.ID, name, price, available, title, creation_date, picture FROM products INNER JOIN categories ON products.idCategory = categories.ID');
				$reponse->execute();
				$products = $reponse->fetchAll();
				//création de la liste html
				?>
				<ul>
					<?php
					foreach($products as $product){
						$dispo = $product['available'] ? 'oui' : 'non';
						echo '<li><ul>';
						echo '	<li><img src="../uploads/thumbnails/' . $product['picture'] . '" width="100px"></li>';
						echo '	<li>' . $product['name'] . '</li>';
						echo '	<li>' . $product['price'] . '</li>';
						echo '	<li>disponible : ' . $dispo . '</li>';
						echo '	<li>' . $product['title'] . '</li>';
						echo '	<li><a href="manage_products.php?IDProduct=' . $product['ID'] . '&delete">supprimer</a></li>';
						echo '</ul></li>';
					}
					?>
				</ul>
			</div>
		</div>
	</div>
	
</body>
<!-- jQuery -->
<script src="../js/jquery.min.js"></script>
<!-- jQuery Easing -->
<script src="../js/jquery.easing.1.3.js"></script>
<!-- Bootstrap -->
<script src="../js/bootstrap.min.js"></script>
</html>