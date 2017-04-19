<?php
class Operation2Data {
	public static $tablename = "operacion2";

	public function Operation2Data(){
		$this->Nombre = "";
		$this->Ingrediente_id = "";
		$this->q = "";
		$this->cut_id = "";
		$this->Operacion_Tipo_id = "";
		$this->created_at = "NOW()";
	}

	public function add(){
		$sql = "insert into ".self::$tablename." (Ingrediente_id,q,Operacion_Tipo_id,Venta_id,created_at) ";
		$sql .= "value (\"$this->Ingrediente_id\",\"$this->q\",$this->Operacion_Tipo_id,$this->Venta_id,$this->created_at)";
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

// partiendo de que ya tenemos creado un objecto Operation2Data previamente utilizamos el contexto
	public function update(){
		$sql = "update ".self::$tablename." set Ingrediente_id=\"$this->Ingrediente_id\",q=\"$this->q\" where Id=$this->Id";
		Executor::doit($sql);
	}

	public static function getById($Id){
		$sql = "select * from ".self::$tablename." where Id=$Id";
		$query = Executor::doit($sql);
		$found = null;
		$data = new Operation2Data();
		while($r = $query[0]->fetch_array()){
			$data->Id = $r['Id'];
			$data->Ingrediente_id = $r['Ingredient_id'];
			$data->q = $r['q'];
			$data->cut_id = $r['cut_id'];
			$data->Operacion_Tipo_id = $r['Operacion_Tipo_id'];
			$data->Venta_id = $r['Venta_id'];
			$data->created_at = $r['created_at'];
			$found = $data;
			break;
		}
		return $found;
	}



	public static function getAll(){
		$sql = "select * from ".self::$tablename;
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new Operation2Data();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Ingrediente_id = $r['Ingrediente_id'];
			$array[$cnt]->q = $r['q'];
			$array[$cnt]->cut_id = $r['cut_id'];
			$array[$cnt]->Operacion_Tipo_id = $r['Operacion_Tipo_id'];
			$cnt++;
		}
		return $array;
	}



	public static function getAllByDateOfficial($start,$end){
 $sql = "select * from ".self::$tablename." where date(created_at) > \"$start\" and date(created_at) <= \"$end\" and is_oficial=1 order by created_at desc";
		if($start == $end){
		 $sql = "select * from ".self::$tablename." where date(created_at) = \"$start\" order by created_at desc";
		}
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new Operation2Data();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Ingrediente_id = $r['Ingrediente_id'];
			$array[$cnt]->q = $r['q'];
			$array[$cnt]->is_oficial = $r['is_oficial'];
			$array[$cnt]->Operacion_Tipo_id = $r['Operacion_Tipo_id'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}

	public static function getAllByDateOfficialBP($producto, $start,$end){
 $sql = "select * from ".self::$tablename." where date(created_at) > \"$start\" and date(created_at) <= \"$end\" and is_oficial=1 and ingredient_id=$product order by created_at desc";
		if($start == $end){
		 $sql = "select * from ".self::$tablename." where date(created_at) = \"$start\" order by created_at desc";
		}
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new Operation2Data();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Ingrediente_id = $r['Ingrediente_id'];
			$array[$cnt]->q = $r['q'];
			$array[$cnt]->Operacion_Tipo_id = $r['Operacion_Tipo_id'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}



	public function getProduct(){
		return IngredientData::getById($this->Ingrediente_id);
	}

	public function getOperationType(){
		return OperationTypeData::getById($this->Operacion_Tipo_id);
	}


////////////////////////////////////////////////////////////////////
	public static function getQ($Ingrediente_id,$cut_id){
		$q=0;
		$operations = self::getAllByProductIdCutId($Ingrediente_id,$cut_id);
		$input_id = OperationTypeData::getByName("entrada")->Id;
		$output_id = OperationTypeData::getByName("salida")->Id;
		foreach($operations as $operacion){
			if($operacion->Operacion_Tipo_id==$input_id){ $q+=$operacion->q; }
			else if($operacion->Operacion_Tipo_id==$output_id){  $q+=(-$operacion->q); }
		}
		// print_r($data);
		return $q;
	}


	public static function getQYesF($Ingrediente_id){
		$q=0;
		$operations = self::getAllByProductId($Ingrediente_id);
		$input_id = OperationTypeData::getByName("entrada")->Id;
		$output_id = OperationTypeData::getByName("salida")->Id;
		foreach($operations as $operacion){
				if($operacion->Operacion_Tipo_id==$input_id){ $q+=$operacion->q; }
				else if($operacion->Operacion_Tipo_id==$output_id){  $q+=(-$operacion->q); }
		}
		// print_r($data);
		return $q;
	}

	public static function getQNoF($Ingrediente_id,$cut_id){
		$q = self::getQ($Ingrediente_id,$cut_id);
		$f = self::getQYesF($Ingrediente_id,$cut_id);
		return $q-$f;

	}


	public static function getAllByProductIdCutId($Ingrediente_id,$cut_id){
		$sql = "select * from ".self::$tablename." where Ingrediente_id=$Ingrediente_id and cut_id=$cut_id order by created_at desc";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new Operation2Data();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Ingrediente_id = $r['Ingrediente_id'];
			$array[$cnt]->q = $r['q'];
			$array[$cnt]->cut_id = $r['cut_id'];
			$array[$cnt]->Operacion_Tipo_id = $r['Operacion_Tipo_id'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}

	public static function getAllByProductId($Ingrediente_id){
		$sql = "select * from ".self::$tablename." where Ingrediente_id=$Ingrediente_id  order by created_at desc";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new Operation2Data();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Ingrediente_id = $r['Ingrediente_id'];
			$array[$cnt]->q = $r['q'];
			$array[$cnt]->Operacion_Tipo_id = $r['Operacion_Tipo_id'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}


	public static function getAllByProductIdCutIdOficial($Ingrediente_id,$cut_id){
		$sql = "select * from ".self::$tablename." where Ingrediente_id=$Ingrediente_id and cut_id=$cut_id and is_oficial=1 order by created_at desc";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new Operation2Data();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Ingrediente_id = $r['Ingrediente_id'];
			$array[$cnt]->q = $r['q'];
			$array[$cnt]->cut_id = $r['cut_id'];
			$array[$cnt]->Operacion_Tipo_id = $r['Operacion_Tipo_id'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}

	public static function getAllByProductIdCutIdUnOficial($Ingrediente_id,$cut_id){
		$sql = "select * from ".self::$tablename." where Ingrediente_id=$Ingrediente_id and cut_id=$cut_id and is_oficial=0 order by created_at desc";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new Operation2Data();
			$array[$cnt]->Id = $r['id'];
			$array[$cnt]->Ingrediente_id = $r['Ingrediente_id'];
			$array[$cnt]->q = $r['q'];
			$array[$cnt]->is_oficial = $r['is_oficial'];
			$array[$cnt]->cut_id = $r['cut_id'];
			$array[$cnt]->Operacion_Tipo_id = $r['Operacion_Tipo_id'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}


	public static function getAllProductsBySellId($Venta_id){
		$sql = "select * from ".self::$tablename." where Venta_id=$Venta_id order by created_at desc";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new Operation2Data();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Ingrediente_id = $r['Ingrediente_id'];
			$array[$cnt]->q = $r['q'];
			$array[$cnt]->Operacion_Tipo_id = $r['Operacion_Tipo_id'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}


	public static function getAllByProductIdCutIdYesF($Ingrediente_id,$cut_id){
		$sql = "select * from ".self::$tablename." where Ingrediente_id=$Ingrediente_id and cut_id=$cut_id and is_oficial=1 order by created_at desc";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new Operation2Data();
						$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Ingrediente_id = $r['Ingrediente_id'];
			$array[$cnt]->q = $r['q'];
			$array[$cnt]->cut_id = $r['cut_id'];
			$array[$cnt]->Operacion_Tipo_id = $r['Operacion_Tipo_id'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}

////////////////////////////////////////////////////////////////////
	public static function getOutputQ($Ingrediente_id,$cut_id){
		$q=0;
		$operations = self::getOutputByProductIdCutId($Ingrediente_id,$cut_id);
		$input_id = OperationTypeData::getByName("entrada")->Id;
		$output_id = OperationTypeData::getByName("salida")->Id;
		foreach($operations as $operacion){
			if($operacion->Operacion_Tipo_id==$input_id){ $q+=$operacion->q; }
			else if($operacion->Operacion_Tipo_id==$output_id){  $q+=(-$operacion->q); }
		}
		// print_r($data);
		return $q;
	}

	public static function getOutputQYesF($Ingrediente_id){
		$q=0;
		$operations = self::getOutputByProductId($Ingrediente_id);
		$input_id = OperationTypeData::getByName("entrada")->Id;
		$output_id = OperationTypeData::getByName("salida")->Id;
		foreach($operations as $operacion){
			if($operacion->Operacion_Tipo_id==$input_id){ $q+=$operacion->q; }
			else if($operacion->Operacion_Tipo_id==$output_id){  $q+=(-$operacion->q); }
		}
		// print_r($data);
		return $q;
	}

	public static function getOutputQNoF($Ingrediente_id,$cut_id){
		$q=0;
		$operations = self::getOutputByProductIdCutIdNoF($Ingrediente_id,$cut_id);
		$input_id = OperationTypeData::getByName("entrada")->Id;
		$output_id = OperationTypeData::getByName("salida")->Id;
		foreach($operations as $operacion){
			if($operacion->Operacion_Tipo_id==$input_id){ $q+=$operacion->q; }
			else if($operacion->Operacion_Tipo_id==$output_id){  $q+=(-$operacion->q); }
		}
		// print_r($data);
		return $q;
	}


	public static function getOutputByProductIdCutId($Ingrediente_id,$cut_id){
		$sql = "select * from ".self::$tablename." where Ingrediente_id=$Ingrediente_id and cut_id=$cut_id and Operacion_Tipo_id=2 order by created_at desc";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new Operation2Data();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Ingrediente_id = $r['Ingrediente_id'];
			$array[$cnt]->q = $r['q'];
			$array[$cnt]->cut_id = $r['cut_id'];
			$array[$cnt]->Operacion_Tipo_id = $r['Operacion_Tipo_id'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}


	public static function getOutputByProductId($Ingrediente_id){
		$sql = "select * from ".self::$tablename." where Ingrediente_id=$Ingrediente_id and Operacion_Tipo_id=2 order by created_at desc";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new Operation2Data();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Ingrediente_id = $r['Ingrediente_id'];
			$array[$cnt]->q = $r['q'];
			$array[$cnt]->Operacion_Tipo_id = $r['Operacion_Tipo_id'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}

////////////////////////////////////////////////////////////////////
	public static function getInputQ($Ingrediente_id,$cut_id){
		$q=0;
		$operations = self::getInputByProductIdCutId($Ingrediente_id,$cut_id);
		$input_id = OperationTypeData::getByName("entrada")->Id;
		$output_id = OperationTypeData::getByName("salida")->Id;
		foreach($operations as $operacion){
			if($operacion->Operacion_Tipo_id==$input_id){ $q+=$operacion->q; }
			else if($operacion->Operacion_Tipo_id==$output_id){  $q+=(-$operacion->q); }
		}
		// print_r($data);
		return $q;
	}

	public static function getInputQYesF($Ingrediente_id){
		$q=0;
		$operations = self::getInputByProductId($Ingrediente_id);
		$input_id = OperationTypeData::getByName("entrada")->Id;
		$output_id = OperationTypeData::getByName("salida")->Id;
		foreach($operations as $operacion){
			if($operation->Operacion_Tipo_id==$input_id){ $q+=$operacion->q; }
			else if($operacion->Operacion_Tipo_id==$output_id){  $q+=(-$operacion->q); }
		}
		// print_r($data);
		return $q;
	}


	public static function getInputByProductIdCutId($Ingrediente_id,$cut_id){
		$sql = "select * from ".self::$tablename." where Ingrediente_id=$Ingrediente_id and cut_id=$cut_id and Operacion_Tipo_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new Operation2Data();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Ingrediente_id = $r['Ingrediente_id'];
			$array[$cnt]->q = $r['q'];
			$array[$cnt]->cut_id = $r['cut_id'];
			$array[$cnt]->Operacion_Tipo_id = $r['Operacion_Tipo_id'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}

	public static function getInputByProductId($Ingrediente_id){
		$sql = "select * from ".self::$tablename." where Ingrediente_id=$Ingrediente_id and Operacion_Tipo_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new Operation2Data();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Ingrediente_id = $r['Ingrediente_id'];
			$array[$cnt]->q = $r['q'];
			$array[$cnt]->Operacion_Tipo_id = $r['Operacion_Tipo_id'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}

	public static function getInputByProductIdCutIdYesF($Ingrediente_id,$cut_id){
		$sql = "select * from ".self::$tablename." where Ingrediente_id=$Ingrediente_id and cut_id=$cut_id and is_oficial=1 and Operacion_Tipo_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new Operation2Data();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Ingrediente_id = $r['Ingrediente_id'];
			$array[$cnt]->q = $r['q'];
			$array[$cnt]->cut_id = $r['cut_id'];
			$array[$cnt]->Operacion_Tipo_id = $r['Operacion_Tipo_id'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}

	public static function getInputByProductIdCutIdNoF($Ingrediente_id,$cut_id){
		$sql = "select * from ".self::$tablename." where Ingrediente_id=$Ingrediente_id and cut_id=$cut_id and is_oficial=0 and Operacion_Tipo_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new Operation2Data();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Ingrediente_id = $r['Ingrediente_id'];
			$array[$cnt]->q = $r['q'];
			$array[$cnt]->cut_id = $r['cut_id'];
			$array[$cnt]->Operacion_Tipo_id = $r['Operacion_Tipo_id'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}
////////////////////////////////////////////////////////////////////////////


}

?>