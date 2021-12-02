<?php

require_once('include.php');

unset($_SESSION['user_id']);
session_destroy();

header('Location: index.php');
