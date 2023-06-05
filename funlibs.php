<?php
error_reporting(0);
date_default_timezone_set("Asia/Jakarta");
$host="$_SERVER[HTTP_HOST]";
$head1="<font size='+1'>RSUD GAMBIRAN KOTA KEDIRI</font>";
 /*
 * File Name: class.crud.php
 * Date: August 17, 2015
 * Author: Alfian Syahroni
 * email : lowshint@gmail.com
 * referensi:
 * Facebook : https://www.facebook.com/sourcecodeonline
 * http://php.net/manual/en/class.pdo.php
 * http://wiki.hashphp.org/PDO_Tutorial_for_MySQL_Developers#Why_use_PDO.3F
 * 
 */
 
class Database extends PDO{
    private $engine; 
    private $host; 
    private $database; 
    private $user; 
    private $pass;
	
    private $result; 	
      
    public function __construct()
	{ 
        $this->engine	= 'mysql'; 
        $this->host	  	= 'localhost'; 
		$this->database = 'itassets';  
		$this->user 	= 'root'; 
        $this->pass 	= 'youC1000@';
		//$this->pass 	= 'sembarang'; 
        $dns = $this->engine.':dbname='.$this->database.";host=".$this->host; 
        parent::__construct( $dns, $this->user, $this->pass ); 
    }

	public function hostnya(){	
		return $this->host;
	}
	public function passnya(){
		return $this->pass;
	}
	public function dbnya(){
		return $this->database;
	}
	public function usernya(){	
		return $this->user;
	}

	
	/*
    * Insert values into the table
    */
	public function insert($table,$rows=null)
	{
		$command = 'INSERT INTO '.$table;
		$row = null; $value=null;
		foreach ($rows as $key => $nilainya)
		{
		  $row	.=",".$key;
		  $value 	.=", :".$key;
		}
		
		$command .="(".substr($row,1).")";
		$command .="VALUES(".substr($value,1).")";
		//echo"$command<br><br>";
	   
		$stmt =  parent::prepare($command);
		$stmt->execute($rows);
		$rowcount = $stmt->rowCount();
		//$rowcount = parent::lastInsertId();
		return $rowcount;
	}
	
	public function insertNotExist($table,$rows=null,$where=null)
	{
		
		$command = 'INSERT INTO '.$table;
		$row = null; $value=null;
		foreach ($rows as $key => $nilainya)
		{
		  $row	.=",".$key;
		  $value 	.=", :".$key;
		}
		
		$command .="(".substr($row,1).")";
		$command .="VALUES(".substr($value,1).")";
		//echo"$command<br><br>";
	   
		$stmt =  parent::prepare($command);
		$stmt->execute($rows);
		$rowcount = $stmt->rowCount();
		//$rowcount = parent::lastInsertId();
		return $rowcount;
	}
	
	//Insert Data and Return Last Insert ID
	public function insertID($table,$rows=null)
	{
		$command = 'INSERT INTO '.$table;
		$row = null; $value=null;
		foreach ($rows as $key => $nilainya)
		{
		  $row	.=",".$key;
		  $value 	.=", :".$key;
		}
		
		$command .="(".substr($row,1).")";
		$command .="VALUES(".substr($value,1).")";
		 // echo"$command";
	   
		$stmt =  parent::prepare($command);
		$stmt->execute($rows);
		//$rowcount = $stmt->rowCount();
		$rowcount = parent::lastInsertId();
		
		return $rowcount;
	}
	
	/*
    * Delete records from the database.
    */
	public function delete($tabel,$where=null)
	{
		$command = 'DELETE FROM '.$tabel;
		
		$list = Array(); $parameter = null;
		foreach ($where as $key => $value) 
		{
		  $list[] = "$key = :$key";
		  $parameter .= ', ":'.$key.'":"'.$value.'"';
		} 
		$command .= ' WHERE '.implode(' AND ',$list);
	
		$json = "{".substr($parameter,1)."}";
		$param = json_decode($json,true);
				
		$query = parent::prepare($command); 
		$query->execute($param);
		$rowcount = $query->rowCount();
        return $rowcount;
	}
   /*
    * Uddate Record
    */
	/*public function update($tabel, $fild = null ,$where = null)
	{
		 $update = 'UPDATE '.$tabel.' SET ';
		 $set=null; $value=null;
		 foreach($fild as $key => $values)
		 {
			 $set .= ', '.$key. ' = :'.$key;
			 $value .= ', ":'.$key.'":"'.$values.'"';
		 }
		 $update .= substr(trim($set),1);
		 $json = '{'.substr($value,1).'}';
		 $param = json_decode($json,true);
		 //echo $json."<br>";
		 if($where != null)
		 {
		    $update .= ' WHERE '.$where;
		 }
		 echo"$update<br><br>";
		 try
			{
			 $query = parent::prepare($update);
			 $query->execute($param);
			 //echo"test<br>";
			}
				catch(Exception $e)
			{
				echo($e->getMessage()); echo"test";
			}
		 $rowcount = $query->rowCount();
         return $rowcount;
    }*/
    public function update($tabel, $fild = null ,$where = null)
	{
		 $update = 'UPDATE '.$tabel.' SET ';
		 $set=null; $value=null;
		 foreach($fild as $key => $values)
		 {
			 $set .= ', '.$key. ' = :'.$key;
			 $value .= ', ":'.$key.'":"'.$values.'"';
			 //$value1[] = '":'.$key.'":"'.$values.'"';
		 }
		 $update .= substr(trim($set),1);
		 if($where != null)
		 {
		    $update .= ' WHERE '.$where;
		 }
		 //echo"$update<br>";
		 try
			{
			 $query = parent::prepare($update);
			 $query->execute($fild);
			 //echo"test<br>";
			}
				catch(Exception $e)
			{
				echo($e->getMessage()); 
				//echo"test";
			}
		 $rowcount = $query->rowCount();
         return $rowcount;
    }
   /*
    * Selects information from the database.
    */
	public function select($table, $rows, $where = null, $order = null, $limit= null)
	{
	    $command = 'SELECT '.$rows.' FROM '.$table;
        if($where != null)
            $command .= ' WHERE '.$where;
        if($order != null)
            $command .= ' ORDER BY '.$order;            
        if($limit != null)
            $command .= ' LIMIT '.$limit;
		// echo"$command<br><br>";
		$query = parent::prepare($command);
		$query->execute();
		
		$posts = array();
		while($row = $query->fetch(PDO::FETCH_ASSOC))
		{
			 $posts[] = $row;
		}
		//return $this->result = json_encode(array('post'=>$posts));
		//return $query->fetch(PDO::FETCH_ASSOC);
 		
        return $posts;	
 	}
	
	
	public function selectcount($tabel,$rows,$where)
	{	
		$q=$this->select($tabel,$rows,$where);
        //return count($q);
		return $q;
 	}
		
	public function nourut2($field, $table, $param, $kdunit, $tgl){
		$lenght = strlen($param);
		$mul=8;
		if($lenght==2){
			$mul=$mul-1;	
			$cab=4;	
		//PU/01/201605/0001
		}elseif($lenght==3){
			$mul=$mul;
			$cab=5;	
		//PUO/01/201605/0001
		}
		
		$thn = date("Y",strtotime($tgl));
		$bln = date("m",strtotime($tgl));
		$query = $this->select($table,"$field AS maxID","SUBSTR($field,1,$lenght)='$param' and SUBSTR($field,$cab,2)='$kdunit' ORDER BY SUBSTR($field,$mul,12) desc limit 1");
		//$query = $this->select($table,"$field AS maxID","SUBSTR($field,1,$lenght)='$param' and substr($field,12,2)='$bln' and substr($field,8,4)='$thn' and substr($field,5,2)='$kdunit' ORDER BY SUBSTR($field,15,4) desc limit 1");
		foreach($query as $data){}
		$idMaxj = $data['maxID'];
		
		$temp=explode("/",$idMaxj);
		
		$noUrutj = intval($temp[3]);
		$noBlnj =  substr($temp[2], 4, 2);
		
		if($noBlnj<> $bln)
		{
			$noUrutj=1;
		} else {
			$noUrutj++;
		}
		$id=$param."/".$kdunit."/".$thn."".$bln."/".sprintf("%04s", $noUrutj);
		return $id;
	}
	public function nourut($field, $table, $param, $kdunit, $tgl){
		$lenght = strlen($param);
		$mul=8;
		if($lenght==2){
			$mul=$mul-1;	
			$cab=4;	
		//PU/01/201605/0001
		}elseif($lenght==3){
			$mul=$mul;
			$cab=5;	
		//PUO/01/201605/0001
		}
		
		$thn = date("y",strtotime($tgl));
		$bln = date("m",strtotime($tgl));
		$tg = date("d",strtotime($tgl));
		//$query = $this->select($table,"$field AS maxID","SUBSTR($field,1,$lenght)='$param' and SUBSTR($field,$cab,2)='$kdunit' ORDER BY SUBSTR($field,$mul,12) desc limit 1");
// 		$query = $this->select($table,"$field AS maxID","SUBSTRING_INDEX($field,'/',1)='$param' and LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX($field,'/',2),'/',-1),3,0)='$kdunit' ORDER BY 
// SUBSTRING_INDEX($field,'/',-2) desc limit 1");
		$query = $this->select($table,"$field AS maxID","SUBSTRING_INDEX(SUBSTRING_INDEX(no_ticket,'/',2),'/',-1)='$param' ORDER BY 
 SUBSTRING_INDEX($field,'/',-2) desc limit 1");
		// echo "select $field AS maxID from $table where SUBSTRING_INDEX(SUBSTRING_INDEX(no_ticket,'/',2),'/',-1)='$param' ORDER BY 
  // SUBSTRING_INDEX($field,'/',-2) desc limit 1";
  // die;
		foreach($query as $data){}
		
		$idMaxj = $data['maxID'];
		$temp=explode("/",$idMaxj);
		
		$noUrutj = intval($temp[2]);
		$noBlnj =  substr($temp[1], 0, 6); 
		
		
		if($noBlnj<> $thn.$bln.$tg)
		{
			$noUrutj=1;
		} else {
			$noUrutj++;
		}

		//$id=$param."/".$kdunit."/".$thn."".$bln."/".sprintf("%05s", $noUrutj);
		$id=$kdunit."/".$thn."".$bln."".$tg."/".sprintf("%03s", $noUrutj);
		return $id;
	}
	public function newIDKM($jenis)
	{

		 $thn = date("Y");
		 $bln = date("m");
		 $tgl = date("d");
		 		
		 if($jenis=='1'){
			 	//Rawat Jalan
				$tabel = "rj";
				$idfield="no_rj";
				$pref="IRJ.";
				$id="id_rj";
				$rows  = "ifnull($idfield,0) as maxID";
				$where = "month(antrian)='$bln' and year(antrian)='$thn' and $id=(select $id as maxID from $tabel WHERE month(antrian)='$bln' and year(antrian)='$thn' ORDER BY $id DESC limit 0,1)";	
		 }
		 if($jenis=='2'){
			 	//Rawat resep
				$tabel = "resep";
				$idfield="no_resep";
				$pref="IRN.";
				$id="id";
				$rows  = "ifnull($idfield,0) as maxID";
				$where = "month(tgl)='$bln' and year(tgl)='$thn' and $id=(select $id as maxID from $tabel WHERE month(tgl)='$bln' and year(tgl)='$thn' ORDER BY $id DESC limit 0,1)";	
		 }
		 if($jenis=='3'){
			 	//konsultasi
				$tabel = "konsultasi";
				$idfield="no_konsul";
				$pref="KSL.";
				$id="id_konsul";
				$rows  = "ifnull($idfield,0) as maxID";
				$where = "month(tgl)='$bln' and year(tgl)='$thn' and $id=(select $id as maxID from $tabel WHERE month(tgl)='$bln' and year(tgl)='$thn' ORDER BY $id DESC limit 0,1)";	
		 }
		 if($jenis=='4'){
			 	//Rawat bayar
				$tabel = "td_bayar";
				$idfield="no_bayar";
				$pref="BYR.";
				$id="id";
				$rows  = "ifnull($idfield,0) as maxID";
				$where = "month(tgl)='$bln' and year(tgl)='$thn' and $id=(select $id as maxID from $tabel WHERE month(tgl)='$bln' and year(tgl)='$thn' ORDER BY $id DESC limit 0,1)";	
		 }
		 if($jenis=='5'){
			 	//Rawat Inap
				$tabel = "irna";
				$idfield="no_ri";
				$pref="IRN.";
				$id="id_ri";
				$rows  = "ifnull($idfield,0) as maxID";
				$where = "month(tgl_keluar)='$bln' and year(tgl_keluar)='$thn' and $id=(select $id as maxID from $tabel WHERE month(tgl_keluar)='$bln' and year(tgl_keluar)='$thn' ORDER BY $id DESC limit 0,1)";	
		 }
		 if($jenis=='6'){
			 	//karcis bayar
				$tabel = "td_bayarkarcis";
				$idfield="no_bayar";
				$pref="KRC.";
				$id="id";
				$rows  = "ifnull($idfield,0) as maxID";
				$where = "month(tgl)='$bln' and year(tgl)='$thn' and $id=(select $id as maxID from $tabel WHERE month(tgl)='$bln' and year(tgl)='$thn' ORDER BY $id DESC limit 0,1)";	
		 }
		 if($jenis=='8'){
			 	//Refund Bayar RJ
				$tabel = "td_refund";
				$idfield="no_refund";
				$pref="RFN.";
				$id="id_refund";
				$rows  = "ifnull($idfield,0) as maxID";
				$where = "month(tglinput)='$bln' and year(tglinput)='$thn' and $id=(select $id as maxID from $tabel WHERE month(tglinput)='$bln' and year(tglinput)='$thn' ORDER BY $id DESC limit 0,1)";	
		 }
		 	 	
			 
		
		$dt=$this->select($tabel,$rows,$where);
		//echo"<br> check select $rows from $tabel Where $where<br>";
		foreach($dt as $value){
			$idMax=$value['maxID'];
		}
		
		$noUrut = (int) substr($idMax, 12, 13);
		$noBln = (int) substr($idMax, 5, 2);

		if($idMax=='0' or empty($idMax))
		{

			$noUrut=1;
		} else {
		
			$noUrut++;
		}
				
		$newID = "$pref". $thn ."". sprintf("%02s",$bln).sprintf("%02s",$tgl)."". sprintf("%06s", $noUrut);	 
		
		return $newID;	 
    }
	
	
	
	public function get_client_ip() {
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
		   $ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		//$loket=$this->select("t_loket","*","ip_loket='$ipaddress'");
		//return $ipaddress;
		foreach($this->select("c1pelayanan.t_loket","*","ip_loket='$ipaddress'") as $loket1){
		//foreach($this->select("c1pelayanan.t_loket","*","ip_loket='::1'") as $loket1){

			}
		//return $loket1['no_loket'];
			//return $ipaddress; 
	}
	
	// Function to get the client ip address
	public function get_client_ip_env() {
	    $ipaddress = '';

	    if (getenv('HTTP_CLIENT_IP'))
	        $ipaddress = getenv('HTTP_CLIENT_IP');
	    else if(getenv('HTTP_X_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	    else if(getenv('HTTP_X_FORWARDED'))
	        $ipaddress = getenv('HTTP_X_FORWARDED');
	    else if(getenv('HTTP_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_FORWARDED_FOR');
	    else if(getenv('HTTP_FORWARDED'))
	        $ipaddress = getenv('HTTP_FORWARDED');
	    else if(getenv('REMOTE_ADDR'))
	        $ipaddress = getenv('REMOTE_ADDR');
	    else
	        $ipaddress = 'UNKNOWN';
	 
	    return $ipaddress;
	}
	
	/*
    * Returns the result set
    */
	public function getResult()
	{
        return $this->result;
    }
	
	public function bulanrom($bulan){

		$bln=array(
					"1"=>"I",
					"2"=>"II",
					"3"=>"III",
					"4"=>"IV",
					"5"=>"V",
					"6"=>"VI",
					"7"=>"VII",
					"8"=>"VIII",
					"9"=>"IX",
					"10"=>"X",
					"11"=>"XI",
					"12"=>"XII",

				);
		return $bln[$bulan];


	}

	static function data_output ( $columns, $data, $isJoin = false )
    {
        $out = array();
        for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
            $row = array();
            for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
                $column = $columns[$j];
                // Is there a formatter?
                if ( isset( $column['formatter'] ) ) {
                    $row[ $column['dt'] ] = ($isJoin) ? $column['formatter']( $data[$i][ $column['field'] ], $data[$i] ) : $column['formatter']( $data[$i][ $column['db'] ], $data[$i] );
                }
                else {
                    $row[ $column['dt'] ] = ($isJoin) ? $data[$i][ $columns[$j]['field'] ] : $data[$i][ $columns[$j]['db'] ];
                }
            }
            $out[] = $row;
        }
        return $out;
    }
    /**
     * Paging
     *
     * Construct the LIMIT clause for server-side processing SQL query
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @return string SQL limit clause
     */
    static function limit ( $request, $columns )
    {
        $limit = '';
        if ( isset($request['start']) && $request['length'] != -1 ) {
            $limit = "LIMIT ".intval($request['start']).", ".intval($request['length']);
        }
        return $limit;
    }
    /**
     * Ordering
     *
     * Construct the ORDER BY clause for server-side processing SQL query
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @param bool  $isJoin  Determine the the JOIN/complex query or simple one
     *
     *  @return string SQL order by clause
     */
    static function order ( $request, $columns, $isJoin = false )
    {
        $order = '';
        if ( isset($request['order']) && count($request['order']) ) {
            $orderBy = array();
            $dtColumns = self::pluck( $columns, 'dt' );
            for ( $i=0, $ien=count($request['order']) ; $i<$ien ; $i++ ) {
                // Convert the column index into the column data property
                $columnIdx = intval($request['order'][$i]['column']);
                $requestColumn = $request['columns'][$columnIdx];
                $columnIdx = array_search( $requestColumn['data'], $dtColumns );
                $column = $columns[ $columnIdx ];
                if ( $requestColumn['orderable'] == 'true' ) {
                    $dir = $request['order'][$i]['dir'] === 'asc' ?
                        'ASC' :
                        'DESC';
                    $dtl=explode(" as ",$column['db']);
                    //$orderBy[] = ($isJoin) ? $column['db'].' '.$dir : '`'.$column['db'].'` '.$dir;
                    $orderBy[] = ($isJoin) ? $dtl[0].' '.$dir : '`'.$dtl[0].'` '.$dir;
                }
            }
            $order = 'ORDER BY '.implode(', ', $orderBy);
            //echo "$order";
        }
        return $order;
    }
    /**
     * Searching / Filtering
     *
     * Construct the WHERE clause for server-side processing SQL query.
     *
     * NOTE this does not match the built-in DataTables filtering which does it
     * word by word on any field. It's possible to do here performance on large
     * databases would be very poor
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @param  array $bindings Array of values for PDO bindings, used in the sql_exec() function
     *  @param  bool  $isJoin  Determine the the JOIN/complex query or simple one
     *
     *  @return string SQL where clause
     */
    static function filter ( $request, $columns, &$bindings, $isJoin = false )
    {
        $globalSearch = array();
        $columnSearch = array();
        $dtColumns = self::pluck( $columns, 'dt' );
        if ( isset($request['search']) && $request['search']['value'] != '' ) {
            $str = $request['search']['value'];
            for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
                $requestColumn = $request['columns'][$i];
                $columnIdx = array_search( $requestColumn['data'], $dtColumns );
                $column = $columns[ $columnIdx ];
                if ( $requestColumn['searchable'] == 'true' ) {
                    $binding = self::bind( $bindings, '%'.$str.'%', PDO::PARAM_STR );
                    $dtl=explode(" as ",$column['db']);
                    //$globalSearch[] = ($isJoin) ? $column['db']." LIKE ".$binding : "`".$column['db']."` LIKE ".$binding;
                    $globalSearch[] = ($isJoin) ? $dtl[0]." LIKE ".$binding : "`".$dtl[0]."` LIKE ".$binding;
                }
            }
        }
        // Individual column filtering
        for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
            $requestColumn = $request['columns'][$i];
            $columnIdx = array_search( $requestColumn['data'], $dtColumns );
            $column = $columns[ $columnIdx ];
            $str = $requestColumn['search']['value'];
            if ( $requestColumn['searchable'] == 'true' &&
                $str != '' ) {
                $binding = self::bind( $bindings, '%'.$str.'%', PDO::PARAM_STR );
            	$dtl=explode(" as ",$column['db']);
                //$columnSearch[] = ($isJoin) ? $column['db']." LIKE ".$binding : "`".$column['db']."` LIKE ".$binding;
            	$columnSearch[] = ($isJoin) ? $dtl[0]." LIKE ".$binding : "`".$dtl[0]."` LIKE ".$binding;
            }
        }
        // Combine the filters into a single string
        $where = '';
        if ( count( $globalSearch ) ) {
            $where = '('.implode(' OR ', $globalSearch).')';
        }
        if ( count( $columnSearch ) ) {
            $where = $where === '' ?
                implode(' AND ', $columnSearch) :
                $where .' AND '. implode(' AND ', $columnSearch);
        }
        if ( $where !== '' ) {
            $where = 'WHERE '.$where;
        }
        return $where;
    }
    /**
     * Perform the SQL queries needed for an server-side processing requested,
     * utilising the helper functions of this class, limit(), order() and
     * filter() among others. The returned array is ready to be encoded as JSON
     * in response to an SSP request, or can be modified if needed before
     * sending back to the client.
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $sql_details SQL connection details - see sql_connect()
     *  @param  string $table SQL table to query
     *  @param  string $primaryKey Primary key of the table
     *  @param  array $columns Column information array
     *  @param  array $joinQuery Join query String
     *  @param  string $extraWhere Where query String
     *
     *  @return array  Server-side processing response array
     *
     */
    static function simple ( $request, $sql_details, $table, $primaryKey, $columns, $joinQuery = NULL, $extraWhere = '', $groupBy = '')
    {
        $bindings = array();
        $db = self::sql_connect( $sql_details );
        // Build the SQL query string from the request
        $limit = self::limit( $request, $columns );
        $order = self::order( $request, $columns, $joinQuery );
        $where = self::filter( $request, $columns, $bindings, $joinQuery );
		// IF Extra where set then set and prepare query
        if($extraWhere)
            $extraWhere = ($where) ? ' AND '.$extraWhere : ' WHERE '.$extraWhere;
        
        $groupBy = ($groupBy) ? ' GROUP BY '.$groupBy .' ' : '';
        
        // Main query to actually get the data
        if($joinQuery){
            $col = self::pluck($columns, 'db', $joinQuery);
            $query =  "SELECT SQL_CALC_FOUND_ROWS ".implode(", ", $col)."
			 $joinQuery
			 $where
			 $extraWhere
			 $groupBy
			 $order
			 $limit";
        }else{
            $query =  "SELECT SQL_CALC_FOUND_ROWS `".implode("`, `", self::pluck($columns, 'db'))."`
			 FROM `$table`
			 $where
			 $extraWhere
			 $groupBy
			 $order
			 $limit";
        }
        //echo $query;
        $data = self::sql_exec( $db, $bindings,$query);
        // Data set length after filtering
        $resFilterLength = self::sql_exec( $db,
            "SELECT FOUND_ROWS()"
        );
        $recordsFiltered = $resFilterLength[0][0];
        // Total data set length
        $resTotalLength = self::sql_exec( $db,
            "SELECT COUNT(`{$primaryKey}`)
			 FROM   `$table`"
        );
        $recordsTotal = $resTotalLength[0][0];
        /*
         * Output
         */
        return array(
            "draw"            => isset ( $request['draw'] ) ?
				intval( $request['draw'] ) :
				0,
            "recordsTotal"    => intval( $recordsTotal ),
            "recordsFiltered" => intval( $recordsFiltered ),
            "data"            => self::data_output( $columns, $data, $joinQuery )
        );
    }
    /**
     * Connect to the database
     *
     * @param  array $sql_details SQL server connection details array, with the
     *   properties:
     *     * host - host name
     *     * db   - database name
     *     * user - user name
     *     * pass - user password
     * @return resource Database connection handle
     */
    static function sql_connect ( $sql_details )
    {
        try {
            $db = @new PDO(
                "mysql:host=localhost;dbname=itassets",
                'root',
                'youC1000@',
                array( PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION )
            );
            $db->query("SET NAMES 'utf8'");
        }
        catch (PDOException $e) {
            self::fatal(
                "An error occurred while connecting to the database. ".
                "The error reported by the server was: ".$e->getMessage()
            );
        }
        return $db;
    }
    /**
     * Execute an SQL query on the database
     *
     * @param  resource $db  Database handler
     * @param  array    $bindings Array of PDO binding values from bind() to be
     *   used for safely escaping strings. Note that this can be given as the
     *   SQL query string if no bindings are required.
     * @param  string   $sql SQL query to execute.
     * @return array         Result from the query (all rows)
     */
    static function sql_exec ( $db, $bindings, $sql=null )
    {
        // Argument shifting
        if ( $sql === null ) {
            $sql = $bindings;
        }
        $stmt = $db->prepare( $sql );
        //echo $sql;
        // Bind parameters
        if ( is_array( $bindings ) ) {
            for ( $i=0, $ien=count($bindings) ; $i<$ien ; $i++ ) {
                $binding = $bindings[$i];
                $stmt->bindValue( $binding['key'], $binding['val'], $binding['type'] );
            }
        }
        // Execute
        try {
            $stmt->execute();
        }
        catch (PDOException $e) {
            self::fatal( "An SQL error occurred: ".$e->getMessage() );
        }
        // Return all
        return $stmt->fetchAll();
    }
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * Internal methods
     */
    /**
     * Throw a fatal error.
     *
     * This writes out an error message in a JSON string which DataTables will
     * see and show to the user in the browser.
     *
     * @param  string $msg Message to send to the client
     */
    static function fatal ( $msg )
    {
        echo json_encode( array(
            "error" => $msg
        ) );
        exit(0);
    }
    /**
     * Create a PDO binding key which can be used for escaping variables safely
     * when executing a query with sql_exec()
     *
     * @param  array &$a    Array of bindings
     * @param  *      $val  Value to bind
     * @param  int    $type PDO field type
     * @return string       Bound key to be used in the SQL where this parameter
     *   would be used.
     */
    static function bind ( &$a, $val, $type )
    {
        $key = ':binding_'.count( $a );
        $a[] = array(
            'key' => $key,
            'val' => $val,
            'type' => $type
        );
        return $key;
    }
    /**
     * Pull a particular property from each assoc. array in a numeric array,
     * returning and array of the property values from each item.
     *
     *  @param  array  $a    Array to get data from
     *  @param  string $prop Property to read
     *  @param  bool  $isJoin  Determine the the JOIN/complex query or simple one
     *  @return array        Array of property values
     */
    static function pluck ( $a, $prop, $isJoin = false )
    {
        $out = array();
        for ( $i=0, $len=count($a) ; $i<$len ; $i++ ) {
            //$out[] = ($isJoin && isset($a[$i]['as'])) ? $a[$i][$prop]. ' AS '.$a[$i]['as'] : $a[$i][$prop];
              $out[] = ($isJoin && isset($a[$i]['as'])) ? $a[$i][$prop]. ' AS '.$a[$i]['as'] : $a[$i][$prop];
        }
        return $out;
    }
	static function _flatten ( $a, $join = ' AND ' )
	{
		if ( ! $a ) {
			return '';
		}
		else if ( $a && is_array($a) ) {
			return implode( $join, $a );
		}
		return $a;
	}

	public function tgl_indo($tanggal){
		$bulan = array (
			1 =>   'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember'
		);
		$pecahkan = explode('-', $tanggal);
		
		// variabel pecahkan 0 = tanggal
		// variabel pecahkan 1 = bulan
		// variabel pecahkan 2 = tahun
	 
		return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
	}

	function getSingleValue($table,$column,$id) { //returns single value from table row by id
		$value = $this->select($table, $column, ["id" => $id]);
		return $value;
	}
}

?>
