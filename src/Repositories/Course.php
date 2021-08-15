<?php

namespace Dashifen\MDiv\Repositories;

use Dashifen\Repository\RepositoryException;

/**
 * Class Course
 *
 * @property-read string $id
 * @property-read string $name
 *
 * @package Dashifen\MDivDownloader\Repositories
 */
class Course extends AbstractCanvasRepository
{
  protected int $id = 0;
  protected string $name = "";
  
  /**
   * Course constructor.
   *
   * @param array $course
   *
   * @throws RepositoryException
   */
  public function __construct(array $course)
  {
    parent::__construct($this->filter($course));
  }
  
  /**
   * setId
   *
   * Sets the id property, which must be numeric.
   *
   * @param int $id
   *
   * @return void
   */
  public function setId(int $id): void
  {
    $this->id = $id;
  }
  
  /**
   * setName
   *
   * Sets the name property, which must not be empty.
   *
   * @param string $name
   *
   * @return void
   * @throws RepositoryException
   */
  public function setName(string $name): void
  {
    if (empty($name)) {
      throw new RepositoryException("Course names cannot be empty");
    }
    
    $this->name = $name;
  }
}

