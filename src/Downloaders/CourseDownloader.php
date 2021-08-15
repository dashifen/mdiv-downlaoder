<?php

namespace Dashifen\MDiv\Downloaders;

use Dashifen\MDiv\Repositories\Course;
use Dashifen\MDiv\DownloaderException;
use GuzzleHttp\Exception\GuzzleException;
use Dashifen\Repository\RepositoryException;

class CourseDownloader extends AbstractDownloader
{
  private array $unnecessary = [
    'Iliff Students',
    'Iliff Student Senate',
    'Trustees - Student Affairs',
  ];
  
  
  /**
   * fetch
   *
   * Gets information about the student's courses and returns it to the
   * calling scope.
   *
   * @return Course[]
   * @throws DownloaderException
   * @throws RepositoryException
   * @throws GuzzleException
   */
  public function fetch(): array
  {
    $courses = $this->getCourses('courses');
    $filtered = $this->filter($courses);
    return array_values($filtered);
  }
  
  /**
   * getCourses
   *
   * Given a URL from which to fetch data, returns an array of Course objects
   * that we construct from the data we get from the API.
   *
   * @param string $url
   *
   * @return array
   * @throws DownloaderException
   * @throws GuzzleException
   * @throws RepositoryException
   */
  private function getCourses(string $url): array
  {
    $courses = $this->downloader->get($url);
    array_walk($courses, fn(&$course) => $course = new Course($course));
    return $courses;
  }
  
  /**
   * filter
   *
   * Filters our the courses we don't need to worry about yet within our
   * downloader.
   *
   * @param Course[] $courses
   *
   * @return array
   * @throws DownloaderException
   * @throws GuzzleException
   * @throws RepositoryException
   */
  private function filter(array $courses): array
  {
    // in addition to the courses we've listed above as unnecessary, we also
    // want to remove any favorite classes.  those are assumed to be the ones
    // in which a person is currently enrolled, and therefore we will wait
    // until they're complete before we download them.
    
    $favorites = $this->getCourses('users/self/favorites/courses');
    
    // we merge the names of our favorite courses into the list of unnecessary
    // ones listed above.  then, we pass that list through array_unique just in
    // case.
    
    $unnecessary = array_unique(array_merge(
      array_map(fn(Course $course) => $course->name, $favorites),
      $this->unnecessary
    ));
    
    // finally, we define our filter such that it will remove any courses from
    // the list we were passed from the calling scope whose names are found in
    // the list of unnecessary courses.
    
    $filter = fn(Course $course) => !in_array($course->name, $unnecessary);
    return array_filter($courses, $filter);
  }
  
  
}
