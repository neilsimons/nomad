<?php

/**
 * global functions for use in templates / development can go here
 */

// play safe with these debugging functions
if(function_exists('e')) return;
if(function_exists('dmsg')) return;
if(function_exists('print_dmsgs')) return;


function e($msg){
    throw new Exception($msg);
}

/**
 * add a debug message to the global array of messages
 * @param string $msg   message prefix
 * @param mixed $mixed  the variable we want to examine
 * @param mixed $tag  can filter by this tag
 * @param int $singlelineoutput should be viewed as single line output
 */
function dmsg($msg, $mixed=null, $tag='', $singlelineoutput=0)
{
    $trace = debug_backtrace();
    if (isset($trace[1])) {
        $caller = $trace[1];
        if (isset($caller['file'])) {
            $file = $caller['file'];
            #$line = $caller['line'];
            $line = $trace[0]['line'];
            $function = $caller['function'];
            $class = $caller['class'];
            $type = $caller['type'];
            $args = [];
            foreach ($caller['args'] as $i => $arg) {
                switch (true) {
                    case(is_string($arg)):
                        $args[] = (strlen($arg) > 10) ? "'" . substr($arg, 0, 10) . "..'" : "'$arg'";
                        break;
                    case(is_array($arg)):
                        $args[] = 'a';
                        break;
                    default:
                        $args[] = 'o';
                        break;
                }
            }
            $callerdesc = $class . $type . $function . "(" . join(",", $args) . ")$line";
        }
    }
    @$GLOBALS['dmsgs'][] = array($callerdesc, $msg, $mixed, $tag, $singlelineoutput);
}


/**
 * print all debug messages
 * if suuplied , $ifAnyOfThese array is checked for a poositive value before printing. usage example [[$_COOKIE['debug'],$_REQUEST['debug']]
 * @param array $ifAnyOfThese
 */
function print_dmsgs(array $ifAnyOfThese=[]){
    $tagFilterStr = '';
    if(count($ifAnyOfThese)){ // only output if one has a positive value. also use any found value as tagFilterStr
        $ok = 0; foreach($ifAnyOfThese as $var){ if($var){ $ok = 1; $tagFilterStr=$var; break; }}
        #echo "$ok, $tagFilterStr\n";
        if(!$ok) return;
        if($tagFilterStr=='1') $tagFilterStr=''; // 1 should not filter
    }

    if(isset($GLOBALS['dmsgs']) && is_array($GLOBALS['dmsgs'])) {
        echo "<pre><small>dmsgs[$tagFilterStr]:\n";
        foreach ($GLOBALS['dmsgs'] as $i => $a) { $callerdesc = $a[0]; $msg = $a[1]; $mixed = $a[2]; $class = $a[3]; $singlelineoutput=$a[4];
            if(strlen($tagFilterStr) && strpos($class, $tagFilterStr)!==0) continue; // filtered out
            echo htmlentities("$class $callerdesc  $msg");
            if(isset($mixed)) {
                $mixed = htmlentities(var_export($mixed,1));
                if($singlelineoutput) $mixed=str_replace(["\n","\r"],'',$mixed);
                echo ": ".$mixed;
            }
            echo "\n";
        }
        echo "</small></pre>\n";
    }
}