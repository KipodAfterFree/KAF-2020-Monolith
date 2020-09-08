<?php
if (isset($_GET["email"]))
    if (filter_var($_GET["email"], FILTER_VALIDATE_EMAIL))
        die("OK");

die("Failed");