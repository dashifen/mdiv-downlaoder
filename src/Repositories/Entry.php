<?php

namespace Dashifen\MDiv\Repositories;

class Entry extends AbstractCanvasRepository
{
  protected int $id = 0;
  protected int $userId = 0;
  protected int $createdAt = 0;
  protected ?int $parentId = null;
  protected string $message = '';
  
  /**
   * @var Entry[]
   */
  protected array $replies = [];
  
  public function __construct(array $data = [])
  {
    if (isset($data['replies'])) {
      array_walk($data['replies'], fn(&$reply) => $reply = new Entry($reply));
    }
    
    $data['created_at'] = strtotime($data['created_at']);
    
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
   * setUserId
   *
   * Sets the user ID property.
   *
   * @param int $userId
   *
   * @return void
   */
  protected function setUserId(int $userId): void
  {
    $this->userId = $userId;
  }
  
  /**
   * setCreatedAt
   *
   * Sets the created at property.
   *
   * @param int $createdAt
   *
   * @return void
   */
  protected function setCreatedAt(int $createdAt): void
  {
    $this->createdAt = $createdAt;
  }
  
  /**
   * setParentId
   *
   * Sets the parent ID property.
   *
   * @param int|null $parentId
   *
   * @return void
   */
  protected function setParentId(?int $parentId): void
  {
    $this->parentId = $parentId;
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
  
  /**
   * setReplies
   *
   * Sets the replies property.
   *
   * @param array $replies
   *
   * @return void
   */
  protected function setReplies(array $replies): void
  {
    $this->replies = $replies;
  }
}
