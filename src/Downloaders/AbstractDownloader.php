<?php

namespace Dashifen\MDiv\Downloaders;

use Dashifen\MDiv\Downloader;

abstract class AbstractDownloader
{
  protected Downloader $downloader;
  
  public function __construct(Downloader $downloader) {
    $this->downloader = $downloader;
  }
  
  abstract public function fetch(): array;
}
