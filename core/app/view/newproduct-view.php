<section class="content">
    <?php 
$categories = CategoryData::getAll();
    ?>
<div class="row">
	<div class="col-md-12">
	<h1>Nuevo Producto</h1>
	<br>
  <div class="box box-primary">
  <table class="table">
  <tr>
  <td>
	<form class="form-horizontal" method="post" enctype="multipart/form-data" id="addproduct" action="index.php?view=addproduct" role="form">

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Imagen</label>
    <div class="col-md-6">
      <input type="file" name="image" id="image" placeholder="">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Codigo de Barras*</label>
    <div class="col-md-6">
      <input type="text" name="barcode" id="product_code" class="form-control" id="barcode" placeholder="Codigo de Barras del Producto">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Nombre*</label>
    <div class="col-md-6">
      <input type="text" name="name" required class="form-control" id="name" placeholder="Nombre del Producto">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Categoria</label>
    <div class="col-md-6">
    <select name="category_id" id="category_id" class="form-control">
    <option value="">-- NINGUNA --</option>
    <?php foreach($categories as $category):?>
      <option value="<?php echo $category->id;?>"><?php echo $category->name;?></option>
    <?php endforeach;?>
      </select>    </div>
  </div>
  
  <div id="info_add_motos" style="display: none; background: #E1EAFF; padding: 10px 0 5px 0px; margin-bottom: 10px;">
        <div class="form-group">
            <div class="col-lg-2"></div>
            <div class="col-lg-6"><strong>Datos adicionales para motos</strong><br class="clear" /></div>
        </div>
      <div class="form-group">        
        <label for="moto_marca" class="col-lg-2 control-label">Marca</label>
        <div class="col-md-6">            
            <input type="text" name="moto_marca" id="moto_marca"  class="form-control" placeholder="Marca" />    
        </div>
      </div>
      <div class="form-group">        
        <label for="moto_modelo" class="col-lg-2 control-label">Modelo</label>
        <div class="col-md-6">            
            <input type="text" name="moto_modelo" id="moto_modelo"  class="form-control" placeholder="Modelo" />    
        </div>
      </div>
      <div class="form-group">        
        <label for="moto_color" class="col-lg-2 control-label">Color</label>
        <div class="col-md-6">            
            <input type="text" name="moto_color" id="moto_color"  class="form-control" placeholder="Color" />    
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
        </div>
      </div>
      <div class="form-group">        
        <label for="moto_chasis" class="col-lg-2 control-label">Chasis</label>
        <div class="col-md-6">            
            <input type="text" name="moto_chasis" id="moto_chasis"  class="form-control" placeholder="Chasis" />    
        </div>
      </div>
      <div class="form-group">        
        <label for="moto_motor" class="col-lg-2 control-label">Motor</label>
        <div class="col-md-6">            
            <input type="text" name="moto_motor" id="moto_motor"  class="form-control" placeholder="Motor" />    
        </div>
      </div>
      <div class="form-group">        
        <label for="moto_peso" class="col-lg-2 control-label">Peso bruto vehicular</label>
        <div class="col-md-6">            
            <input type="text" name="moto_peso" id="moto_peso"  class="form-control" placeholder="Peso bruto vehicular" />    
        </div>
      </div>
      <div class="form-group">        
        <label for="moto_combustible" class="col-lg-2 control-label">Combustible</label>
        <div class="col-md-6">            
            <input type="text" name="moto_combustible" id="moto_combustible"  class="form-control" placeholder="Combustible" />    
        </div>
      </div>
      
      
      <div class="form-group">
        <label for="inputFechaBodega" class="col-lg-2 control-label">Fecha ingreso bodega:</label>
        <div class="col-md-6">
          <input type="date" name="inputFechaBodega" class="form-control" id="inputFechaBodega" placeholder="Fecha ingreso bodega">
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
        </div>
      </div>
      
  </div>
  
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Descripcion</label>
    <div class="col-md-6">
      <textarea name="description" class="form-control" id="description" placeholder="Descripcion del Producto"></textarea>
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Precio de Entrada*</label>
    <div class="col-md-6">
      <input type="text" name="price_in" required class="form-control" id="price_in" placeholder="Precio de entrada">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Precio de Salida*</label>
    <div class="col-md-6">
      <input type="text" name="price_out" required class="form-control" id="price_out" placeholder="Precio de salida">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Unidad*</label>
    <div class="col-md-6">
      <input type="text" name="unit" class="form-control" id="unit" placeholder="Unidad del Producto">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Presentacion</label>
    <div class="col-md-6">
      <input type="text" name="presentation" class="form-control" id="inputEmail1" placeholder="Presentacion del Producto">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Minima en inventario:</label>
    <div class="col-md-6">
      <input type="text" name="inventary_min" class="form-control" id="inputEmail1" placeholder="Minima en Inventario (Default 10)">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Inventario inicial:</label>
    <div class="col-md-6">
      <input type="text" name="q" class="form-control" id="inputEmail1" placeholder="Inventario inicial">
    </div>
  </div>
  
  

  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <button type="submit" class="btn btn-primary">Agregar Producto</button>
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
    
    $("#product_code").keydown(function(e){
        if(e.which==17 || e.which==74 ){
            e.preventDefault();
        }else{
            console.log(e.which);
        }
    })
});

</script>
</section>