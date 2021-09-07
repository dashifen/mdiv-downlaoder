<?php

namespace Dashifen\MDiv\Repositories;

use Dashifen\Repository\RepositoryException;

/**
 * Class Course
 *
 * @property-read string $id
 * @property-read string $name
 * @property-read array  $files;
 *
 * @package Dashifen\MDivDownloader\Repositories
 */
class Course extends AbstractCanvasRepository
{
  protected int $id = 0;
  protected string $name = "";
  
  /**
   * @var File[]
   */
  protected array $files = [];
  
  /**
   * Course constructor.
   *
   * @param array  $course
   * @param File[] $files
   *
   * @throws RepositoryException
   */
  public function __construct(array $course, array $files)
  {
    $filtered = $this->filter($course);
    $filtered['files'] = $files;
    parent::__construct($filtered);
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
  protected function setId(int $id): void
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
  protected function setName(string $name): void
  {
    if (empty($name)) {
      throw new RepositoryException("Course names cannot be empty");
    }
    
    $this->name = $name;
  }
  
  /**
   * setFiles
   *
   * Sets the files property.
   *
   * @param File[] $files
   *
   * @return void
   */
  protected function setFiles(array $files): void
  {
    $this->files = $files;
  }
}

