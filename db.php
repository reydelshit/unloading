<?php

$connLocal = new mysqli("localhost", "root", "", "ism");


if ($connLocal->connect_error) {
    die("Connection failed: " . $connLocal->connect_error);
}
