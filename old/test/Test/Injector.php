<?php declare(strict_types = 1); declare(strict_types=1);

namespace Test;

use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestListenerDefaultImplementation;

class Injector implements TestListener {

  use TestListenerDefaultImplementation;

  /**
   * @var
   */
  protected $globalVars;

  /**
   * @var
   */
  protected $prefix;

  /**
   * @param string[] $globalVars
   * @param string $prefix
   */
  public function __construct($globalVars = [], $prefix = '___') {
    $this->globalVars = $globalVars;
    $this->prefix = $prefix;
  }

  public function startTest(Test $test): void {
    if ($test instanceof TestCase) {
      foreach (array_merge($this->globalVars, $this->getParsedGlobalAnnotations($test)) as $value) {
        if (!isset($GLOBALS[ltrim($value, '$')])) {
          error_log(
            'Trying to inject non existing global variable ' . $value .
            ' for ' . get_class($test) . '::' . $test->getName()
          );
          exit(1);
        } else {
          $test->{$this->prefix . ltrim($value, '$')} = $GLOBALS[ltrim($value, '$')];
        }
      }
    }
  }

  protected function getParsedGlobalAnnotations(TestCase $test): array {
    $annotations = $test->getAnnotations();
    $globalVarAnnotations = array_filter(
      $annotations['class'],
      function ($annotationName) {
        return $annotationName = 'Inject';
      },
      ARRAY_FILTER_USE_KEY
    );

    return isset($globalVarAnnotations['Inject']) ? $globalVarAnnotations['Inject'] : [];
  }

}
