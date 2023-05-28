<header>
  <div class="responsive-menu header-left">
    <img src="../static/assets/logo-light.png"class="header-logo"onclick="window.location='/home';" />
  </div>

  <form action=""method="POST"class="responsive-menu header-right">
    <button class="btn-nav"name="submit_profile"type="submit"><?php
      $username = $_COOKIE['username'];
      echo $username;
      ?></button>
    <button class="btn-nav logout"name="submit_logout"type="submit">LOGOUT</button>
  </form>

</header>
