<?

/**
 * classe que trata da conexao com os bancos de dados e execucao de consultas
 *
 * @author 		Glauber Costa e outros
 * @since 		08/04/2008
 * @version 	0.10
 * @name 		class_banco.php
 * @abstract 	Classe que trata das informacoes relativas a bancos de dados
 */

class DataBase {
	
	/**
	 * @abstract armazenar o usuario do banco de dados
	 * @var string
	 */
	var $user;
	
	/**
	 * @abstract Armazenar a senha do banco de dados
	 * @var string
	 */
	var $password;
	
	/**
	 * @abstract Armazenar o ip/dns do servidor de banco de dados
	 * @var string
	 */
	var $host;
	
	/**
	 * @abstract Armazenar o banco de dados a ser trabalhado
	 * @var string
	 */
	var $db;
	
	/**
	 * @abstract Armazenar os erros dos bancos de dados
	 * @var unknown_type
	 */
	 var $db_error;
	
	/**
	 * @abstract Armazena a conexao com o banco de dados
	 * @var resource
	 */
	 var $connection;
	
	/**
	 * @abstract Armazena a porta que da acesso a conexao com banco de dados
	 * @var string
	 */
	 var $port;
	
	/**
	 * @abstract Armazena codigo do erro do banco de dados caso haja
	 * @var string
	 */
	 var $error="";
	

	/**
	 * @name Database
	 * @abstract Construtor da classe DataBase
	 * @author Glauber Costa
	 * @param string $server
	 * @param string $user
	 * @param string $pass
	 * @param strintg $db
	 */
	
	function DataBase($server, $user, $pass, $db=""){
		$this->set_host($server);
		$this->set_user($user);
		$this->set_pass($pass);
		if(!isset($db))
			$this->set_db($db);
		$con = $this->set_connection($this->host,$this->user,$this->password);
		$err = $this->error_message($con);
		if(empty($err) && $this->db_error($this->db)) 
			$this->select_database($db);
	}

	/**
	 * @name unset_user
	 * @abstract Libera o atributo user
	 * @author Glauber Costa
	 * */
	 function unset_user(){
		if(isset($this->user))
		  unset($this->user);
	}
	
	/**
	 * @name unset_host
	 * @abstract Libera o atributo host
	 * @author Glauber Costa
	 * */
	 function unset_host(){
		if(isset($this->host))
		  unset($this->host);
	}

	/**
	 * @name unset_password
	 * @abstract Libera o atributo password
	 * @author Glauber Costa
	 * */
	 function unset_password(){
		if(isset($this->password))
		  unset($this->password);
	}
	
	/**
	 * @name unset_db
	 * @abstract Libera o atributo db
	 * @author Glauber Costa
	 * */
	 function unset_db(){
		if(isset($this->db))
		  unset($this->db);
	}
		
	/**
	 * @name set_user
	 * @abstract Tratar o nome do usuario do banco de dados
	 * @param string $user
	 * @author Glauber Costa
	 */
	 function set_user($user){
		$this->user = $user;
	}
	
	/**
	 * @name set_pass
	 * @abstract Tratar a senha do usario do banco de dados
	 * @param string $pass
	 * @author Glauber Costa
	 */
	 function set_pass($pass){
		$this->password = $pass;
	}
	
	/**
	 * @name set_host
	 * @abstract Tratar o endereco do servidor de banco de dados
	 * @param string $host
	 * @author Glauber Costa
	 */
	 function set_host($host){
		$this->host = $host;
	}

	/**
	 * @name set_db
	 * @abstract Armazenar o nome do banco de dados
	 * @param string $data_base
	 * @author Glauber Costa
	 */
	 function set_db($data_base){
		$this->db = $data_base;
	}
	

	 function get_db(){
		return isset($this->db)?$this->db:0;
		
	}
	/**
	 * @name error_message
	 * @abstract Retorna mensagens de erro do sistema
	 * @param int $id
	 * @author Glauber Costa
	 * @return string Mensagem de erro
	 */
	 function error_message($id,$str_value=""){
		$msg = "";
		switch ($id){
			case $id===2: $msg = "Erro $id Nao foi possivel conectar"; break;
			case $id===3: $msg = "Erro $id Senha invalida ou vazia"; break;
			case $id===4: $msg = "Erro $id Host $str_value invalido"; break;
			case $id===5: $msg = "Erro $id Usuario $str_value invalido!"; break;
			case $id===6: $msg = "Erro $id Banco de dados nao existe!"; break;
			case $id===7: $msg = "Erro $id Consulta incorreta: $str_value";break;
			case $id===8: $msg = "Erro $id Banco invalido";
			case $id===9: $msg = "Erro $id tabela $str_value nao existe";
			case $id===10 :$msg = "Erro $id Nao foi possivel recuperar informacoes $str_value";
			case $id===11 :$msg = "Erro $id Nao foi possivel inserir $str_value";
		}
		return $msg;
	}
	
	/**
	 * @name user_error()
	 * @abstract Verificar se existe usuario
	 * @param string $user
	 * @author Glauber Costa
	 * @return 5 para usuario vazio e 0 para verdadeiro
	 */
	 function user_error(){
		if(!empty($this->user)){
		   	$this->error="";
			return false;
		}else {
		   $this->error=$this->error_message(5);
		   return true;	
		}
	}
	
	/**
	 * @name host_error
	 * @abstract Verificar se existe host
	 * @param string $host
	 * @author Glauber Costa
	 * @return 4 para host vazio true para host correto
	 */

	 function host_error(){
		return !empty($this->host)?false:4;
	}

	/**
	 * @name pass_error
	 * @abstract Verificar se existe senha
	 * @param string $pass
	 * @author Glauber Costa
	 * @return 3 para senha vazia 0 para senha correto
	 */

	 function pass_error(){
		return !empty($this->password)?false:3;
	}
	
	/**
	 * @name db_error
	 * @abstract Verificar se o banco esta preenchido
	 * @param string $db
	 * @author Glauber Costa
	 * @return 8 para banco vazio e 0 para preenchido
	 */
	 function db_error(){
		return !empty($this->db)?false:8;		
	}
		
	/**
	 * @name set_connection
	 * @abstract Conecta a um banco de dados;
	 * @author Glauber Costa
	 * @return resource_id se conectado ou 2 se nao conectar
	 */
	 function set_connection(){
	   if(!$this->connection = @mysql_connect($this->host,$this->user,$this->password)){
	    $this->error=mysql_error();
	   }else{
	   	$this->error="";
	   }
	   return $this->connection;
	}

	/**
	 * @name select_database
	 * @abstract Seleciona o banco de dados a ser trabalhado
	 * @param string $DBname
	 * @author Glauber Costa
	 * @return resource se conseguir selecionar o banco de dados e 6 se nao conseguir selecionar
	 */
	 function select_database($DBname){
		$this->set_db($DBname);
		return mysql_select_db($DBname)?@mysql_select_db($DBname):6;
	}
	
	/**
	 * @name run_query
	 * @abstract Envia uma consulta ao MySQL
	 * @param string $query
	 * @param string $lnk_ident
	 * @author Glauber Costa
	 * @return Resource em caso de sucesso ou 7 caso contrario
	 */
	 function run_query($query,$lnk_ident=""){
		//echo "<br>".$query."<br>";
		$retorno = empty($lnk_ident)?@mysql_query($query):@mysql_query($query,$lnk_ident);
		return $retorno?$retorno:7;
	}

	/**
	 * @name get_db_info
	 * @abstract Busca no banco de dados selecionado todas as tabelas e descreve todos os campos
	 * @author Glauber Costa
	 * @return array tridimensional contendo as especificacoes de todas as tabelas de um banco de dados
	 */
	 function get_db_info(){
		$i=0;
		$num_tables = mysql_list_tables($this->db);
		if(sizeof(mysql_num_rows($num_tables))>0){
			for($i= 0; $i < mysql_num_rows($num_tables); $i++){
				$db_array[mysql_table_name($num_tables,$i)] = $this->get_table_info(mysql_table_name($num_tables,$i));
			}
			return $db_array;
		}
		else return 8;
	}

	/**
	 * @name get_table_info
	 * @abstract Captura as informacoes sobre os campos de determinada tabela
	 * @param string $table
	 * @author Glauber Costa
	 * @return array contendo as especificacoes de cada campo de uma tabela
	 */
	 function get_table_info($table){
		$field_names = $this->run_query("SHOW COLUMNS FROM `{$this->db}`.$table");
		$erro = $this->error_message($field_names);
		if(empty($erro)) {
			for($i= 0; $i < mysql_num_rows($field_names); $i++){
				$aux = mysql_fetch_assoc($field_names);
				$name = $aux[0];
				$field_array[] = $aux;
			}
			return $field_array;
		}
		else return 9;
	}	
	
	/**
	 * @name get_field_ref
	 * @abstract Retorna referencias das chaves estrangeiras
	 * @param string $table
	 * @param string $field
	 * @author Leonardo Weslei Diniz
	 * @return array com as informacoes : banco de dados de origem do campo,tabela de origem do campo,nome do campo,banco de dados de referencia do campo,tabela de referencia do campo,nome do campo de referencia do campo
	 */
	 function get_field_ref($db=false,$table,$field){
	 	$db=($db==false)?$this->db:$db;
		$query="
		SELECT
			ref.TABLE_SCHEMA db_o,
			ref.TABLE_NAME table_o,
			ref.COLUMN_NAME field_o,
			ref.REFERENCED_TABLE_SCHEMA db_r,
			ref.REFERENCED_TABLE_NAME table_r,
			ref.REFERENCED_COLUMN_NAME field_r
		FROM
			`information_schema`.`KEY_COLUMN_USAGE` as ref
		WHERE
			ref.TABLE_SCHEMA='{$db}'
			AND
			ref.TABLE_NAME='{$table}'
			AND
			ref.COLUMN_NAME='{$field}'";
		$res=$this->run_query($query);
		$i = 0;
		$info = array();
		while($result = mysql_fetch_array($res))
		{
			$info["db_o"]=$result['db_o'];
			$info["table_o"]=$result['table_o'];
			$info["field_o"]=$result['field_o'];
			$info["db_r"]=$result['db_r'];
			$info["table_r"]=$result['table_r'];
			$info["field_r"]=$result['field_r'];
		}
		return (mysql_num_rows($res)>0)?$info:array();
	}
	
	/**
	 * @name get_field_info
	 * @abstract Retorna informacoes sobre cada flag das tabelas
	 * @param string $table
	 * @param string $info
	 * @author Glauber Costa
	 * @return array com informacoes sobre os flags dos campos da tabela
	 */
	 function get_field_info($table,$info){
		$_field_info = $this->get_table_info($table);
		$_eField = $this->error_message($_field_info);
		$_info = array();
		if(empty($_eField)){
			for($i=0;$i< sizeof($_field_info);$i++)
				array_push($_info,$_field_info[$i][$info]);
			return $_info;
		}
		else return 10;
	}
		
	
	/**
	 * @name add_quote
	 * @abstract Adiciona aspas a valores em uma matriz ou variavel escalar
	 * @param array or string $value
	 * @author Glauber Costa
	 * @return array or string quoted
	 */
	 function add_quote($value){
		if(is_array($value)){
			foreach ($value as $position => $line){
				if($line!=NULL)$value[$position] = "\"".$line."\"";
				if($line==NULL)$value[$position] = "NULL";
			}
		}
		else{
			$value = "\"".$value."\"";		
		}
		return $value;
	}
	

	/**
	 * @name get_field_flag
	 * @abstract Busca por informacoes especificas de um campo
	 * @param string $table: Tabela onde reside o campo
	 * @param string $flag: Atributo do campo a ser buscado {Field,Type,Null,Key,Default,Extra}
	 * @param string $value: Valor a ser buscado em cada um dos atributos de flag
	 * @author Glauber Costa
	 * @return false se nao encontrado o valor ou a posicao caso o encontre
	 */
	 function get_field_flag($table,$flag,$value){
		$flag = $this->get_field_info($table,$flag);
		return array_search($value,$flag);
	}
	
	/**
	 * @name insert
	 * @abstract Insere registro na tabela
	 * @param string $table
	 * @param array $fields
	 * @param array $values
	 * @author Glauber Costa
	 * @return 1 se verdadeiro e 11 se falso
	 */
	 function insert($table,$values=array(),$fields=""){
		$field_info = $this->get_field_info($table,'Field');
		$_tError = $this->error_message($field_info);
		$values = $this->add_quote($values);
		$pos=$this->get_field_flag($table,"Extra","auto_increment");
		if((sizeof($values) != sizeof($field_info)) && $pos>=0){
			$aux_values = $values;
			$j=0;
			for($i=0;$i<sizeof($field_info)-1;$i++){
				if($j==$pos){
					$values[$j] = "NULL";
					$j++;
				}
				$values[$j]=$aux_values[$i];
				$j++;
			}
		}
		if(empty($_tError)){
			$_fields = (empty($fields)) ? $_fields = implode(",",$field_info) : $_fields = implode(",",$fields);
			$_values = implode(",",$values);
			$_qInsert = "insert into $table ($_fields) values($_values)";
			$_rInsert = $this->run_query($_qInsert);
			$_iError = $this->error_message($_rInsert,$_qInsert);
			echo $_iError;
			return (empty($_iError))?1:11;
		}
		else return 11;
	}
	
	/**
	 * @name delete
	 * @abstract Remove registros das tabelas que obedecam a condicao $condition ou todos se $condition for vazia
	 * @param string $table
	 * @param string $condition
	 * @author Glauber Costa
	 * @return 1 se excluido com sucesso ou 12 em caso de erro
	 */
	 function delete($table,$condition=""){
		$query = "delete from $table where ";
		$query .= (empty($condition))?"1":$condition;
		$_rDelete = $this->run_query($query);
		$_rError = $this->error_message($_rDelete,$query);
		return (empty($_rError))?true:12;
	}
	
	/**
	 * @name update
	 * @abstract Atualiza os campos $fields com os valores $values da tabela $table que atendam a condicao $condition
	 * @param string $table
	 * @param array $fields
	 * @param array $values
	 * @param string $condition
	 * @author Glauber Costa
	 * @return int
	 */
	 function update($table,$fields=array(),$values=array(),$condition){
	 	if(empty($fields) || empty($values) || (sizeof($fields) != sizeof($values))){
			return 0;
		}
		else {
			for($i=0;$i<sizeof($fields);$i++){
				if($values[$i]==NULL) $_fields_aux[$i] = $fields[$i]." = NULL ".$values[$i] ;
				if($values[$i]=="\"\"") $_fields_aux[$i] = $fields[$i]." = NULL" ;
				else $_fields_aux[$i] = $fields[$i]." = ".$values[$i] ;
				//echo $fields[$i]." = ".$values[$i]."<br>";
			}
		}
		$_fields= implode(",",$_fields_aux);
		$query = "update $table set $_fields where $condition";
		//echo "<br>".$query."<br>";
		$_rUpdate = $this->run_query($query);
		$_uError = $this->error_message($_rUpdate,$query);
		if(empty($_uError)) return 1;
		else return 0;
	}
	
	/**
	 * @name returned_rows
	 * @abstract Retorna o namero de registros afetados por uma consulta
	 * @param string $query: consulta a ser analisada
	 * @param resource $lnk_ident: [opcional] Identificador da conexao
	 * @author Glauber Costa
	 * @return int
	 */
	 function returned_rows($query,$lnk_ident=""){
		$run = (empty($lnk_ident))?$this->run_query($query):$this->run_query($query,$lnk_ident);
		$ret = $this->error_message($run,$query);
		return (empty($ret))?mysql_num_rows($run):$ret;
	}
	
	/**
	 * @name close_database
	 * @abstract Fecha uma conexao com o banco de dados
	 * @param resource $idDataBase Identificador da conexao
	 * @author Glauber Costa
	 * @return bool
	 */
	 function close_database(){
		$this->unset_db();
		mysql_close($this->connection);
	}
	
	/**
	 * @name mysql_version
	 * @abstract Retorna a versao do mysql
	 * @author Glauber Costa
	 * @return string
	 */
	 function mysql_version(){
		$version = mysql_fetch_row($this->run_query("select version()"));
		return $version[0];
	}
	
	 function createDB($dbname,$user="",$server="",$password=""){
		if(!empty($user) && !empty($server) && !empty($password)){
			$newDB = new DataBase($server,$user,$password);
			$connection = $newDB->connection;
		}
		else $connection = $this->connection;
		$_Create = $this->run_query(mysql_create_db($dbname,$connection));
		return $_Create?$dbname:$_Create;
	}
}
?>