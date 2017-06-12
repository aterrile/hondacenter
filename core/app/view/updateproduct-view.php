<?php

if(count($_POST)>0){
	$product = ProductData::getById($_POST["product_id"]);
    
	$product->barcode = $_POST["barcode"];
	$product->name = $_POST["name"];
	$product->price_in = $_POST["price_in"];
	$product->price_out = $_POST["price_out"];
	$product->unit = $_POST["unit"];

  $product->description = $_POST["description"];
  $product->presentation = $_POST["presentation"];
  $product->inventary_min = $_POST["inventary_min"];
  $category_id="NULL";
  if($_POST["category_id"]!=""){ $category_id=$_POST["category_id"];}

  $is_active=0;
  if(isset($_POST["is_active"])){ $is_active=1;}

  $product->is_active=$is_active;
  $product->category_id=$category_id;

	$product->user_id = $_SESSION["user_id"];
	$product->update();

	if(isset($_FILES["image"])){
		$image = new Upload($_FILES["image"]);
		if($image->uploaded){
			$image->Process("storage/products/");
			if($image->processed){
				$product->image = $image->file_dst_name;
				$product->update_image();
			}
		}
	}


    $info_motos = new InfoMotosData();
    $info_motos->product_id = $product->id;                
    $info_motos->delete();
    if( $category_id == 1 ){        
        extract($_POST);
        $info_motos->product_id = $product->id;
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

	setcookie("prdupd","true");
	print "<script>window.location='index.php?view=editproduct&id=$_POST[product_id]';</script>";


}


?>
