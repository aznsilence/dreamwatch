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

	$color = "";
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
	$product_id = $_POST['product_id'];

	$newProduct = new Product($bdd);
	$brand_id = $newProduct->getBrandID($_POST['brand']);


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
		$color = "red";
	}

	if(isset($_POST['reference']) && $_POST['reference']=="")
	{
		$error_product .= "You must add a reference<br/>";
		$color = "red";
	}

	if(isset($_POST['category']) && $_POST['category']=="0")
	{
		$error_product .= "You must choose a category<br/>";
		$color = "red";
	}

	if(isset($_POST['material']) && $_POST['material']=="0")
	{
		$error_product .= "You must choose a material<br/>";
		$color = "red";
	}

	if(isset($_POST['shape']) && $_POST['shape']=="0")
	{
		$error_product .= "You must choose a shape<br/>";
		$color = "red";
	}

	if(isset($_POST['country']) && $_POST['country']=="0")
	{
		$error_product .= "You must choose a country<br/>";
		$color = "red";
	}

	if(isset($_POST['style']) && $_POST['style']=="0")
	{
		$error_product .= "You must choose a style<br/>";
		$color = "red";
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
		//echo "no error";
		$editProduct = new Product($bdd);
			

		if($name != $editProduct->getProduct($product_id,"name"))
		{
			//echo "change name";
			$editProduct->setProduct($product_id,$name,"name");
		}
		

		if($reference != $editProduct->getProduct($product_id,"reference"))
		{
			//echo "change name";
			$editProduct->setProduct($product_id,$reference,"reference");
		}	


		if($serial_number != $editProduct->getProduct($product_id,"serial_number"))
		{
			//echo "change name";
			$editProduct->setProduct($product_id,$serial_number,"serial_number");
		}	

		if($price != $editProduct->getProduct($product_id,"price"))
		{
			//echo "change name";
			$editProduct->setProduct($product_id,$price,"price");
		}


		if($description != $editProduct->getProduct($product_id,"description"))
		{
			//echo "change name";
			$editProduct->setProduct($product_id,$description,"description");
		}


		if($quantity != $editProduct->getProduct($product_id,"quantity"))
		{
			//echo "change name";
			$editProduct->setProduct($product_id,$quantity,"quantity");
		}	


		if($category != $editProduct->getProduct($product_id,"category_id"))
		{
			//echo "change name";
			$editProduct->setProduct($product_id,$category,"category_id");
		}

	
		if($brand_id != $editProduct->getProduct($product_id,"brand_id"))
		{
			//echo "change name";
			$editProduct->setProduct($product_id,$brand_id,"brand_id");
		}

		if($style != $editProduct->getProduct($product_id,"style_id"))
		{
			//echo "change name";
			$editProduct->setProduct($product_id,$style,"style_id");
		}

		if($material != $editProduct->getProduct($product_id,"material_id"))
		{
			//echo "change name";
			$editProduct->setProduct($product_id,$style,"material_id");
		}

		if($shape != $editProduct->getProduct($product_id,"shape_id"))
		{
			//echo "change name";
			$editProduct->setProduct($product_id,$shape,"shape_id");
		}

		if($origin_country != $editProduct->getProduct($product_id,"origin_country_id"))
		{
			//echo "change name";
			$editProduct->setProduct($product_id,$origin_country,"origin_country_id");
		}

		if($functionalities != $editProduct->getProduct($product_id,"functionality_id"))
		{
			//echo "change name";
			$editProduct->setProduct($product_id,$functionalities,"functionality_id");
		}

		if($target_final != $editProduct->getProduct($product_id,"image_src"))
		{
			//echo "change name";
			$editProduct->setProduct($product_id,$target_final,"image_src");
		}

			$color = "#a2bb00";
			$error_product = "Your product has been modified";
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
	$message = "";

}

if(isset($_GET['delete_id']) && is_numeric($_GET['delete_id']))
{
	$deleteProduct = new Product($bdd);
	$deleteProduct->deleteProduct($_GET['delete_id']);
	$color = "#a2bb00";
	$error_product = "Your product has been deleted";
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
		<article>
			<br/>
			<br/>
				<table>
			<caption>List of the products</caption>
			<?php
			if(isset($error_file) && $error_file != "")
			{
				echo "<span style='color:".$color."' class='message'>".$error_file."</span>";
			}

			if(isset($error_product) && $error_product != "")
			{
				echo "<span style='color:".$color."' class='message'>".$error_product."</span>";
			}
			?>
			<thead>
				<tr>
					<th>ID</th>
					<th>Name of the product</th>
					<th>Reference</th>
					<th><span class="glyphicon glyphicon-trash"></span></th>
					<th><span class="glyphicon glyphicon-pencil"></span></th>
				</tr>
			</thead>
			<tbody>
			<?php
			$req = $bdd->query("SELECT name,id,reference FROM products");
			
			while($data = $req->fetch())
			{
			?>
				<tr>
					<td><?php echo $data['id'] ?></td>
					<td><?php echo $data['name'] ?></td>
					<td><?php echo $data['reference'] ?></td>
					<td><a href="edit_product.php?delete_id=<?php echo $data['id']; ?>" class="delete btn btn-danger">delete</a></td>
					<td><a href="#myModal" data-toggle="modal" id="<?php echo $data['id']; ?>" data-target="#edit-modal" class="edit btn btn-primary">edit</a></td>
				</tr>
			<?php
			}
			?>
			</tbody>
		</table>
		</article>

		<br/>
		<br/>
		<br/>
	</div>


<div id="edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <form action="edit_product.php" method="POST" enctype="multipart/form-data">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body edit-content">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button class="btn btn-primary" type="submit" name="modal_submit">Save changes</button>
            </div>
            </form>
        </div>
    </div>
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

	    $('#edit-modal').on('show.bs.modal', function(e) {

        var $modal = $(this),
            esseyId = e.relatedTarget.id;

        $.ajax({
            cache: false,
            type: 'POST',
            url: 'backend_edit_product.php',
            data: 'EID=' + esseyId,
            success: function(data) {
                $modal.find('.edit-content').html(data);
            }
        });
    })

</script>
</body>
</html>