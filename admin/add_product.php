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
	$brand_id = $newProduct->getBrandID($_POST['brand']);


	if(isset($_POST['functionalities'])  && is_array($_POST['functionalities']))
	{
		$functionalities = implode(',',$_POST['functionalities']);
	}
	else
	{
		$functionalities = "";
		$error_product .= "You must add a functionalities<br/>";
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


  if(isset($_FILES['image_product']['name']))
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
        $email_foler = str_replace('@', '', $reference);
        $salt = substr(str_shuffle("azertyuiopqsdfghjklmwxvbn123456789"),1,8);

        $email_salt = $email_foler.$salt;

        if(!is_dir("../uploads/".$email_salt."")) //if folder not exist
        {
          mkdir("../uploads/".$email_salt."", 0777, true);
          $currentDir = getcwd();
          $target = $currentDir .'/../uploads/'.$email_salt.'/'.basename($_FILES['image_product']['name']);
          $target_final = '/uploads/'.$email_salt.'/'.basename($_FILES['image_product']['name']);
          //echo $target;
          move_uploaded_file($_FILES['image_product']['tmp_name'], '..'.$target_final);
          $message = 'Congratulations!  Your file was accepted.';

          $editUser = new User($bdd);
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
  else
  {
    $target_final = "";
  }

	if($error_product=="")
	{
		echo "no error";
		$newProduct = new Product($bdd);
		if($newProduct->existReference($reference)==true)
		{
			$error_product .= "The reference already exist";
		}
		else
		{
			$newProduct->addProduct($name,$reference,$serial_number,$price,$description,$quantity,$category,$brand_id,$style,$material,$shape,$origin_country,$functionalities,$target_final);			
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
			<?php
			if(isset($error_product) && $error_product != "")
			{
				echo "<span style='color:red'>".$error_product."</span>";
			}
			?>					
			<form action="add_product.php" method="POST" enctype="multipart/form-data">
				<h4>Add product</h4>
				<br/>

				<p>
					<label for="name">Name:</label>
					<input type="text" name="name" id="name" value="<?php echo $name; ?>" />
				</p>
				<p>
					<label for="reference">Reference:</label>
					<input type="text" name="reference" id="reference"  value="<?php echo $reference; ?>"/>
				</p>
				<p>
					<label for="serial_number">Serial number:</label>

					<input type="text" name="serial_number" id="serial_number"  value="<?php echo $serial_number; ?>"/>
				</p>
				<p>
					<label for="search-brand">Brand:</label>

					<input type="text" name="brand" id="search_brand"  value="<?php echo $brand; ?>" />
				</p>
				<p>

					<label for="category">Country:</label>

					<select name="country">
						<option value="0" selected="selected">Choose a country</option>
						<?php
						$newCategory = new Product($bdd);
						$newCategory->getSelecteur("brands_country");
						?>

					</select>
				</p>
				<p>
					<label for="category">Category:</label>

					<select name="category">
						<option value="0" selected="selected">Choose a category</option>
						<?php
						$newCategory = new Product($bdd);
						$newCategory->getSelecteur("categories");
						?>
					</select>
				</p>
				<p>
					<label for="price">Price:</label>

					<input type="text" name="price" id="price"  value="<?php echo $price; ?>" />
				</p>
				<p>
					<label for="description">Description:</label>

					<input type="text" name="description" id="description"  value="<?php echo $description; ?>" />
				</p>
				<p>
					<label for="quantity">Quantity:</label>

					<input type="text" name="quantity" id="quantity"  value="<?php echo $quantity; ?>" />
				</p>

				<p>
					<label for="material">Material:</label>

					<select name="material">
						<option value="0">Choose a material</option>									
						<?php
						$newMaterial = new Product($bdd);
						$newMaterial->getSelecteur("materials");
						?>
					</select>
				</p>

				<p>
					<label for="style">Styles:</label>

					<select name="style">
						<option value="0">Choose a style</option>									
						<?php
						$newStyle = new Product($bdd);
						$newStyle->getSelecteur("styles");
						?>
					</select>
				</p>
				<p>

					<label for="shape">Shape:</label>
					<select name="shape">
						<option value="0">Choose a shape</option>
						<?php
						$newShape = new Product ($bdd);
						$newShape->getSelecteur("shapes");
						?>
					</select>
				</p>
			</p>
			<p>
				<label for="shape">Functionalities:</label>
				<select id="functionalities" multiple="multiple" name="functionalities[]">
					<?php
					$newShape = new Product ($bdd);
					$newShape->getSelecteur("functionalities");
					?>
				</select>
			</p>

			<p>
				<label for="image">Upload image :</label>
				<input type="file" name="image_product" size="25" id="image" />
				<input type="hidden" name="type" value="addProduct" />
			</p>
			<br/>
			<input type="submit" value="Add a product">					
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