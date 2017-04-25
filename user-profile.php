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

if(isset($_POST['saveprofile']))
{

  $first_name = $_POST['firstname'];
  $last_name =  $_POST['lastname'];
  $email =  $_POST['email'];
  $gender =  $_POST['gender'];
  $user_id = $_POST['user_id'];

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
  $alias = $_POST['alias'];
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
  $alias = "";

}

if(isset($_POST['saveprofile'])){

  $nbletter_first_name = strlen($_POST['firstname']);
  $nbletter_last_name = strlen($_POST['lastname']);
  
  if($nbletter_first_name < 3 || $nbletter_first_name > 10){

    $error[] = "Invalid first name<br/>";
    $class_message = "error";
  }

  if($nbletter_last_name < 3 || $nbletter_last_name > 10){

    $error[] = "Invalid last name<br/>";
    $class_message = "error";
  }

  if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

    $error[] = "Invalid email<br/>";
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

        if(!is_dir("uploads/".$email_salt."")) //if folder not exist
        {
          mkdir("uploads/".$email_salt."", 0777, true);
          $currentDir = getcwd();
          $target = $currentDir .'uploads/'.$email_salt.'/'.basename($_FILES['avatar']['name']);
          $target_final = 'uploads/'.$email_salt.'/'.basename($_FILES['avatar']['name']);
          //echo $target;
          move_uploaded_file($_FILES['avatar']['tmp_name'], $target_final);
          $message = 'Congratulations!  Your file was accepted.';

          $editUser = new User($bdd);

          if($target_final!=$editUser->getUser($user_id,"avatar"))
          {
            $editUser->setUser($user_id,$target_final,"avatar");
          }
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
      $editUser = new User($bdd);

      if($email != $editUser->getUser($user_id,"email"))
      {
          if($editUser->existUser($email))
          {
              $error[] = "This email already exists";
          }
          else
          {
              $editUser->setUser($user_id,$email,"email");
          }
      }
      else
      {
          if($first_name!=$editUser->getUser($user_id,"first_name"))
          {
            $editUser->setUser($user_id,$first_name,"first_name");
          }

          if($last_name!=$editUser->getUser($user_id,"last_name"))
          {
            $editUser->setUser($user_id,$last_name,"last_name");
          }

          if($gender!=$editUser->getUser($user_id,"gender"))
          {
            $editUser->setUser($user_id,$gender,"gender");
          }

          if($zipcode!=$editUser->getUser($user_id,"zipcode_id"))
          {
            $editUser->setUser($user_id,$zipcode,"zipcode_id");
          }

          if($address!=$editUser->getUser($user_id,"address"))
          {
            $editUser->setUser($user_id,$address,"address");
          }

          if($birth_date!=$editUser->getUser($user_id,"birthdate"))
          {
            $editUser->setUser($user_id,$birth_date,"birthdate");
          }

          if($alias!=$editUser->getUser($user_id,"alias"))
          {
            $editUser->setUser($user_id,$alias,"alias");
          }

          $error[] = "The user was modified successfully";
          $class_message = "validate";
        }
      }
   }


if(isset($_POST['send_message']))
{
	

	if($_POST['message']!="" && $_POST['receive_user']!=0)
	{
		
		//echo $_POST['message'];
		//echo $_POST['receive_user'];
		//echo $user_id;
		$sendMessage = new Message($bdd);
		$sendMessage->addMessage($_POST['message'],$_POST['receive_user'],$user_id);
	}
}

if(isset($_GET['delete_id']) && is_numeric($_GET['delete_id']))
{
	$deleteUser = new User($bdd);

  if($deleteUser->getAdmin($_GET['delete_id'])==1)
  {
    $error[] = "You can't delete an administrator";
    $class_message = "error";

  }
  else
  {
    $deleteUser->deleteUser($_GET['delete_id']);
    $class_message = "validate";
    $error[] = "The user has been deleted";
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
				      <span class="icon"><i class="glyphicon glyphicon-search"></i></span>
				      <input type="hidden" name="submit_search"/>
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
<div class="modal_form">

			<?php

				$editUser = new User($bdd);

				$zipcode_id = $editUser->getUser($user_id,"zipcode_id");

				if($editUser->getUser($user_id,"gender")=="Male"){

					$check_male = "checked";
				}
				else
				{
					$check_male = "";
				}
				
				if($editUser->getUser($user_id,"gender")=="Female")
				{
					$check_female = "checked";
				}
				else
				{
					$check_female = "";
				}

				if($editUser->getUser($user_id,"is_admin")==1){

					$check_yes = "checked";
				}
				else
				{
					$check_yes = "";
				}
				
				if($editUser->getUser($user_id,"is_admin")==0)
				{
					$check_no = "checked";
				}
				else
				{
					$check_no = "";
				}

			?>
				<h4>My profile username : <?php echo $editUser->getUser($user_id,"alias")?></h4>
				<br/>

			<p class="text-left">
			<?php
			if($editUser->getUser($user_id,"avatar")!="")
			{
				$avatar = $editUser->getUser($user_id,"avatar");

				echo "<img src='".$avatar."' width='200' class='avatar' />";
			}
			else
			{
				echo "<img src='https://upload.wikimedia.org/wikipedia/commons/a/ac/No_image_available.svg' width='150'/>";
			}
			?>
			</p>
			<br/><br/><br/>
			<form action="user-profile.php" method="POST" enctype="multipart/form-data">
              <p class="gender">
              <label for="gender">Gender :</label>
              <input type="radio" class="form-check" name="gender" value="Male" id="gender" <?php echo $check_male; ?>>&nbsp;&nbsp;&nbsp;Male
              <input type="radio" class="form-check"  name="gender" value="Female" id="gender" <?php echo $check_female; ?>>&nbsp;&nbsp;&nbsp;Female
              </p>
              <p class="input">
              <label for="username">Username :</label>
              <input type="text"  name="alias" id="username" placeholder="Mushu" value="<?php echo $editUser->getUser($user_id,"alias") ?>">
              </p>
              <p class="input">
              <label for="firstname">First name :</label>
              <input type="text"  name="firstname" id="firstname" placeholder="Paul" value="<?php echo ucfirst($editUser->getUser($user_id,"first_name")) ?>">
              </p>
              <p class="input">
              <label for="lastname">Last name :</label>
              <input type="text" name="lastname" placeholder="Dupont" value="<?php echo strtoupper($editUser->getUser($user_id,"last_name")) ?>" >
              </p>
              <p class="input">
              <label for="email">Email :</label>
            <input name="email" type="text" placeholder="paul@mail.com" value="<?php echo $editUser->getUser($user_id,"email") ?>">
              </p>
              <p class="input">
              <label for="address">Address :</label>
              <input type="text" name="address" placeholder="Address" value="<?php echo $editUser->getUser($user_id,"address") ?>">
              </p>
              <p class="input">
              <label for="datebirth">Date of birth :</label>
              <input name="birthdate" type="text" data-validation="birthdate"  placeholder="(1986-01-31)" value="<?php echo $editUser->getUser($user_id,"birthdate") ?>" >
              </p>
              <p class="input">
              <label for="zipcode">Zipcode :</label>
              <input type="text" name="zipcode" placeholder="75000" value="<?php echo $editUser->getUser($user_id,"zipcode_id") ?>">
              <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
              </p>
              <p class="input">
              <label for="zipcode">Ville :</label>
              <span><?php echo $editUser->getTown($zipcode_id); ?></span>
              </p>
               <p>
                <label for="image">Upload avatar :</label>
                <input type="file" name="avatar" size="25" id="image" />
              </p>
              <p>
              <label for="image"></label>
              <input type="hidden" name="saveprofile" />
              <input type="submit" name="" class="btn btn-success" value="Save my profile" />
              </p>
              </form>
              <div class="clear-both"></div>
              <br/>
			<br/>
				<div>
		<p><strong>SEND A MESSAGE TO A USER :</strong></p>
		<form method="POST" action="user-profile.php">
		<label>Choose user :</label>
		<select name="receive_user">
			<option value="0">Choose a user</option>
			<?php
				$editUser->getSelectUser($user_id);
			?>
		</select><br/>
		<label>Message :</label>
		<textarea rows="4" cols="50" name="message">Votre message...</textarea>
		<input type="hidden" name="send_message"><br/>
		<label></label>
		<input type="submit" value="Send a message" class="btn btn-warning">
		</form>


		<br/><br/><br/><br/>
		<ul class="message_system">
		<li>READ YOUR MESSAGE :<br/><br/>	</li>
			<?php 

			$message = new Message($bdd);
			$message->readMessage($user_id);

			?>
		</ul>

	</div>
</div>
	</div>
	<br/>
	<br/>
	<br/>
	<br/>
	<br/>
	<br/>
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
