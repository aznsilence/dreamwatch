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


$error_product = "";

if(!empty($_POST))
{

if(isset($_POST['product']))
{

$target_final = "";
$error_product = "";
$name = $_POST['name'];
$reference = $_POST['reference'];
$serial_number = $_POST['serial_number'];
$brand = $_POST['brand'];
$category = $_POST['category'];
$price = $_POST['price'];
$description = $_POST['description'];
$quantity = $_POST['quantity'];
$material = $_POST['material'];
$style = $_POST['style'];
$shape = $_POST['shape'];
$shape = $_POST['shape'];
$origin_country = $_POST['country'];

$newProduct = new Product($bdd);
echo $newProduct->getBrandID($_POST['brand']);


if(isset($_POST['functionalities'])  && is_array($_POST['functionalities']))
{
	$functionalities = implode(',',$_POST['functionalities']);
}
else
{
	$functionalities = "";
}

if(isset($_POST['name']) && $_POST['name']=="")
{
	$error_product .= "You must add a name<br/>";
}

if(isset($_POST['reference']) && $_POST['reference']=="")
{
	$error_product .= "You must add a reference<br/>";
}

if(isset($_POST['category']) && $_POST['category']=="0")
{
	$error_product .= "You must choose a category<br/>";
}

if(isset($_POST['material']) && $_POST['material']=="0")
{
	$error_product .= "You must choose a material<br/>";
}

if(isset($_POST['shape']) && $_POST['shape']=="0")
{
	$error_product .= "You must choose a shape<br/>";
}

if(isset($_POST['country']) && $_POST['country']=="0")
{
	$error_product .= "You must choose a country<br/>";
}

if(isset($_POST['style']) && $_POST['style']=="0")
{
	$error_product .= "You must choose a style<br/>";
}


	if($_FILES['image_product']['name'])
	{
		//if no errors...
		if(!$_FILES['image_product']['error'])
		{
			//now is the time to modify the future file name and validate the file
			$new_file_name = strtolower($_FILES['image_product']['tmp_name']); //rename file
			if($_FILES['image_product']['size'] > (1024000)) //can't be larger than 1 MB
			{
				$valid_file = false;
				$message = 'Oops!  Your file\'s size is to large.';
			}
			else
			{
				$valid_file = true;
			}
			
			//if the file has passed the test
			if($valid_file)
			{
				//move it to where we want it to be
				
				if(!is_dir("uploads/".$reference."")) //if folder not exist
				{
					mkdir("uploads/".$reference."", 0777, true);
					$currentDir = getcwd();
					$target = $currentDir .'/uploads/'.$reference.'/'.basename($_FILES['image_product']['name']);
					$target_final = '/uploads/'.$reference.'/'.basename($_FILES['image_product']['name']);
					//echo $target;
					move_uploaded_file($_FILES['image_product']['tmp_name'], $target);
					$message = 'Congratulations!  Your file was accepted.';
				}
				else
				{
					$message = 'The reference folder already exist';
				}
			}
		}
		//if there is an error...
		else
		{
			//set that to be the returned message
			$message = 'Ooops!  Your upload triggered the following error:  '.$_FILES['image_product']['error'];
			$target = "";
		}
	}

	if($error_product=="")
	{
		echo "no error";
		$newProduct = new Product($bdd);
		if($newProduct->getReference($reference)==true)
		{
			$error_product .= "The reference already exist";
		}
		else
		{
			$newProduct->addProduct($name,$reference,$serial_number,$price,$description,$quantity,$category,$brand,$style,$material,$shape,$origin_country,$functionalities,$target_final);			
		}
	}
}

if($_POST['add_category'])
{
	if($_POST['category_name']!="")
	{
		$newCategory = new Category($bdd);
		$newCategory->addCategory($_POST['category_name']);
	}
}
}
else
{
	$name = "";
	$reference = "";
	$serial_number = "";
	$brand = "";
	$category = "";
	$price = "";
	$description = "";
	$quantity = "";
	$material = "";
	$style = "";
	$shape = "";
	$functionalities = "";
	$origin_country = "";
	$category_name = "";
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

    <div class="well well-sm">
        <strong>Display</strong>
        <div class="btn-group">
            <a href="#" id="list" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-th-list">
            </span>List</a> <a href="#" id="grid" class="btn btn-default btn-sm"><span
                class="glyphicon glyphicon-th"></span>Grid</a>
        </div>
    </div>
    
    <div id="products" class="row list-group">
        <div class="item  col-xs-4 col-lg-4">
            <div class="thumbnail">
                <img class="group list-group-image" src="http://placehold.it/400x250/000/fff" alt=""  />
                <div class="caption">
                    <h4 class="group inner list-group-item-heading">
                        Product title</h4>
                    <p class="group inner list-group-item-text">
                        Product description... Lorem ipsum dolor sit amet, consectetuer adipiscing elit,
                        sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.</p>
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <p class="lead">
                                $21.000</p>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <a class="btn btn-success" href="http://www.jquery2dotnet.com">Details</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
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


    $('#list').click(function(event){event.preventDefault();$('#products .item').addClass('list-group-item');});
    $('#grid').click(function(event){event.preventDefault();$('#products .item').removeClass('list-group-item');$('#products .item').addClass('grid-group-item');});

     $('#functionalities').multiselect();

     $.validate({
		  modules : 'security,date',
		  onModulesLoaded : function() {
		    var optionalConfig = {
		      fontSize: '10pt',
		      padding: '4px',
		      bad : 'Very bad',
		      weak : 'Weak',
		      good : 'Good',
		      strong : 'Strong'
		    };

		    $('input[name="password"]').displayPasswordStrength(optionalConfig);
		  }
		});
	
	});

	    $('#search_brand').autocomplete({

    source : '../functions/list-brands.php'
	
	});

	</script>
</body>
</html>