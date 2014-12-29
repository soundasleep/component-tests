<?php

namespace ComponentTests;

class ComponentTestTest extends ComponentTest {

  function getRoots() {
    return array(__DIR__ . "/..");
  }

  /**
   * Test the actual implementation of the testing class.
   */
  function getExcludes() {
    return array("/resources/", "/vendor/");
  }

}
