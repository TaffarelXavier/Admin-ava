<?php

session_start();

unset($_SESSION['_sessao']);
unset($_SESSION['captcha']);
unset($_SESSION['user_id']);
unset($_SESSION['user_nivel']);
unset($_SESSION['user_name']);
unset($_SESSION['security']);
echo '1';