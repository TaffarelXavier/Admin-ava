<?php

session_start();
unset($_SESSION['_sessao']);
echo session_destroy();