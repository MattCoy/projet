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
				if(!empty($_POST)){
					//le formulaire a été envoyé
					//si les variables existent, que l'email est valide et que le mot de passe et le pseudo ne sont pas vides
					if(isset($_POST['address']) AND isset($_POST['email']) AND isset($_POST['phone']) AND !empty($_POST['address']) AND filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) AND preg_match('#^0[1-9][0-9]{8}$#', $_POST['phone'])){
						
						$reponse = $bdd->prepare('UPDATE shopdetails SET address = :address, email = :email, phone = :phone');
						$reponse->bindValue(':address', htmlspecialchars($_POST['address']), PDO::PARAM_STR);
						$reponse->bindValue(':email', htmlspecialchars($_POST['email']), PDO::PARAM_STR);
						$reponse->bindValue(':phone', htmlspecialchars($_POST['phone']), PDO::PARAM_INT);
						if($reponse->execute()){
							?>
							<div class="alert alert-success">
								<strong>Success!</strong> Mise à jour des coordonnées
							</div>
							<?php
						}
						else{
							?>
							<div class="alert alert-danger">
								<strong>Error!</strong> problème lors de la mise à jour
							</div>
							<?php
						}
					}
					else{
						//
						?>
						<div class="alert alert-danger">
						<strong>Error!</strong> vérifiez les champs du formulaire bon sang!
						</div>
						<?php
					}
				}

				//récupération des infos boutique
				$reponseCat = $bdd->prepare('SELECT * FROM shopdetails');
				$reponseCat->execute();
				//une ligne donc fetch() suffit
				$infos = $reponseCat->fetch();
				?>

				<form method="POST">
					<div class="form-group">
						<label>Adresse</label>
						<input type="text" name="address" class="form-control" value="<?php if(isset($infos['address'])){ echo $infos['address'];} ?>">
					</div>
					<div class="form-group">
						<label>Email</label>
						<input type="text" name="email" class="form-control" value="<?php if(isset($infos['email'])){ echo $infos['email'];} ?>">
					</div>
					<div class="form-group">
						<label>Téléphone</label>
						<input type="text" name="phone" class="form-control" value="<?php if(isset($infos['phone'])){ echo $infos['phone'];} ?>">
					</div>
					<button type="submit" class="btn btn-default">Envoyer</button>
				</form>
			</div>
			<div class="col-md-6">
				<h2>Slider</h2>
				<form method="POST" enctype="multipart/form-data">
					<div class="form-group">
						<label>Slide</label>
						<input type="text" name="slide" class="form-control" value="">
					</div>					
					<button type="submit" class="btn btn-default">Ajouter un slide</button>
				</form>
				
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