<?php

namespace Dashifen\MDiv;

use Dotenv\Dotenv;
use Dashifen\Debugging\DebuggingTrait;

class Downloader
{
  use DebuggingTrait {
   isDebug as isDashifenDebug;
  }
  
  public static bool $debug;
  
  public function __construct(bool $debug = true) {
    Dotenv::createImmutable(dirname(__DIR__))->safeLoad();
    self::$debug = $debug;
  }
  
  /**
   * isDebug
   *
   * Inherited from our DebuggingTrait, we first check the static value of
   * our debug property and then the baseline isDebug method of the trait that
   * we've renamed with an "as" statement above.
   *
   * @return bool
   */
  public static function isDebug(): bool
  {
    return self::$debug || self::isDashifenDebug();
  }
}
