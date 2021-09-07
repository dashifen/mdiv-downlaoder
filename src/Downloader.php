<?php

namespace Dashifen\MDiv;

use Dotenv\Dotenv;
use GuzzleHttp\Client;
use Dashifen\MDiv\Repositories\Topic;
use Dashifen\Debugging\DebuggingTrait;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ClientException;
use Dashifen\MDiv\Repositories\Discussion;
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
  protected string $completedFile;
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
  public function __construct(bool $debug = true)
  {
    self::$debug = $debug;
    $root = dirname(__DIR__);
    Dotenv::createImmutable($root)->safeLoad();
    
    $this->headers = [
      'Authorization' => 'Bearer ' . $_ENV['CANVAS_TOKEN'],
      'Content-Tye'   => 'application/x-www-form-urlencoded',
    ];
    
    $this->completedFile = $root . '/completed.json';
    $this->completed = json_decode(file_get_contents($this->completedFile), true);
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
      $sanitaryCourseName = $this->sanitize($course->name);
      
      if (in_array($sanitaryCourseName, $this->completed)) {
        continue;
      }
      
      echo $course->name . '<br>';
      $folder = 'courses/' . $sanitaryCourseName;
      
      if (!is_dir($folder)) {
        mkdir($folder);
      }
      
      if (!is_dir($folder . '/files')) {
        $topicDownloader->setCourseId($course->id);
        $topicDownloader->setGroups($this->user->getGroups($course->id));
        foreach ($topicDownloader->fetch() as $topic) {
          $name = $topic->dueAt !== 0
            ? date('Ymd', $topic->dueAt) . '-' . $this->sanitize($topic->title)
            : $this->sanitize($topic->title);
          
          $name .= '.json';
          
          if (is_file($folder . '/' . $name)) {
            continue;
          }
          
          foreach ($this->getTopicUrls($topic) as $url) {
            try {
              $discussion = $this->get($url);
              $discussion = new Discussion($topic, $discussion);
              $discussion = json_encode($discussion, JSON_PRETTY_PRINT);
              file_put_contents($folder . '/' . $name, $discussion);
            } catch (ClientException $e) {
              echo 'Unable to get ' . $url . '<br>';
              continue;
            }
          }
        }
      }
      
      foreach ($course->files as $file) {
        $filename = $folder . '/files/' . $file->displayName;
        
        if (is_file($filename)) {
          continue;
        }
        
        $data = @file_get_contents($file->url);
        
        if (empty($data)) {
          echo $file->url;
        } else {
          file_put_contents($filename, $data);
        }
      }
      
      $this->completed[] = $this->sanitize($course->name);
      
      $completed = array_unique($this->completed);
      $completed = json_encode($completed, JSON_PRETTY_PRINT);
      file_put_contents($this->completedFile, $completed);
    }
  }
  
  /**
   * sanitize
   *
   * Converts sets non-word characters to dashes and returns the lower case
   * version of the resulting string.
   *
   * @param string $unsanitary
   *
   * @return string
   */
  private function sanitize(string $unsanitary): string
  {
    return strtolower(preg_replace('/\W+/', '-', $unsanitary));
  }
  
  /**
   * getTopicUrls
   *
   * Returns an array of URLs to check for discussion topic contents.
   *
   * @param Topic $topic
   *
   * @return array
   */
  private function getTopicUrls(Topic $topic): array
  {
    // we want to start with the URL that we think is most likely to work.
    // that's determined by whether or not this is a course or group-based
    // topic.
    
    return is_numeric($topic->courseId)
      ? [
        'courses/' . $topic->courseId . '/discussion_topics/' . $topic->id . '/view',
        'groups/' . $topic->groupId . '/discussion_topics/' . $topic->id . '/view',
      ] : [
        'groups/' . $topic->groupId . '/discussion_topics/' . $topic->id . '/view',
        'courses/' . $topic->courseId . '/discussion_topics/' . $topic->id . '/view',
      ];
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
