<?php

namespace Dashifen\MDiv\Repositories;

use Dashifen\Repository\RepositoryException;

/**
 * @property-read int    $id
 * @property-read int    $dueAt
 * @property-read int    $assignmentId
 * @property-read string $title
 * @property-read string $message
 */
class Topic extends AbstractCanvasRepository
{
  protected int $id;
  protected int $dueAt;
  protected ?int $assignmentId;
  protected string $title;
  protected string $message;
  
  public function __construct(array $topic)
  {
    $filtered = $this->filter($topic);
    $filtered['dueAt'] = $topic['assignment']['due_at'] ?? null;
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
   * setAssignmentId
   *
   * Sets the assignment ID property.
   *
   * @param int|null $assignmentId
   *
   * @return void
   */
  protected function setAssignmentId(?int $assignmentId): void
  {
    $this->assignmentId = $assignmentId;
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
