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

  $first_name = $_POST['firstname'];
  $last_name =  $_POST['lastname'];
  $email =  $_POST['email'];
  $email_confirmation =  $_POST['email_confirmation'];
  $password =  $_POST['password'];
  $gender =  $_POST['gender'];
  $is_admin = $_POST['administrator'];

  switch ($gender) {
    case 'Male':
    $check_male = "checked";
    $check_female = "";
    break;

    default:
    $check_female = "checked";
    $check_male = "";
    break;
  }

  $address =  $_POST['address'];
  $zipcode =  $_POST['zipcode'];
  $birth_date = $_POST['birthdate'];
}
else
{
  $first_name = "";
  $last_name =  "";
  $email =  "";
  $password =  "";
  $gender =  "";
  $address =  "";
  $birth_date =  "";
  $zipcode =  "";
  $check_male = "";
  $check_female = "";
  $email_confirmation = "";
  $avatar = "";
}

if(!empty($_POST)){

  $nbletter_first_name = strlen($_POST['firstname']);
  $nbletter_last_name = strlen($_POST['lastname']);
  $nbletter_password = strlen($_POST['password']);
  $nbletter_password_confirmation = strlen($_POST['password_confirmation']);

  if($nbletter_first_name < 3 || $nbletter_first_name > 10){

    $error[] = "Invalid first name<br/>";
    $class_message = "error";
  }

  if($nbletter_last_name < 3 || $nbletter_last_name > 10){

    $error[] = "Invalid last name<br/>";
    $class_message = "error";
  }
  
  if($nbletter_password < 3 || $nbletter_password > 10 && $nbletter_password_confirmation < 3 || $nbletter_password_confirmation > 10 || $_POST['password']!= $_POST['password_confirmation']){

    $error[] = "Invalid password or password confirmation<br/>";
    $class_message = "error";
  }

  if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) || $_POST['email'] != $_POST['email_confirmation']) {

    $error[] = "Invalid email or email confirmation<br/>";
    $class_message = "error";
  }


  if(isset($_FILES['avatar']['name']))
  {
    //if no errors...
    if(!$_FILES['avatar']['error'])
    {
      //now is the time to modify the future file name and validate the file
      $new_file_name = strtolower($_FILES['avatar']['tmp_name']); //rename file
      if($_FILES['avatar']['size'] > (1024000)) //can't be larger than 1 MB
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
        $email_foler = str_replace('@', '', $email);
        $salt = substr(str_shuffle("azertyuiopqsdfghjklmwxvbn123456789"),1,8);

        $email_salt = $email_foler.$salt;

        if(!is_dir("../uploads/".$email_salt."")) //if folder not exist
        {
          mkdir("../uploads/".$email_salt."", 0777, true);
          $currentDir = getcwd();
          $target = $currentDir .'/../uploads/'.$email_salt.'/'.basename($_FILES['avatar']['name']);
          $target_final = '/uploads/'.$email_salt.'/'.basename($_FILES['avatar']['name']);
          //echo $target;
          move_uploaded_file($_FILES['avatar']['tmp_name'], '..'.$target_final);
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
      $message = 'Ooops!  Your upload triggered the following error:  '.$_FILES['avatar']['error'];
      $target = "";
    }
  }
  else
  {
    $target_final = "";
  }

  if(empty($error))
  {
    $newUser = new User($bdd);
    if($newUser->existUser($email))
    {
     $error[] = "This username already exists";
     $class_message = "error";
   }
   else 
   {

    if($newUser->register($first_name,$last_name,$email,$password,$gender,$zipcode,$address,$birth_date,$target_final,$is_admin)==TRUE)
    {
      $error[] = "The user was created successfully";
      $class_message = "validate";
    }
  }
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
	<link rel="stylesheet" type="text/css" href="../asset/plugins/HorizontalDropDownMenu/css/component.css" />

	<link rel="stylesheet" type="text/css" href="../asset/js/jquery/jquery-ui.css"/>
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
              <H2 class="title">ADD A USER</H2>

              <p class="<?php echo $class_message; ?>"><?php 

                if(isset($error))
                {
                  foreach ($error as $error) {

                    echo $error;
                  }
                }

                ?></p>        
          <form action="add_user.php" method="post" id="registration"  class="formulaire" enctype="multipart/form-data">
              <p class="gender">
              <label for="gender">Gender :</label>
              <input type="radio" name="gender" value="Male" id="gender" <?php echo $check_male; ?> checked>&nbsp;&nbsp;&nbsp;Male
              <input type="radio" name="gender" value="Female" id="gender" <?php echo $check_female; ?>>&nbsp;&nbsp;&nbsp;Female
              </p>
              <p class="input">
              <label for="firstname">First name :</label>
              <input type="text"  name="firstname" id="firstname" placeholder="Paul" value="<?php echo $first_name; ?>">
              </p>
              <p class="input">
              <label for="lastname">Last name :</label>
              <input type="text" name="lastname" placeholder="Dupont" value="<?php echo $last_name; ?>" >
              </p>
              <p class="input">
              <label for="email">Email :</label>
            <input name="email" type="text" placeholder="paul@mail.com" value="<?php echo $email; ?>">
              </p>
              <p class="input">
              <label for="email_confirmation">Confirmation email :</label>
              <input type="text" name="email_confirmation" placeholder="Confirm your email*" value="<?php echo $email_confirmation; ?>" >
              <p class="input">
              <label for="password">Password :</label>
            <input type="password" name="password" placeholder="Your password*" >
              </p>
              <p class="input">
              <label for="password_confirmation">Password confirmation :</label>
              <input type="password" name="password_confirmation" placeholder="Confirm your password*">
              </p>
              <p class="input">
              <label for="address">Address :</label>
              <input type="text" name="address" placeholder="Address" value="<?php echo $address; ?>">
              </p>
              <p class="input">
              <label for="datebirth">Date of birth :</label>
              <input name="birthdate" type="text" data-validation="birthdate"  placeholder="(1986-01-31)" >
              </p>
              <p class="input">
              <label for="zipcode">Zipcode :</label>
              <input type="text" name="zipcode" placeholder="75000" value="<?php echo $zipcode; ?>">
              </p>
              <p>
                <label for="admin">Administrator :</label>
              <input type="radio" name="administrator" value="1" id="admin" >&nbsp;&nbsp;&nbsp;Yes
              <input type="radio" name="administrator" value="0" id="admin" checked>&nbsp;&nbsp;&nbsp;No
              </p>
              <p>
                <label for="image">Upload avatar :</label>
                <input type="file" name="avatar" size="25" id="image" />
              </p>
              <div class="clear-both"></div>
              <br/> <br/> 
            <p>
              <input id="submit" name="submit" type="submit" value="Add user" >
            </p>
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

<script>
	$(document).ready(function(){


		cbpHorizontalMenu.init();


		$('#functionalities').multiselect();

  });

	$('#search_brand').autocomplete({

		source : '../functions/list-brands.php'

	});

</script>
</body>
</html>