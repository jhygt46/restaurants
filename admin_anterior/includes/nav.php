<?php
    
    include("info_nav.php");
    
?>
<div class="nav">
    <input id="navw" type="hidden" value="1" />
    <ul class="navlist">
        <?php for($i=0; $i<count($menu); $i++){ ?>
        <li class="lt">
            <div class="ti clearfix" id="menu-<?php echo $i; ?>">
                <div class="icon ico<?php echo $menu[$i]['ico']; ?>"></div>
                <div class="title"><?php echo $menu[$i]['categoria']; ?></div>
                <!--<div class="rows">13</div>-->
            </div>
            <ul<?php if(isset($menu[$i]['show'])){ ?> style="display:block" <?php } ?>>
                <?php for($j=0; $j<count($menu[$i]['subcategoria']); $j++){ ?>
                <li><a class='navlink' onClick="navlink('<?php echo $menu[$i]['subcategoria'][$j]['link']; ?>')"><?php echo $menu[$i]['subcategoria'][$j]['nombre']; ?></a><!--<p>12</p>--></li>
                <?php } ?>
            </ul>
            <div class="tooltip clearfix">
                <div class="toolrows"></div>
                <div class="toolcont">
                        <?php for($j=0; $j<count($menu[$i]['subcategoria']); $j++){ ?>
                        <div class="nt"><a class='navlink' onClick="navlink('<?php echo $menu[$i]['subcategoria'][$j]['link']; ?>')"><?php echo $menu[$i]['subcategoria'][$j]['nombre']; ?></a><!--<p>13</p>--></div>
                        <?php } ?>
                </div>
            </div>
        </li>
        <?php } ?>
    </ul>
</div>