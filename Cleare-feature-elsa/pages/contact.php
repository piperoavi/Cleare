<?php $page_title = "Contact — Clearè"; ?>
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


<section class="static-page">
  <div class="static-card">
    <div class="section-eyebrow">Get in Touch</div>
    <h1 class="section-title"><em>Contact</em> Us</h1>

    <form class="auth-form" action="contact.php" method="POST" style="margin-top: 32px;">
      <div class="form-group">
        <label for="contact_name">Full Name</label>
        <input type="text" id="contact_name" name="name" placeholder="Jane Doe" required>
      </div>
      <div class="form-group">
        <label for="contact_email">Email</label>
        <input type="email" id="contact_email" name="email" placeholder="you@example.com" required>
      </div>
      <div class="form-group">
        <label for="contact_msg">Message</label>
        <textarea id="contact_msg" name="message" rows="5"
          style="padding:13px 16px; border:1px solid rgba(91,175,201,0.25); border-radius:10px;
                 font-family:'DM Sans',sans-serif; font-size:14px; color:var(--ink);
                 background:var(--white); outline:none; resize:vertical; width:100%;"
          placeholder="How can we help you?" required></textarea>
      </div>
      <button type="submit" class="btn-primary btn-full">Send Message</button>
    </form>
  </div>
</section>

<?php include('../includes/footer.php'); ?>

<?php include(__DIR__ . '/../includes/nav-js.php'); ?>

</body>
</html>