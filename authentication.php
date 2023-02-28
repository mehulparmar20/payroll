<?php
include_once 'mail.php';

$client->verifyEmailAddress([
    'EmailAddress' => '<string>', // REQUIRED
]);