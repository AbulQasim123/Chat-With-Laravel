<?php

$ch = curl_init("https://www.googleapis.com/oauth2/v4/token");

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo curl_error($ch);
} else {
    echo "OK";
}

curl_close($ch);
