<?php
include_once('functions/DBconf.php');

spl_autoload_register(function($class) {
  include "functions/".$class .".class.php";
});

if(!empty($_POST))
{

  $first_name = $_POST['firstname'];
  $last_name =  $_POST['lastname'];
  $email =  $_POST['email'];
  $email_confirmation =  $_POST['email_confirmation'];
  $password =  $_POST['password'];
  $gender =  $_POST['gender'];

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

  $zipcode =  $_POST['zipcode'];
  $birth_date = $_POST['birth_date'];
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

  $color = "orange";

  $nbletter_first_name = strlen($_POST['firstname']);
  $nbletter_last_name = strlen($_POST['lastname']);
  $nbletter_password = strlen($_POST['password']);
  $nbletter_password_confirmation = strlen($_POST['password_confirmation']);

  if($nbletter_first_name < 3 || $nbletter_first_name > 10){

    $error[] = "Invalid first name<br/>";
  }

  if($nbletter_last_name < 3 || $nbletter_last_name > 10){

    $error[] = "Invalid last name<br/>";
  }

  if($nbletter_password < 3 || $nbletter_password > 10 && $nbletter_password_confirmation < 3 || $nbletter_password_confirmation > 10 || $_POST['password']!= $_POST['password_confirmation']){

    $error[] = "Invalid password or password confirmation<br/>";
  }

  if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) || $_POST['email'] != $_POST['email_confirmation']) {

    $error[] = "Invalid email or email confirmation<br/>";
  }

  if(empty($error))
  {
    $newUser = new User($bdd);
    if($newUser->existUser($email))
    {
     $error[] = "This username already exists";
   }
   else
   {
    echo "non error";
    if($newUser->register($first_name,$last_name,$email,$password,$gender,$zipcode,$birth_date)==TRUE)
    {
      $_SESSION["user_created"] = 1;
      //$newUser->redirect("login.php");

    }
  }
}
}



?>
<!doctype html>
<HTML lang="fr">
  <head>
    <title>Dream Watch: the best selection of watches, flash sales at best price </title>
    <meta charset="utf-8">
    <link href='https://fonts.googleapis.com/css?family=Vollkorn:400,400italic' rel='stylesheet' type='text/css'>
    <link href="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/theme-default.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="asset/css/dreamwatch_register.css" type="text/css">

    <script src="asset/js/jquery/external/jquery/jquery.js"></script>
    <script src="asset/js/jquery/jquery-ui.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
  <body>
    <header>

    </header>
    <main>    <div class="container">

      <div class="row">
          <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
            <p class="error"><?php

                            if(isset($error))
                            {
                              foreach ($error as $error) {

                                echo $error;
                              }
                            }

                            ?>

          <form role="form" action="register.php" method="post" id="registration">
      			<h2 class="h2-form">Fill in this form to access our exclusive sales</h2>
                  <hr/>
      			<div class="row">
      				<div class="col-xs-12 col-sm-6 col-md-6">
      					<div class="form-group">
                  <input type="text"  name="firstname" placeholder="First name*" class="form-control input-lg" value="<?php echo $first_name; ?>" data-validation="required length" data-validation-length="min3" data-validation-error-msg="Your first name must have at least 3 letters">
      					</div>
      				</div>
      				<div class="col-xs-12 col-sm-6 col-md-6">
      					<div class="form-group">
                  <input type="text" name="lastname" placeholder="Last name*" class="form-control input-lg" value="<?php echo $last_name; ?>" data-validation="required length" data-validation-length="min3" data-validation-error-msg="Your last name must have at least 3 letters">
      					</div>
      				</div>
      			</div>

            			      <div class="row">
                  				<div class="col-xs-12 col-sm-6 col-md-6">
                  					<div class="form-group">
                              <input type="email" name="email" placeholder="Email*" class="form-control input-lg"  value="<?php echo $email; ?>" data-validation="required email" data-validation-error-msg="The email is invalid">
                  					</div>
                  				</div>
                  				<div class="col-xs-12 col-sm-6 col-md-6">
                  					<div class="form-group">
                              <input type="email" name="email_confirmation" placeholder="Confirm your email*" class="form-control input-lg" value="<?php echo $email_confirmation; ?>" data-validation="confirmation required" data-validation-error-msg="The email confirmation could not be confirmed" data-validation-confirm="email">
                  					</div>
                  				</div>
                  			</div>
                        <div class="row">
      				<div class="col-xs-12 col-sm-6 col-md-6">
      					<div class="form-group">
                  <input type="password" name="password" placeholder="Password*" class="form-control input-lg" data-validation="strength" data-validation-strength="1" >
      					</div>
      				</div>
      				<div class="col-xs-12 col-sm-6 col-md-6">
      					<div class="form-group">
                  <input type="password" name="password_confirmation" placeholder="Confirm your password*" class="form-control input-lg"  data-validation="confirmation required" data-validation-error-msg="The password confirmation could not be confirmed" data-validation-confirm="password">
      					</div>
      				</div>
      			</div><div class="row">
                    <div class="col-xs-12 col-sm-5 col-md-5">

                <div class="form-group">
                  <input type="date" name="birth_date" class="form-control input-lg"  data-validation="birthdate" data-validation-error-msg="example (1986-01-31)" placeholder="Date of birth" data-validation-optional="true">>
                </div>
              </div>
              <div class="col-xs-6 col-sm-3 col-md-3">
                <div class="form-group">
                  <input type="text" name="zipcode" placeholder="ZIP CODE" class="form-control input-lg" value="<?php echo $zipcode; ?>" data-validation="number" data-validation-optional="true" data-validation-error-msg="The zipcode is invalid">
                </div>
              </div>
                      <div class="col-xs-6 col-sm-4 col-md-4">
                  <div class="form-group">
                    <select name="gender" class="form-control input-lg input-lg" <?php echo $check_male; ?> data-validation="required"  data-validation-error-msg="Please choose the gender">
                                  <option value="">Gender</option>
                                  <option value="Male">Male</option>
                                  <option value="Female">Female</option>
                              </select>
                </div>
              </div>
            </div>
                  <hr/>
      			<div class="row">
      				<div class="col-xs-8 col-md-6"><input type="submit" name="submit" value="Register" class="btn btn-primary btn-block btn-lg"></div>
              <div class="col-xs-4 col-md-6"><a href="login.php" class="btn btn-success btn-block btn-lg">Login</a></div>
            </div>
      		</form>
      	</div>
      </div>

      </div>
    </article>
  </section>
      </main>
    <footer>
      <address>
        <!--  <a href="mailto:steex44@gmail.com"></a> -->
      </address>
    </footer>
<script type="text/javascript">
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
</script>

  </body>

  </html>
