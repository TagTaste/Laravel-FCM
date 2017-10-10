<?php
$code = file_exists(__DIR__ . "/../.down") ? 500 : 200;
http_response_code($code);