<?php
class IngredientData {
	public static $tablename = "ingredient";

	public function IngredientData(){
		$this->Nombre = "";
		$this->Precio_entrada = "";
		$this->Precio_salida = "";
		$this->Unidad = "";
		$this->Usuario_id = "";
		$this->Presentacion = "0";
		$this->created_at = "NOW()";
	}

	public function add(){
		$sql = "insert into ".self::$tablename." (Codigo,Nombre,Precio_salida,Usuari_id,Unidad) ";
		$sql .= "value (\"$this->Codigo\",\"$this->Nombre\",\"$this->Precio_salida\",$this->Usuario_id,\"$this->Unidad\")";
		return Executor::doit($sql);
	}

	public static function delById($Id){
		$sql = "delete from ".self::$tablename." where Id=$Id";
		Executor::doit($sql);
	}
	public function del(){
		$sql = "delete from ".self::$tablename." where Id=$this->Id";
		Executor::doit($sql);
	}

// partiendo de que ya tenemos creado un objecto IngredientData previamente utilizamos el contexto
	public function update(){
		$sql = "update ".self::$tablename." set Nombre=\"$this->Nombre\",Precio_entrada=\"$this->Precio_entrada\",Precio_salida=\"$this->Precio_salida\",Unidad=\"$this->Unidad\",presentation=\"$this->Presentacion\",Categoria_id=\"$this->Categoria_id\",Codigo=\"$this->Codigo\",Duracion=\"$this->Duracion\" where Id=$this->Id";
		Executor::doit($sql);
	}

	public function active(){
		$sql = "update ".self::$tablename." set is_active=1 where Id=$this->Id";
		Executor::doit($sql);
	}

	public function hide(){
		$sql = "update ".self::$tablename." set is_active=0 where Id=$this->Id";
		Executor::doit($sql);
	}


	public static function getById($Id){
		$sql = "select * from ".self::$tablename." where Id=$Id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new IngredientData());
	}



	public static function getAll(){
		$sql = "select * from ".self::$tablename." ";
		$query = Executor::doit($sql);
		return Model::many($query[0],new IngredientData());
	}

	public static function getAllActive(){
		$sql = "select * from ".self::$tablename."  where is_active=1";
		$query = Executor::doit($sql);
		return Model::many($query[0],new IngredientData());
	}


	public static function getAllUnActive(){
		$sql = "select * from ".self::$tablename."  where is_active=0";
		$query = Executor::doit($sql);
		return Model::many($query[0],new IngredientData());
	}



	public static function getAllByPage($start_from,$limit){
		$sql = "select * from ".self::$tablename." where id>=$start_from limit $limit";
		$query = Executor::doit($sql);
		return Model::many($query[0],new IngredientData());
	}


	public static function getLike($p){
		$sql = "select * from ".self::$tablename." where Nombre like '%$p%' or Id like '%$p%'";
		$query = Executor::doit($sql);
		return Model::many($query[0],new IngredientData());
	}

	public static function getActiveLike($p){
		$sql = "select * from ".self::$tablename." where (Nombre like '%$p%' or Id like '%$p%') and is_active=1 ";
		$query = Executor::doit($sql);
		return Model::many($query[0],new IngredientData());
	}


	public static function getAllByUserId($user_id){
		$sql = "select * from ".self::$tablename." where Usuario_id=$Usuario_id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new IngredientData());
	}







}

?>