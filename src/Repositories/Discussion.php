<?php

namespace Dashifen\MDiv\Repositories;

use Dashifen\Repository\RepositoryException;

class Discussion extends AbstractCanvasRepository
{
  protected Topic $topic;
  
  /**
   * @var Participant[]
   */
  protected array $participants;
  
  /**
   * @var Entry[]
   */
  protected array $entries;
  
  public function __construct(Topic $topic, array $data = [])
  {
    $data['topic'] = $topic;
    $data['entries'] = $data['view'];
    parent::__construct($this->filter($data));
  }
  
  /**
   * setTopic
   * 
   * Sets the topic property.
   * 
   * @param Topic $topic
   *                    
   * @return void
   */
  protected function setTopic(Topic $topic): void
  {
    $this->topic = $topic;
  }
  
  /**
   * setParticipants
   *
   * Sets the participants property.
   *
   * @param array $participants
   *
   * @return void
   * @throws RepositoryException
   */
  protected function setParticipants(array $participants): void
  {
    $this->participants = array_map(fn($p) => new Participant($p), $participants);
  }
  
  /**
   * setEntries
   *
   * Sets the entries property.
   *
   * @param array $entries
   *
   * @return void
   * @throws RepositoryException
   */
  protected function setEntries(array $entries): void
  {
    $this->entries = array_map(fn($e) => new Entry($e), $entries);
  }
}
