<?php

namespace Dashifen\MDiv\Repositories;

use Dashifen\Repository\RepositoryException;

/**
 * @property-read int    $id
 * @property-read int    $dueAt
 * @property-read ?int   $courseId
 * @property-read ?int   $groupId
 * @property-read string $title
 * @property-read string $message
 */
class Topic extends AbstractCanvasRepository
{
  protected int $id;
  protected int $dueAt;
  protected ?int $courseId = null;
  protected ?int $groupId = null;
  protected string $message;
  protected string $title;
  
  public function __construct(array $topic, string $url)
  {
    $filtered = $this->filter($topic);
    $filtered['dueAt'] = $topic['assignment']['due_at'] ?? null;
    
    // if the $url starts with "course" then we use the number with in it
    // to set our course ID property.  otherwise, it's a group-based topic and
    // we set the group ID property.
    
    $idIndex = strpos($url, 'course') !== false ? 'courseId' : 'groupId';
    $filtered[$idIndex] = preg_replace('/\D+/', '', $url);
    parent::__construct($filtered);
  }
  
  /**
   * setId
   *
   * Sets the ID property.
   *
   * @param int $id
   *
   * @return void
   */
  protected function setId(int $id): void
  {
    $this->id = $id;
  }
  
  /**
   * setCourseId
   *
   * Sets the course ID property.
   *
   * @param int|null $courseId
   *
   * @return void
   */
  protected function setCourseId(?int $courseId): void
  {
    $this->courseId = $courseId;
  }
  
  /**
   * setGroupId
   *
   * Sets the group ID property.
   *
   * @param int|null $groupId
   *
   * @return void
   */
  protected function setGroupId(?int $groupId): void
  {
    $this->groupId = $groupId;
  }
  
  /**
   * setDueAt
   *
   * Sets the due at property.
   *
   * @param string|null $due
   *
   * @return void
   * @throws RepositoryException
   */
  protected function setDueAt(?string $due): void
  {
    $ts = $due !== null ? strtotime($due) : 0;
    
    if ($ts === false) {
      throw new RepositoryException('Invalid date: ' . $due,
        RepositoryException::INVALID_VALUE);
    }
    
    $this->dueAt = $ts;
  }
  
  /**
   * setTitle
   *
   * Sets the title property.
   *
   * @param string $title
   *
   * @return void
   */
  protected function setTitle(string $title): void
  {
    $this->title = $title;
  }
  
  /**
   * setMessage
   *
   * Sets the message property.
   *
   * @param string $message
   *
   * @return void
   */
  protected function setMessage(string $message): void
  {
    $this->message = $message;
  }
}
