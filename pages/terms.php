<?php $page_title = "Terms & Privacy | Clearè"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $page_title; ?></title>
  <link rel="icon" type="image/svg+xml" href="/Cleare/assets/images/favicon.svg">
<link rel="icon" type="image/png" sizes="32x32" href="/Cleare/assets/images/favicon-32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/Cleare/assets/images/favicon-16.png">
<link rel="apple-touch-icon" sizes="180x180" href="/Cleare/assets/images/favicon-180.png">
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include(__DIR__ . '/../includes/nav.php'); ?>


<section class="static-page">
  <div class="static-card">
    <div class="section-eyebrow">Legal</div>
    <h1 class="section-title">Terms & <em>Privacy</em></h1>

    <h3 style="font-family:'Cormorant Garamond',serif; font-size:22px; font-weight:400; margin: 32px 0 12px;">Terms of Use</h3>
    <p>Clearè is an academic e-commerce project built for educational purposes only. All products, prices and transactions shown on this platform are fictional and for demonstration purposes. No real purchases or payments are processed.</p>

    <h3 style="font-family:'Cormorant Garamond',serif; font-size:22px; font-weight:400; margin: 32px 0 12px;">Privacy Policy</h3>
    <p>Any personal information entered on this platform (name, email, address) is used solely for demonstrating the functionality of the application. No data is shared with third parties or used for commercial purposes.</p>

    <h3 style="font-family:'Cormorant Garamond',serif; font-size:22px; font-weight:400; margin: 32px 0 12px;">Cookies</h3>
    <p>This platform uses PHP sessions to manage the shopping cart and user authentication. No third-party tracking cookies are used.</p>
  </div>
</section>

<?php include('../includes/footer.php'); ?>

<?php include(__DIR__ . '/../includes/nav-js.php'); ?>
</body>
</html>