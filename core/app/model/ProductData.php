<?php
class ProductData {
	public static $tablename = "product";

	public function ProductData(){
		$this->name = "";
		$this->price_in = "";
		$this->price_out = "";
		$this->unit = "";
		$this->user_id = "";
		$this->presentation = "0";
		$this->created_at = "NOW()";
	}

	public function getCategory(){ return CategoryData::getById($this->category_id);}

	public function add(){
		$sql = "insert into ".self::$tablename." (barcode,name,description,price_in,price_out,user_id,presentation,unit,category_id,inventary_min,created_at) ";
		$sql .= "value (\"$this->barcode\",\"$this->name\",\"$this->description\",\"$this->price_in\",\"$this->price_out\",$this->user_id,\"$this->presentation\",\"$this->unit\",$this->category_id,$this->inventary_min,NOW())";		
        
        return Executor::doit($sql);
	}
	public function add_with_image(){
		$sql = "insert into ".self::$tablename." (barcode,image,name,description,price_in,price_out,user_id,presentation,unit,category_id,inventary_min,created_at) ";
		$sql .= "value (\"$this->barcode\",\"$this->image\",\"$this->name\",\"$this->description\",\"$this->price_in\",\"$this->price_out\",$this->user_id,\"$this->presentation\",\"$this->unit\",$this->category_id,$this->inventary_min,NOW())";
		
        return Executor::doit($sql);
	}


	public static function delById($id){
		$sql = "delete from ".self::$tablename." where id=$id";
		Executor::doit($sql);
	}
	public function del(){
		$sql = "delete from ".self::$tablename." where id=$this->id";
		Executor::doit($sql);
	}

// partiendo de que ya tenemos creado un objecto ProductData previamente utilizamos el contexto
	public function update(){
		$sql = "update ".self::$tablename." set barcode=\"$this->barcode\",name=\"$this->name\",price_in=\"$this->price_in\",price_out=\"$this->price_out\",unit=\"$this->unit\",presentation=\"$this->presentation\",category_id=$this->category_id,inventary_min=\"$this->inventary_min\",description=\"$this->description\",is_active=\"$this->is_active\" where id=$this->id";
		Executor::doit($sql);
	}

	public function del_category(){
		$sql = "update ".self::$tablename." set category_id=NULL where id=$this->id";
		Executor::doit($sql);
	}


	public function update_image(){
		$sql = "update ".self::$tablename." set image=\"$this->image\" where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ProductData());

	}
    
    public static function getInfoMoto($product_id){
        $info = new InfoMotosData();         
		$sql = "select * from info_motos where product_id=$product_id";        
		$query = Executor::doit($sql);
		return Model::one($query[0],$info);
	}



	public static function getAll(){
		$sql = "select * from ".self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getAllByCategoryId($id){
		$sql = "select * from ".self::$tablename." where category_id=$id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getAllByPage($start_from,$limit){
		$sql = "select * from ".self::$tablename." where id>=$start_from limit $limit";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}


	public static function getLike($p){
		$sql = "select * from ".self::$tablename." where barcode like '%$p%' or name like '%$p%' or id like '%$p%'";
        $query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}



	public static function getAllByUserId($user_id){
		$sql = "select * from ".self::$tablename." where user_id=$user_id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

}



class InfoMotosData{
	public static $tablename = "info_motos";

	public function InfoMotosData(){
	   $this->product_id = "";
		$this->moto_marca = "";
		$this->moto_modelo = "";
		$this->moto_color = "";
        $this->moto_year = "";
        $this->moto_chasis = "";
        $this->moto_motor = "";
		$this->moto_peso = "";
        $this->moto_combustible = "";
        $this->fecha_ingreso_bodega = "";
        $this->estado = "";
	}   
    
    public function add(){		
        $sql = "
        INSERT INTO ".self::$tablename." 
            (product_id, marca, modelo, color, year, chasis, motor, peso, combustible, fecha_ingreso_bodega, estado)
        VALUES 
            ($this->product_id,'$this->moto_marca','$this->moto_modelo','$this->moto_color','$this->moto_year','$this->moto_chasis','$this->moto_motor','$this->moto_peso','$this->moto_combustible','$this->fecha_ingreso_bodega','$this->estado')";
        return Executor::doit($sql);        
	}
    
    public function delete(){		
        $sql = "DELETE FROM ".self::$tablename." WHERE  product_id = $this->product_id";            
        return Executor::doit($sql);        
	}    
}


?>