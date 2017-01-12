<?php
class MyDB {
	var $config = array();
	function MyDB($config){
		$this->config = $config;
	}
	function query($query = ''){
		//returns an array of values
		if($query == '' || strlen($query) < 6 || strpos($query, "select") != 0 ){
			return false;
		}
		$db = new mysqli($this->config['host'], $this->config['user'], $this->config['pass'], $this->config['db_name'], $this->config['port']);
		if (mysqli_connect_errno()) {
			if(isset($debug) && $debug == 1) {
				printf("Connect failed: %s\n", mysqli_connect_error());
				exit;
			}
			return false;
		}
		$result = $db->query($query);
		if (!$result) {
			if(isset($debug) && $debug == 1) {
				printf("Query failed: %s\n", $db->error);
			}
			return false;
		} 
		$rows = false;
		while($row = $result->fetch_row()) {
			$rows[]=$row;
		}
		$result->close();
		$db->close();
		return $rows;
	}
	function select($query = ''){
		//returns an array of object littreals
		if($query == '' || strlen($query) < 6 || strpos($query, "select") != 0 ){
			return false;
		}
		$db = new mysqli($this->config['host'], $this->config['user'], $this->config['pass'], $this->config['db_name'], $this->config['port']);
		if (mysqli_connect_errno()) {
			if(isset($debug) && $debug == 1) {
				printf("Connect failed: %s\n", mysqli_connect_error());
				exit;
			}
			return false;
		}
		$result = $db->query($query);
		if (!$result) {
			if(isset($debug) && $debug == 1) {
				printf("Query failed: %s\n", $db->error);
			}
			return false;
		} 
		$rows = false;
		while($row = $result->fetch_array(MYSQL_ASSOC)) {
			$rowdata = json_encode($row);
			$rows[] = json_decode($rowdata);
		}
		$result->close();
		$db->close();
		return $rows;
	}
	function insert($table, $json, $types){
		//$json holds the column-value data pairs of type $types to inseet in $table
		$db = new mysqli($this->config['host'], $this->config['user'], $this->config['pass'], $this->config['db_name'], $this->config['port']);
		if (mysqli_connect_errno()) {
			if(isset($debug) && $debug == 1){
				printf("Connect failed: %s\n", mysqli_connect_error());
				exit;
			}
			return false;
		}
		$args = json_decode ( $json );
		$keys = array();
		$values = array();
		$qs = array();
		//prepare data for insertion
		foreach ($args as $key => $value) {
			array_push( $keys, $key );
			array_push($values, $value );
			array_push($qs, '?');
		}
		$keys = implode(',', $keys);
		$qs = implode(',', $qs);
		$stmt_cols_vals = "INSERT INTO `$table` ($keys) values ($qs)";
		$stmt = $db->prepare($stmt_cols_vals);
		$stmt->bind_param($types, ...$values);
		$stmt->execute();
		$result = $stmt->get_result();
		$db->close();
		return $result;
	}

	function update($table, $json, $conditions, $types){
		$db = new mysqli($this->config['host'], $this->config['user'], $this->config['pass'], $this->config['db_name'], $this->config['port']);
		if (mysqli_connect_errno()) {
			if(isset($debug) && $debug == 1){
				printf("Connect failed: %s\n", mysqli_connect_error());
				exit;
			}
			return false;
		}
		if(!isset($conditions)){
			$conditions = "";
		}else{
			$conditions = " " . $conditions;
		}
		$data = json_decode ( $json );
		$keys = array();
		$values = array();
		//prepare data for update
		foreach ($data as $key => $value) {
			array_push( $keys, $key . '=?' );
			array_push( $values, $value );
		}
		$keys = implode(',', $keys);
		$stmt_cols_vals = "UPDATE `$table` SET $keys$conditions";
		$stmt = $db->prepare($stmt_cols_vals);
		$stmt->bind_param($types, ...$values);
		$stmt->execute();
		$result = $stmt->get_result();
		$db->close();
		return $result;
	}
}
?>
