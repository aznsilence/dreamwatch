<?php
include_once('functions/DBconf.php');

spl_autoload_register(function($class) {
  include "functions/".$class .".class.php";
});

if(!isset($_SESSION['user_id']) && !isset($_COOKIE['user_id']))
{
	header("Location: login.php");
}


if(isset($_SESSION['user_id']))
{
	$user_id = $_SESSION['user_id'];
}

if(isset($_COOKIE['user_id']))
{
	$user_id = $_COOKIE['user_id'];
}

if(isset($_GET['logout']) && $_GET['logout']==1)
{
	$logout = new User($bdd);
	$logout->logout();
}

if(!empty($_POST['submit_search']) && $_POST['search']!="")
{
$search = $_POST['search'];
}
else {
  $search = "";
}

if(!empty($_POST['newsletter_submit']))
{
	$newNewsletter = new Newsletter($bdd);
	
	if($newNewsletter->existNewsletter($_POST['newsletter'])==1)
	{
		$message = "email already exist";
	}
	else
	{
		$newNewsletter->addNewsletter($_POST['newsletter']);
	}
}

$getUser = new User($bdd);
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link href="https://fonts.googleapis.com/css?family=Playfair+Display" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
	<link rel="stylesheet" href="asset/css/dreamwatch_site.css" type="text/css">
	<link rel="stylesheet" href="asset/framework/bootstrap/css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" type="text/css" href="asset/plugins/HorizontalDropDownMenu/css/component.css" />
	<link rel="stylesheet" type="text/css" href="asset/plugins/Carousel/slick/slick.css"/>

	<link rel="stylesheet" type="text/css" href="asset/plugins/Carousel/slick/slick-theme.css"/>
</head>
<body>
	<div class="promo text-center">S&Eacute;LECTION DES TOPS VENTES&nbsp;&nbsp;&nbsp;&nbsp;<a href="list_product.php">Je découvre</a></div>
	<header>
		<div class="row text-center">
			<div class="text-center logo"><h1>THE DREAMWATCH</h1></div>
		</div>
		<div class="row text-center picto-search">
			<div class="col-md-3" style="background-color: white"></div>
			<div class="col-md-6">
				<div class="text-left picto text-center">

					<ul>
					<li><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span><br/>
					<span class="picto-text"><a href="index.php?logout=1" target="_SELF">Log-out</a></span></li>
					<li><span class="glyphicon glyphicon-user" aria-hidden="true"></span><br/>
					<span class="picto-text"><a href="user-profile.php" target="_SELF">Account (<?php echo $getUser->getUser($user_id,"first_name"); ?>)</a></span></li>
					<li><span class="glyphicon glyphicon-map-marker"></span><br/>
					<span class="picto-text">Shops</span></li>
					<li><span class="glyphicon glyphicon-shopping-cart"></span><br/>
					<span class="picto-text">Cart</span></li>
					<?php
					if($getUser->getAdmin($user_id)==1)
					{
					?>
					<li><span class="glyphicon glyphicon-list-alt"></span><br/>
					<span class="picto-text"><a href="admin/admin.php" target="_SELF">Backoffice</a></span></li>
					<?php
					}
					?>
					<li><span class="glyphicon glyphicon-home"></span><br/>
					<span class="picto-text"><a href="index.php" target="_SELF">Home</a></span></li>
					</ul>
				</div>
        <form action="index.php" method="post">
				<div class="text-right">
				      <input type="text" id="search"  name="search"placeholder="Search..." />
				      <span class="icon"><i class="glyphicon glyphicon-search"></i></span><br/><br/>
				      <input type="submit" class="btn btn-primary" name="submit_search" value="search" />
        </div>
      </form>

				<div class="clear"></div>
			</div>
			<div class="col-md-3" style="background-color: white"></div>
		</div>
		<nav id="cbp-hrmenu" class="cbp-hrmenu">
			<ul>
				<li>
					<a href="index.php">MAN</a>
					<div class="cbp-hrsub">
						<div class="cbp-hrsub-inner">
							<div class="liste-submenu">
								<h4>By Brands</h4>
								<ul>
								<?php
								$newMenu = new Product($bdd);
								$newMenu->getMenu("brands",1);
								?>
								</ul>
								<h4>By Styles</h4>
								<ul>
								<?php
								$newMenu = new Product($bdd);
								$newMenu->getMenu("styles",1);
								?>
								</ul>
							</div>
							<div>
								<h4>By Materials</h4>
								<ul>
								<?php
								$newMenu = new Product($bdd);
								$newMenu->getMenu("materials",1);
								?>
								</ul>
								<h4>By Shapes</h4>
								<ul>
								<?php
								$newMenu = new Product($bdd);
								$newMenu->getMenu("shapes",1);
								?>
								</ul>
							</div>
							<div class="image-submenu">
								<br/><br/><br/><br/>
								<img src="http://www.chezmaman.com/12468/montre-daniel-wellington-cornwall-rose-gold-black-36.jpg" width="250" />
							</div>
						</div><!-- /cbp-hrsub-inner -->
					</div><!-- /cbp-hrsub -->
				</li>
				<li>
					<a href="index.php">WOMAN</a>
										<div class="cbp-hrsub">
						<div class="cbp-hrsub-inner">
							<div class="liste-submenu">
								<h4>By Brands</h4>
								<ul>
								<?php
								$newMenu = new Product($bdd);
								$newMenu->getMenu("brands",2);
								?>
								</ul>
								<h4>By Styles</h4>
								<ul>
								<?php
								$newMenu = new Product($bdd);
								$newMenu->getMenu("styles",2);
								?>
								</ul>
							</div>
							<div>
								<h4>By Materials</h4>
								<ul>
								<?php
								$newMenu = new Product($bdd);
								$newMenu->getMenu("materials",2);
								?>
								</ul>
								<h4>By Shapes</h4>
								<ul>
								<?php
								$newMenu = new Product($bdd);
								$newMenu->getMenu("shapes",2);
								?>
								</ul>
							</div>
							<div class="image-submenu">
								<br/><br/><br/><br/>
								<img src="https://ocarat.com/71275/classic-southampton-lady-rose-gold-36-mm-daniel-wellington.jpg" width="300" />
							</div>
						</div><!-- /cbp-hrsub-inner -->
					</div><!-- /cbp-hrsub -->
				</li>
				<li class="luxe"><a href="index.php">LUXURY</a></li>
				<li>
					<a href="index.php">BRANDS</a>
					<div class="cbp-hrsub">
						<div class="cbp-hrsub-inner">
							<div>
								<h4>Marque</h4>
								<ul>
								<?php
								$newMenu = new Product($bdd);
								$newMenu->getMenu("brands",0);
								?>
								</ul>
							</div>
							<div class="logo-brands">
								<ul>
									<li><img src="http://www.b2bwatches.co.uk/store/1/0LELE/Banner_DW0_20_7_15.jpg" width="150" /></li>
									<li><img src="http://www.rugby15.co.za/wp-content/uploads/2016/07/All_blacks_logo-700x400.png" width="150" /></li>
									<li><img src="https://www.braceletsdemontres.com/media/catalog/product/cache/6/image/9df78eab33525d08d6e5fb8d27136e95/e/s/esprit_timewear.jpg" width="150" /></li>
								</ul>
							</div>
							<div class="logo-brands">
								<ul>
									<li><img src="http://www.b2bmontres.fr/store/1/0LELE/Banner_HUGO_LOGO_11_9_15.jpg" width="150" /></li>
									<li><img src="http://www.hematite-france.com/wp-content/uploads/2016/09/Logo-Pierre-Lannier-09-16.png" width="150" /></li>
									<li><img src="http://cdn2.yoox.biz/Os/armanigroup/images/loghi/2560/emporioarmani_black.png" width="150" /></li>
								</ul>
							</div>
						</div>
					</div>
				</li>
				<li><a href="index.php">DISCOUNT</a></li>
			</ul>
		</nav>
	</header>
	<main>
		<div class="row text-center">
			<div class="col-md-12">
				<div class="single-item">
					<div class="slide1"><img src="http://www.westendcollection.com.au/media/images/main/DWSS16_046_AD.JPG" class="img-responsive" /></div>
					<div class="slide1"><img src="http://www.westendcollection.com.au/media/images/main/DWSS16_046_AD.JPG" class="img-responsive" /></div>
					<div class="slide1"><img src="http://www.westendcollection.com.au/media/images/main/DWSS16_046_AD.JPG" class="img-responsive" /></div>
				</div>
			</div>
		</div>
		<div class="container">
<div class="container">
    <div class="well well-sm">
        <strong>Display</strong>
        <div class="btn-group">
            <a href="#" id="list" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-th-list">
            </span>List</a> <a href="#" id="grid" class="btn btn-default btn-sm"><span
                class="glyphicon glyphicon-th"></span>Grid</a>
        </div>
    </div>

        <div id="products" class="row list-group">
    <?php
    $brand_id = "";
    if (isset($_POST['submit_search'])) {
    $req = $bdd->prepare("SELECT products.id as product_id ,products.image_src as product_image, products.name as name, products.reference, products.price, products.description, brands.name as brand_name FROM products INNER JOIN brands ON products.brand_id = brands.id WHERE brands.name LIKE :search OR products.reference LIKE :search OR products.name LIKE :search ORDER BY products.name ASC");
    $req->execute(array(':search' => '%'.$search.'%'));
  } else {
    $req = $bdd->prepare("SELECT products.id as product_id ,products.name as name, products.reference, products.price, products.description, brands.name as brand_name FROM products INNER JOIN brands ON products.brand_id = brands.id ORDER BY products.name ASC");
    $req->execute();
  }

    while($data = $req->fetch())
    {
    ?>

    		<div class="item col-xs-4 col-lg-4">
            <div class="thumbnail">
            <?php
            if($data['product_image']!="")
            {
            ?>
                <img class="group list-group-image" src="http://esquireuk.cdnds.net/16/07/1455902737-watch-junghans-automatic-43.jpg" alt="" width="200" />
            <?php
            }
            else
            {
            ?>

      			<p class="text-center"><img class="group list-group-image" src="https://upload.wikimedia.org/wikipedia/commons/a/ac/No_image_available.svg" width="200" /></p>
            <?php
        	}
            ?>
                <div class="caption">
                    <h4 class="group inner list-group-item-heading">
                        <?php echo $data['name'] ?></h4>
                        <h5 class="group inner list-group-item-heading">
                            <?php echo $data['brand_name'] ?></h5>
                            <p class="group inner list-group-item-text">
                              <?php echo $data['reference'] ?>
                            </p>

                    <p class="group inner list-group-item-text">
                      <?php echo $data['description'] ?>
                    </p>
                        <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <p class="lead">
                              <?php echo $data['price'] ?></p>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <a class="btn btn-success" href="product-page.php?product_id=<?php echo $data['product_id'] ?>">view details</a>
                        </div>
                    </div>
                </div>
            </div>
      </div>
  <?php
  }
  ?>
        </div>
	</div>
	</div>
	</main>
	<footer>
<br/>
<br/>
<ul>
<li class="footermenu">
	<ul>
		<li><H5>DREAMWATCH</H5></li>
		<li>Qui sommes-nous ?</li>
		<li>Nos engagements</li>
		<li>Nos boutiques</li>
		<li>Affiliation</li>
		<li>Plan du site</li>
		<li>Vidéos</li>
	</ul>
</li>
<li class="footermenu">
	<ul>
		<li><H5>AVANTAGES CLIENTS</H5></li>
		<li>Carte cadeau</li>
		<li>Parrainage</li>
		<li>Fidélité</li>
		<li>Codes Réduction</li>
		<li>Newsletter</li>
		<li>Avis client</li>
	</ul>
</li>
<li class="footermenu">
	<ul>
		<li><H5>AIDE</H5></li>
		<li>FAQ</li>
		<li>Guide technique</li>
		<li>Quelle montre offrir ?</li>
		<li>Comparateur</li>
		<li>Guide pratique</li>
		<li>Remboursement</li>
	</ul>
</li>
<li class="footermenu">
	<ul>
		<li><H5>INFORMATIONS LÉGALES</H5></li>
		<li>Conditions des offres</li>
		<li>Conditions générales de vente</li>
		<li>Mentions légales/li>
		<li>Crédits</li>
		<li>Nous contacter</li>
		<li>Cookies</li>
	</ul>
</li>
<li class="footermenu">
<ul>
	<li>NEWSLETTER :</li>
	<li>
	<form action="index.php" method="POST">
	<div class="form-group">
		<input type="text" name="newsletter" class="form-control" data-validation="required email" data-validation-error-msg="The email is invalid"><br/>
		<input type="submit" name="newsletter_submit" class="btn btn-info" value="Add me to newsletter">
	</div>
	</form>
	</li>
	<li>&nbsp;</li>
</ul>
</li>
</ul>


</footer>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="asset/framework/bootstrap/js/bootstrap.min.js"></script>
	<script src="asset/plugins/HorizontalDropDownMenu/js/cbpHorizontalMenu.min.js"></script>
	<script type="text/javascript" src="asset/plugins/Carousel/slick/slick.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script> 
	<script>
	$(document).ready(function(){

		$('.single-item').slick({
  			fade: true,
  			dots:true,
  			adaptiveHeight: true,
  			accessibility:true
		});

	cbpHorizontalMenu.init();


    $('#list').click(function(event){event.preventDefault();$('#products .item').addClass('list-group-item');});
    $('#grid').click(function(event){event.preventDefault();$('#products .item').removeClass('list-group-item');$('#products .item').addClass('grid-group-item');});

     $.validate({
		  modules : 'security,date',
		  onModulesLoaded : function() {
		    var optionalConfig = {
		      fontSize: '10pt',
		      padding: '4px',
		    };

		    $('input[name="password"]').displayPasswordStrength(optionalConfig);
		  }
		});

	});

	</script>
</body>
</html>
