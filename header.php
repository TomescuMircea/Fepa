<?php
// Function to check if JWT is present and valid (simplified check for demonstration purposes)
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function isLoggedIn(): bool
{
    if (isset($_COOKIE['jwt'])) {
        // Here you can add more complex validation of the JWT if needed
        return true;
    }
    return false;
}

$loggedIn = isLoggedIn();

function isAdmin(): bool
{
    if (isset($_COOKIE['jwt'])) {
        $jwt = $_COOKIE['jwt'];
        $key = new Key (SECRET_KEY, 'HS256');
        $decoded = JWT::decode($jwt, $key);
        // Extract the user id
        $role = $decoded->data->role;
        if ($role == 'admin') {
            return true;
        }
    }
    return false;
}

$admin = isAdmin();
?>
<script src="https://kit.fontawesome.com/0643bf7196.js" crossorigin="anonymous"></script>
<header typeof="WPHeader">
    <div id="header">
      <div id="header__img">
        <a href="<?php echo BASE_URL; ?>/index.php"><img src="<?php echo BASE_URL; ?>/images/new_logo_white.svg" alt="image of the logo"></a>
      </div>

      <button id="header__menu-button" onclick="toggleDropdown()">
        Menu
      </button>

      <nav id="header__nav-bar">
        <ul id="nav-bar__menu">
          <li><a class="menu__link" href="<?php echo BASE_URL; ?>/index.php" property="url">Home</a></li>
          <li><a class="menu__link" href="<?php echo BASE_URL; ?>/index.php/reportAnimal" property="url">Report</a></li>
          <li><a class="menu__link" href="<?php echo BASE_URL; ?>/index.php/reports?orderBy=newest" property="url">Reports</a></li>
          <li><a class="menu__link" href="<?php echo BASE_URL; ?>/index.php/stats" property="url">Statistics</a></li>
          <li><a class="menu__link" href="<?php echo BASE_URL; ?>/index.php/contact" property="url">Contact</a></li>
          <li><a class="menu__link" href="<?php echo BASE_URL; ?>/index.php/about" property="url">About</a></li>
          <li><a class="menu__link" href="<?php echo BASE_URL; ?>/index.php/help" property="url">Help</a></li>
            <?php if ($loggedIn): ?>
                <li><a class="menu__link menu__link--logout" href="<?php echo BASE_URL; ?>/index.php/logout" property="url"><i class="fa-solid fa-right-from-bracket"></i>
                    </a></li>
            <?php else: ?>
                <li><a class="menu__link menu__link--login" href="<?php echo BASE_URL; ?>/index.php/login" property="url">Login</a></li>
            <?php endif; ?>
            <?php if ($admin): ?>
                <li><a class="menu__link menu__link--admin" href="<?php echo BASE_URL; ?>/index.php/dashboard" property="url">Admin dashboard</a></li>
            <?php endif; ?>
        </ul>
      </nav>
        <div id="header__img">
            <a href="<?php echo BASE_URL; ?>/index.php/rss"><img id="rss__img" src="<?php echo BASE_URL; ?>/images/rss.png" alt="image of the logo"></a>
        </div>

    </div>
  </header>
