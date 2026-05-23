<?php

$connLocal = new mysqli("localhost", "root", "", "ism_unloading");


if ($connLocal->connect_error) {
    die("Connection failed: " . $connLocal->connect_error);
}
