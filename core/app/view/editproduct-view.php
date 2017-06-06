<section class="content">
<?php
$product = ProductData::getById($_GET["id"]);
$info_moto = ProductData::getInfoMoto($product->id);
$categories = CategoryData::getAll();

if($product!=null):
?>
<div class="row">
	<div class="col-md-12">
	<h1><?php echo $product->name ?> <small>Editar Producto</small></h1>
  <?php if(isset($_COOKIE["prdupd"])):?>
    <p class="alert alert-info">La informacion del producto se ha actualizado exitosamente.</p>
  <?php setcookie("prdupd","",time()-18600); endif; ?>
	<br>
<div class="box box-primary">
  <table class="table">
  <tr>
  <td>
		<form class="form-horizontal" method="post" id="addproduct" enctype="multipart/form-data" action="index.php?view=updateproduct" role="form">

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Imagen*</label>
    <div class="col-md-8">
      <input type="file" name="image" id="name" placeholder="">
<?php if($product->image!=""):?>
  <br>
        <img src="storage/products/<?php echo $product->image;?>" class="img-responsive" style="max-width: 130px; height: auto;">
<?php endif;?>
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Codigo de barras*</label>
    <div class="col-md-8">
      <input type="text" name="barcode" class="form-control" id="barcode" value="<?php echo $product->barcode; ?>" placeholder="Codigo de barras del Producto">
    </div>
  </div>
    <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Nombre*</label>
    <div class="col-md-8">
      <input type="text" name="name" class="form-control" id="name" value="<?php echo $product->name; ?>" placeholder="Nombre del Producto">
    </div>
  </div>
<div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Categoria</label>
    <div class="col-md-8">
    <select name="category_id" id="category_id" class="form-control">
    <option value="">-- NINGUNA --</option>
    <?php foreach($categories as $category):?>
      <option value="<?php echo $category->id;?>" <?php if($product->category_id!=null&& $product->category_id==$category->id){ echo "selected";}?>><?php echo $category->name;?></option>
    <?php endforeach;?>
      </select>    </div>
  </div>
  
  <div id="info_add_motos" style="<?php echo( $product->category_id == 1 ) ? '' : 'display:none'; ?>; background: #E1EAFF; padding: 10px 0 5px 0px; margin-bottom: 10px;">
        <div class="form-group">
            <div class="col-lg-2"></div>
            <div class="col-lg-6"><strong>Datos adicionales para motos</strong><br class="clear" /></div>
        </div>
      <div class="form-group">        
        <label for="moto_marca" class="col-lg-2 control-label">Marca</label>
        <div class="col-md-6">            
            <input value="<?php echo $info_moto->marca ?>" type="text" name="moto_marca" id="moto_marca" required class="form-control" placeholder="Marca" />    
        </div>
      </div>
      <div class="form-group">        
        <label for="moto_modelo" class="col-lg-2 control-label">Modelo</label>
        <div class="col-md-6">            
            <input value="<?php echo $info_moto->modelo ?>" type="text" name="moto_modelo" id="moto_modelo" required class="form-control" placeholder="Modelo" />    
        </div>
      </div>
      <div class="form-group">        
        <label for="moto_color" class="col-lg-2 control-label">Color</label>
        <div class="col-md-6">            
            <input value="<?php echo $info_moto->color ?>" type="text" name="moto_color" id="moto_color" required class="form-control" placeholder="Color" />    
        </div>
      </div>
      <div class="form-group">        
        <label for="moto_year" class="col-lg-2 control-label">Año</label>
        <div class="col-md-6">            
            <select name="moto_year" id="moto_year" class="form-control">
                <?php for( $i=( (int)date('Y') + 2 ); $i>=1900; $i-- ){ ?>
                <option value="<?php echo $i; ?>" <?php echo ($i==date('Y')) ? 'selected' : ''; ?> ><?php echo $i; ?></option>
                <?php  } ?>
            </select>
            <script> $("#moto_year").val("<?php echo $info_moto->year ?>") </script>
        </div>
      </div>
      <div class="form-group">        
        <label for="moto_chasis" class="col-lg-2 control-label">Chasis</label>
        <div class="col-md-6">            
            <input value="<?php echo $info_moto->chasis ?>" type="text" name="moto_chasis" id="moto_chasis" required class="form-control" placeholder="Chasis" />    
        </div>
      </div>
      <div class="form-group">        
        <label for="moto_motor" class="col-lg-2 control-label">Motor</label>
        <div class="col-md-6">            
            <input value="<?php echo $info_moto->motor ?>" type="text" name="moto_motor" id="moto_motor" required class="form-control" placeholder="Motor" />    
        </div>
      </div>
      <div class="form-group">        
        <label for="moto_peso" class="col-lg-2 control-label">Peso bruto vehicular</label>
        <div class="col-md-6">            
            <input value="<?php echo $info_moto->peso ?>" type="text" name="moto_peso" id="moto_peso" required class="form-control" placeholder="Peso bruto vehicular" />    
        </div>
      </div>
      <div class="form-group">        
        <label for="moto_combustible" class="col-lg-2 control-label">Combustible</label>
        <div class="col-md-6">            
            <input value="<?php echo $info_moto->combustible ?>" type="text" name="moto_combustible" id="moto_combustible" required class="form-control" placeholder="Combustible" />    
        </div>
      </div>
      
      <div class="form-group">
        <label for="inputFechaBodega" class="col-lg-2 control-label">Fecha ingreso bodega:</label>
        <div class="col-md-6">
          <input value="<?php echo $info_moto->fecha_ingreso_bodega ?>" type="date" name="inputFechaBodega" class="form-control" id="inputFechaBodega" placeholder="Fecha ingreso bodega">
        </div>
      </div>
      
      <div class="form-group">
        <label for="inputEstado" class="col-lg-2 control-label">Estado:</label>
        <div class="col-md-6">
          <select name="inputEstado" class="form-control" id="inputEstado">
            <option value="">Seleccione</option>
            <option value="PROPIA">PROPIA</option>
            <option value="DEMO">DEMO</option>
            <option value="TEST">TEST</option>
          </select>
          <script> $("#inputEstado").val("<?php echo $info_moto->estado ?>") </script>
        </div>
      </div>
      
  </div>
  
  
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Descripcion</label>
    <div class="col-md-8">
      <textarea name="description" class="form-control" id="description" placeholder="Descripcion del Producto"><?php echo $product->description;?></textarea>
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Precio de Entrada*</label>
    <div class="col-md-8">
      <input type="text" name="price_in" class="form-control" value="<?php echo $product->price_in; ?>" id="price_in" placeholder="Precio de entrada">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Precio de Salida*</label>
    <div class="col-md-8">
      <input type="text" name="price_out" class="form-control" id="price_out" value="<?php echo $product->price_out; ?>" placeholder="Precio de salida">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Unidad*</label>
    <div class="col-md-8">
      <input type="text" name="unit" class="form-control" id="unit" value="<?php echo $product->unit; ?>" placeholder="Unidad del Producto">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Presentacion</label>
    <div class="col-md-8">
      <input type="text" name="presentation" class="form-control" id="inputEmail1" value="<?php echo $product->presentation; ?>" placeholder="Presentacion del Producto">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label">Minima en inventario:</label>
    <div class="col-md-8">
      <input type="text" name="inventary_min" class="form-control" value="<?php echo $product->inventary_min;?>" id="inputEmail1" placeholder="Minima en Inventario (Default 10)">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-3 control-label" >Esta activo</label>
    <div class="col-md-8">
<div class="checkbox">
    <label>
      <input type="checkbox" name="is_active" <?php if($product->is_active){ echo "checked";}?>> 
    </label>
  </div>
    </div>
  </div>

  <div class="form-group">
    <div class="col-lg-offset-3 col-lg-8">
    <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
      <button type="submit" class="btn btn-success">Actualizar Producto</button>
    </div>
  </div>
</form>
</td>
</tr>
</table>
</div>
	</div>
</div>

<script>
$(document).ready(function(){
    $("#category_id").change(function(){
        if( $(this).val() == 1 ){
            $("#info_add_motos").slideDown('fast');
        } else {
            $("#info_add_motos").slideUp('fast')
        }
    });
})
</script>

<?php endif; ?>
</section>