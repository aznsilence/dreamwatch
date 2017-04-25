<!doctype html>
<HTML lang="fr">
<head>

  <title></title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="dreamwatch.css" type="text/css">

  </head>
  <body>

    <header>
      <H1>Welcome to Dreamwatch</H1>
    </header>

      <nav>
        <ul>
        </ul>
      </nav>

  <main>
    <section>


      <article>
      </article>
          <article>
            <form action="register.php" method="post">

    <input type="email" name="email" placeholder="Email*" required>

    <input type="email" name="email_confirmation" placeholder="Confirm your email*" required>

    <input type="password" name="password" placeholder="Password*" required>

    <input type="password" name="password_confirmation" placeholder="Confirm your password*" required>

    <input type="text"  name="firstname" placeholder="First name*" required>

    <input type="text" name="lastname" placeholder="Last name*" required>

    <input type="address" name="address" placeholder="Zip code" >
<!--set the gender as required -->
    <label for="gender">Gender</label>
    <input type="radio" name="gender" value="Male" required>Male
    <input type="radio" name="gender" value="Female">Female

    <!-- add JQuerry or JS effect to add a calendar -->
    <input type="date" name="birth_date" placeholder="1980-01-01">

    <input type="text" name="zipcode" placeholder="75000">

    <label for="alias">Create your Alias</label>
    <input type="text" name="alias" id="alias">

    <input id="submit" name="submit" type="submit" value="Ready!">

</form>

    </article>
  </section>
        </main>

          <aside>
            <p> </p>

          </aside>
          <div class="container">
            <p></p>
          </div>

<footer>
  <address>
<!--  <a href="mailto:steex44@gmail.com"></a> -->
  </address>
</footer>


</body>

  </html>
