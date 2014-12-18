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

  /**
   * Test the actual implementation of the testing class.
   */
  function testShouldExclude() {
    $this->assertTrue($this->shouldExclude(__DIR__ . "/../resources/"));
    $this->assertTrue($this->shouldExclude(__DIR__ . "/../resources/1/2/3"));
    $this->assertFalse($this->shouldExclude(__DIR__ . "/../resource"));
    $this->assertFalse($this->shouldExclude(__DIR__ . "/../resource/1/2/3"));
  }

  /**
   * Test the actual implementation of the testing class.
   * Tests {@link #getResolvedPath($root, $filename, $path)}.
   */
  function testGetResolvedPath() {
    $this->assertEquals(__DIR__ . "/../1/2/3.php", $this->getResolvedPath(__DIR__ . "/parent.php", "/../1/2/3.php"));
    $this->assertEquals(__DIR__ . "/src/../1/2/3.php", $this->getResolvedPath(__DIR__ . "/src/parent.php", "/../1/2/3.php"));
  }

}
