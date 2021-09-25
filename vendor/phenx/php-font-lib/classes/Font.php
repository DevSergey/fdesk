<?php
class Font {
  static $debug = false;
  public static function load($file) {
    $header = file_get_contents($file, false, null, null, 4);
    $class = null;
    switch($header) {
      case "\x00\x01\x00\x00": 
      case "true": 
      case "typ1": 
        $class = "Font_TrueType"; break;
      case "OTTO":
        $class = "Font_OpenType"; break;
      case "wOFF":
        $class = "Font_WOFF"; break;
      case "ttcf":
        $class = "Font_TrueType_Collection"; break;
      default: 
        $magicNumber = file_get_contents($file, false, null, 34, 2);
        if ($magicNumber === "LP") {
          $class = "Font_EOT";
        }
    }
    if ($class) {
      require_once dirname(__FILE__)."/$class.php";
      $obj = new $class;
      $obj->load($file);
      return $obj;
    }
    return null;
  }
  static function d($str) {
    if (!self::$debug) return;
    echo "$str\n";
  }
  static function UTF16ToUTF8($str) {
    return mb_convert_encoding($str, "utf-8", "utf-16");
  }
  static function UTF8ToUTF16($str) {
    return mb_convert_encoding($str, "utf-16", "utf-8");
  }
}
