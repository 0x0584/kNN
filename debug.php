<?php
require_once 'global.php';

$__DEBUG = false;

function d_array($foo, $msg = "> ") {
    //if (__DEBUG === false) {
    echo $msg.implode(", ", $foo).BR;
    //}
}

function d_on() {
    $__DEBUG = true;
}

function d_off() {
    $__DEBUG = false;
}
?>