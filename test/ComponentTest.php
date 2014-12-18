<?php

namespace ComponentTests;

abstract class ComponentTest extends \PHPUnit_Framework_TestCase {

  /**
   * Get a list of paths to search over, e.g. __DIR__.
   */
  abstract function getRoots();

  function getExcludes() {
    return array();
  }

  function iterateOver($root, $extension, $callback) {
    if ($handle = opendir($root)) {
      while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
          // do we exclude this path?
          if ($this->shouldExclude($root . "/" . $entry)) {
            continue;
          }

          if (is_dir($root . "/" . $entry)) {
            $this->iterateOver($root . "/" . $entry, $extension, $callback);
          } else if (substr(strtolower($entry), -strlen($extension)) == strtolower($extension)) {
            $callback($root . "/" . $entry);
          }
        }
      }
      closedir($handle);
    }
  }

  function shouldExclude($path) {
    foreach ($this->getExcludes() as $pattern) {
      if (strpos($path, $pattern) !== false) {
        return true;
      }
    }
    return false;
  }

  /**
   * Test that all JSON files are valid.
   */
  function testJSONLint() {
    foreach ($this->getRoots() as $root) {
      $this->iterateOver($root, ".json", function($filename) {
        $this->assertNotNull(json_decode(file_get_contents($filename)), "File '$filename' was not valid JSON");
      });
    }
  }

}
