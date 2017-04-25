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
				$user_id = $_POST['EID'];

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
				<h4>Edit User : <?php echo ucfirst($editUser->getUser($user_id,"first_name"))." ".strtoupper($editUser->getUser($user_id,"last_name")) ?></h4>
				<br/>

			<p class="text-center">
			<?php
			if($editUser->getUser($user_id,"avatar")!="")
			{
				$avatar = $editUser->getUser($user_id,"avatar");

				echo "<img src='../".$avatar."' width='200' class='avatar' />";
			}
			else
			{
				echo "";
			}
			?>
			</p>
			<br/><br/><br/>
              <p class="gender">
              <label for="gender">Gender :</label>
              <input type="radio" name="gender" value="Male" id="gender" <?php echo $check_male; ?>>&nbsp;&nbsp;&nbsp;Male
              <input type="radio" name="gender" value="Female" id="gender" <?php echo $check_female; ?>>&nbsp;&nbsp;&nbsp;Female
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
                <label for="admin">Administrator :</label>
              <input type="radio" name="administrator" value="1" id="admin" <?php echo $check_yes ?>>&nbsp;&nbsp;&nbsp;Yes
              <input type="radio" name="administrator" value="0" id="admin" <?php echo $check_no ?>>&nbsp;&nbsp;&nbsp;No
              </p>
               <p>
                <label for="image">Upload avatar :</label>
                <input type="file" name="avatar" size="25" id="image" />
              </p>
              <div class="clear-both"></div>
              <br/>
			<br/>
</div>

</main>
</body>
<script src="../asset/js/jquery/external/jquery/jquery.js"></script>
<script src="../asset/js/jquery/jquery-ui.js"></script>
<script src="../asset/framework/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="../asset/plugins/multiselect/multiselect.js"></script>
</html>