<?php

namespace Dashifen\MDiv\Downloaders;

use Dashifen\MDiv\Repositories\Group;
use Dashifen\MDiv\Repositories\Topic;
use Dashifen\MDiv\DownloaderException;
use GuzzleHttp\Exception\GuzzleException;
use Dashifen\Repository\RepositoryException;

class TopicDownloader extends AbstractDownloader
{
  /**
   * @var Group[]
   */
  protected array $groups;
  protected int $courseId;
  
  /**
   * setGroups
   *
   * Sets the group property.
   *
   * @param array $groups
   *
   * @return void
   */
  public function setGroups(array $groups): void
  {
    $this->groups = $groups;
  }
  
  /**
   * setCourseId
   *
   * Sets the course ID property.
   *
   * @param int $courseId
   *
   * @return void
   */
  public function setCourseId(int $courseId): void
  {
    $this->courseId = $courseId;
  }
  
  /**
   * fetch
   *
   * Gets information about the discussion topics for the specified course and
   * groups within said course.
   *
   * @return array
   * @throws GuzzleException
   * @throws DownloaderException
   * @throws RepositoryException
   */
  public function fetch(): array
  {
    if (!isset($this->courseId) || !isset($this->groups)) {
      throw new DownloaderException('Cannot fetch: topics.',
        DownloaderException::CANNOT_FETCH);
    }
    
    $allTopics = [];
    foreach ($this->getUrls() as $url) {
      $topics = $this->downloader->get($url);
      array_walk($topics, fn($topic) => new Topic($topic, $this->downloader));
      $allTopics[] = $topics;
    }
    
    return $allTopics;
  }
  
  /**
   * getUrls
   *
   * Returns an array of URLs from which we select topics.
   *
   * @return array
   */
  private function getUrls(): array
  {
    // we start with discussions for the specified course.  we know we have
    // one of those, so we know we can get topics for it.
    
    $urls = ['courses/' . $this->courseId . '/discussion_topics'];
    
    // then, we add in any topics for the groups related to this course.  since
    // not all courses have groups, this might not add any more URLs to our
    // list.
    
    foreach ($this->groups as $group) {
      $urls[] = 'groups/' . $group->id . '/discussion_topics';
    }
    
    return $urls;
  }
  
}