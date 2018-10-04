<?php
$theme       = nomad::theme();
$pagestyle = nomad::getPageStyle();
$sitepath = nomad::sitePath();
?>

    </div><!-- end #middle -->

    <div id="footer" class="">

        <!-- <p>All communication to: admin(at)chamconference.org.</p> -->


        <div id="site-by" class="small">Site by
            <a href="http://www.nomadit.co.uk">NomadIT
                <img src="<?=$sitepath?>/res-nomad/shared/images/nomad-icon-72.png"
                     alt="NomadIT" width="30" height="27"/>
            </a>
        </div>

        <div id="modified-on" class="small">
            <span class="hidden-xs hidden-sm"> page modified </span> <?=nomad::pageModifiedDate()?>
            <a class="hidden-xs" target="_blank" id="html5-badge" href="http://validator.w3.org/check?uri=referer"><img
                src="<?=$sitepath?>/res-nomad/shared/images/HTML5-logo.svg"
                alt="Valid HTML"
                title="Valid HTML"/>
            </a>
        </div>
    </div>
</div><!-- end #middle-and-footer -->


