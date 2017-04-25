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

$getUser = new User($bdd);
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link href="https://fonts.googleapis.com/css?family=Playfair+Display" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
	<link rel="stylesheet" href="asset/css/dreamwatch_product-page.css" type="text/css">
	<link rel="stylesheet" href="asset/framework/bootstrap/css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" type="text/css" href="asset/plugins/HorizontalDropDownMenu/css/component.css" />
	<link rel="stylesheet" type="text/css" href="asset/plugins/Carousel/slick/slick.css"/>

	<link rel="stylesheet" type="text/css" href="asset/plugins/Carousel/slick/slick-theme.css"/>
</head>
<body>
  <!-- BEGUIN SHARE BUTTON FACEBOOK JSK CODE-->
  <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<!-- START SHARE BUTTON FACEBOOK JSK CODE-->

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
				<div class="text-right">
				      <input type="search" id="search" placeholder="Search..." />
				      <span class="icon"><i class="glyphicon glyphicon-search"></i></span>
				</div>
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


    	<div class="container">
    		<div class="card">
    			<div class="container-fliud">
    				<div class="wrapper row">
    					<div class="preview col-md-6">

    						<div class="preview-pic tab-content">
    						  <div class="tab-pane active" id="pic-1"><img src="http://www.1001-montres.fr/3640-thickbox/montre-homme-hugo-boss-bracelet-silicone.jpg" /></div>
    						  <div class="tab-pane" id="pic-2"><img src="http://www.1001-montres.fr/3640-thickbox/montre-homme-hugo-boss-bracelet-silicone.jpg" /></div>
    						  <div class="tab-pane" id="pic-3"><img src="http://www.1001-montres.fr/3640-thickbox/montre-homme-hugo-boss-bracelet-silicone.jpg" /></div>
    						  <div class="tab-pane" id="pic-4"><img src="http://www.1001-montres.fr/3640-thickbox/montre-homme-hugo-boss-bracelet-silicone.jpg" /></div>
    						  <div class="tab-pane" id="pic-5"><img src="http://www.1001-montres.fr/3640-thickbox/montre-homme-hugo-boss-bracelet-silicone.jpg" /></div>
    						</div>
    						<ul class="preview-thumbnail nav nav-tabs">
    						  <li class="active"><a data-target="#pic-1" data-toggle="tab"><img src="http://www.1001-montres.fr/3640-thickbox/montre-homme-hugo-boss-bracelet-silicone.jpg" /></a></li>
    						  <li><a data-target="#pic-2" data-toggle="tab"><img src="http://www.1001-montres.fr/3640-thickbox/montre-homme-hugo-boss-bracelet-silicone.jpg" /></a></li>
    						  <li><a data-target="#pic-3" data-toggle="tab"><img src="http://www.1001-montres.fr/3640-thickbox/montre-homme-hugo-boss-bracelet-silicone.jpg" /></a></li>
    						  <li><a data-target="#pic-4" data-toggle="tab"><img src="http://www.1001-montres.fr/3640-thickbox/montre-homme-hugo-boss-bracelet-silicone.jpg" /></a></li>
    						  <li><a data-target="#pic-5" data-toggle="tab"><img src="http://www.1001-montres.fr/3640-thickbox/montre-homme-hugo-boss-bracelet-silicone.jpg" /></a></li>
    						</ul>

    					</div>
    					<div class="details col-md-6">
    					<?php $detailProduct = new Product($bdd); ?>
    						<h3 class="product-title"><?php echo $detailProduct->getProduct($_GET['product_id'],"name") ?></h3>
                			<h4>Descriptions: </h4>

    						<p class="product-description"><?php echo $detailProduct->getProduct($_GET['product_id'],"description") ?></p>
    						<h4 class="price">Price: <?php echo $detailProduct->getProduct($_GET['product_id'],"price") ?></h4>

                <div class="col-xs-12 col-md-12">
    							<button class="add-to-cart btn btn-success" type="button">add to cart</button>
    						</div>
                <div class="col-xs-6 col-md-12">

                

              </div>
  <div class="col-xs-6 col-md-12">
</div>
</div>
    				</div>
</br>
</br>
    					<div class="fb-share-button" style="float:left; padding-right: 15px" data-href="http://www.1001-montres.fr/montres-homme/1695-montre-homme-all-blacks-total-blacks-3389556803479.html" data-layout="button_count" data-size="small" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fwww.1001-montres.fr%2Fmontres-homme%2F1695-montre-homme-all-blacks-total-blacks-3389556803479.html&amp;src=sdkpreparse">Share</a></div>
    					
    					<div>
                <iframe src="https://platform.twitter.com/widgets/tweet_button.html?size=l&url=https%3A%2F%2Fdev.twitter.com%2Fweb%2Ftweet-button&via=Dreamwatch&related=twitterapi%2Ctwitter&text=You%20will%20love%20this%20product%20:&hashtags=DreamWatch"width="140"height="28"title="Twitter Tweet Button"style="border: 0; overflow: hidden;"></iframe></div>
		

		<div class="details_tab">
		<h4>DETAILS</h4>
		<?php
		$brand_id = $detailProduct->getProduct($_GET['product_id'],"brand_id");
		$origin_id = $detailProduct->getProduct($_GET['product_id'],"origin_country_id");
		$shape_id = $detailProduct->getProduct($_GET['product_id'],"shape_id");
		$material_id = $detailProduct->getProduct($_GET['product_id'],"material_id");
		$style_id = $detailProduct->getProduct($_GET['product_id'],"style_id");
		$functionality_id = $detailProduct->getProduct($_GET['product_id'],"functionality_id");
		$getFunctionality = new Functionality($bdd);
		?>
		
	 <div><strong>BRAND :</strong><?php echo $detailProduct->getSubCategoryname($brand_id,"brands"); ?></div>
	 <div><strong>ORIGIN COUNTRY :</strong> <?php echo $detailProduct->getSubCategoryname($origin_id,"brands_country"); ?></div>
	 <div><strong>QUANTITY :</strong> <?php echo $detailProduct->getProduct($_GET['product_id'],"quantity") ?></div>
	  <div><strong>REFERENCE :</strong> <?php echo $detailProduct->getProduct($_GET['product_id'],"reference") ?></div>
	  <div><strong>SHAPE :</strong> <?php echo $detailProduct->getSubCategoryname($shape_id,"shapes"); ?></div>
	  <div><strong>MATERIALS :</strong> <?php echo $detailProduct->getSubCategoryname($material_id,"materials"); ?></div>
	  <div><strong>STYLE :</strong> <?php echo $detailProduct->getSubCategoryname($style_id,"styles"); ?></div>
	  <div><strong>FUNCTIONALITIES :</strong> -

	  <?php 

	  	if(strpos($functionality_id,",")==true)
		{
			$tab = explode(",", $functionality_id);
			
			foreach($tab as $value){
				
				echo " - ".$getFunctionality->getFunctionality($value);
			}
		}
		else
		{
			echo $getFunctionality->getFunctionality($functionality_id);
		} 

	  ?>

	  </div>
    	</div>
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
	<script>
	$(document).ready(function(){

		$('.single-item').slick({
			autoplay: true,
			speed: 1500,
  			fade: true,
  			dots:true,
  			adaptiveHeight: true,
  			accessibility:true
		});

	cbpHorizontalMenu.init();


    $('#list').click(function(event){event.preventDefault();$('#products .item').addClass('list-group-item');});
    $('#grid').click(function(event){event.preventDefault();$('#products .item').removeClass('list-group-item');$('#products .item').addClass('grid-group-item');});

	});

	</script>
</body>
</html>
