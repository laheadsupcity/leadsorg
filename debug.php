<?php

class Debug {

  public static function dumpR($object, $die = false) {
    echo("<pre>");
    print_r([$object]);
    echo("</pre>");

    if ($die) {
      die;
    }
  }

}
