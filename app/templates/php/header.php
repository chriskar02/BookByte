<header>
  <div class="responsive-menu header-left">
    <img src="../static/assets/logo-light.png"class="header-logo"onclick="window.location='/';" />
  </div>

  <form action=""method="POST"class="responsive-menu header-right">
    <label class="tag green"id="tag-valid-handler">HANDLER FOR THIS USER</label>
    <label class="tag orange"id="tag-req-handler">REQUESTED HANDLER</label>
    <label class="tag blue"id="tag-verified-handler">VERIFIED HANDLER</label>
    <label class="tag darkgreen"id="tag-admin">ADMIN</label>
    <button class="btn-nav"name="submit_profile"type="submit"><?php
      $username = $_COOKIE['username'];
      echo $username;
      ?></button>
    <button class="btn-nav logout"name="submit_logout"type="submit">LOGOUT</button>
  </form>

</header>
