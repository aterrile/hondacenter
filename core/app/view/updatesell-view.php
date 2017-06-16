<?php
extract($_POST);

if( @$cobrado ){
    $flag_cobrado = 1;
} else {
    $flag_cobrado = 0;
}

SellData::updateAbonos($abono_id,$flag_cobrado,$factura_asociada_abono);

Header('Location: index.php?view=onesell&id=' . $sell_id);
exit();

?>
