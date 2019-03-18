<?php

class Debug {

  public static function dumpR($object, $die = false) {
    echo("<pre style='border: 1px solid;'>");
    var_dump([$object]);
    echo("</pre>");

    if ($die) {
      die;
    }
  }

}
