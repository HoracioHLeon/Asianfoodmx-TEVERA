<?php
class CategoryData {
	public static $tablename = "categoria";

	public function CategoryData(){
		$this->Nombre = "";
	}

	public function add(){
		$sql = "insert into ".self::$tablename." (Nombre) ";
		$sql .= "value (\"$this->Nombre\")";
		Executor::doit($sql);
	}

	public static function delById($Id){
		$sql = "delete from ".self::$tablename." where Id=$Id";
		Executor::doit($sql);
	}

	public function del(){
		$sql = "delete from ".self::$tablename." where id=$this->Id";
		Executor::doit($sql);
	}

	public function hide(){
		$sql = "update ".self::$tablename." set is_active=0 where Id=$this->Id";
		Executor::doit($sql);
	}

	public function active(){
		$sql = "update ".self::$tablename." set is_active=1 where Id=$this->Id";
		Executor::doit($sql);
	}


	public static function getById($Id){
		$sql = "select * from ".self::$tablename." where id=$Id";
		$query = Executor::doit($sql);
		$found = null;
		$data = new CategoryData();
		while($r = $query[0]->fetch_array()){
			$data->id = $r['Id'];
			$data->name = $r['Nombre'];
			$found = $data;
			break;
		}
		return $found;
	}

	public static function getByName($Nombre){
		 $sql = "select * from ".self::$tablename." where Nombre=\"$Nombre\"";
		$query = Executor::doit($sql);
		$found = null;
		$data = new CategoryData();
		while($r = $query[0]->fetch_array()){
			$data->id = $r['Id'];
			$data->name = $r['Nombre'];
			$found = $data;
			break;
		}
		return $found;
	}


	public static function getAll(){
		$sql = "select * from ".self::$tablename."";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new CategoryData();
			$array[$cnt]->id = $r['Id'];
			$array[$cnt]->name = $r['Nombre'];
			$cnt++;
		}
		return $array;
	}

	public static function getAllActive(){
		$sql = "select * from ".self::$tablename." where is_active=1";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new CategoryData();
			$array[$cnt]->id = $r['Id'];
			$array[$cnt]->name = $r['Nombre'];
			$cnt++;
		}
		return $array;
	}

	public static function getAllUnActive(){
		$sql = "select * from ".self::$tablename." where is_active=0";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new CategoryData();
			$array[$cnt]->id = $r['Id'];
			$array[$cnt]->name = $r['Nombre'];
			$cnt++;
		}
		return $array;
	}


}

?>