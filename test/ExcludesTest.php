<?php

namespace ComponentTests;

/**
 * We can't both test the component-tests component AND the functionality
 * of that component in the same class, because it's very likely that this
 * component may be part of another component (with a parent /vendor/ path)
 * and thus <i>all</i> of the shouldExclude tests will always return true.
 */
class ExcludesTest extends ComponentTest {

  function getRoots() {
    // no roots
    return array();
  }

  /**
   * Test the actual implementation of the testing class.
   */
  function getExcludes() {
    return array("/TestExclude/");
  }

  /**
   * Test the actual implementation of the testing class.
   */
  function testShouldExclude() {
    $this->assertTrue($this->shouldExclude(__DIR__ . "/../TestExclude/"));
    $this->assertTrue($this->shouldExclude(__DIR__ . "/../TestExclude/1/2/3"));
    $this->assertFalse($this->shouldExclude(__DIR__ . "/../TestExclud"),
      "should have not excluded " . __DIR__ . "/../TestExclud");
    $this->assertFalse($this->shouldExclude(__DIR__ . "/../TestExclud/1/2/3"));
  }

  /**
   * Test the actual implementation of the testing class.
   * Tests {@link #getResolvedPath($root, $filename, $path)}.
   */
  function testGetResolvedPath() {
    $this->assertEquals(__DIR__ . "/../1/2/3.php", $this->getResolvedPath(__DIR__ . "/parent.php", "/../1/2/3.php"));
    $this->assertEquals(__DIR__ . "/src/../1/2/3.php", $this->getResolvedPath(__DIR__ . "/src/parent.php", "/../1/2/3.php"));
  }

  /**
   * Switch "\" to "/" as necessary
   */
  function switchSlashes($s) {
    return str_replace("\\", "/", $s);
  }

  /**
   * Test the actual implementation of the testing class.
   */
  function testShouldExcludeWindows() {
    $this->assertTrue($this->shouldExclude($this->switchSlashes(__DIR__ . "/../TestExclude/")));
    $this->assertTrue($this->shouldExclude($this->switchSlashes(__DIR__ . "/../TestExclude/1/2/3")));
    $this->assertFalse($this->shouldExclude($this->switchSlashes(__DIR__ . "/../TestExclud")),
      "should have not excluded " . $this->switchSlashes(__DIR__ . "/../TestExclud"));
    $this->assertFalse($this->shouldExclude($this->switchSlashes(__DIR__ . "/../TestExclud/1/2/3")));
  }

}
