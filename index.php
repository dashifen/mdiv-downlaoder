<?php

namespace Dashifen;

require 'vendor/autoload.php';

use Dashifen\MDiv\Downloader;

$downloader = new Downloader(true);
$downloader->download();

echo 'done';
