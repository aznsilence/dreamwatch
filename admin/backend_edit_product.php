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

	<link rel="stylesheet" type="text/css" href="../asset/js/jquery/jquery-ui.css"/>
	<link rel="stylesheet" type="text/css" href="../asset/plugins/multiselect/multiselect.css"/>
</head>
<body>
<div class="modal_form">

			<?php
				$product_id = $_POST['EID'];
				$editProduct = new Product($bdd);
				$brand_id = $editProduct->getProduct($product_id,"brand_id");
			?>
				<h4>Edit product : <?php echo $editProduct->getProduct($product_id,"name") ?></h4>
				<br/>

				<p>
					<label for="name">Name:</label>
					<input type="text" name="name" id="name" value="<?php echo $editProduct->getProduct($product_id,"name") ?>" />
				</p>
				<p>
					<label for="reference">Reference:</label>
					<input type="text" name="reference" id="reference"  value="<?php echo $editProduct->getProduct($product_id,"reference") ?>"/>
				</p>
				<p>
					<label for="serial_number">Serial number:</label>

					<input type="text" name="serial_number" id="serial_number"  value="<?php echo $editProduct->getProduct($product_id,"serial_number") ?>"/>
				</p>
				<p>
					<label for="search-brand">Brand:</label>

					<input type="text" name="brand" id="search_brand"  value="<?php echo $editProduct->getBrandName($brand_id) ?>" />
				</p>
				<p>

					<label for="category">Country:</label>

					<select name="country">
						<option value="0" selected="selected">Choose a country</option>
						<?php
						$newCategory = new Product($bdd);
						$newCategory->getSelecteur("brands_country",$editProduct->getProduct($product_id,"origin_country_id"));
						?>
						
					</select>
				</p>
				<p>
					<label for="category">Category:</label>

					<select name="category">
						<option value="0" selected="selected">Choose a category</option>
						<?php
						$newCategory = new Product($bdd);
						$newCategory->getSelecteur("categories",$editProduct->getProduct($product_id,"category_id"));
						?>
						
					</select>
				</p>
				<p>
					<label for="price">Price:</label>

					<input type="text" name="price" id="price"  value="<?php echo $editProduct->getProduct($product_id,"price") ?>" />
				</p>
				<p>
					<label for="description">Description:</label>

					<input type="text" name="description" id="description"  value="<?php echo $editProduct->getProduct($product_id,"description") ?>" />
				</p>
				<p>
					<label for="quantity">Quantity:</label>

					<input type="text" name="quantity" id="quantity"  value="<?php echo $editProduct->getProduct($product_id,"quantity") ?>" />
				</p>

				<p>
					<label for="material">Material:</label>

					<select name="material">
						<option value="0">Choose a material</option>									
						<?php
						$newMaterial = new Product($bdd);
						$newMaterial->getSelecteur("materials",$editProduct->getProduct($product_id,"material_id"));
						?>
					</select>
				</p>

				<p>
					<label for="style">Styles:</label>

					<select name="style">
						<option value="0">Choose a style</option>									
						<?php
						$newStyle = new Product($bdd);
						$newStyle->getSelecteur("styles",$editProduct->getProduct($product_id,"style_id"));
						?>
					</select>
				</p>
				<p>

					<label for="shape">Shape:</label>
					<select name="shape">
						<option value="0">Choose a shape</option>
						<?php
						$newShape = new Product ($bdd);
						$newShape->getSelecteur("shapes",$editProduct->getProduct($product_id,"shape_id"));
						?>
					</select>
				</p>
			</p>
			<p>
				<label for="shape">Functionalities:</label>
				<select id="functionalities" multiple="multiple" name="functionalities[]">
					<?php
					$newShape = new Product ($bdd);
					$newShape->getSelecteur("functionalities",$editProduct->getProduct($product_id,"functionality_id"));
					?>
				</select>
				<input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
			</p>

			<p>
				<label for="image">Upload image :</label>
				<input type="file" name="image_product" size="25" id="image" />
				<input type="hidden" name="type" value="addProduct" />
			</p>
			<br/>
</div>

</main>
</body>
<script src="../asset/js/jquery/external/jquery/jquery.js"></script>
<script src="../asset/js/jquery/jquery-ui.js"></script>
<script src="../asset/framework/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="../asset/plugins/multiselect/multiselect.js"></script>

<script>
	$(document).ready(function(){

		$('#functionalities').multiselect();	
	});

	$('#search_brand').autocomplete({
		source : 'list-brands.php',
	});

</script>
</html>