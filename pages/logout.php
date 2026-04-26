<?php
session_start();
session_destroy();
header('Location: /cleare/index.php');
exit;