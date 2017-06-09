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
		<h1>Admin - CMS</h1>
		<div class="row">

		</div>
		<div class="row">
			<h2>infos boutique</h2>
			<div class="col-md-6">
				<?php
				if(!empty($_FILES)){
					//le formulaire a été envoyé
					//si le fichier a bien été envoyé
					$maxfilesize = 5242880; //1 Mo
					if(isset($_FILES['slide']) AND $_FILES['slide']['error'] == 0 AND $_FILES['slide']['size'] < $maxfilesize){

						//pas d'erreur et le fichier n'est pas trop volumineux
						//on teste l'extension
						$extensions_autorisees = array('jpg', 'jpeg', 'png', 'gif');
						$fileInfo = pathinfo($_FILES['slide']['name']);
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
								$newImage = imagecreatefromjpeg($_FILES['slide']['tmp_name']);	
							}
							elseif($extension == 'png'){
								//png
								$newImage = imagecreatefrompng($_FILES['slide']['tmp_name']);
							}
							else{
								//fichier gif
								$newImage = imagecreatefromgif($_FILES['slide']['tmp_name']);
							}
							
							//largeur
							$imageWidth = imagesx($newImage);
							//hauteur
							$imageHeight = imagesy($newImage);

							//echo $imageWidth;
							//je décide de la largeur des miniatures
							$newWidth = 200;
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
								imagejpeg($miniature, '../images/thumbnails/' . $nom . '.' . $extension);
							}
							elseif($extension == 'png'){
								imagepng($miniature, '../images/thumbnails/' . $nom . '.' . $extension);
							}
							else{
								imagegif($miniature, '../images/thumbnails/' . $nom . '.' . $extension);
							}

							move_uploaded_file($_FILES['slide']['tmp_name'], '../images/'.$nom.'.'.$extension);
							//on enregistre les infos dans la base de données
							$reponse = $bdd->prepare('INSERT INTO slider (slide) VALUES (:slide)');
							$reponse->bindValue(':slide', $nom . '.' . $extension, PDO::PARAM_STR);
							if($reponse->execute()){
								?>
								<div class="alert alert-success">
									<strong>Success!</strong> Slide ajouté
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
						if($_FILES['slide']['error'] > 0){
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
			?>
			<div class="col-md-6">
				<h2>Slider</h2>
				<form method="POST" action="shop_slider.php" enctype="multipart/form-data">
					<div class="form-group">
						<label>Slide</label>
						<input type="file" name="slide" class="form-control" value="">
					</div>					
					<button type="submit" class="btn btn-default">Ajouter un slide</button>
				</form>
				
			</div>
			<div class="col-md-6">
				<h2>Slider</h2>
				<?php
				//si on supprime un slide
				if(isset($_GET['delete']) AND isset($_GET['IDSlide']) AND ctype_digit($_GET['IDSlide'])){
					//on récupère d'abord le nom du fichier image pour pouvoir le supprimer ensuite
					$reponse = $bdd->prepare('SELECT slide FROM slider WHERE ID = :id');
					$reponse->bindValue(':id', $_GET['IDSlide'], PDO::PARAM_INT);
					$reponse->execute();
					if($slide = $reponse->fetch()){						
						//puis requête de suppression						
						$reponse = $bdd->prepare('DELETE FROM slider WHERE ID = :id');
						$reponse->bindValue(':id', $_GET['IDSlide'], PDO::PARAM_INT);
						if($reponse->execute()){
							//slide supprimé de la bdd, on efface les fichiers
							unlink('../images/' . $slide['slide']);
							unlink('../images/thumbnails/' . $slide['slide']);
							?>
							<div class="alert alert-success">
								<strong>Success!</strong> Slide supprimé
							</div>
							<?php
						}
						else{
							?>
							<div class="alert alert-warning">
								<strong>Success!</strong> Problème lors de la suppression
							</div>
							<?php
						}
					}
					else{
						//pas de slide trouvé
						?>
						<div class="alert alert-danger">
							<strong>Success!</strong> mauvais paramètre de suppression
						</div>
						<?php
					}
				}
				//récupération des images slider
				$reponse = $bdd->prepare('SELECT * FROM slider');
				$reponse->execute();
				$slider = $reponse->fetchAll();
				//création de la liste
				?>
				<ul>
					<?php
					foreach($slider as $slide){
						echo '<li><img src="../images/thumbnails/' . $slide['slide'] . '"><br><a href="shop_slider.php?IDSlide=' . $slide['ID'] . '&delete">supprimer</a></li>';
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