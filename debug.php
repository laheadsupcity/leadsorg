<?php

class Debug {

  public static function dumpR($object, $die = false) {
    echo("<pre>");
    var_dump([$object]);
    echo("</pre>");

    if ($die) {
      die;
    }
  }

}
