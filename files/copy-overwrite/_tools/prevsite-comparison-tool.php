<?php



/**
 * frameset container for helping translation
 * TODO just compied from sss translation wrapper, change for comparing with prevsite
 */





switch(@$_REQUEST['part']){
    case('framelefttop'):

        output_framelefttop();
        break;

    case('framerighttop'):
        // include the main index.php in config only mode! must be done here in global scope ..
        $ff_bootstrap_config_only = 1;  include dirname($_SERVER['SCRIPT_FILENAME']).'/index.php'; if(class_exists('dm',0)) dm::disable();
        #$lcs		 = lcs();
        #$defaultlc	 = defaultlc();
        output_framerighttop();
        break;

    default:
        output_frameset();
}




/**
 * Enter description here...
 *
 */
function output_frameset(){
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>translation tool</title>
    <!--<script type="text/javascript" src="/res/0/jquery.1.4.2.min.js"></script>-->
    <? javascript() ?>
</head>
<frameset rows="50,*">
    <frameset cols="*,*">
        <frame src="?part=framelefttop" class="frame lefttop" id="framelefttop" name="framelefttop" />
        <frame src="?part=framerighttop" class="frame righttop" id="framerighttop" name="framerighttop" />
    </frameset>
    <frameset cols="*,*" frameborder="5" framespacing="0" >
        <frame src="about:blank" class="frame left" id="frameleft" name="frameleft" />
        <frame src="about:blank" class="frame right" id="frameright" name="frameright" />
    </frameset>
</frameset>
</html>
<?
}





function output_framelefttop(){
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
    <style type="text/css">
        body{ margin:0px; padding:0; background-color:#333; color:#fff; }
        textarea#msgs{ vertical-align:top; background-color:#333; color:#fff; border:0; }
    </style>
</head>
<body>
<input type="text" id="leftiuri" size="50">
<button onclick="top.refreshLeft()">refresh</button>
<textarea id="msgs" cols="30" rows="2"></textarea>
</body>
</html>
<?
}



function output_framerighttop(){
#$lcs		 = array('fr','de','es','nl','pl');
#$lcs		 = array_keys($hash_lc_language);
#$defaultlc	 = 'en';
// now loaded from conf!
$lcs		 = lcs();
$defaultlc	 = defaultlc();

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
    <style type="text/css">
        body{ margin:0px; padding:0; background-color:#333; color:#fff; }
        textarea#msgs{ vertical-align:top; background-color:#333; color:#fff; border:0; }
    </style>
</head>
<body>
<input type="text" id="rightiuri" size="50">
<button onclick="top.refreshRight()">refresh</button>
<select id="lcs" onchange="top.syncRightframe()">
    <option value="">select language code</option>
    <? foreach($lcs as $lc){ $language = getLanguage($lc); ?>
        <? if($lc===$defaultlc) continue ?>
        <option value="<?= $lc ?>"><?=$lc?> : <?=$language?></option>
    <? } ?>
</select>
<button onclick="top.finished()">exit</button>
</body>
</html>
<?
}






function javascript(){
    ?>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
    <script type="text/javascript">
        function finished(){
            location.pathname = '/'; // navigates to homepage!
        }
        function clog(thing){
            try{ console.log(thing); } catch(e) { }
        }

        function msg(str){ // msg
            var doc	 = document.getElementById("framelefttop").contentDocument;	//console.log(doc);
            var obmsgs		 = $('#msgs', doc).get(0);
            $(obmsgs).append(str+"\n");
            obmsgs.scrollTop = obmsgs.scrollHeight;
        }

        function refreshLeft(){
            var iuri	 = get_leftiuri();
            var win		 = document.getElementById("frameleft").contentWindow;
            var url		 = '/'+iuri;
            msg('left navigating to '+url);
            win.location = url;
        }

        function refreshRight(){
            var lc		 = getSelectedLc();
            if(!lc.length){
                msg('no language selected');
                return;
            }
            var iuri	 = get_rightiuri();
            var win		 = document.getElementById("frameright").contentWindow;
            var url		 = '/'+lc+'/'+iuri;
            msg('left navigating to '+url);
            win.location = url;
        }


        function syncRightframe(){ // copy left to right, run refreshright
            set_rightiuri( get_leftiuri() );
            refreshRight();
        }


        function bindscrollingbehaviour(){
            // standad frames
            var left		 = document.getElementById("frameleft");
            var right		 = document.getElementById("frameright");
            left	 = left.contentWindow;
            right	 = right.contentWindow;

            $(left).unbind();
            $(right).unbind();

            $(left).scroll(function() {
                $(right).scrollTop($(left).scrollTop());
                $(right).scrollLeft($(left).scrollLeft());
            });
            $(right).scroll(function() {
                $(left).scrollTop($(right).scrollTop());
                $(left).scrollLeft($(right).scrollLeft());
            });
        }

        function setPageLoaded(callingwindow, iuri){ // called from sss documents within the frames when page document ready
            // always rebind scrolling
            bindscrollingbehaviour();
            //msg(iuri);
            //console.log(callingwindow);
            if(callingwindow.frameElement.id == 'frameleft'){
                // set inputvalue
                set_leftiuri( iuri );
                msg('left loaded:'+iuri);
                // sync right to location in left
                set_rightiuri( get_leftiuri() );
                refreshRight();
                //var tgturl = callingwindow.location.href;
                //var tgtpath = callingwindow.location.pathname;
                //g_iuri_current = iuri;
                //syncRightframe();
            } else {
                msg('right loaded');
            }
        }

        function get_leftiuri(){
            var doc		 = document.getElementById("framelefttop").contentDocument;	//console.log(doc);
            var leftiuri = $('#leftiuri',doc).val();
            return leftiuri;
        }
        function set_leftiuri( iuri ){
            var doc		 = document.getElementById("framelefttop").contentDocument;	//console.log(doc);
            $('#leftiuri',doc).val( iuri );
        }
        function get_rightiuri(){
            var doc		 = document.getElementById("framerighttop").contentDocument;	//console.log(doc);
            var rightiur = $('#rightiuri',doc).val();
            return rightiur;
        }
        function set_rightiuri( iuri ){
            var doc		 = document.getElementById("framerighttop").contentDocument;	//console.log(doc);
            $('#rightiuri',doc).val( iuri );
        }



        $(function(){
            // load root page in left frame
            $("#frameleft").attr('src', '/');
            // sync scrolling - doesnt work with document ready
        });
        $(window).load(function(){
            // has to go in window onload for some readson
            bindscrollingbehaviour();
        });
    </script>
    <?
}

