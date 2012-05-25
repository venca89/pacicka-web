<?php

include "MyBlog.php";
include "pocitadlo.php";

include "head.php";

include "menu.php";

toVsechnoVim();
list($uni,$cel) =  pocitej();
echo "$uni/$cel";

/*printContent();
printFoot();   */

echo '<hr />';

$otazka='Kolik klepů udělá datel?';
$moznosti=array('1', 'jiná otázka','17643', 'vlez mi na hrb' );
$soubor='w/datel';
anketa($otazka,$moznosti,$soubor);

include "foot.php";

?>