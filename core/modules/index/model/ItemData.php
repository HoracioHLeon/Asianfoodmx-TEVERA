<?php
class ItemData {
	public static $tablename = "mesa";

	public function ItemData(){
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
		$sql = "delete from ".self::$tablename." where Id=$this->Id";
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
		$sql = "select * from ".self::$tablename." where Id=$Id";
		$query = Executor::doit($sql);
		$found = null;
		$data = new ItemData();
		while($r = $query[0]->fetch_array()){
			$data->Id = $r['Id'];
			$data->Nombre = $r['Nombre'];
			$found = $data;
			break;
		}
		return $found;
	}

	public static function getByName($Nombre){
		 $sql = "select * from ".self::$tablename." where Nombre=\"$Nombre\"";
		$query = Executor::doit($sql);
		$found = null;
		$data = new ItemData();
		while($r = $query[0]->fetch_array()){
			$data->Id = $r['Id'];
			$data->Nombre = $r['Nombre'];
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
			$array[$cnt] = new ItemData();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Nombre = $r['Nombre'];
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
			$array[$cnt] = new ItemData();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Nombre = $r['Nombre'];
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
			$array[$cnt] = new ItemData();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Nombre = $r['Nombre'];
			$cnt++;
		}
		return $array;
	}


}

?>