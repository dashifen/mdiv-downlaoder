<?php

namespace Dashifen\MDiv;

use Dotenv\Dotenv;
use GuzzleHttp\Client;
use Dashifen\Debugging\DebuggingTrait;
use GuzzleHttp\Exception\GuzzleException;
use Dashifen\Repository\RepositoryException;
use Dashifen\MDiv\Downloaders\TopicDownloader;
use Dashifen\MDiv\Downloaders\CourseDownloader;

class Downloader
{
  use DebuggingTrait {
   isDebug as isDashifenDebug;
  }
  
  public static bool $debug;
  protected array $completed;
  protected array $headers;
  protected User $user;
  
  /**
   * __construct
   *
   * Downloader constructor.
   *
   * @param bool $debug
   *
   * @throws DownloaderException
   * @throws GuzzleException
   * @throws RepositoryException
   */
  public function __construct(bool $debug = true) {
    self::$debug = $debug;
    $root = dirname(__DIR__);
    Dotenv::createImmutable($root)->safeLoad();
    
    $this->headers = [
      'Authorization' => 'Bearer ' . $_ENV['CANVAS_TOKEN'],
      'Content-Tye'   => 'application/x-www-form-urlencoded',
    ];
    
    $file = $root . '/completed.json';
    $this->completed = json_decode(file_get_contents($file), true);
    $this->completed['file'] = $file;
    
    $this->user = new User($this);
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
  
  /**
   * download
   *
   * Performs the necessary work to download information about the student's
   * time at the school.
   *
   * @return void
   * @throws DownloaderException
   * @throws GuzzleException
   * @throws RepositoryException
   */
  public function download(): void
  {
    $topicDownloader = new TopicDownloader($this);
    $courseDownloader = new CourseDownloader($this);
    foreach ($courseDownloader->fetch() as $course) {
      $topicDownloader->setCourseId($course->id);
      $topicDownloader->setGroups($this->user->getGroups($course->id));
      $topics = $topicDownloader->fetch();
      
      
      
      
      
      break;
    }
  }
  
  
  /**
   * get
   *
   * Wraps our client's get method to return a decoded JSON object that is a
   * response from the Canvas API.
   *
   * @param string $url
   *
   * @return array
   * @throws GuzzleException
   * @throws DownloaderException
   */
  public function get(string $url): array
  {
    // we add per_page=1000000 because the likelihood that there's ever a
    // million items to return seems slim.  probably we could do a smaller
    // number, but go big or go home, right?
    
    $url .= strpos($url, '?') === false ? '?' : '&';
    $url .= 'per_page=1000000';
    
    $response = (new Client())->get(
      'https://iliff.instructure.com/api/v1/' . $url,
      [
        'headers' => $this->headers,
        'debug'   => false,
      ]
    );
    
    if ($response->getStatusCode() !== 200) {
      throw new DownloaderException($response->getReasonPhrase(),
        DownloaderException::INVALID_RESPONSE
      );
    }
    
    $json = $response->getBody()->getContents();
    return json_decode($json, true);
  }
}
