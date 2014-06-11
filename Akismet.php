<?php

/*
 * Author:   Fahmi Adib fahmi.adib@gmail.com
 * License:  http://www.gnu.org/copyleft/gpl.html GPL
 * Link:     https://github.com/fadib/Akismet
 */

function Services_Akismet_autoload($className) {
    if (substr($className, 0, 15) != 'Services_Akismet') {
        return false;
    }
    $file = str_replace('_', '/', $className);
    $file = str_replace('Services/', '', $file);
    return include dirname(__FILE__) . "/{$file}.php";
}

spl_autoload_register('Services_Akismet_autoload');

