<?php
include_once('../functions/DBconf.php');

spl_autoload_register(function($class) {
	include "../functions/".$class .".class.php";
});

if(!isset($_SESSION['user_id']) && !isset($_COOKIE['user_id']))
{
	header("Location: ../login.php");
}

if(isset($_SESSION['user_id']) || isset($_COOKIE['user_id']))
{
	
	if(isset($_SESSION['user_id']))
	{
		$user_id = $_SESSION['user_id'];
	}
	else if (isset($_COOKIE['user_id']))
	{
		$user_id = $_COOKIE['user_id'];
	}

	$userData = new User($bdd);
	
	if($userData->getAdmin($user_id)!=1)
	{
		//echo "check admin";
		header("Location: ../index.php");
	}
}



if(!empty($_POST))
{

	$error = "";
	$name = $_POST['name'];

	if($name!="")
	{
		$newStyle = new Style($bdd);

		if($newStyle->existStyle($name)==1)
		{
			$error = "Your style already exist";
			$class_message = "error";
		}
		else
		{
			$newStyle->addStyle($name);

			$error = "Your style has been created";
			$class_message = "validate";
		}
	}
	else
	{
		$error = "You must add a style name";
		$class_message = "error";		
	}

}
else
{
	$name = "";
}


?> 
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link href="https://fonts.googleapis.com/css?family=Playfair+Display" rel="stylesheet"> 
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet"> 
	<link rel="stylesheet" href="../asset/css/dreamwatch_site.css" type="text/css">
	<link href="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/theme-default.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="../asset/framework/bootstrap/css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" type="text/css" href="../asset/plugins/HorizontalDropDownMenu/css/component.css" />
	<link rel="stylesheet" type="text/css" href="../asset/plugins/Carousel/slick/slick.css"/>
	<link rel="stylesheet" type="text/css" href="../asset/js/jquery/jquery-ui.css"/>
	<link rel="stylesheet" type="text/css" href="../asset/plugins/Carousel/slick/slick-theme.css"/>
	<link rel="stylesheet" type="text/css" href="../asset/plugins/multiselect/multiselect.css"/>
</head>
<body>
	<header class="admin">
		<div class="row text-center">
			<div class="text-center logo"><h1>THE DREAMWATCH BACK-OFFICE</h1></div>
		</div>

		<nav id="cbp-hrmenu" class="text-center admin cbp-hrmenu">
			<ul class="resize-submenu">
        <li>
          <a href="admin.php?form=functionality">USERS</a>
          <div class="cbp-hrsub">
            <div class="cbp-hrsub-inner"> 
              <div class="liste-submenu">
                <h4>User settings</h4>
                <ul>
                  <li><a href="add_user.php">Add a user</a></li>
                  <li><a href="edit_user.php">Edit a user</a></li>
                </ul>
              </div>
            </div><!-- /cbp-hrsub-inner -->
          </div><!-- /cbp-hrsub -->
        </li>
				<li>
					<a href="#" target="_SELF">PRODUCTS</a>
					<div class="cbp-hrsub">
						<div class="cbp-hrsub-inner"> 
							<div class="liste-submenu">
								<h4>Product settings</h4>
								<ul>
									<li><a href="add_product.php">Add a product</a></li>
									<li><a href="edit_product.php">Edit a product</a></li>
								</ul>
							</div>
						</div><!-- /cbp-hrsub-inner -->
					</div><!-- /cbp-hrsub -->
				</li>
				<li>
          <a href="admin.php?form=category">CATEGORIES</a>
          <div class="cbp-hrsub">
            <div class="cbp-hrsub-inner"> 
              <div class="liste-submenu">
                <h4>Category settings</h4>
                <ul>
                  <li><a href="add_category.php">Add a category</a></li>
                  <li><a href="edit_category.php">Edit a category</a></li>
                </ul>
              </div>
            </div><!-- /cbp-hrsub-inner -->
          </div><!-- /cbp-hrsub -->
        </li>
        <li>
          <a href="admin.php?form=material">MATERIALS</a>
          <div class="cbp-hrsub">
            <div class="cbp-hrsub-inner"> 
              <div class="liste-submenu">
                <h4>Material settings</h4>
                <ul>
                  <li><a href="add_material.php">Add a material</a></li>
                  <li><a href="edit_material.php">Edit a material</a></li>
                </ul>
              </div>
            </div><!-- /cbp-hrsub-inner -->
          </div><!-- /cbp-hrsub -->
        </li>
        <li>
          <a href="admin.php?form=brands">BRANDS</a>
          <div class="cbp-hrsub">
            <div class="cbp-hrsub-inner"> 
              <div class="liste-submenu">
                <h4>Brand settings</h4>
                <ul>
                  <li><a href="add_brand.php">Add a brand</a></li>
                  <li><a href="edit_brand.php">Edit a brand</a></li>
                </ul>
              </div>
            </div><!-- /cbp-hrsub-inner -->
          </div><!-- /cbp-hrsub -->
        </li>
        <li>
          <a href="admin.php?form=styles">STYLES</a>
          <div class="cbp-hrsub">
          <div class="cbp-hrsub-inner"> 
            <div class="liste-submenu">
              <h4>Style settings</h4>
              <ul>
                <li><a href="add_style.php">Add a style</a></li>
                <li><a href="edit_style.php">Edit a style</a></li>
              </ul>
            </div>
          </div><!-- /cbp-hrsub-inner -->
        </div><!-- /cbp-hrsub -->
        </li>
        <li>
          <a href="admin.php?form=shapes">SHAPES</a>
          <div class="cbp-hrsub">
            <div class="cbp-hrsub-inner"> 
              <div class="liste-submenu">
                <h4>Shape settings</h4>
                <ul>
                  <li><a href="add_shape.php">Add a shape</a></li>
                  <li><a href="edit_shape.php">Edit a shape</a></li>
                </ul>
              </div>
            </div><!-- /cbp-hrsub-inner -->
          </div><!-- /cbp-hrsub -->
        </li>
        <li>
          <a href="admin.php?form=functionality">FUNCTIONALITIES</a>
          <div class="cbp-hrsub">
            <div class="cbp-hrsub-inner"> 
              <div class="liste-submenu">
                <h4>Functionality settings</h4>
                <ul>
                  <li><a href="add_functionality.php">Add a functionality</a></li>
                  <li><a href="edit_functionality.php">Edit a functionality</a></li>
                </ul>
              </div>
            </div><!-- /cbp-hrsub-inner -->
          </div><!-- /cbp-hrsub -->
        </li>
      </ul>
    </nav>
	</header>
	<main>

		<div class="container admin">
			<br/>
			<br/>
			
			<h4>Add Style</h4>
			<?php
			if(isset($error) && $error != "")
			{
				echo "<span class='".$class_message."'>".$error."</span>";
			}
			?>	
			<br/><br/>
			<form action="add_style.php" method="POST">
				<label for="label">Name:</label>
				<input type="text" name="name" id="label" value="<?php echo $name ?>" />
				<br/>
				<br/>
				<input type="submit" value="Add style">
			</form>

		<br/>
		<br/>
		<br/>
	</div>
</main>
<script src="../asset/js/jquery/external/jquery/jquery.js"></script>
<script src="../asset/js/jquery/jquery-ui.js"></script>
<script src="../asset/framework/bootstrap/js/bootstrap.min.js"></script>
<script src="../asset/plugins/HorizontalDropDownMenu/js/cbpHorizontalMenu.min.js"></script>
<script type="text/javascript" src="../asset/plugins/Carousel/slick/slick.min.js"></script>
<script type="text/javascript" src="../asset/plugins/multiselect/multiselect.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script> 

<script>
	$(document).ready(function(){


		cbpHorizontalMenu.init();


	});


</script>
</body>
</html>