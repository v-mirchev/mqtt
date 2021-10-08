<?php declare(strict_types=1);

namespace Test;

use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestListenerDefaultImplementation;

class Injector implements TestListener {

  use TestListenerDefaultImplementation;

  public function startTest(Test $test): void {
    if ($test instanceof TestCase) {
      $this->backupGlobals();
      $this->readGlobalAnnotations($test);
    }
  }

  public function endTest(Test $test, $time): void {
    $this->restoreGlobals();
  }

  private function backupGlobals(): void {
  }

  private function restoreGlobals(): void {
  }

  private function readGlobalAnnotations(TestCase $test): void {
    $globalVars = $this->parseGlobalAnnotations($test);
    if (!empty($globalVars['inject'])) {
      foreach ($globalVars['inject'] as $value) {
        if (!isset($GLOBALS[ltrim($value, '$')])) {
          $test->fail('Trying to inject non existing global variable ' . $value);
          return;
        } else {
          $test->{ltrim($value, '$')} = $GLOBALS[ltrim($value, '$')];
        }
      }
    }
  }

  private function parseGlobalAnnotations(TestCase $test): array {
    $annotations = $test->getAnnotations();
    $globalVarAnnotations = array_filter(
      array_merge_recursive($annotations['class'], $annotations['method']),
      function ($annotationName) {
        return in_array($annotationName, ['inject']);
      },
      ARRAY_FILTER_USE_KEY
    );

    return $globalVarAnnotations;
  }

}
