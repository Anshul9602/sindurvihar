// app/Helpers/password_helper.php
<?php

function hash_password($password)
{
    return hash('sha256', $password);
}
