<section class="content">
<div class="btn-group pull-right">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-download"></i> Descargar <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu">
    <li><a href="report/onesell-word.php?id=<?php echo $_GET["id"];?>"><i class="fa fa-file-word-o"></i> Word 2007 (.docx)</a></li>
    <li><a onclick="thePDF()" id="makepdf" class=""><i class="fa fa-file-pdf-o"></i> Descargar PDF</a>
    <li><a href="report/onesell-xlsx.php?id=<?php echo $_GET["id"];?>"><i class="fa fa-file-excel-o"></i> Excel 2007 (.xlsx)</a></li>
  </ul>
</div>
<h1>Resumen de Venta</h1>
<?php if(isset($_GET["id"]) && $_GET["id"]!=""):?>
<?php
$sell = SellData::getById($_GET["id"]);
$operations = OperationData::getAllProductsBySellId($_GET["id"]);
$total = 0;

$abonos = SellData::getAbonosById($sell->id);
$descuentos = SellData::getDescuentosById($sell->id);

?>
<?php
if(isset($_COOKIE["selled"])){
  foreach ($operations as $operation) {
//    print_r($operation);
    $qx = OperationData::getQByStock($operation->product_id,StockData::getPrincipal()->id);
    // print "qx=$qx";
      $p = $operation->getProduct();
    if($qx==0){
      echo "<p class='alert alert-danger'>El producto <b style='text-transform:uppercase;'> $p->name</b> no tiene existencias en inventario.</p>";      
    }else if($qx<=$p->inventary_min/2){
      echo "<p class='alert alert-danger'>El producto <b style='text-transform:uppercase;'> $p->name</b> tiene muy pocas existencias en inventario.</p>";
    }else if($qx<=$p->inventary_min){
      echo "<p class='alert alert-warning'>El producto <b style='text-transform:uppercase;'> $p->name</b> tiene pocas existencias en inventario.</p>";
    }
  }
  setcookie("selled","",time()-18600);
}

?>
<div class="box box-primary">
<table class="table table-bordered">
<?php if($sell->person_id!=""):
$client = $sell->getPerson();
?>
    <tr>
        <td colspan="2"><strong>Cliente</strong></td>
    </tr>
    <tr>
        <td> <strong>Nombre: </strong> </td>
        <td> <?php echo $client->name." ".$client->lastname;?> </td>
    </tr>
    <tr>
        <td><strong>Rut: </strong></td>
        <td><?php echo $client->no;?></td>
    </tr>
    <tr>
        <td><strong>Dirección: </strong></td>
        <td> <?php echo $client->address1;?> </td>
    </tr>    
    <tr>
        <td><strong>Telefono: </strong></td>
        <td> <?php echo $client->phone1;?> </td>
    </tr>    
    <tr>
        <td> <strong>Email: </strong> </td>
        <td> <?php echo $client->email1;?> </td>
    </tr>    

<?php endif; ?>
<?php if($sell->user_id!=""):
$user = $sell->getUser();
?>
<tr>
  <td>Atendido por</td>
  <td><?php echo $user->name." ".$user->lastname;?></td>
</tr>
<?php endif; ?>
</table>
</div>
<br>
<div class="box box-primary">
<table class="table table-bordered table-hover">
  <thead>
    <th>Codigo</th>
    <th>Cantidad</th>
    <th>Datos del Producto</th>
    <th>Precio Unitario</th>
    <th>Total</th>

  </thead>
<?php
  foreach($operations as $operation){
    $product  = $operation->getProduct();
    $info_moto = ProductData::getInfoMoto($product->id);    
?>
<tr>
  <td><?php echo $product->id ;?></td>
  <td><?php echo $operation->q ;?></td>
  <td>
  <?php if( $info_moto ){ ?>
    <strong>Modelo:</strong> <?php echo $product->name;?><br />
    <strong>Marca: </strong> <?php echo $info_moto->marca;?> <br />    
    <strong>Color: </strong><?php echo $info_moto->color;?><br />
    <strong>Año: </strong><?php echo $info_moto->year;?><br />
    <strong>Chasis: </strong><?php echo $info_moto->chasis;?><br />
    <strong>Motor: </strong><?php echo $info_moto->motor;?><br />
    <strong>Peso: </strong><?php echo $info_moto->peso;?><br />
    <strong>Combustible: </strong><?php echo $info_moto->combustible;?>
    <?php } else { ?>
    <?php echo $product->name;?>
    <?php } ?>
    </td>
  <td>$ <?php echo number_format($operation->price_out,2,".",",") ;?></td>
  <td><b>$ <?php echo number_format($operation->q*$operation->price_out,2,".",",");$total+=$operation->q*$operation->price_out;?></b></td>
</tr>
<?php
  }
  ?>
</table>
</div>
<br><br>
<div class="row">
<div class="col-lg-10 col-sm-12">


<form action="index.php?view=updatesell" method="post" enctype="multipart/form-data">
    <input type="hidden" name="sell_id" value="<?php echo $_GET["id"] ?>" />
    <div class="box box-primary">
        <div class="box-header"> <strong>Abonos</strong> </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th> Tipo </th>
                    <th> Monto </th>
                    <th> Fecha </th>
                    <th> Identificador </th>
                    <th colspan="2"> Extra </th>

                </tr>
            </thead>
            <tbody>
            <?php
            $tot = 0;
            foreach( $abonos as $abono ){
                $cadena = $abono->tipo;
                $buscar = "CREDITO";
                $resultado = strpos($cadena, $buscar);

                ?>
                  <tr>
                    <td><?php echo $abono->tipo ?></td>
                    <td>
                        <?php
                        $tot+=$abono->monto;
                        echo "$ " . number_format($abono->monto,0,',','.');
                        ?>
                    </td>
                    <td>
                        <?php
                        $str_fecha = strtotime($abono->fecha);
                        echo date('d-M-Y', $str_fecha);
                        ?>
                    </td>
                    <td>
                        <?php echo $abono->identificador ?>
                    </td>
                    <td>
                        <?php
                        if($resultado !== FALSE){
                        ?>
                        <div class="form-group">
                            <input type="checkbox" id="cobrado" name="cobrado" <?php if( $abono->cobrado ){ echo "checked"; } ?> /> <label for="cobrado">Cobrado</label>
                            <br />
                            <input type="text" name="factura_asociada_abono" id="factura_asociada_abono" value="<?php echo $abono->factura_nro ?>" /> &nbsp; <label for="factura_asociada_abono">Número Factura</label>
                            <input type="hidden" name="abono_id" value="<?php echo $abono->id ?>" />
                        </div>
                        <?php
                        }
                        ?>
                    </td>
                    <?php if( @$abono->carta ){ ?>
                    <td>
                        <a href="storage/cartas/<?php echo $abono->carta ?>" target="_blank" class="btn btn-default">Ver Carta</a>
                    </td>
                    <?php } ?>
                  </tr>
            <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>
                        <strong>Total:</strong>
                    </th>
                    <th><?php echo "$ " . number_format($tot,0,',','.'); ?></th>
                    <th colspan="4"></th>
                </tr>
            </tfoot>
        </table>
    </div>

    <?php if($resultado !== FALSE){ ?>
    <button type="submit" class="btn btn-success pull-right">Actualizar</button>
    <?php } ?>


    <?php if($descuentos){ ?>
    <!-- Descuentos -->
    <div class="box box-primary">
        <div class="box-header"> <strong>Descuentos</strong> </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th> Tipo </th>
                    <th> Monto </th>
                    <th> Glosa </th>
                </tr>
            </thead>
            <tbody>
            <?php
            $tot = 0;
            foreach( $descuentos as $discount ){
            ?>
              <tr>
                <td><?php echo SellData::getTipoDescuento($discount->tipo); ?></td>
                <td>
                    <?php
                    $tot+=$discount->monto;
                    echo "$ " . number_format($discount->monto,0,',','.');
                    ?>
                </td>
                <td>
                    <?php echo $discount->glosa ?>
                </td>

              </tr>
            <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>
                        <strong>Total:</strong>
                    </th>
                    <th><?php echo "$ " . number_format($tot,0,',','.'); ?></th>
                    <th colspan="4"></th>
                </tr>
            </tfoot>
        </table>
    </div>
    <?php } ?>

</form>

<br style="clear: both;" />
<br style="clear: both;" />

<div class="box box-primary">
<table class="table table-bordered">
  <tr>
    <td><h4>Descuento:</h4></td>
    <td><h4>$ <?php echo number_format($sell->discount,0,',','.'); ?></h4></td>
  </tr>
  <tr>
    <td><h4>SubTotal:</h4></td>
    <td><h4>$ <?php echo number_format($total - $sell->discount,0,',','.'); ?></h4></td>
  </tr>
  <tr>
    <td><h4>Total:</h4></td>
    <td><h4>$ <?php echo number_format($total,0,',','.'); ?></h4></td>
  </tr>
</table>
</div>

<?php if($sell->person_id!=""):
$credit=PaymentData::sumByClientId($sell->person_id)->total;

?>
<div class="box box-primary">
<table class="table table-bordered">
  <tr>
    <td><h4>Saldo anterior:</h4></td>
    <td><h4>$ <?php echo number_format($credit-$total,0,',','.'); ?></h4></td>
  </tr>
  <tr>
    <td><h4>Saldo Actual:</h4></td>
    <td><h4>$ <?php echo number_format($credit,0,',','.'); ?></h4></td>
  </tr>
</table>
</div>
<?php endif;?>
</div>
</div>






<script type="text/javascript">
        function thePDF() {

var columns = [
//    {title: "Reten", dataKey: "reten"},
    {title: "Codigo", dataKey: "code"}, 
    {title: "Cantidad", dataKey: "q"}, 
    {title: "Nombre del Producto", dataKey: "product"}, 
    {title: "Precio unitario", dataKey: "pu"}, 
    {title: "Total", dataKey: "total"}, 
//    ...
];


var columns2 = [
//    {title: "Reten", dataKey: "reten"},
    {title: "", dataKey: "clave"}, 
    {title: "", dataKey: "valor"}, 
//    ...
];

var rows = [
  <?php foreach($operations as $operation):
  $product  = $operation->getProduct();
  ?>

    {
      "code": "<?php echo $product->id; ?>",
      "q": "<?php echo $operation->q; ?>",
      "product": "<?php echo $product->name; ?>",
      "pu": "$ <?php echo number_format($operation->price_out,2,".",","); ?>",
      "total": "$ <?php echo number_format($operation->q*$operation->price_out,2,".",","); ?>",
      },
 <?php endforeach; ?>
];

var rows2 = [
<?php if($sell->person_id!=""):
$person = $sell->getPerson();
?>

    {
      "clave": "Cliente",
      "valor": "<?php echo $person->name." ".$person->lastname; ?>",
      },
      <?php endif; ?>
    {
      "clave": "Atendido por",
      "valor": "<?php echo $user->name." ".$user->lastname; ?>",
      },

];

var rows3 = [

    {
      "clave": "Descuento",
      "valor": "$ <?php echo number_format($sell->discount,2,'.',',');; ?>",
      },
    {
      "clave": "Subtotal",
      "valor": "$ <?php echo number_format($sell->total,2,'.',',');; ?>",
      },
    {
      "clave": "Total",
      "valor": "$ <?php echo number_format($sell->total-$sell->discount,2,'.',',');; ?>",
      },
];


// Only pt supported (not mm or in)
var doc = new jsPDF('p', 'pt');
        doc.setFontSize(26);
        doc.text("NOTA DE VENTA", 40, 65);
        doc.setFontSize(14);
        doc.text("Fecha: <?php echo $sell->created_at; ?>", 40, 80);
//        doc.text("Operador:", 40, 150);
//        doc.text("Header", 40, 30);
  //      doc.text("Header", 40, 30);

doc.autoTable(columns2, rows2, {
    theme: 'grid',
    overflow:'linebreak',
    styles: {
        fillColor: [100, 100, 100]
    },
    columnStyles: {
        id: {fillColor: 255}
    },
    margin: {top: 100},
    afterPageContent: function(data) {
//        doc.text("Header", 40, 30);
    }
});


doc.autoTable(columns, rows, {
    theme: 'grid',
    overflow:'linebreak',
    styles: {
        fillColor: [100, 100, 100]
    },
    columnStyles: {
        id: {fillColor: 255}
    },
    margin: {top: doc.autoTableEndPosY()+15},
    afterPageContent: function(data) {
//        doc.text("Header", 40, 30);
    }
});

doc.autoTable(columns2, rows2, {
    theme: 'grid',
    overflow:'linebreak',
    styles: {
        fillColor: [100, 100, 100]
    },
    columnStyles: {
        id: {fillColor: 255}
    },
    margin: {top: doc.autoTableEndPosY()+15},
    afterPageContent: function(data) {
//        doc.text("Header", 40, 30);
    }
});
//doc.setFontsize
//img = new Image();
//img.src = "liberacion2.jpg";
//doc.addImage(img, 'JPEG', 40, 10, 610, 100, 'monkey'); // Cache the image using the alias 'monkey'
doc.setFontSize(20);
doc.setFontSize(12);
doc.text("Generado por el Sistema de inventario", 40, doc.autoTableEndPosY()+25);
doc.save('sell-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
//doc.output("datauri");

        }
    </script>

<script>
  $(document).ready(function(){
  //  $("#makepdf").trigger("click");
    $("#factura_asociada_abono").keyup(function(){
        if( $(this).val().length > 0 ){
            $("#cobrado").prop('checked',true);
        } else {
            $("#cobrado").prop('checked',false);
        }

    })
  });
</script>




<?php else:?>
  501 Internal Error
<?php endif; ?>
</section>
