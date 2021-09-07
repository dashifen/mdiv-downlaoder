<?php

namespace Dashifen\MDiv\Repositories;

/**
 * @property-read int $id
 * @property-read string $url
 * @property-read string $displayName
 * @property-read string $contentType
 */
class File extends AbstractCanvasRepository
{
  protected int $id = 0;
  protected string $url = '';
  protected string $displayName = '';
  protected string $contentType = '';
  
  public function __construct(array $data = [])
  {
    parent::__construct($this->filter($data));
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
   * setUrl
   *
   * Sets the URL property.
   *
   * @param string $url
   *
   * @return void
   */
  protected function setUrl(string $url): void
  {
    $this->url = $url;
  }
  
  /**
   * setDisplayName
   *
   * Sets the display name property.
   *
   * @param string $displayName
   *
   * @return void
   */
  protected function setDisplayName(string $displayName): void
  {
    $this->displayName = $displayName;
  }
  
  /**
   * setContentType
   *
   * Sets the content type property.
   *
   * @param string $contentType
   *
   * @return void
   */
  protected function setContentType(string $contentType): void
  {
    $this->contentType = $contentType;
  }
  
  
  
}
