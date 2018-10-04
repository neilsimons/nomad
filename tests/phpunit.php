<?php
/**
 * Created by PhpStorm.
 * User: ns
 * Date: 24/05/2018
 * Time: 01:20
 */

// config
$phpunitphar = '/app/lib/phar/phpunit-5.phar';
$phpunitconf =  __DIR__.'/phpunit.xml';

$cmd = '--configuration '.$phpunitconf;

/*
// It's not possible to entirely disable output buffering when using mod_proxy/mod_proxy_fcgi,
// however, you can still have responses streamed in chunks.

// turn off ob (if on by default)
@ob_end_flush();
@ob_end_flush();
#echo "test line 1\n";
#flush();
#usleep(1000000 * 0.5);
#echo "test line 2\n";
#flush();
*/

// plaintext output (mostly)
#header("Content-Type: text/plain");
echo "<pre>\n"; // cos output contains SOME html
echo "[phpunit-5.phar] $cmd\n\n";

// provide command line args
$_SERVER['argv'] = explode(" ", $cmd);
// include the phar, cacth and displose of the shebang line
ob_start();     include $phpunitphar;       ob_get_clean();

// run
PHPUnit_TextUI_Command::main();
