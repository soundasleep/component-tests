<?php

namespace ComponentTests;

class FailingTest extends ComponentTest {

  function getRoots() {
    return array(__DIR__ . "/../resources/failures");
  }

  /**
   * Don't exclude anything, especially /vendor/ paths!
   * Causes false positives on Windows
   */
  function getExcludes() {
    return array();
  }

  function testJSONLint() {
    try {
      parent::testJSONLint();
    } catch (\PHPUnit\Framework\ExpectationFailedException $e) {
      // expected
      return;
    }

    $this->fail("Expected an assertion failure");
  }

  function testPHPLint() {
    try {
      parent::testPHPLint();
    } catch (\PHPUnit\Framework\ExpectationFailedException $e) {
      // expected
      return;
    }

    $this->fail("Expected an assertion failure");
  }

  /**
   * @return true if {@link #testPHPLint()} error should be printed to the console
   */
  function printPHPLintErrors() {
    return false;
  }

  function testComposerJSONSchema() {
    try {
      parent::testComposerJSONSchema();
    } catch (\PHPUnit\Framework\ExpectationFailedException $e) {
      // expected
      return;
    }

    $this->fail("Expected an assertion failure");
  }

  /**
   * @return true if {@link #testComposerJSONSchema()} error should be printed to the console
   */
  function printComposerJSONSchemaErrors() {
    return false;
  }

  function testPHPRequiresUseDir() {
    try {
      parent::testPHPRequiresUseDir();
    } catch (\PHPUnit\Framework\ExpectationFailedException $e) {
      // expected
      return;
    }

    $this->fail("Expected an assertion failure");
  }

  function testPHPRequiresExist() {
    try {
      parent::testPHPRequiresExist();
    } catch (\PHPUnit\Framework\ExpectationFailedException $e) {
      // expected
      return;
    }

    $this->fail("Expected an assertion failure");
  }

}
