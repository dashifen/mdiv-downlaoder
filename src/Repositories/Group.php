<?php

namespace Dashifen\MDiv\Repositories;

/**
 * @property-read int $id
 * @property-read int $courseId
 * @property-read int $name
 */
class Group extends AbstractCanvasRepository
{
  protected int $id;
  protected int $courseId;
  protected string $name;
  
  public function __construct(array $group)
  {
    parent::__construct($this->filter($group));
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
   * @param int $courseId
   *
   * @return void
   */
  protected function setCourseId(int $courseId): void
  {
    $this->courseId = $courseId;
  }
  
  /**
   * setName
   *
   * Sets the name property.
   *
   * @param string $name
   *
   * @return void
   */
  protected function setName(string $name): void
  {
    $this->name = $name;
  }
}
