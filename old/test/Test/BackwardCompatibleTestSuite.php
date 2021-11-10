<?php declare(strict_types = 1);

namespace Test;

if (!class_exists('\PHPUnit_Framework_TestSuite')) {
  class PHPUnit_Framework_TestSuite extends \PHPUnit\Framework\TestSuite {}
}

class BackwardCompatibleTestSuite extends PHPUnit_Framework_TestSuite {

  /**
   * @static
   * @return TestSuite
   */
  public static function suite() {
    $suite = new self;
    foreach (self::toRun() as $file) {
      $suite->addTestFile($file);
    }
    return $suite;
  }

  /**
   * @return array an array of files to be run by PHPUnit
   */
  private static function toRun() {
    $run = __DIR__ . '/../';
    if ($run === null) {
      throw new Exception(sprintf("No environment variable to run (%s) found.", self::ENV_RUN));
    }
    $result = array();
    foreach (explode(";", $run) as $part) {
      if (is_dir($part)) {
        $result = array_merge($result, self::rglob("*[Tt]est.php", $part . DIRECTORY_SEPARATOR));
      } elseif (is_file($part)) {
        $result[] = $part;
      } else {
        throw new \Exception(sprintf("Argument '%s' neither file nor directory.", $part));
      }
    }
    return $result;
  }

  /**
   * Recursive {@link http://php.net/manual/en/function.glob.php glob()}.
   *
   * @access private
   * @static
   *
   * @param  string $pattern the pattern passed to glob(), default is "*"
   * @param  string $path    the path to scan, default is
   *                         {@link http://php.net/manual/en/function.getcwd.php the current working directory}
   * @param  int    $flags   the flags passed to glob()
   * @return array  an array of files in the given path matching the pattern.
   * @link http://php.net/manual/en/function.glob.php
   * @link http://php.net/manual/en/function.getcwd.php
   */
  private static function rglob($pattern = '*', $path = '', $flags = 0) {
    $paths = glob($path . '*', GLOB_MARK | GLOB_ONLYDIR | GLOB_NOSORT) or array();
    $files = glob($path . $pattern, $flags) or array();
    foreach ($paths as $path) {
      $files = array_merge($files, self::rglob($pattern, $path, $flags));
    }
    return $files;
  }

}
