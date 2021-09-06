<?php

namespace Dashifen\MDiv;

use Dashifen\Exception\Exception;

class DownloaderException extends Exception
{
  public const INVALID_RESPONSE = 1;
  public const CANNOT_FETCH = 2;
  public const DEAD_MONKEY = 3;
}
