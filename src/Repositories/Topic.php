<?php

namespace Dashifen\MDiv\Repositories;

/**
 * @property-read int $id
 * @property-read int $assignmentId
 * @property-read string $title
 */
class Topic extends AbstractCanvasRepository
{
  protected int $id;
  protected ?int $assignmentId;
  protected string $title;
  
  public function __construct(array $topic)
  {
    parent::__construct($this->filter($topic));
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
  
  
}
