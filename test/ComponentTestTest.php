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
    return array("/resources/");
  }

  /**
   * Test the actual implementation of the testing class.
   */
  function testShouldExclude() {
    $this->assertTrue($this->shouldExclude(__DIR__ . "/../resources/"));
    $this->assertTrue($this->shouldExclude(__DIR__ . "/../resources/1/2/3"));
    $this->assertFalse($this->shouldExclude(__DIR__ . "/../resource"));
    $this->assertFalse($this->shouldExclude(__DIR__ . "/../resource/1/2/3"));
  }

}
