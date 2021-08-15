<?php

namespace Dashifen\MDiv;

use Dashifen\MDiv\Repositories\Group;
use GuzzleHttp\Exception\GuzzleException;
use Dashifen\Repository\RepositoryException;

class User
{
  /**
   * @var Group[]
   */
  protected array $groups = [];
  protected Downloader $downloader;
  
  /**
   * __construct
   *
   * User constructor.
   *
   * @param Downloader $downloader
   *
   * @throws DownloaderException
   * @throws GuzzleException
   * @throws RepositoryException
   */
  public function __construct(Downloader $downloader)
  {
    $this->downloader = $downloader;
    $this->setGroups();
  }
  
  /**
   * setGroups
   *
   * Fetches group data from the Canvas API and sets our groups  property.
   *
   * @return void
   * @throws DownloaderException
   * @throws RepositoryException
   * @throws GuzzleException
   */
  private function setGroups(): void
  {
    $groups = $this->downloader->get('users/self/groups');
    $this->groups = array_map(fn($group) => new Group($group), $groups);
  }
  
  /**
   * getGroups
   *
   * Returns either the entire list of groups in our property or a subset of
   * them which are for the specified course.
   *
   * @param int|null $courseId
   *
   * @return Group[]
   */
  public function getGroups(?int $courseId = null): array
  {
    return $courseId !== null
      ? array_filter($this->groups, fn(Group $g) => $g->courseId === $courseId)
      : $this->groups;
  }
}
