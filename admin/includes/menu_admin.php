<nav class="navbar navbar-default">
	<ul>
		<li><a href="index.php">Admin home</a></li>
		<li><a href="manage_products.php">Gérer les produits</a></li>
		<li><a href="shop_details.php">CMS</a></li>
		<li><a href="shop_slider.php">Slider</a></li>
		<?php
		//affichage du bouton déco si session active
		if(isset($_SESSION['id'])){
			?>
			<li><a href="index.php?logout">log out</a></li>
			<?php
		}
		?>
	</ul>
</nav>