<?php
// $symbol = ConfigurationData::getByPreffix("currency")->val;
$iva_name = ConfigurationData::getByPreffix("imp-name")->val;
$iva_val = ConfigurationData::getByPreffix("imp-val")->val;

?>
<section class="content">
<div class="row">
	<div class="col-md-12">
	<h1>Cotizacion</h1>
	<p><b>Buscar producto por nombre o por codigo:</b></p>
		<form id="searchp">
		<div class="row">
			<div class="col-md-6">
				<input type="hidden" name="view" value="newcotization">
				<input type="text" id="product_code" name="product" class="form-control">
			</div>
			<div class="col-md-3">
			<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> Buscar</button>
			</div>
		</div>
		</form>
<div id="show_search_results"></div>

<script>
//jQuery.noConflict();

$(document).ready(function(){
	$("#searchp").on("submit",function(e){
		e.preventDefault();
		
		$.get("./?action=searchproduct2",$("#searchp").serialize(),function(data){
			$("#show_search_results").html(data);
		});
		$("#product_code").val("");

	});
	});

$(document).ready(function(){
    $("#product_code").keydown(function(e){
        if(e.which==17 || e.which==74){
            e.preventDefault();
        }else{
            console.log(e.which);
        }
    })
});
</script>

<?php if(isset($_SESSION["errors"])):?>
<h2>Errores</h2>
<p></p>
<table class="table table-bordered table-hover">
<tr class="danger">
	<th>Codigo</th>
	<th>Producto</th>
	<th>Mensaje</th>
</tr>
<?php foreach ($_SESSION["errors"]  as $error):
$product = ProductData::getById($error["product_id"]);
?>
<tr class="danger">
	<td><?php echo $product->id; ?></td>
	<td><?php echo $product->name; ?></td>
	<td><b><?php echo $error["message"]; ?></b></td>
</tr>

<?php endforeach; ?>
</table>
<?php
unset($_SESSION["errors"]);
 endif; ?>


<!--- Carrito de compras :) -->
<?php if(isset($_SESSION["cotization"])):
$total = 0;
?>
<h2>Lista de venta</h2>
<div class="box box-primary">
<table class="table table-bordered table-hover">
<thead>
	<th style="width:30px;">Codigo</th>
	<th style="width:30px;">Cantidad</th>
	<th style="width:30px;">Unidad</th>
	<th>Producto</th>
	<th style="width:30px;">Precio Unitario</th>
	<th style="width:30px;">Precio Total</th>
	<th ></th>
</thead>
<?php foreach($_SESSION["cotization"] as $p):
$product = ProductData::getById($p["product_id"]);
?>
<tr >
	<td><?php echo $product->id; ?></td>
	<td ><?php echo $p["q"]; ?></td>
	<td><?php echo $product->unit; ?></td>
	<td><?php echo $product->name; ?></td>
	<td><b>$ <?php echo number_format($product->price_out,2,".",","); ?></b></td>
	<td><b>$ <?php  $pt = $product->price_out*$p["q"]; $total +=$pt; echo number_format($pt,2,".",","); ?></b></td>
	<td style="width:30px;"><a href="index.php?view=clearcart&product_id=<?php echo $product->id; ?>" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancelar</a></td>
</tr>

<?php endforeach; ?>
</table>
</div>
<form method="post" class="form-horizontal" id="processsell" action="index.php?action=savecotization">
<h2>Resumen</h2>

      <input type="hidden" name="total" value="<?php echo $total; ?>" class="form-control" placeholder="Total">
      <div class="clearfix"></div>
<br>
<div class="row">
    <div class="col-md-6">
        <div class="box box-primary">
            <div style="padding: 10px;">
                <strong>Abonos</strong>
                
                <br style="clear: both;" />
                <div id="lista_abonos">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width: 50%;">
                                Tipo<br />
                                <select id="row_tipo_abono" class="form-control">
                                    <option value="">Seleccione...</option>
                                    <option value="EFECTIVO">EFECTIVO</option>
                                    <option value="TRANSFERENCIA">TRANSFERENCIA</option>
                                    <option value="TARJETA DEBITO">TARJETA DEBITO</option>
                                    <option value="TARJETA CREDITO">TARJETA CREDITO</option>
                                    <option value="CREDITO MARUBENNI">CREDITO MARUBENNI</option>
                                    <option value="CREDITO TANNER">CREDITO TANNER</option>
                                    <option value="OTRO CREDITO">OTRO CREDITO</option>
                                    <option value="VALE VISTA">VALE VISTA</option>
                                    <option value="CHEQUE">CHEQUE</option>
                                    <option value="OC">OC</option>      
                                </select>
                                </th>
                                <th style="width: 25%;">
                                Monto<br />
                                <input id="row_monto_abono" type="text" class="form-control" value="0" style="text-align: right;" />
                                </th>
                                <th style="width: 25%;">
                                Fecha<br />
                                <input id="row_fecha_abono" type="date" class="form-control" value="<?php echo date('Y-m-d') ?>" style="text-align: right;" />
                                </th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <a href="#" id="btn_add_abonos" class="btn btn-success btn-md"><i class="fa fa-plus"></i></a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="box box-primary">
        <table class="table table-bordered">
        <tr>
        	<td><p>Subtotal</p></td>
        	<td><p><b>$ <?php echo number_format($total*(1 - ($iva_val/100) ),2,'.',','); ?></b></p></td>
        </tr>
        <tr>
        	<td><p><?php echo $iva_name." (".$iva_val."%) ";?></p></td>
        	<td><p><b>$ <?php echo number_format($total*($iva_val/100),2,'.',','); ?></b></p></td>
        </tr>
        <tr>
        	<td><p>Total</p></td>
        	<td><p><b>$ <?php echo number_format($total,2,'.',','); ?></b></p></td>
        </tr>
        
        </table>
        </div>
          <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
              <div class="checkbox">
                <label>
                  <input name="is_oficial" type="hidden" value="1">
                </label>
              </div>
            </div>
          </div>
        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
              <div class="checkbox">
                <label>
        		<a href="index.php?view=clearcart" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancelar</a>
                <button class="btn btn-success"><i class="glyphicon glyphicon-send"></i> Guardar Cotizacion</button>
                </label>
              </div>
            </div>
          </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function(){
    $("#btn_add_abonos").click(function(e){
        e.preventDefault();
        var tipo = $("#row_tipo_abono").val();
        var monto = $("#row_monto_abono").val();
        var fecha = $("#row_fecha_abono").val();
        
        cont = '<tr>';
        cont += '<td>'+tipo+'</td>';
        cont += '<td style="text-align: right;">'+monto+'</td>';
        cont += '<td style="text-align: right;">'+fecha+'</td>';
        cont += '</tr>';              
        
        $("#lista_abonos tbody").append(cont);
    })
})
</script>

<?php endif; ?>

</div>
</section>