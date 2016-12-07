<?php

require_once 'cookie_login.php';

if ($logged == true) {
    echo $userArray['Username'] . " is logged in";
} else {
    echo "User not logged in";
}

?>