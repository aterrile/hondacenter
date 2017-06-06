<?php

if(count($_POST)>0){
  $product = new ProductData();  
  
  $product->barcode = $_POST["barcode"];
  $product->name = $_POST["name"];
  $product->price_in = $_POST["price_in"];
  $product->price_out = $_POST["price_out"];
  $product->unit = $_POST["unit"];
  $product->description = $_POST["description"];
  $product->presentation = $_POST["presentation"];
  //$product->inventary_min = $_POST["inventary_min"];
  $category_id="NULL";
  if($_POST["category_id"]!=""){ $category_id=$_POST["category_id"];}
  $inventary_min="\"\"";
  if($_POST["inventary_min"]!=""){ $inventary_min=$_POST["inventary_min"];}

  $product->category_id=$category_id;
  $product->inventary_min=$inventary_min;
  $product->user_id = $_SESSION["user_id"];


  if(isset($_FILES["image"])){
    $image = new Upload($_FILES["image"]);
    if($image->uploaded){
      $image->Process("storage/products/");
      if($image->processed){
        $product->image = $image->file_dst_name;
        $prod = $product->add_with_image();
      }
    }else{

  $prod= $product->add();
    }
  }
  else{
  $prod= $product->add();

  }


if( $category_id == 1 ){
    $info_motos = new InfoMotos();
    extract($_POST);
    $info_motos->product_id = $prod[1];
    $info_motos->moto_marca = $moto_marca;
    $info_motos->moto_modelo = $moto_modelo;
    $info_motos->moto_color = $moto_color;
    $info_motos->moto_year = $moto_year;
    $info_motos->moto_chasis = $moto_chasis;
    $info_motos->moto_motor = $moto_motor;
    $info_motos->moto_peso = $moto_peso;
    $info_motos->moto_combustible = $moto_combustible;
    $info_motos->fecha_ingreso_bodega = $inputFechaBodega;
    $info_motos->estado = $inputEstado;
    
    $addInfo = $info_motos->add();
}

if($_POST["q"]!="" || $_POST["q"]!="0"){
 $op = new OperationData();
 $op->product_id = $prod[1] ;
 $op->stock_id = StockData::getPrincipal()->id;
 $op->operation_type_id=OperationTypeData::getByName("entrada")->id;
 $op->price_in =$_POST["price_in"];
 $op->price_out= $_POST["price_out"];
 $op->q= $_POST["q"];
 $op->sell_id="NULL";
$op->is_oficial=1;
$op->add();
}

print "<script>window.location='index.php?view=products';</script>";


}


?>