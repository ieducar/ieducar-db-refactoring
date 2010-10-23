<?php

class Extractor
{
  private $sqlFile = null;
  private $destinationDir = null;
  private $fileContents = '';
  private $fileLines = array();
  private $functionStartStatment = 'CREATE FUNCTION';
  private $functionEndStatment = '    LANGUAGE plpgsql;';

  public function __construct($sqlFile = null, $destinationDir = null)
  {
    $this->setSqlFile($sqlFile);
    $this->setDestinationDir($destinationDir);
  }

  public function setSqlFile($sqlFile)
  {
    $this->sqlFile = $sqlFile;
  }

  public function setDestinationDir($destinationDir)
  {
    $this->destinationDir = $destinationDir;
  }

  public function getSqlFile()
  {
    if (is_null($this->sqlFile)) {
      return dirname(__FILE__) . '/../database/ieducar.sql';
    }
    return $this->sqlFile;
  }

  public function getDestinationDir()
  {
    if (is_null($this->destinationDir)) {
      return dirname(__FILE__) . '/../extracted_functions';
    }
    return $this->destinationDir;
  }

  private function loadFileContents()
  {
    $this->fileContents = file_get_contents($this->getSqlFile());
    $this->fileLines = explode("\n", $this->fileContents);
  }

  public function getAndSaveFunctions()
  {
    $this->loadFileContents();
    $functions = $this->getFunctions();
    $this->saveFunctions($functions);
    return $functions;
  }

  private function saveFunctions(array $functions)
  {
    $functionNumber = 1;
    foreach ($functions as $functionName => $functionLines) {
      $contents = array();
      foreach ($functionLines as $functionLine) {
        $contents[] = $functionLine;
      }

      $filepath = $this->getFunctionFilepath($functionNumber, $functionName);
      file_put_contents($filepath, implode("\n", $contents));
      $functionNumber++;
    }
  }

  private function getFunctionFilepath($functionNumber, $functionName)
  {
    $filename = sprintf('%03d_%s.sql', $functionNumber, $functionName);
    return $this->getDestinationDir() . '/' . $filename;
  }

  private function getFunctions()
  {
    $functions = array();
    $functionName = '';

    foreach ($this->fileLines as $lineNumber => $line) {
      if ($this->isLineStartFunctionStatment($line)) {
        if (! $this->isUserDefinedFunction($lineNumber, $this->fileLines)) {
          break;
        }

        $functionName = $this->getFunctionName($line);
      }

      // Fora do escopo da função, passa para a próxima linha.
      if ($functionName == '') {
        continue;
      }

      $functions[$functionName][$lineNumber] = $line;

      if ($this->isLineEndFunctionStatment($line)) {
        $functionName = '';
      }
    }

    return $functions;
  }

  private function isLineStartFunctionStatment($line)
  {
    if ($this->lineStartsWith($line, $this->functionStartStatment)) {
      return true;
    }
    return false;
  }

  private function isLineEndFunctionStatment($line)
  {
    if ($this->lineStartsWith($line, $this->functionEndStatment)) {
      return true;
    }
    return false;
  }

  private function getFunctionName($functionSignature)
  {
    $regex = '/^(CREATE FUNCTION) (.*)\(/';
    $matches = array();
    preg_match($regex, $functionSignature, $matches);
    return $matches[2];
  }

  private function isUserDefinedFunction($lineNumber, $lines)
  {
    $ii = $lineNumber;

    for ($i = 0; $i < 1000; $i++) {
      if ($this->isLineEndFunctionStatment($lines[$ii])) {
        return true;
      }
      $ii++;
    }

    return false;
  }

  private function lineStartsWith($line, $starts)
  {
    $regex = '/^(' . $starts . ')/';
    $matches = array();
    preg_match($regex, $line, $matches);
    if (count($matches) == 0) {
      return false;
    }
    return true;
  }
}