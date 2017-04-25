<?php
include_once('functions/DBconf.php');

spl_autoload_register(function($class) {
  include "functions/".$class .".class.php";
});


if(isset($_SESSION['user_id']))
{
  header('Location: index.php');
}

if(isset($_SESSION['user_created']))
{
  $valide_registration = "Thank you for your registration !";
}
else
{
  $valide_registration = "";
}


$error = "";

if(!empty($_POST))
{
  $email = $_POST['email'];
  $password = $_POST['password'];
  $is_cookie = $_POST['is_cookie'];

  if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

    $error .= "Invalid email<br/>";
  }

  if ($_POST['password']=="") {

    $error .= "Invalid password<br/>";
  }

  if($error=="")
  {

    $logUser = new User($bdd);
    if($logUser->login($email,$password,$is_cookie))
    {
      $logUser->redirect("index.php");
      echo "<span style='color:white'>CONNECTE</span>";
    }
    else
    {
      $error .= "Incorrect email or password<br/>";
      echo "<span style='color:white'>CONNECTE</span>";
    }
  }
   $display = "block";
}
else
{
  $email = "";
  $display = "none";
}
?>
<!doctype html>
<HTML lang="fr">
  <head>
    <title>Dream Watch: the best selection of watches, flash sales at best price </title>
    <meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <link rel="stylesheet" href="asset/css/dreamwatch_login.css" type="text/css">
    <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
  <!-- Latest compiled and minified JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


    <script src="asset/js/jquery/external/jquery/jquery.js"></script>
    <script src="asset/js/jquery/jquery-ui.min.js"></script>
    <script type="text/javascript">

    </script>
  </head>
  <body>

    <main>
      <div class="animated3 fadeInLeft companyname">
      <h1 > Dream Watch</h1>
</div>
<p class="caption">Watch & Dream...</p>
      <div class="login-container">
            <div class="flex-item">
              <h3 class="login-h2">Member access</h3><br/>
              <p class="validation-form"><?php echo $valide_registration;?></p>
                        <p class="validation-form"><?php echo $error;?></p>

                  <form action="login.php" method="post" role="form">
                <div class="form-group">
                  <input type="email" class="form-control" name="email" placeholder="Email" value="<?php echo $email; ?>">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control" name="password" placeholder="Password">
                </div>

                  <input type="hidden" class="form-control" name="is_cookie" value="0">
                  <div class="form-group">
                    <input type="checkbox" class="form-link" name="is_cookie" value="1"> Remember me

                </div>
                  <div class="form-group">

                <input type="submit" name="submit" value="Enter" class="btn btn-primary">
</div>
<a href="#" class="hoverpass">Forgot your password?</a>

              </form>
            </div>

            <?php
                  if(!isset($_SESSION['user_created']))
                  {
                    ?>
            <div class="flex-item">
            <br/>
              <H2 class="login-h2">Not yet a member ?<H2>
                <a href="register.php" name="button" class="btn btn-primary">Sign up now !</a>
              </div>
              <?php
                    }
                    ?>
            </div>

      </main>
    <footer>
      <address>
      </address>
    </footer>
  </body>

  </html>
