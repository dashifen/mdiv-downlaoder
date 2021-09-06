<?php

namespace Dashifen\MDiv\Repositories;

/**
 * @property-read int $id
 * @property-read string $displayName
 * @property-read string $avatarImageUrl
 */
class Participant extends AbstractCanvasRepository
{
  protected int $id;
  protected string $displayName;
  protected string $avatarImageUrl;
  
  public function __construct(array $data)
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
   * setAvatarImageUrl
   *
   * Sets the avatar image url property.
   *
   * @param string $avatarImageUrl
   *
   * @return void
   */
  protected function setAvatarImageUrl(string $avatarImageUrl): void
  {
    $this->avatarImageUrl = $avatarImageUrl;
  }
}
