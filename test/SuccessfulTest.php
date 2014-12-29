<?php

namespace ComponentTests;

class SuccessfulTest extends ComponentTest {

  function getRoots() {
    return array(__DIR__ . "/../resources/successes");
  }

  /**
   * Don't exclude anything, especially /vendor/ paths!
   * Causes false positives on Windows
   */
  function getExcludes() {
    return array();
  }

}
