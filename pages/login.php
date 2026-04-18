<?php
$page_title = "Login — Clearè";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $page_title; ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include(__DIR__ . '/../includes/nav.php'); ?>


<section class="auth-page">
  <div class="auth-card">

    <div class="auth-logo">Clear<span>è</span></div>
    <h1 class="auth-title">Welcome back</h1>
    <p class="auth-sub">Sign in to your account</p>

    <form class="auth-form" action="login.php" method="POST">

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email"
               placeholder="you@example.com" required>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password"
               placeholder="••••••••" required>
      </div>

      <button type="submit" class="btn-primary btn-full">Sign In</button>

    </form>

    <p class="auth-switch">
      Don't have an account?
      <a href="register.php">Create one</a>
    </p>

  </div>
</section>

<?php include(__DIR__ . '/../includes/nav-js.php'); ?>

</body>
</html>