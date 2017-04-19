<?php
class OperationData {
	public static $tablename = "operacion";

	public function OperationData(){
		$this->Nombre = "";
		$this->Producto_id = "";
		$this->q = "";
		$this->cut_id = "";
		$this->Operacion_Tipo_id = "";
		$this->is_oficial = "0";
		$this->created_at = "NOW()";
	}

	public function add(){
		$sql = "insert into ".self::$tablename." (Producto_id,q,Operacion_Tipo_id,is_oficial,Venta_id,created_at) ";
		$sql .= "value (\"$this->Producto_id\",\"$this->q\",$this->Operacion_Tipo_id,\"$this->is_oficial\",$this->Venta_id,$this->created_at)";
		return Executor::doit($sql);
	}

	public function add_q(){
		$sql = "update ".self::$tablename." set q=$this->q where id=$this->Id";
		Executor::doit($sql);
	}

	public static function delById($Id){
		$sql = "delete from ".self::$tablename." where Id=$Id";
		Executor::doit($sql);
	}
	public function del(){
		$sql = "delete from ".self::$tablename." where Id=$this->Id";
		Executor::doit($sql);
	}

// partiendo de que ya tenemos creado un objecto OperationData previamente utilizamos el contexto
	public function update(){
		$sql = "update ".self::$tablename." set Producto_id=\"$this->Producto_id\",q=\"$this->q\",is_oficial=\"$this->is_oficial\" where Id=$this->Id";
		Executor::doit($sql);
	}

	public static function getById($Id){
		$sql = "select * from ".self::$tablename." where Id=$Id";
		$query = Executor::doit($sql);
		$found = null;
		$data = new OperationData();
		while($r = $query[0]->fetch_array()){
			$data->Id = $r['Id'];
			$data->Producto_id = $r['Producto_id'];
			$data->q = $r['q'];
			$data->is_oficial = $r['is_oficial'];
			$data->Operacion_Tipo_id = $r['Operacion_Tipo_id'];
			$data->Venta_id = $r['Venta_id'];
			$data->created_at = $r['created_at'];
			$found = $data;
			break;
		}
		return $found;
	}



	public static function getAll(){
		$sql = "select * from ".self::$tablename." order by created_at desc";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new OperationData();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Producto_id = $r['Producto_id'];
			$array[$cnt]->q = $r['q'];
			$array[$cnt]->is_oficial = $r['is_oficial'];
			$array[$cnt]->Operacion_Tipo_id = $r['Operacion_Tipo_id'];
			$array[$cnt]->created_at = $r['created_at'];
						$cnt++;
		}
		return $array;
	}

	public static function getAllDates(){
		$sql = "select *,date(created_at) as d from ".self::$tablename." group by date(created_at) order by created_at desc";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new OperationData();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Producto_id = $r['Producto_id'];
			$array[$cnt]->q = $r['q'];
			$array[$cnt]->is_oficial = $r['is_oficial'];
			$array[$cnt]->Operacion_Tipo_id = $r['Operacion_Tipo_id'];
			$array[$cnt]->created_at = $r['created_at'];
			$array[$cnt]->d = $r['d'];
						$cnt++;
		}
		return $array;
	}


	public static function getAllByDate($start){
 $sql = "select *,Precio_salida as price,producto.Nombre as name,categoria.Nombre as cname from ".self::$tablename." inner join producto on (operacion.Producto_id=producto.Id) inner join categoria on (producto.Categoria_id=categoria.Id) inner join venta on (venta.Id=operacion.Venta_id) where date(operacion.created_at) = \"$start\"  and is_applied=1  order by operacion.created_at desc";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new OperationData();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Product_id = $r['Producto_id'];
			$array[$cnt]->q = $r['q'];
			$array[$cnt]->is_oficial = $r['is_oficial'];
			$array[$cnt]->price = $r['price'];
			$array[$cnt]->name = $r['name'];
			$array[$cnt]->cname = $r['cname'];
			$array[$cnt]->Unidad = $r['Unidad'];
			$array[$cnt]->Operacion_Tipo_id = $r['Operacion_Tipo_id'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}

	public static function getAllByDateAndCategoryId($start,$cat_id){
 $sql = "select *,Precio_salida as price,producto.Nombre as name,categoria.Nombre as cname,producto.Unidad as punit from ".self::$tablename." inner join venta on (venta.Id=operacion.Venta_id) inner join producto on (operacion.Producto_id=product.Id) inner join categoria on (producto.Category_id=categoria.Id) where date(operacion.created_at) = \"$start\"  and producto.Categoria_id=$cat_id and venta.is_applied=1 order by operacion.created_at desc";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new OperationData();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Producto_id = $r['Producto_id'];
			$array[$cnt]->q = $r['q'];
			$array[$cnt]->is_oficial = $r['is_oficial'];
			$array[$cnt]->price = $r['price'];
			$array[$cnt]->name = $r['name'];
			$array[$cnt]->cname = $r['cname'];
			$array[$cnt]->Unidad = $r['punit'];
			$array[$cnt]->Operacion_type_id = $r['Operacion_Tipo_id'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}
	public static function getAllByDateOfficial($start,$end){
 $sql = "select * from ".self::$tablename." where date(created_at) > \"$start\" and date(created_at) <= \"$end\"  order by created_at desc";
		if($start == $end){
		 $sql = "select * from ".self::$tablename." where date(created_at) = \"$start\" order by created_at desc";
		}
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new OperationData();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Producto_id = $r['Producto_id'];
			$array[$cnt]->q = $r['q'];
			$array[$cnt]->is_oficial = $r['is_oficial'];
			$array[$cnt]->Operacion_Tipo_id = $r['Operacion_Tipo_id'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}




	public static function getAllByDateOfficialBP($product, $start,$end){
 $sql = "select * from ".self::$tablename." where date(created_at) > \"$start\" and date(created_at) <= \"$end\"  and Producto_id=$producto order by created_at desc";
		if($start == $end){
		 $sql = "select * from ".self::$tablename." where date(created_at) = \"$start\" and Producto_id=$producto order by created_at desc";
		}
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new OperationData();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Producto_id = $r['Producto_id'];
			$array[$cnt]->q = $r['q'];
			$array[$cnt]->is_oficial = $r['is_oficial'];
			$array[$cnt]->Operacion_Tipo_id = $r['Operacion_Tipo_id'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}



	public function getProduct(){
		return ProductData::getById($this->Producto_id);
	}

	public function getOperationType(){
		return OperationTypeData::getById($this->Operacion_Tipo_id);
	}


////////////////////////////////////////////////////////////////////
	public static function getQ($Producto_id,$cut_id){
		$q=0;
		$operations = self::getAllByProductIdCutId($Producto_id,$cut_id);
		$input_id = OperationTypeData::getByName("entrada")->id;
		$output_id = OperationTypeData::getByName("salida")->id;
		foreach($operations as $operacion){
			if($operacion->Operacion_Tipo_id==$input_id){ $q+=$operacion->q; }
			else if($operacion->Operacion_Tipo_id==$output_id){  $q+=(-$operacion->q); }
		}
		// print_r($data);
		return $q;
	}


	public static function getQYesF($Producto_id,$cut_id){
		$q=0;
		$operations = self::getAllByProductIdCutId($Producto_id,$cut_id);
		$input_id = OperationTypeData::getByName("entrada")->id;
		$output_id = OperationTypeData::getByName("salida")->id;
		foreach($operations as $operacion){
			if($operacion->is_oficial){
				if($operacion->Operacion_Tipo_id==$input_id){ $q+=$operacion->q; }
				else if($operacion->Operacion_Tipo_id==$output_id){  $q+=(-$operacion->q); }
			}
		}
		// print_r($data);
		return $q;
	}

	public static function getQNoF($Producto_id,$cut_id){
		$q = self::getQ($Producto_id,$cut_id);
		$f = self::getQYesF($Producto_id,$cut_id);
		return $q-$f;

	}


	public static function getAllByProductIdCutId($Producto_id,$cut_id){
		$sql = "select * from ".self::$tablename." where Producto_id=$Producto_id and cut_id=$cut_id order by created_at desc";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new OperationData();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Producto_id = $r['Producto_id'];
			$array[$cnt]->q = $r['q'];
			$array[$cnt]->is_oficial = $r['is_oficial'];
			$array[$cnt]->cut_id = $r['cut_id'];
			$array[$cnt]->Operacion_Tipo_id = $r['Operacion_Tipo_id'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}


	public static function getAllByProductIdCutIdOficial($Producto_id,$cut_id){
		$sql = "select * from ".self::$tablename." where Producto_id=$Producto_id and cut_id=$cut_id  order by created_at desc";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new OperationData();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Product_id = $r['Product_id'];
			$array[$cnt]->q = $r['q'];
			$array[$cnt]->is_oficial = $r['is_oficial'];
			$array[$cnt]->cut_id = $r['cut_id'];
			$array[$cnt]->Operacion_Tipo_id = $r['Operacion_Tipo_id'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}

	public static function getAllByProductIdCutIdUnOficial($Producto_id,$cut_id){
		$sql = "select * from ".self::$tablename." where Producto_id=$Product_id and cut_id=$cut_id and is_oficial=0 order by created_at desc";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new OperationData();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Product_id = $r['Product_id'];
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
			$array[$cnt] = new OperationData();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Producto_id = $r['Producto_id'];
			$array[$cnt]->q = $r['q'];
			$array[$cnt]->is_oficial = $r['is_oficial'];
			$array[$cnt]->Operacion_Tipo_id = $r['Operacion_Tipo_id'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}


	public static function getAllByProductIdCutIdYesF($Producto_id,$cut_id){
		$sql = "select * from ".self::$tablename." where Producto_id=$Producto_id and cut_id=$cut_id  order by created_at desc";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new OperationData();
						$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Producto_id = $r['Producto_id'];
			$array[$cnt]->q = $r['q'];
			$array[$cnt]->is_oficial = $r['is_oficial'];
			$array[$cnt]->cut_id = $r['cut_id'];
			$array[$cnt]->Operacion_Tipo_id = $r['Operacion_Tipo_id'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}

	public static function getAllByProductIdCutIdNoF($Producto_id,$cut_id){
		$sql = "select * from ".self::$tablename." where Producto_id=$Producto_id and cut_id=$cut_id and is_oficial=0 order by created_at desc";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new OperationData();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Producto_id = $r['Producto_id'];
			$array[$cnt]->q = $r['q'];
			$array[$cnt]->is_oficial = $r['is_oficial'];
			$array[$cnt]->cut_id = $r['cut_id'];
			$array[$cnt]->Operacion_Tipo_id = $r['Operacion_Tipo_id'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}
////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
	public static function getOutputQ($Producto_id,$cut_id){
		$q=0;
		$operations = self::getOutputByProductIdCutId($Producto_id,$cut_id);
		$input_id = OperationTypeData::getByName("entrada")->Id;
		$output_id = OperationTypeData::getByName("salida")->Id;
		foreach($operations as $operaCion){
			if($operacion->Operacion_Tipo_id==$input_id){ $q+=$operacion->q; }
			else if($operacion->Operacion_Tipo_id==$output_id){  $q+=(-$operacion->q); }
		}
		// print_r($data);
		return $q;
	}

	public static function getOutputQYesF($Producto_id,$cut_id){
		$q=0;
		$operations = self::getOutputByProductIdCutIdYesF($Producto_id,$cut_id);
		$input_id = OperationTypeData::getByName("entrada")->Id;
		$output_id = OperationTypeData::getByName("salida")->Id;
		foreach($operations as $operacion){
			if($operacion->Operacion_Tipo_id==$input_id){ $q+=$operacion->q; }
			else if($operacion->Operacion_Tipo_id==$output_id){  $q+=(-$operacion->q); }
		}
		// print_r($data);
		return $q;
	}

	public static function getOutputQNoF($product_id,$cut_id){
		$q=0;
		$operations = self::getOutputByProductIdCutIdNoF($Producto_id,$cut_id);
		$input_id = OperationTypeData::getByName("entrada")->Id;
		$output_id = OperationTypeData::getByName("salida")->Id;
		foreach($operations as $operacion){
			if($operation->Operacion_Tipo_id==$input_id){ $q+=$operacion->q; }
			else if($operacion->Operacion_Tipo_id==$output_id){  $q+=(-$operacion->q); }
		}
		// print_r($data);
		return $q;
	}


	public static function getOutputByProductIdCutId($Producto_id,$cut_id){
		$sql = "select * from ".self::$tablename." where Producto_id=$Producto_id and cut_id=$cut_id and Operacion_Tipo_id=2 order by created_at desc";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new OperationData();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Producto_id = $r['Producto_id'];
			$array[$cnt]->q = $r['q'];
			$array[$cnt]->is_oficial = $r['is_oficial'];
			$array[$cnt]->cut_id = $r['cut_id'];
			$array[$cnt]->Operacion_Tipo_id = $r['Operacion_Tipo_id'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}


	public static function getOutputByProductIdCutIdYesF($Producto_id,$cut_id){
		$sql = "select * from ".self::$tablename." where Producto_id=$Producto_id and cut_id=$cut_id  and Operacion_Tipo_id=2 order by created_at desc";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new OperationData();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Producto_id = $r['Producto_id'];
			$array[$cnt]->q = $r['q'];
			$array[$cnt]->is_oficial = $r['is_oficial'];
			$array[$cnt]->cut_id = $r['cut_id'];
			$array[$cnt]->Operacion_Tipo_id = $r['Operacion_Tipo_id'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}

	public static function getOutputByProductIdCutIdNoF($Producto_id,$cut_id){
		$sql = "select * from ".self::$tablename." where Producto_id=$Producto_id and cut_id=$cut_id and is_oficial=0 and Operacion_Tipo_id=2 order by created_at desc";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new OperationData();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Producto_id = $r['Producto_id'];
			$array[$cnt]->q = $r['q'];
			$array[$cnt]->is_oficial = $r['is_oficial'];
			$array[$cnt]->cut_id = $r['cut_id'];
			$array[$cnt]->Operacion_Tipo_id = $r['Operacion_Tipo_id'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}
////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
	public static function getInputQ($Producto_id,$cut_id){
		$q=0;
		$operations = self::getInputByProductIdCutId($Producto_id,$cut_id);
		$input_id = OperationTypeData::getByName("entrada")->Id;
		$output_id = OperationTypeData::getByName("salida")->Id;
		foreach($operations as $operacion){
			if($operacion->Operacion_Tipo_id==$input_id){ $q+=$operacion->q; }
			else if($operacion->Operacion_Tipo_id==$output_id){  $q+=(-$operacion->q); }
		}
		// print_r($data);
		return $q;
	}

	public static function getInputQYesF($Producto_id,$cut_id){
		$q=0;
		$operations = self::getInputByProductIdCutIdYesF($Producto_id,$cut_id);
		$input_id = OperationTypeData::getByName("entrada")->Id;
		$output_id = OperationTypeData::getByName("salida")->Id;
		foreach($operations as $operacion){
			if($operacion->Operacion_Tipo_id==$input_id){ $q+=$operacion->q; }
			else if($operacion->Operacion_Tipo_id==$output_id){  $q+=(-$operacion->q); }
		}
		// print_r($data);
		return $q;
	}

	public static function getInputQNoF($Producto_id,$cut_id){
		$q=0;
		$operations = self::getInputByProductIdCutIdNoF($Producto_id,$cut_id);
		$input_id = OperationTypeData::getByName("entrada")->Id;
		$output_id = OperationTypeData::getByName("salida")->Id;
		foreach($operations as $operacion){
			if($operacion->Operacion_Tipo_id==$input_id){ $q+=$operacion->q; }
			else if($operacion->Operacion_Tipo_id==$output_id){  $q+=(-$operacion->q); }
		}
		// print_r($data);
		return $q;
	}


	public static function getInputByProductIdCutId($Producto_id,$cut_id){
		$sql = "select * from ".self::$tablename." where Producto_id=$Producto_id and cut_id=$cut_id and Operacion_Tipo_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new OperationData();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Producto_id = $r['Producto_id'];
			$array[$cnt]->q = $r['q'];
			$array[$cnt]->is_oficial = $r['is_oficial'];
			$array[$cnt]->cut_id = $r['cut_id'];
			$array[$cnt]->Operacion_Tipo_id = $r['Operacion_Tipo_id'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}


	public static function getInputByProductIdCutIdYesF($Producto_id,$cut_id){
		$sql = "select * from ".self::$tablename." where Producto_id=$Producto_id and cut_id=$cut_id  and Operacion_Tipo_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new OperationData();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Producto_id = $r['Producto_id'];
			$array[$cnt]->q = $r['q'];
			$array[$cnt]->is_oficial = $r['is_oficial'];
			$array[$cnt]->cut_id = $r['cut_id'];
			$array[$cnt]->Operacion_Tipo_id = $r['Operacion_Tipo_id'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}

	public static function getInputByProductIdCutIdNoF($Producto_id,$cut_id){
		$sql = "select * from ".self::$tablename." where Producto_id=$Producto_id and cut_id=$cut_id and is_oficial=0 and Operacion_Tipo_id=1 order by created_at desc";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new OperationData();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Producto_id = $r['Producto_id'];
			$array[$cnt]->q = $r['q'];
			$array[$cnt]->is_oficial = $r['is_oficial'];
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