<?php
// このファイルは/users/include.php
// 他の場所に移す際は以下のものを書き換えること
// + __ROOT__
// + __USER__

define('__ROOT__', dirname(dirname(__FILE__)));
require_once __ROOT__.'/functions/basic.php';
require_once __ROOT__.'/functions/auth.php';
