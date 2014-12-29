<?php

namespace ComponentTests;

abstract class ComponentTest extends \PHPUnit_Framework_TestCase {

  /**
   * Get a list of paths to search over, e.g. __DIR__.
   */
  abstract function getRoots();

  /**
   * May be extended by child classes to define a list of path
   * names that will be excluded by {@link #iterateOver()}.
   */
  function getExcludes() {
    return array("/vendor/");
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
            call_user_func($callback, $root . "/" . $entry);
          }
        }
      }
      closedir($handle);
    }
  }

  /**
   * Should the given (absolute or relative) path be excluded based
   * on {@link #getExcludes()}?
   */
  function shouldExclude($path) {
    foreach ($this->getExcludes() as $pattern) {
      if (strpos($path, $pattern) !== false) {
        return true;
      }
    }
    return false;
  }

  function _testJSONLint($filename) {
    $this->assertNotNull(json_decode(file_get_contents($filename)), "File '$filename' was not valid JSON");
  }

  /**
   * Test that all JSON files are valid.
   */
  function testJSONLint() {
    foreach ($this->getRoots() as $root) {
      $this->iterateOver($root, ".json", array($this, '_testJSONLint'));
    }
  }

  /**
   * @return true if {@link #testPHPLint()} error should be printed to the console
   */
  function printPHPLintErrors() {
    return true;
  }

  function _testPHPLint($filename) {
    $return = 0;
    $output_array = array();
    $output = exec("php -l " . escapeshellarg($filename) . " 2>&1", $output_array, $return);
    if ($return !== 0 && $this->printPHPLintErrors()) {
      echo "[$filename]\n" . implode("\n", $output_array);
    }
    $this->assertFalse(!!$return, "File '$filename' failed PHP lint: '$output' ($return)");
  }

  /**
   * Test that all PHP files are valid.
   */
  function testPHPLint() {
    foreach ($this->getRoots() as $root) {
      $this->iterateOver($root, ".php", array($this, '_testPHPLint'));
    }
  }

  /**
   * @return true if {@link #testComposerJSONSchema()} error should be printed to the console
   */
  function printComposerJSONSchemaErrors() {
    return true;
  }

  function _testComposerJSONSchema($filename) {
    // Get the schema and data as objects
    $retriever = new \JsonSchema\Uri\UriRetriever;
    $schema = $retriever->retrieve("file://" . __DIR__ . "/../composer-schema.json");
    $data = json_decode(file_get_contents($filename));

    // // If you use $ref or if you are unsure, resolve those references here
    // // This modifies the $schema object
    // $refResolver = new JsonSchema\RefResolver($retriever);
    // $refResolver->resolve($schema, 'file://' . __DIR__);

    // Validate
    $validator = new \JsonSchema\Validator();
    $validator->check($data, $schema);

    $message = "(no message)";
    $errors = $validator->getErrors();
    if (count($errors) > 0) {
      $message = trim($errors[0]['property'] . " " . $errors[0]['message']);
    }
    if (!$validator->isValid() && $this->printComposerJSONSchemaErrors()) {
      foreach ($validator->getErrors() as $error) {
        echo sprintf("[%s] %s\n", $error['property'], $error['message']);
      }
    }

    $this->assertTrue($validator->isValid(), "File '$filename' was not valid Composer JSON according to composer-schema.json: $message");
  }

  /**
   * Test that all `composer.json` files are valid.
   * This is nicer over `composer validate` because we don't want to deal with warnings;
   * just critical errors.
   */
  function testComposerJSONSchema() {
    foreach ($this->getRoots() as $root) {
      $this->iterateOver($root, "composer.json", array($this, '_testComposerJSONSchema'));
    }
  }

  function _testPHPRequiresUseDir($filename) {
    $s = file_get_contents($filename);
    if (preg_match('#\n[^*/]*((require|require_once|include|include_once|file_exists))\(("|\')[^/]+#m', $s, $matches)) {
      $this->assertFalse(true, "Found " . $matches[1] . "() that did not use __DIR__ in '" . $filename . "': '" . trim($matches[0]) . "'");
    }
  }

  /**
   * Test that all PHP files that use `require()` or `include()`
   * use __DIR__ to prevent relative include problems.
   */
  function testPHPRequiresUseDir() {
    foreach ($this->getRoots() as $root) {
      $this->iterateOver($root, ".php", array($this, '_testPHPRequiresUseDir'));
    }
  }

  function _testPHPRequiresExist($filename) {
    $s = file_get_contents($filename);
    if (preg_match_all('#\n[^*/]*(require|require_once|include|include_once)\(__DIR__ . ("|\')([^"\']+)("|\')#m', $s, $matches_array, PREG_SET_ORDER)) {
      foreach ($matches_array as $matches) {
        $path = $matches[3];

        // path should start with /
        $this->assertTrue(substr($path, 0, 1) == "/", "Included path '$path' in '$filename' did not start with /");

        // get relative dir
        $resolved = $this->getResolvedPath($filename, $path);
        $this->assertTrue(file_exists($resolved), "Included path '$path' in '$filename' was not found: [$resolved]");
      }
    }
  }

  /**
   * Test that all PHP files that use `require()` or `include()`
   * exist.
   */
  function testPHPRequiresExist() {
    foreach ($this->getRoots() as $root) {
      $this->iterateOver($root, ".php", array($this, '_testPHPRequiresExist'));
    }
  }

  /**
   * @param $filename absolute path of the file we're currently looking at
   * @param $path the relative path referenced in the include
   */
  function getResolvedPath($filename, $path) {
    // if $filename is always absolute, then we can just use the $filename without
    // filename + the relative path
    $bits = explode("/", $filename);
    unset($bits[count($bits)-1]);
    return implode("/", $bits) . $path;
  }

}
