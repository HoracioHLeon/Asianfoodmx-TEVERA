<?php
class UserData {
	public static $tablename = "usuario";


	public function Userdata(){
		$this->Nombre = "";
		$this->Apellidos = "";
		$this->Correo = "";
		$this->Fotografia = "";
		$this->Contraseña = "";
		$this->created_at = "NOW()";
	}

	public function add(){
		$sql = "insert into usuario (Nombre,Apellidos,Correo,Contraseña,created_at) ";
		$sql .= "value (\"$this->Nombre\",\"$this->Apellidos\",\"$this->Correo\",\"$this->Contraseña\",$this->created_at)";
		Executor::doit($sql);
	}
	public function add_admin(){
		$sql = "insert into usuario (Nombre,Apellidos,Correo,Contraseña,is_admin,created_at) ";
		$sql .= "value (\"$this->Nombre\",\"$this->Apellidos\",\"$this->Correo\",\"$this->Contraseña\",1,$this->created_at)";
		Executor::doit($sql);
	}

	public function add_cajero(){
		$sql = "insert into usuario (Nombre,Apellidos,Correo,Contraseña,is_cajero,created_at) ";
		$sql .= "value (\"$this->Nombre\",\"$this->Apellidos\",\"$this->Correo\",\"$this->Contraseña\",1,$this->created_at)";
		Executor::doit($sql);
	}

	public function add_mesero(){
		$sql = "insert into usuario (Nombre,Apellidos,Correo,Contraseña,is_mesero,created_at) ";
		$sql .= "value (\"$this->Nombre\",\"$this->Apellidos\",\"$this->Correo\",\"$this->Contraseña\",1,$this->created_at)";
		Executor::doit($sql);
	}

	public static function delById($id){
		$sql = "delete from ".self::$tablename." where Id=$Id";
		Executor::doit($sql);
	}
	public function del(){
		$sql = "delete from ".self::$tablename." where Id=$this->Id";
		Executor::doit($sql);
	}

// partiendo de que ya tenemos creado un objecto UserData previamente utilizamos el contexto
	public function update(){
		$sql = "update ".self::$tablename." set Nombre=\"$this->Nombre\",Correo=\"$this->Correo\",Fotografia=\"$this->Fotografia\",Contraseña=\"$this->Contraseña\" where Id=$this->Id";
		Executor::doit($sql);
	}

	public static function getById($Id){
		$sql = "select * from ".self::$tablename." where Id=$Id";
		$query = Executor::doit($sql);
		$found = null;
		$data = new UserData();
		while($r = $query[0]->fetch_array()){
			$data->Id = $r['Id'];
			$data->Nombre = $r['Nombre'];
			$data->Apellidos = $r['Apellidos'];
			$data->Correo = $r['Correo'];
			$data->Contraseña = $r['Contraseña'];
			$data->created_at = $r['created_at'];

			$data->is_admin = $r['is_admin'];
			$data->is_cajero = $r['is_cajero'];
			$data->is_mesero = $r['is_mesero'];

			$found = $data;
			break;
		}
		return $found;
	}

	public static function getByMail($mail){
		$sql = "select * from ".self::$tablename." where Correo=\"$mail\"";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new UserData();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Nombre = $r['Nombre'];
			$array[$cnt]->Apellidos = $r['Apellidos'];
			$array[$cnt]->Correo = $r['Correo'];
			$array[$cnt]->Contraseña = $r['Contraseña'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}


	public static function getAll(){
		$sql = "select * from ".self::$tablename;
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new UserData();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Nombre = $r['Nombre'];
			$array[$cnt]->Apellidos = $r['Apellidos'];
			$array[$cnt]->Correo = $r['Correo'];
			$array[$cnt]->Contraseña = $r['Contraseña'];

			$array[$cnt]->is_admin = $r['is_admin'];
			$array[$cnt]->is_mesero = $r['is_mesero'];
			$array[$cnt]->is_cajero = $r['is_cajero'];

			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}

	public static function getAllMeseros(){
		$sql = "select * from ".self::$tablename." where is_mesero=1";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new UserData();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Nombre = $r['Nombre'];
			$array[$cnt]->Apellidos = $r['Apellidos'];
			$array[$cnt]->Correo = $r['Correo'];
			$array[$cnt]->Contraseña = $r['Contraseña'];

			$array[$cnt]->is_admin = $r['is_admin'];
			$array[$cnt]->is_mesero = $r['is_mesero'];
			$array[$cnt]->is_cajero = $r['is_cajero'];

			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}


	public static function getLike($q){
		$sql = "select * from ".self::$tablename." where Nombre like '%$q%'";
		$query = Executor::doit($sql);
		$array = array();
		$cnt = 0;
		while($r = $query[0]->fetch_array()){
			$array[$cnt] = new UserData();
			$array[$cnt]->Id = $r['Id'];
			$array[$cnt]->Nombre = $r['Nombre'];
			$array[$cnt]->Correo = $r['Correo'];
			$array[$cnt]->password = $r['Contraseña'];
			$array[$cnt]->created_at = $r['created_at'];
			$cnt++;
		}
		return $array;
	}


}

?>