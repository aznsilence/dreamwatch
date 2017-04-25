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
  $gender =  $_POST['gender'];
  $is_admin = $_POST['administrator'];
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

}

if(!empty($_POST)){

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

          if($is_admin!=$editUser->getUser($user_id,"is_admin"))
          {
            $editUser->setUser($user_id,$is_admin,"is_admin");
          }

          $error[] = "The user was modified successfully";
          $class_message = "validate";
        }
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
			<caption>List of the users</caption>
			<?php
			if(isset($error_file) && $error_file != "")
			{
				echo "<span style='color:".$color."' class='message'>".$error_file."</span>";
			}


        if(isset($error))
        { 
          echo "<span class='".$class_message."'>";

          foreach ($error as $error) {

            echo $error;
          }

          echo "</span>";
        }
			?>
			<thead>
				<tr>
					<th>ID</th>
					<th>Name of the user</th>
					<th>Email</th>
					<th>Administrator</th>
					<th><span class="glyphicon glyphicon-trash"></span></th>
					<th><span class="glyphicon glyphicon-pencil"></span></th>
				</tr>
			</thead>
			<tbody>
			<?php
			$req = $bdd->query("SELECT first_name,last_name,id,email,is_admin FROM users");
			
			while($data = $req->fetch())
			{
				if($data['is_admin']==1)
				{
					$is_admin = "Yes";
				}
				else
				{
					$is_admin = "No";
				}
			?>
				<tr>
					<td><?php echo $data['id'] ?></td>
					<td><?php echo ucfirst($data['first_name'])." ".strtoupper($data['last_name']) ?></td>
					<td><?php echo $data['email'] ?></td>
					<td><?php echo $is_admin; ?></td>
					<td><a href="edit_user.php?delete_id=<?php echo $data['id']; ?>" class="delete btn btn-danger">delete</a></td>
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
        <form action="edit_user.php" method="POST" enctype="multipart/form-data">
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
            url: 'backend_edit_user.php',
            data: 'EID=' + esseyId,
            success: function(data) {
                $modal.find('.edit-content').html(data);
            }
        });
    })

</script>
</body>
</html>