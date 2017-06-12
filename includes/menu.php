<nav class="fh5co-nav" role="navigation">
	<div class="container">
		<div class="row">
			<div class="col-md-3 col-xs-2">
				<div id="fh5co-logo"><a href="index.php">Bout Hic</a></div>
			</div>
			<div class="col-md-4 col-xs-4 text-center menu-1">
				<ul>
					<li class="has-dropdown">
						<a href="products.php">Nos Produits</a>
						<ul class="dropdown">
							<li><a href="single.html">Single Shop</a></li>
						</ul>
					</li>
					<li><a href="about.html">About</a></li>
					<li class="has-dropdown">
						<a href="services.html">Services</a>
						<ul class="dropdown">
							<li><a href="#">Web Design</a></li>
							<li><a href="#">eCommerce</a></li>
							<li><a href="#">Branding</a></li>
							<li><a href="#">API</a></li>
						</ul>
					</li>
					<li><a href="contact.php">Contact</a></li>
				</ul>
			</div>
			<div class="col-md-5 col-xs-6 text-right hidden-xs menu-2">
				<ul>
					<li class="search">
						<form action="products.php">
							<div class="input-group">
								<select name="order">
									<option value="0">prix croissant</option>
									<option value="1">prix décroissant</option>
								</select>
								<input type="text" placeholder="Search.." name="search">								
								<span class="input-group-btn">
									<button class="btn btn-primary" type="submit"><i class="icon-search"></i></button>
								</span>
							</div>
						</form>
					</li>
					<li >
						<form action="admin/index.php" method="POST">
								<div class="input-group">
									<input type="text" placeholder="email" name="email">
									<input type="text" placeholder="pass" name="mdp">								
									<span class="input-group-btn">
										<button class="btn btn-primary" type="submit" style="color:grey;">log in</button>
									</span>
								</div>
							</form>
					</li>
				</ul>
			</div>
		</div>

	</div>
</nav>