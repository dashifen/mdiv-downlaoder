<?php

namespace Dashifen\MDiv\Repositories;

use Dashifen\Repository\RepositoryException;

class Discussion extends AbstractCanvasRepository
{
  /**
   * @var Participant[]
   */
  protected array $participants = [];
  
  /**
   * @var Entry[]
   */
  protected array $entries = [];
  
  public function __construct(array $data = [])
  {
    $data['entries'] = $data['view'] ?? [];
    parent::__construct($this->filter($data));
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
  public function setParticipants(array $participants): void
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
  public function setEntries(array $entries): void
  {
    $this->entries = array_map(fn($e) => new Entry($e), $entries);
  }
}
