<?php

namespace Dashifen\MDiv\Repositories;

use Dashifen\Repository\Repository;
use Dashifen\CaseChangingTrait\CaseChangingTrait;

abstract class AbstractCanvasRepository extends Repository
{
  use CaseChangingTrait;
  
  /**
   * filter
   *
   * Given an array of data, filters it leaving only the indices that match
   * the properties of the object which extends this one.
   *
   * @param array $data
   *
   * @return array
   */
  protected function filter(array $data): array
  {
    // the API gives us keys like course_id, but we want courseId to match the
    // style of PHP object property names.  there's not an easy way to do that
    // work, so we'll grab the keys, map them from snake_case to camelCase, and
    // then combine it all back together again.
    
    $keys = array_keys($data);
    $keys = array_map(fn($key) => $this->snakeToCamelCase($key), $keys);
    $data = array_combine($keys, array_values($data));
    
    // finally, we can use array_filter to keep any properties from the re-
    // constructed $data array.  array_filter keeps values for which the filter
    // returns true, so as long as a property exists of the same name as one of
    // the array's keys, that value will be in the array we return to the
    // // calling scope.
  
    $filter = fn($key) => property_exists(static::class, $key);
    return array_filter($data, $filter, ARRAY_FILTER_USE_KEY);
  }
}
