<?php

echo header('Pragma: no-cache');
echo header("Cache-Control: no-store, no-cache, max-age=0, must-revalidate");
echo header('Content-Type: application/json');

echo $content_for_layout;
