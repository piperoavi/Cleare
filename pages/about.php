<?php $page_title = "About Us | Clearè"; ?>
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
    <div class="section-eyebrow">Our Story</div>
    <h1 class="section-title">About <em>Clearè</em></h1>
    <p>Clearè is a curated skincare and beauty platform built around one simple belief: your skin deserves clarity. We carefully select products with clean, effective ingredients — no unnecessary fillers, no misleading claims.</p>
    <p>Our focus is skincare first. From lightweight serums to SPF essentials, every product in our catalogue has been chosen with your skin health in mind. We also carry a thoughtful selection of makeup, hair and body products for a complete routine.</p>
    <p>Clearè was built as an academic project by a team of five students passionate about both technology and beauty.</p>
    <a href="shop.php" class="btn-primary" style="margin-top: 32px; display: inline-flex;">Shop Now</a>
  </div>
</section>

<?php include('../includes/footer.php'); ?>

<?php include(__DIR__ . '/../includes/nav-js.php'); ?>

</body>
</html>