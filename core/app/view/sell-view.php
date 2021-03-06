<?php
// $symbol = ConfigurationData::getByPreffix("currency")->val;
$iva_name = ConfigurationData::getByPreffix("imp-name")->val;
$iva_val = ConfigurationData::getByPreffix("imp-val")->val;
?>
<style>
  
#v{
    width:320px;
    height:240px;
}
#qr-canvas{
    display:none;
}
#qrfile{
    width:320px;
    height:240px;
}
#mp1{
    text-align:center;
    font-size:35px;
}
#imghelp{
    position:relative;
    left:0px;
    top:-160px;
    z-index:100;
    font:18px arial,sans-serif;
    background:#f0f0f0;
  margin-left:35px;
  margin-right:35px;
  padding-top:10px;
  padding-bottom:10px;
  border-radius:20px;
}

</style>
<section class="content">






<div class="row">
	<div class="col-md-12">
	<h1>Venta</h1>
	<p><b>Buscar producto por nombre o por codigo:</b></p>
		<form id="searchp">
		<div class="row">
			<div class="col-md-3">
				<input type="hidden" name="view" value="sell">
				<input type="text" id="product_name" name="product_name" class="form-control" placeholder="Nombre del Producto">
			</div>

			<div class="col-md-3">
				<input type="hidden" name="view" value="sell">
				<input type="text" id="product_code" name="product_code" class="form-control" placeholder="Codigo de Barra">
			</div>


			<div class="col-md-1">
			<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> Buscar</button>
			</div>
      <div class="col-md-1">
      <button type="button" id="readqr" class="btn btn-default"><i class="fa fa-qrcode"></i> Buscar por QR</button>
      </div>

		</div>
		</form>


<div style="display:none;" id="qrreader">
<div id="mainbody">
<a class="selector" id="webcamimg" onclick="setwebcam()" align="left">Camara</a>
<a class="selector" id="qrimg" src="cam.png" onclick="setimg()" align="right">Imagen</a>
<div id="outdiv">
</div>
<div id="result">-- Scaning --</div>
<canvas id="qr-canvas" width="800" height="600"></canvas>


<button onclick="captureToCanvas()">Capture</button><br>
</div>
</div>

<script>
  $(document).ready(function(){
      $("#readqr").click(function(){
        qrreader = document.getElementById("qrreader");
        if(qrreader.style.display=="none"){
          qrreader.style.display="block";
          load();
        }else if(qrreader.style.display=="block"){
          qrreader.style.display="none";
          var MediaStream = window.MediaStream;

          if (typeof MediaStream === 'undefined' && typeof webkitMediaStream !== 'undefined') {
              MediaStream = webkitMediaStream;
          }

          /*global MediaStream:true */
          if (typeof MediaStream !== 'undefined' && !('stop' in MediaStream.prototype)) {
              MediaStream.prototype.stop = function() {
                  this.getAudioTracks().forEach(function(track) {
                      track.stop();
                  });

                  this.getVideoTracks().forEach(function(track) {
                      track.stop();
                  });
              };
          }

        }

      });
  });
</script>

<div id="show_search_results"></div>

<script>
//jQuery.noConflict();

$(document).ready(function(){
	$("#searchp").on("submit",function(e){
		e.preventDefault();

    code = $("#product_code").val();
    name = $("#product_name").val();
		if(name!=""){
		$.get("./?action=searchproduct",$("#searchp").serialize()+"&go=name",function(data){
			$("#show_search_results").html(data);
		});
		$("#product_name").val("");
    }
    else if(code!=""){
    $.get("./?action=searchproduct",$("#searchp").serialize()+"&go=code",function(data){
      $("#show_search_results").html(data);
    });
    $("#product_code").val("");
    }

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
<?php if(isset($_SESSION["cart"])):
$total = 0;
?>
<h2>Lista de venta</h2>
<div class="box box-primary">
<table class="table table-bordered table-hover">
<thead>
	<th style="width:30px;">Codigo</th>
    
    <th style="width: 30px;">Nombre</th>
    <th style="width: 30px;">Marca</th>
    <th style="width: 30px;">Modelo</th>
    <th style="width: 30px;">Color</th>
    <th style="width: 30px;">Chasis</th>
    
    
    
	<th style="width:30px;">Cantidad</th>
	<th style="width:30px;">Unidad</th>
	<th>Producto</th>
	<th style="width:30px;">Precio Unitario</th>
	<th style="width:30px;">Precio Total</th>
	<th ></th>
</thead>
<?php foreach($_SESSION["cart"] as $p):
$product = ProductData::getById($p["product_id"]);
$info_moto = ProductData::getInfoMoto($p["product_id"]);
?>
<tr >
	<td><?php echo $product->id; ?></td>
    
    <td><?php echo $product->name; ?></td>
    <td> <?php echo $info_moto->marca; ?> </td>
    <td> <?php echo $info_moto->modelo; ?> </td>
    <td> <?php echo $info_moto->color; ?> </td>
    <td> <?php echo $info_moto->chasis; ?> </td>
    
    
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
<form method="post" class="form-horizontal" id="processsell" action="index.php?view=processsell" enctype="multipart/form-data">
<h2>Resumen</h2>
<div class="row">
<div class="col-md-3">
    <label class="control-label">Almacen</label>
    <div class="col-lg-12">
    <h4 class=""><?php 
    echo StockData::getPrincipal()->name;
    ?></h4>
    </div>
  </div>

<div class="col-md-3">
    <label class="control-label">Cliente</label>
    <div class="col-lg-12">
    <?php 
$clients = PersonData::getClients();
    ?>
    <select name="client_id" id="client_id" class="form-control">
    <option value="">-- NINGUNO --</option>
    <?php foreach($clients as $client):?>
    	<option value="<?php echo $client->id;?>"><?php echo $client->name." ".$client->lastname;?></option>
    <?php endforeach;?>
    	</select>
    </div>
  </div>
<div class="col-md-3">
    <label class="control-label">Descuento</label>
    <div class="col-lg-12">
      <input type="text" name="discount" class="form-control" required value="0" id="discount" placeholder="Descuento">
    </div>
  </div>
 <div class="col-md-3">
    <label class="control-label">Efectivo</label>
    <div class="col-lg-12">
      <input type="text" name="money" value="0" class="form-control" id="money" placeholder="Efectivo">
    </div>
  </div>
  </div>

  <!--
  <div class="row">
        <div class="col-md-6">
            <label class="control-label">Pago</label>
            <div class="col-lg-12">
            <?php
        $clients = PData::getAll();
            ?>
            <select name="p_id" id="p_id" class="form-control">
            <?php foreach($clients as $client):?>
            	<option value="<?php echo $client->id;?>"><?php echo $client->name;?></option>
            <?php endforeach;?>
            	</select>
            </div>
          </div>
        <div class="col-md-6">
            <label class="control-label">Entrega</label>

            <div class="col-lg-12">
            <?php
        $clients = DData::getAll();
            ?>
            <select name="d_id" class="form-control">
            <?php foreach($clients as $client):?>
            	<option value="<?php echo $client->id;?>"><?php echo $client->name;?></option>
            <?php endforeach;?>
            	</select>
            </div>
          </div>
    </div>
    -->

    <input type="hidden" name="p_id" id="p_id" value="1" />
    <input type="hidden" name="d_id" id="d_id" value="1" />



      <input type="hidden" name="total" value="<?php echo $total; ?>" class="form-control" placeholder="Total">
      <div class="clearfix"></div>
<br>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div style="padding: 10px;">
                <strong>Abonos</strong>
                
                <br style="clear: both;" />
                <div id="lista_abonos">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width: 20%;">
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
                                <th style="width: 20%;">
                                Monto<br />
                                <input id="row_monto_abono" type="text" class="form-control" value="0" style="text-align: right;" />
                                </th>
                                <th style="width: 20%;">
                                Fecha<br />
                                <input id="row_fecha_abono" type="date" class="form-control" value="<?php echo date('Y-m-d') ?>" style="text-align: right;" />
                                </th>
                                <th style="width: 40%;">
                                Identificador<br />
                                <input id="row_identificador_abono" type="text" class="form-control" value="" style="text-align: right;" />
                                </th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <a href="#" id="btn_add_abonos" class="btn btn-primary btn-md"><i class="fa fa-plus"></i></a>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-8">
        <div class="box box-success">
            <div style="padding: 10px;">
                <strong>Descuentos</strong>

                <br style="clear: both;" />
                <div id="lista_descuentos">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width: 33%;">
                                Tipo<br />
                                <select id="row_tipo_descuento" class="form-control">
                                    <option value="">Seleccione...</option>
                                    <option value="1">BONO HONDA</option>
                                    <option value="2">BONO DEALER</option>
                                    <option value="3">BONO HONDA + DEALER</option>
                                    <option value="4">BONO MARUBENNI/TANNER</option>
                                </select>
                                </th>
                                <th style="width: 33%;">
                                Monto<br />
                                <input id="row_monto_descuento" type="text" class="form-control" value="0" style="text-align: right;" />
                                </th>
                                <th style="width: 33%;">
                                Glosa<br />
                                <input id="row_glosa_descuento" type="text" class="form-control" value="" style="text-align: right;" />
                                </th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <a href="#" id="btn_add_descuentos" class="btn btn-success btn-md"><i class="fa fa-plus"></i></a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
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
                <button class="btn btn-primary"><i class="glyphicon glyphicon-usd"></i><i class="glyphicon glyphicon-usd"></i> Finalizar Venta</button>
                </label>
              </div>
            </div>
          </div>

    </div>

</div>

<div class="row">
    <div class="col-lg-3 col-sm-12 col-xs-12">
        <div class="box box-primary">
            <div class="box-body">
                <input type="text" name="comision_dealer" /> &nbsp; <label>Comision Dealer</label>
            </div>

        </div>
    </div>
</div>

</form>


<script>

$(document).ready(function(){
    
    $(document).on('click','.btn_delete_abono', function(e){
        e.preventDefault();
        $(this).closest('tr').fadeOut(300, function(){
            $(this).remove();
        })
        var monto_restar = parseInt( $(this).closest('tr').find('.tot_efectivo').val() );
        var monto_total_efectivo = ( $("#money").val() - monto_restar )
        $("#money").val( monto_total_efectivo );
    })
    
    index = 0;
    var total_efectivo = 0;
    $("#btn_add_abonos").click(function(e){
        e.preventDefault();
        if( $("#row_tipo_abono").val() == "" ){
            alert("Debe seleccionar un tipo de abono");
        } else {
            var tipo = $("#row_tipo_abono").val();
            var monto = parseInt($("#row_monto_abono").val());
            var fecha = $("#row_fecha_abono").val();
            var identificador = $("#row_identificador_abono").val();
            
            cont = '<tr>';
            cont += '<td>'+tipo+' <input type="hidden" name="abonos['+index+'][tipo]" value="'+tipo+'" /> </td>';
            cont += '<td style="text-align: right;">'+monto+' <input type="hidden" class="tot_efectivo" name="abonos['+index+'][monto]" value="'+monto+'" /></td>';
            cont += '<td style="text-align: right;">'+fecha+' <input type="hidden" name="abonos['+index+'][fecha]" value="'+fecha+'" /></td>';
            cont += '<td style="text-align: left;">'+identificador+' <input type="hidden" name="abonos['+index+'][identificador]" value="'+identificador+'" />';
            if( ( tipo == 'CREDITO MARUBENNI' ) || ( tipo == 'CREDITO TANNER' ) ){
                cont += '<br><input type="file" id="carta_aceptacion_creditos" name="carta_aceptacion_creditos" style="display:inline">';
            }
            cont += '</td>';
            cont += '<td> <a href="#" class="btn btn-xs btn-danger btn_delete_abono"><i class="fa fa-trash-o"></i></a></td>';
            cont += '</tr>';
            
            current_total = parseInt($("#money").val());
            $("#money").val( current_total + monto );
            
            $("#lista_abonos tbody").append(cont);
            index++;
            
            $("#row_tipo_abono, #row_identificador_abono").val('');
            $("#row_monto_abono").val('0');
        }
    })


    $(document).on('click','.btn_delete_descuento', function(e){
        e.preventDefault();
        $(this).closest('tr').fadeOut(300, function(){
            $(this).remove();
        })
        var monto_restar = parseInt( $(this).closest('tr').find('.tot_efectivo').val() );
        var monto_total_efectivo = ( $("#discount").val() - monto_restar )
        $("#discount").val( monto_total_efectivo );
    })

    index = 0;
    var total_efectivo = 0;
    $("#btn_add_descuentos").click(function(e){
        e.preventDefault();
        if( $("#row_tipo_descuento").val() == "" ){
            alert("Debe seleccionar un tipo de descuento");
        } else {
            var tipo = $("#row_tipo_descuento option:selected").text();
            var tipo_val = $("#row_tipo_descuento").val();
            var monto = parseInt($("#row_monto_descuento").val());
            var glosa = $("#row_glosa_descuento").val();

            cont = '<tr>';
            cont += '<td>'+tipo+' <input type="hidden" name="descuentos['+index+'][tipo]" value="'+tipo_val+'" /> </td>';
            cont += '<td style="text-align: right;">'+monto+' <input type="hidden" class="tot_efectivo" name="descuentos['+index+'][monto]" value="'+monto+'" /></td>';
            cont += '<td style="text-align: right;">'+glosa+' <input type="hidden" name="descuentos['+index+'][glosa]" value="'+glosa+'" /></td>';
            cont += '<td> <a href="#" class="btn btn-xs btn-danger btn_delete_descuento"><i class="fa fa-trash-o"></i></a> </td>';
            cont += '</tr>';

            current_total = parseInt($("#discount").val());
            $("#discount").val( current_total + monto );

            $("#lista_descuentos tbody").append(cont);
            index++;

            $("#row_tipo_descuento, #row_glosa_descuento").val('');
            $("#row_monto_descuento").val('0');
        }
    })


})

$("#processsell").submit(function(e){
	discount = $("#discount").val();
    p = $("#p_id").val();
    client = $("#client_id").val();
    money = $("#money").val();
    if( $("#lista_abonos tbody tr").length == 0 ){
        alert("Debe ingresar al menos 1 abono");
        e.preventDefault();
    } else {
        if(money!=""){
            if(p!=4){
            	if(money<(<?php echo $total;?>-discount)){
            		alert("Efectivo insificiente!");
            		e.preventDefault();
            	}else{
            	   if( ( $("input[name=carta_aceptacion_creditos]").length > 0 ) && ( $("input[name=carta_aceptacion_creditos]").val() == "" ) ){
            	       alert("Debe adjuntar la carta de la Entidad Financiera del Crédito");
                       e.preventDefault();
            	   } else {
                		if(discount==""){ discount=0;}
                		go = confirm("Cambio: $"+(money-(<?php echo $total;?>-discount ) ) );
                		if(go){}
                			else{e.preventDefault();}
                    }
            	}
            }else if(p==4){ // usaremos credito
              if(client!=""){
                // procedemos
                cli=null;
                <?php 
                foreach(PersonData::getClients() as $cli){
                  echo " cli[$cli->id]=$cli->has_credit ;";
                }
                ?>
            
                if(cli[client]==1){
                  // si el cliente tiene credito entonces procedemos a hacer la venta a credito :D
            
                }else{
                  // el cliente no tiene credito
                  alert("El cliente seleccionado no cuenta con credito!");
                  e.preventDefault();
            
                }
              }else{
                // 
                alert("Debe seleccionar un cliente!");
                e.preventDefault();
              }
        
            }
        }else{
            alert("Campo de pago vacio")
            e.preventDefault();
        }
    }
});
</script>
        

<?php endif; ?>

</div>
</section>
