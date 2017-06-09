<?php session_start();
//connect to bdd
require_once('../includes/connBdd.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Admin - Home</title>
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
		<h1>Admin - Home</h1>
		<div class="row">

		</div>
		<div class="row">
			<h2>Inscription d'un nouveau collaborateur</h2>
			<div class="col-md-6">
				<?php
			//si les variables existent, que l'email est valide et que le mot de passe et le pseudo ne sont pas vides
				if(isset($_POST['nom']) AND isset($_POST['email']) AND filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) AND isset($_POST['mdp']) AND !empty($_POST['nom']) AND !empty($_POST['mdp']) AND $_POST['mdp'] == $_POST['mdp2']){

				//on vérifie que l'email n'est pas déjà présent dans la base
					$reponse = $bdd->prepare('SELECT * FROM users WHERE email = :email');
					$reponse->bindValue(':email', htmlspecialchars($_POST['email']), PDO::PARAM_STR);
					$reponse->execute();
					$resultat = $reponse->fetchAll();
				//on vérifie que le tableau de résultats est vide
					if(count($resultat) == 0){
					//on enregistre l'utilisateur dans la bdd

					// encryptage du mot de passe avec password_hash()
					// trim() supprime les espaces en début et fin de chaîne
					//Attention le champ mdp dans la base de donnees est en VARCHAR mais il doit pouvoir compter au moins 100 caractères!!!
						$mdp = password_hash(trim($_POST['mdp']), PASSWORD_DEFAULT); 
					//on récupère le role utilisateur
						$role = $_POST['role'];

						$reponse = $bdd->prepare('INSERT INTO users (name, email, password, role) VALUES(:nom, :email, :mdp, :role)');
						$reponse->bindValue(':nom', htmlspecialchars($_POST['nom']), PDO::PARAM_STR);
						$reponse->bindValue(':email', htmlspecialchars($_POST['email']), PDO::PARAM_STR);
						$reponse->bindValue(':mdp', htmlspecialchars($mdp), PDO::PARAM_STR);
						$reponse->bindValue(':role', htmlspecialchars($role), PDO::PARAM_STR);
						if($reponse->execute()){
							?>
							<div class="alert alert-success">
								<strong>Success!</strong> Utilisateur ajouté
							</div>
							<?php
						}

					}
					else{
						echo 'email déjà présent dans la bdd';
					}

				}
				?>
				<form method="POST">
					<div class="form-group">
						<label>Nom</label>
						<input type="text" name="nom" class="form-control">
					</div>
					<div class="form-group">
						<label>Email</label>
						<input type="text" name="email" class="form-control">
					</div>
					<div class="form-group">
						<label>Mdp</label>
						<input type="text" name="mdp" class="form-control">
					</div>
					<div class="form-group">
						<label>Répéter Mdp</label>
						<input type="text" name="mdp2" class="form-control">
					</div>
					<div class="form-group">
						<label>Attribuer un rôle à cet utilisateur</label>
						<select name="role" class="form-control">
							<option value="admin">Admin</option>
							<option value="vendeur">vendeur</option>
						</select>
					</div>
					<button type="submit" class="btn btn-default">Envoyer</button>
				</form>
			</div>
			<div class="col-md-6">
				<h2>Liste des utilisateurs</h2>
				<?php
				//récupération de tout les utilisateurs du site
				$reponse = $bdd->prepare('SELECT * FROM users');
				$reponse->execute();
				$users = $reponse->fetchAll();
				//création de la liste html
				?>
				<ul>
					<?php
					foreach($users as $user){
						echo '<li><ul>';
						echo '	<li>' . $user['name'] . '</li>';
						echo '	<li>' . $user['email'] . '</li>';
						echo '	<li>' . $user['role'] . '</li>';
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