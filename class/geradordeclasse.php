<?php
	require_once("class/database.php");
/**
 * Leonardo Weslei
 * @version 1.0 
 *
 */
	class geradordeclasse
	{
		var $user;
		var $password;
		var $host;
		var $db;
		var $table;
		var $project;
		var $author;
		var $servidor;
		var $pasta_to_save;
		var $info=false;
		function geradordeclasse($host,$user,$password,$db=false,$table=false,$project="Projeto criado pelo Leonardo Weslei",$author="Leonardo Weslei Diniz <leonardoweslei@gmail.com>",$pasta_to_save="class/"){
			$this->user			= $user;
			$this->password		= $password;
			$this->host			= $host;
			$this->db			= $db;
			$this->table		= $table;
			$this->project		= $project;
			$this->author		= $author;
			$this->pasta_to_save= $pasta_to_save."/";
			$this->servidor		= new DataBase
			(
				$this->host,
				$this->user,
				$this->password,
				$this->db
			);
		}
		
		function dbinfo($table=false)
		{
			if(!$this->info){
				$bd=$this->servidor->get_db_info();
				$content=array();
				foreach($bd as $i=>$value){
					$tmp=array();
					foreach($value as $j=>$field){
						$tmp[$field['Field']]=$field;
						if($field['Key']=="MUL"){
							$tmp[$field['Field']]["Ref"]=$this->servidor->get_field_ref($this->db,$i,$field['Field']);
						}
					}
					$content[$i]=$tmp;
				}
				$this->info=$bd=$content;
				return ($table)?(!isset($bd[$table])?array():$bd[$table]):$bd;
			}
			return $this->info;
		}
		function getfunctionget($table=false){
			$info=$this->dbinfo();
			$functions=array();
			foreach($info as $tablei=>$fields){
				$tmp=array();
				foreach($fields as $key=>$datafield){
					$tmp[]="
		/**
		 * Retorna o ".$key."
		 *
		 * @author Leonardo Weslei <leonardoweslei@gmail.com>
		 * @author ".$this->author."
		 * @since  ".date("d/m/Y")." ".date("H:i:s")."
		 * @final  ".date("d/m/Y")." ".date("H:i:s")."
		 * @package ".$this->project."
		 * @subpackage ".$tablei."
		 * @name get".$key."
		 * @version 1.0
		 * @return ".$datafield['Type']."
		 * @access public
		 */
		function get".$key."( )
		{
			return \$this->".$key.";
		} // end of member function get".$key."
";
				}
				$functions[$tablei]=$tmp;
			}
			$info=$functions;
			return ($table)?(!isset($info[$table])?array():$info[$table]):$info;
		}
		
		function getsetclass($table=""){
			$info=$this->dbinfo();
			$functions=array();
			foreach($info as $tablei=>$fields){
				$params=array();
				foreach($fields as $key=>$datafield){
					$cpt=explode(" ",$datafield["Type"]);
					$cpt=$cpt[0];
					if ($datafield['Key']=="MUL")$cpt="mixed (".$cpt." or object ".$datafield["Ref"]["table_r"].")";
					if ($datafield['Key']=="PRI")$cpt="mixed (".$cpt." or object ".$tablei.")";
					$params[]="
		 * @param ".$cpt." \$".$key."";
				}
				$params=implode("",$params);
				$tmp=array();
				$all=$this->getelementochave($tablei,"all");
				$paf=implode("=false,
			\$",$all);
				$id=$this->getelementochave($tablei,"PRI");
				$id=$id[0];
				$id=$fields[$id];
				$tid=explode(" ",$id["Type"]);
				$tid=$tid[0];
				$tid="mixed (object ".$tablei." ou ".$tid.")";
				$id=$id["Field"];
				$condid=$this->getifsattr("PRI",$tablei,"
			if(!empty(\$ATTRIBUTE_HERE))
			{
				if(is_object(\$ATTRIBUTE_HERE))
				{
					\$ATTRIBUTE_HERE=\$ATTRIBUTE_HERE->SUB_ATTRIBUTE_HERE;
				}
				\$this->ATTRIBUTE_HERE=\$ATTRIBUTE_HERE;
			}");
				$condid=implode("",$condid);
				$set=$this->getelementochave($tablei,"");
				$set=implode(",
				\$tmp->",$set);
				$set="
			return \$tmp->set".$tablei."(
				\$tmp->".$set."
			);";
				foreach($fields as $key=>$datafield){
					if ($datafield['Key']!="PRI" && $datafield['Key']!="MUL" ) {
						$tmp[]="
			if(!empty(\${$key}))
			{
				\$this->{$key}=\${$key};
			}";
					}
				}
				$ifp=$this->getifsattr("MUL",$tablei,"
			if(!empty(\$ATTRIBUTE_HERE))
			{
				if(is_object(\$ATTRIBUTE_HERE))
				{
					\$ATTRIBUTE_HERE=\$ATTRIBUTE_HERE->SUB_ATTRIBUTE_HERE;
				}
				\$this->ATTRIBUTE_HERE=\$ATTRIBUTE_HERE;
			}");
				$ifs=implode("",$ifp);
				$tmp[]=$ifs;
				$tmp=implode("",$tmp);
				$pabd=implode("\",
				\"",$all);
				$pabd2=implode(" .\"\\\"\",
				\"\\\"\". \$this->",$all);
				$tmp2="
			\$config	= new Configuracao();
			\$servidor = new DataBase
			(
				\$config->host,
				\$config->user,
				\$config->passwd,
				\$config->bd
			);
			\$campos = array
			(
				\"".$pabd."\"
			);
			\$valores = array
			(
				\"\\\"\". \$this->".$pabd2.".\"\\\"\"
			);
			\$condicao = \"".$id." = \".\"\\\"\".\$this->{$id}.\"\\\"\";
			if(\$servidor->update(\"`{$tablei}`\", \$campos, \$valores, \$condicao) == 1)
			{
				\$servidor->close_database();
				\$servidor = \"\";
				return 1;
			}else
			{
				\$servidor->close_database();
				\$servidor = \"\";
				return 0;
			}";
				$tmp="
		/**
		 * Altera o objeto ".$tablei."
		 *
		 * @author Leonardo Weslei
		 * @author ".$this->author."
		 * @since  ".date("d/m/Y")." ".date("H:i:s")."
		 * @final  ".date("d/m/Y")." ".date("H:i:s")."
		 * @package ".$this->project."
		 * @subpackage ".$tablei."
		 * @name set".$tablei."
		 * @version 1.0".$params."
		 * @return int
		 * @access public
		 */
		function set".$tablei."
		(
			\$".$paf."=false
		)
		{".$condid.$tmp.$tmp2."
		} // end of member function set".$tablei."
";
				$functions[$tablei]=$tmp;
			}
			$info=$functions;
			return ($table)?(!isset($info[$table])?array():$info[$table]):$info;
		}
		
		function getnewobjectclass($table=""){
			$info=$this->dbinfo();
			$functions=array();
			foreach($info as $tablei=>$fields){
				$params=array();
				foreach($fields as $key=>$datafield){
					$cpt=explode(" ",$datafield["Type"]);
					$cpt=$cpt[0];
					if ($datafield['Key']=="MUL")$cpt="mixed (".$cpt." or object ".$datafield["Ref"]["table_r"].")";
					$params[]="
		 * @param ".$cpt." \$".$key."";
				}
				$params=implode("",$params);
				$tmp=array();
				$all=$this->getelementochave($tablei,"all");
				$paf=implode("=false,
			\$",$all);
				$id=$this->getelementochave($tablei,"PRI");
				$id=$id[0];
				$id=$fields[$id];
				$tid=explode(" ",$id["Type"]);
				$tid=$tid[0];
				$tid="mixed (object ".$tablei." ou ".$tid.")";
				$id=$id["Field"];
				$condid=$this->getifsattr("PRI",$tablei,"
			if(!empty(\$ATTRIBUTE_HERE))
			{
				if(is_object(\$ATTRIBUTE_HERE))
				{
					\$ATTRIBUTE_HERE=\$ATTRIBUTE_HERE->SUB_ATTRIBUTE_HERE;
				}
				\$this->ATTRIBUTE_HERE=\$ATTRIBUTE_HERE;
			}");
				$condid=implode("",$condid);
				foreach($fields as $key=>$datafield){
					if ($datafield['Key']!="PRI" && $datafield['Key']!="MUL" ) {
						$tmp[]="
			if(!empty(\${$key}))
			{
				\$this->{$key}=\${$key};
			}";
					}
				}
				$ifp=$this->getifsattr("MUL",$tablei,"
			if(!empty(\$ATTRIBUTE_HERE))
			{
				if(is_object(\$ATTRIBUTE_HERE))
				{
					\$ATTRIBUTE_HERE=\$ATTRIBUTE_HERE->SUB_ATTRIBUTE_HERE;
				}
				\$this->ATTRIBUTE_HERE=\$ATTRIBUTE_HERE;
			}");
				$ifs=implode("",$ifp);
				$tmp[]=$ifs;
				$tmp=implode("",$tmp);
				$pabd=implode(",
				\$this->",$all);
				$tmp2="
			\$config	= new Configuracao();
			\$servidor = new DataBase
			(
				\$config->host,
				\$config->user,
				\$config->passwd,
				\$config->bd
			);
			\$insert = array
			(
				\$this->".$pabd."
			);
			\$ret2 = \$servidor->insert(\"`".$tablei."`\", \$insert);
			\$servidor = \"\";
			return mysql_insert_id();";
				$tmp="
		/**
		 * Cria um registro do objeto ".$tablei." no banco de dados
		 *
		 * @author Leonardo Weslei
		 * @author ".$this->author."
		 * @since  ".date("d/m/Y")." ".date("H:i:s")."
		 * @final  ".date("d/m/Y")." ".date("H:i:s")."
		 * @package ".$this->project."
		 * @subpackage ".$tablei."
		 * @name new".$tablei."
		 * @version 1.0".$params."
		 * @return int
		 * @access public
		 */
		function new".$tablei."
		(
			\$".$paf."=false
		)
		{".$condid.$tmp.$tmp2."
		} // end of member function new".$tablei."
";
				$functions[$tablei]=$tmp;
			}
			$info=$functions;
			return ($table)?(!isset($info[$table])?array():$info[$table]):$info;
		}
		
		function getconstructclass($table=""){
			$info=$this->dbinfo();
			$functions=array();
			foreach($info as $tablei=>$fields){
				$params=array();
				foreach($fields as $key=>$datafield){
					$cpt=explode(" ",$datafield["Type"]);
					$cpt=$cpt[0];
					if ($datafield['Key']=="MUL")$cpt="mixed (".$cpt." or object ".$datafield["Ref"]["table_r"].")";
					$params[]="
		 * @param ".$cpt." \$".$key."";
				}
				$params=implode("",$params);
				$tmp=array();
				$all=$this->getelementochave($tablei,"all");
				$paf=implode("=false,
			\$",$all);
				$parac=implode(",
				\$",$all);
				$id=$this->getelementochave($tablei,"PRI");
				$id=$id[0];
				$id=$fields[$id];
				$tid=explode(" ",$id["Type"]);
				$tid=$tid[0];
				$tid="mixed (object ".$tablei." ou ".$tid.")";
				$id=$id["Field"];
				$condid=$this->getifsattr("PRI",$tablei,"
			if(!empty(\$ATTRIBUTE_HERE))
			{
				if(is_object(\$ATTRIBUTE_HERE))
				{
					\$ATTRIBUTE_HERE=\$ATTRIBUTE_HERE->SUB_ATTRIBUTE_HERE;
				}
				\$this->ATTRIBUTE_HERE=\$ATTRIBUTE_HERE;
			}");
				$condid=implode("",$condid);
				foreach($fields as $key=>$datafield){
					if ($datafield['Key']!="PRI" && $datafield['Key']!="MUL" ) {
						$tmp[]="
			if(!empty(\${$key}))
			{
				\$this->{$key}=\${$key};
			}";
					}
				}
				$ifp=$this->getifsattr("MUL",$tablei,"
			if(!empty(\$ATTRIBUTE_HERE))
			{
				if(is_object(\$ATTRIBUTE_HERE))
				{
					\$ATTRIBUTE_HERE=\$ATTRIBUTE_HERE->SUB_ATTRIBUTE_HERE;
				}
				\$this->ATTRIBUTE_HERE=\$ATTRIBUTE_HERE;
			}");
				$ifs=implode("",$ifp);
				$tmp[]=$ifs;
				$tmp=implode("",$tmp);
				$pabd=implode(",
				\$this->",$all);
				$tmpx="
		/**
		 * Construtor PHP5
		 *
		 * @author Leonardo Weslei
		 * @author ".$this->author."
		 * @since  ".date("d/m/Y")." ".date("H:i:s")."
		 * @final  ".date("d/m/Y")." ".date("H:i:s")."
		 * @package ".$this->project."
		 * @subpackage ".$tablei."
		 * @name __construct
		 * @version 1.0".$params."
		 * @access public
		 */
		function __construct
		(
			\$".$paf."=false
		)
		{
			\$this->{$tablei}(
				\$".$parac."
			);
		} // end of member function __construct
";
				$tmp="
		/**
		 * Construtor
		 * Instancia o objeto ".$tablei." e preenche seus atributos caso os mesmos possua um valor
		 *
		 * @author Leonardo Weslei
		 * @author ".$this->author."
		 * @since  ".date("d/m/Y")." ".date("H:i:s")."
		 * @final  ".date("d/m/Y")." ".date("H:i:s")."
		 * @package ".$this->project."
		 * @subpackage ".$tablei."
		 * @name ".$tablei."
		 * @version 1.0".$params."
		 * @access public
		 */
		function ".$tablei."
		(
			\$".$paf."=false
		)
		{".$condid.$tmp."
		} // end of member function ".$tablei."
";
				$functions[$tablei]=$tmpx.$tmp;
			}
			$info=$functions;
			return ($table)?(!isset($info[$table])?array():$info[$table]):$info;
		}
		
		function getfunctiondel($table=false){
			$info=$this->dbinfo();
			$functions=array();
			foreach($info as $tablei=>$fields){
				$tmp=array();
				$id=$this->getelementochave($tablei);
				$id=$id[0];
				$id=$fields[$id];
				$tid=explode(" ",$id["Type"]);
				$tid=$tid[0];
				$tid="mixed (object ".$tablei." ou ".$tid.")";
				$id=$id["Field"];
				$condid=$this->getifsattr("PRI",$tablei,"
			if(!empty(\$ATTRIBUTE_HERE))
			{
				if(is_object(\$ATTRIBUTE_HERE))
				{
					\$ATTRIBUTE_HERE=\$ATTRIBUTE_HERE->SUB_ATTRIBUTE_HERE;
				}
				\$tmp=new ".$tablei."(\$ATTRIBUTE_HERE);
			}else
			{
				\$tmp=new ".$tablei."(\$this->".$id.");
			}");
				$condid=implode("",$condid);
				$tmp[]="
		/**
		 * Deleta {$tablei} de acordo com a {$id} recebida
		 * 
		 * @author Leonardo Weslei
		 * @author ".$this->author."
		 * @since  ".date("d/m/Y")." ".date("H:i:s")."
		 * @final  ".date("d/m/Y")." ".date("H:i:s")."
		 * @package ".$this->project."
		 * @subpackage ".$tablei."
		 * @name del".$key."
		 * @version 1.0
		 * @param ".$tid." \$".$id."
		 * @return int
		 * @access public
		 */
		function del{$tablei}
		(
			\${$id} = false
		)
		{{$condid}
			\$config = new Configuracao();
			\$servidor = new DataBase
			(
				\$config->host,
				\$config->user,
				\$config->passwd,
				\$config->bd
			);
			\$condicao = \"{$id} = \\\"\".\$tmp->{$id}.\"\\\"\";
			return \$servidor->delete(\"`{$tablei}`\", \$condicao);
			\$servidor->close_database();
		} // end of member function del{$tablei}";
				$functions[$tablei]=$tmp;
			}
			$info=$functions;
			return ($table)?(!isset($info[$table])?array():$info[$table]):$info;
		}
		
		function getfunctionset($table=false){
			$info=$this->dbinfo();
			$functions=array();
			foreach($info as $tablei=>$fields){
				$tmp=array();
				$id=$this->getelementochave($tablei);
				$id=$id[0];
				$id=$fields[$id];
				$tid=explode(" ",$id["Type"]);
				$tid=$tid[0];
				$tid="mixed (object ".$tablei." ou ".$tid.")";
				$id=$id["Field"];
				$condid=$this->getifsattr("PRI",$tablei,"
			if(!empty(\$ATTRIBUTE_HERE))
			{
				if(is_object(\$ATTRIBUTE_HERE))
				{
					\$ATTRIBUTE_HERE=\$ATTRIBUTE_HERE->SUB_ATTRIBUTE_HERE;
				}
				\$tmp=new ".$tablei."(\$ATTRIBUTE_HERE);
				\$tmp->search".$tablei."forkey(\$this->ATTRIBUTE_HERE);
			}else
			{
				\$tmp=new ".$tablei."(\$this->".$id.");
				\$tmp->search".$tablei."forkey(\$this->ATTRIBUTE_HERE);
			}");
				$condid=implode("",$condid);
				$set=$this->getelementochave($tablei,"");
				$set=implode(",
				\$tmp->",$set);
				$set="
			return \$tmp->set".$tablei."(
				\$tmp->".$set."
			);";
				foreach($fields as $key=>$datafield){
					$cpt=explode(" ",$datafield["Type"]);
					$cpt=$cpt[0];
					if ($datafield['Key']=="MUL") {
						$ifp=$this->getifsattr("MUL",$tablei,"
			if(is_object(\$ATTRIBUTE_HERE))
			{
				\$ATTRIBUTE_HERE=\$ATTRIBUTE_HERE->SUB_ATTRIBUTE_HERE;
			}");
						$ifs=$ifp[$key];
						$ifs=$condid.$ifs;
						$cpt="mixed (".$cpt." or object ".$datafield["Ref"]["table_r"].")";
					}else{
						$ifs=$condid;
					}
					if ($datafield['Key']!="PRI"){
						$tmp[]="
		/**
		 * Altera o ".$key."
		 *
		 * @author Leonardo Weslei
		 * @author ".$this->author."
		 * @since  ".date("d/m/Y")." ".date("H:i:s")."
		 * @final  ".date("d/m/Y")." ".date("H:i:s")."
		 * @package ".$this->project."
		 * @subpackage ".$tablei."
		 * @name set".$key."
		 * @version 1.0
		 * @param ".$cpt." \$".$key."
		 * @param ".$tid." \$".$id."
		 * @return int
		 * @access public
		 */
		function set".$key."
		(
			\$".$key.",
			\$".$id."=false
		)
		{".$ifs."
			\$tmp->".$key."=\$".$key.";".$set."
		} // end of member function set".$key."
";
					}
				}
				$functions[$tablei]=$tmp;
			}
			$info=$functions;
			return ($table)?(!isset($info[$table])?array():$info[$table]):$info;
		}
		
		function getiniclass($table=false){
			$info=$this->dbinfo();
			$functions=array();
			foreach($info as $tablei=>$fields){
				$params=array();
				foreach($fields as $key=>$datafield){
					$cpt=explode(" ",$datafield["Type"]);
					$cpt=$cpt[0];
					$params[]="
		/**
		 * {$key} da tabela {$tablei} do banco de dados
		 *
		 * @var ".$cpt." \$".$key."
		 */
		var \$".$key."=NULL;";
				}
				
				$ifp=$this->getifsattr("MUL",$tablei,"
	conf_require(\"/class/ATTRIBUTE_T_HERE.php\");");
				$params=implode("",$params);
				$ifp2="
	require_once(\"../conf.php\");";
				$ifp2="
	conf_require(\"/class/database.php\");";
				$ifp2.="
	conf_require(\"/class/configuracao.php\");";
				$ifp=$ifp2.implode("",$ifp);
				$tmp=array();
				$all=$this->getelementochave($tablei,"all");
				$tmp=$ifp."
	/**
	 * class {$tablei}
	 * Classe Basica para lidar com a tebela {$tablei} do bandco de dados
	 *
	 * @author Leonardo Weslei
	 * @author ".$this->author."
	 * @since  ".date("d/m/Y")." ".date("H:i:s")."
	 * @final  ".date("d/m/Y")." ".date("H:i:s")."
	 * @package ".$this->project."
	 * @subpackage ".$tablei."
	 * @name ".$tablei."
	 * @version 1.0
	 */
	class ".$tablei."
	{
		/*** Attributes: ***/".$params;
				$functions[$tablei]=$tmp;
			}
			$info=$functions;
			return ($table)?(!isset($info[$table])?array():$info[$table]):$info;
		}
		
		function getfinishclass($table=false){
			$info=$this->dbinfo();
			$functions=array();
			foreach($info as $tablei=>$fields){
				$params=array();
				$tmp="
	} // end of class ".$tablei."
";
				$functions[$tablei]=$tmp;
			}
			$info=$functions;
			return ($table)?(!isset($info[$table])?array():$info[$table]):$info;
		}
		
		function getfunctionbuscaid($table=false){
			$info=$this->dbinfo();
			$functions=array();
			foreach($info as $tablei=>$fields){
				$tmp=array();
				$id=$this->getelementochave($tablei);
				$id=$id[0];
				$params=array();
				$cpt=explode(" ",$fields[$id]["Type"]);
				$cpt=$cpt[0];
				$params[]="
		 * @param ".$cpt." \$".$id."";
				$params=implode("",$params);
				$id=$fields[$id];
				$tid=explode(" ",$id["Type"]);
				$tid=$tid[0];
				$tid="mixed (object ".$tablei." ou ".$tid.")";
				$id=$id["Field"];
				$ifp2=$this->getifsattr("MUL",$tablei,"
				if(\$attribute==\"ATTRIBUTE_HERE\" && \$persistencia!=false)
				{
					\$tmp_ATTRIBUTE_HERE= new ATTRIBUTE_T_HERE(\$resultado[\$attribute]);
					\$this->\$attribute = \$tmp_ATTRIBUTE_HERE->searchATTRIBUTE_T_HEREforkey(\$resultado[\$attribute]);
					\$tmp->ATTRIBUTE_HERE= \$tmp_ATTRIBUTE_HERE;
				}");
				$ifp2=implode("",$ifp2);
				$condid=$this->getifsattr("PRI",$tablei,"
			if(!empty(\$ATTRIBUTE_HERE))
			{
				if(is_object(\$ATTRIBUTE_HERE))
				{
					\$ATTRIBUTE_HERE=\$ATTRIBUTE_HERE->SUB_ATTRIBUTE_HERE;
				}
				\$tmp->ATTRIBUTE_HERE=\$ATTRIBUTE_HERE;
			}");
				$condid=implode("",$condid);
				$tmp2="
			\$config	= new Configuracao();
			\$servidor = new DataBase
			(
				\$config->host,
				\$config->user,
				\$config->passwd,
				\$config->bd
			);
			\$resultado = mysql_fetch_array
			(
				\$servidor->run_query
				(
					\"SELECT
						*
					FROM
						`".$tablei."`
					WHERE
						`".$id."`='\".\$this->".$id.".\"'\"
				)
			);
			\$class_vars = get_class_vars(get_class(\$this));
			foreach (\$class_vars as \$attribute => \$value)
			{
				\$this->\$attribute = \$resultado[\$attribute];".$ifp2."
			}
			\$servidor = \"\";";
				$tmp="
		/**
		 * Pesquisa de objetos ".$tablei." no banco de dados pela \$".$id."
		 *
		 * @author Leonardo Weslei
		 * @author ".$this->author."
		 * @since  ".date("d/m/Y")." ".date("H:i:s")."
		 * @final  ".date("d/m/Y")." ".date("H:i:s")."
		 * @package ".$this->project."
		 * @subpackage ".$tablei."
		 * @name search".$tablei."forkey
		 * @version 1.0".$params."
		 * @param bolleam \$persistencia
		 * @return object ".$tablei."
		 * @access public
		 */
		function search".$tablei."forkey
		(
			\$".$id."=false,
			\$persistencia=false
		)
		{
			\$tmp=new $tablei();".$condid.$tmp2."
		} // end of member function search".$tablei."forkey
";
				$functions[$tablei]=$tmp;
			}
			$info=$functions;
			return ($table)?(!isset($info[$table])?array():$info[$table]):$info;
		}
		function getfunctionbusca($table=false){
			$info=$this->dbinfo();
			$functions=array();
			foreach($info as $tablei=>$fields){
				$params=array();
				foreach($fields as $key=>$datafield){
					$cpt=explode(" ",$datafield["Type"]);
					$cpt=$cpt[0];
					if ($datafield['Key']=="MUL")$cpt="mixed (".$cpt." or object ".$datafield["Ref"]["table_r"].")";
					if ($datafield['Key']=="PRI")$cpt="mixed (".$cpt." or object ".$tablei.")";
					$params[]="
		 * @param ".$cpt." \$".$key."";
				}
				$tmp=array();
				$all=$this->getelementochave($tablei,"all");
				$paf=implode("=false,
			\$",$all);
				$id=$this->getelementochave($tablei);
				$id=$id[0];
				$cpt=explode(" ",$fields[$id]["Type"]);
				$cpt=$cpt[0];
				$params=implode("",$params);
				$id=$fields[$id];
				$tid=explode(" ",$id["Type"]);
				$tid=$tid[0];
				$tid="mixed (object ".$tablei." ou ".$tid.")";
				$id=$id["Field"];
				$condid=$this->getifsattr("PRI",$tablei,"
			if(!empty(\$ATTRIBUTE_HERE))
			{
				if(is_object(\$ATTRIBUTE_HERE))
				{
					\$ATTRIBUTE_HERE=\$ATTRIBUTE_HERE->SUB_ATTRIBUTE_HERE;
				}
				\$this->ATTRIBUTE_HERE=\$ATTRIBUTE_HERE;
			}");
				$condid=implode("",$condid);
				foreach($fields as $key=>$datafield){
					if ($datafield['Key']!="PRI" && $datafield['Key']!="MUL" ) {
						$tmp[]="
			if(!empty(\${$key}))
			{
				\$this->{$key}=\${$key};
			}";
					}
				}
				$ifp=$this->getifsattr("MUL",$tablei,"
			if(!empty(\$ATTRIBUTE_HERE))
			{
				if(is_object(\$ATTRIBUTE_HERE))
				{
					\$ATTRIBUTE_HERE=\$ATTRIBUTE_HERE->SUB_ATTRIBUTE_HERE;
				}
				\$this->ATTRIBUTE_HERE=\$ATTRIBUTE_HERE;
			}");
				$ifs=implode("",$ifp);
				$tmp[]=$ifs;
				$tmp=implode("",$tmp);
				$pabd=implode(",
				\$this->",$all);
				
				$ifp2=$this->getifsattr("MUL",$tablei,"
					if(\$attribute==\"ATTRIBUTE_HERE\" && \$persistencia!=false)
					{
						\$tmp_ATTRIBUTE_HERE= new ATTRIBUTE_T_HERE(\$NumRes[\$attribute]);
						\$tmp->\$attribute = \$tmp_ATTRIBUTE_HERE->searchATTRIBUTE_T_HEREforkey(\$NumRes[\$attribute]);
					\$tmp->ATTRIBUTE_HERE= \$tmp_ATTRIBUTE_HERE;
					}");
				$ifp2=implode("",$ifp2);
				$tmp2="
			\$config	= new Configuracao();
			\$servidor = new DataBase
			(
				\$config->host,
				\$config->user,
				\$config->passwd,
				\$config->bd
			);
			\$likes = array();
			\$class_vars = get_class_vars(get_class(\$this));
			\$i = 0;
			foreach (\$class_vars as \$attribute => \$value)
			{
				if(!empty(\$this->\$attribute))
				{
					\$likes[\$i] = \$attribute.\" like	'%\".\$this->\$attribute.\"%' \";
					\$i++;
				}
			}
			if(count(\$likes)>0)
			{
				\$_likes = implode(\" AND \", \$likes);
				\$query = \"
					SELECT
						*
					FROM
						`{$tablei}`
					WHERE
						\".\$_likes;
			}else
			{
				\$query = \"
					SELECT
						*
					FROM
						`{$tablei}`
				\";
			}
			\$query .= (strlen(\$order)>0)?\"
					ORDER BY
						\".\$order:\"\";
			\$query .= (strlen(\$limit)>0)?\"
					Limit
						\".\$limit:\"\";
			\$resultado = \$servidor->run_query(\$query);
			\$retorno=array();
			\$i = 0;
			while(\$NumRes = mysql_fetch_array(\$resultado))
			{
				\$tmp = new {$tablei}();
				\$class_vars = get_class_vars(get_class(\$tmp));
				foreach (\$class_vars as \$attribute => \$value)
				{
					\$tmp->\$attribute = \$NumRes[\$attribute];".$ifp2."
				}
				\$retorno[\$i] = \$tmp;
				\$i++;
				}
			\$servidor = \"\";
			return \$retorno;";
				$tmp="
		/**
		 * Pesquisa de objetos ".$tablei." no banco de dados pelos seus dados diferentes de vazio
		 *
		 * @author Leonardo Weslei
		 * @author ".$this->author."
		 * @since  ".date("d/m/Y")." ".date("H:i:s")."
		 * @final  ".date("d/m/Y")." ".date("H:i:s")."
		 * @package ".$this->project."
		 * @subpackage ".$tablei."
		 * @name search".$tablei."
		 * @version 1.0".$params."
		 * @param string \$order
		 * @param string \$limit
		 * @param bolleam \$persistencia
		 * @return array object ".$tablei."
		 * @access public
		 */
		function search".$tablei."
		(
			\$".$paf."=false,
			\$order=\"\",
			\$limit=\"\",
			\$persistencia=false
		)
		{".$condid.$tmp.$tmp2."
		} // end of member function search".$tablei."
";
				$functions[$tablei]=$tmp;
			}
			$info=$functions;
			return ($table)?(!isset($info[$table])?array():$info[$table]):$info;
		}
		
		function getelementochave($table=false,$mod="PRI"){
			$info=$this->dbinfo();
			$functions=array();
			foreach($info as $tablei=>$fields){
				$tmp=array();
				foreach($fields as $key=>$datafield){
					if ($datafield['Key']=="PRI" && $mod=="PRI") {
						$tmp[]=$key;
					}
					if ($datafield['Key']=="MUL"&& $mod=="MUL") {
						$tmp[]=$key;
					}else {
					$tmp[]=$key;
					}
				}
				$functions[$tablei]=$tmp;
			}
			$info=$functions;
			return ($table)?(!isset($info[$table])?array():$info[$table]):$info;
		}
		
		function gettelementochave($mod="",$table=false){
			$info=$this->dbinfo();
			$functions=array();
			foreach($info as $tablei=>$fields){
				$tmp=array();
				foreach($fields as $key=>$datafield){
					if(strlen($mod)>0){
						if ($datafield['Key']==$mod) {
							$tmp[]="\$this->".$key;
						}
					}else{
						$tmp[]="\$this->".$key;
					}
				}
				$functions[$tablei]=$tmp;
			}
			$info=$functions;
			return ($table)?(!isset($info[$table])?array():$info[$table]):$info;
		}
		
		function getifsattr($mod="",$table=false,$cond_r,$subatrp=false,$table_rp=false){
			$info=$this->dbinfo();
			$functions=array();
			foreach($info as $tablei=>$fields){
				$tmp=array();
				foreach($fields as $key=>$datafield){
					if(strlen($mod)>0){
						if ($datafield['Key']=="PRI" && $mod=="PRI") {
							$subkey=($subatrp)?$subatrp:$datafield['Field'];
							$tmp[$key]=str_replace("ATTRIBUTE_HERE",$key,str_replace("SUB_ATTRIBUTE_HERE",$subkey,$cond_r));
						}
						if ($datafield['Key']=="MUL" && $mod=="MUL"){
							$subkey=($subatrp)?$subatrp:$datafield['Ref']["field_r"];
							$table_r=($table_rp)?$table_rp:$datafield['Ref']["table_r"];
							$tmp[$key]=str_replace("ATTRIBUTE_HERE",$key,
								str_replace("SUB_ATTRIBUTE_HERE",$subkey,
									str_replace("ATTRIBUTE_T_HERE",$table_r,$cond_r)
								)
							);
						}
					}else{
						$subkey=($subatrp)?$subatr:$key;
						$tmp[$key]=str_replace("ATTRIBUTE_HERE",$key,str_replace("SUB_ATTRIBUTE_HERE",$subkey,$cond_r));
					}
				}
				$functions[$tablei]=$tmp;
			}
			$info=$functions;
			return ($table)?(!isset($info[$table])?array():$info[$table]):$info;
		}
		
		function getclass($table=""){
			$ar=array();
			$ar1=array();
			$ar2=array();
			$ar3=array();
			$ar4=array();
			$ar5=array();
			$ar6=array();
			$ar7=array();
			$ar8=array();
			$ar9=array();
			if(strlen($table)>0){
				$ar[$table]=$this->getfinishclass($table);
				$ar1[$table]=$this->getfunctionset($table);
				$ar2[$table]=$this->getfunctionget($table);
				$ar3[$table]=$this->getsetclass($table);
				$ar4[$table]=$this->getnewobjectclass($table);
				$ar5[$table]=$this->getfunctionbuscaid($table);
				$ar6[$table]=$this->getfunctiondel($table);
				$ar7[$table]=$this->getfunctionbusca($table);
				$ar8[$table]=$this->getconstructclass($table);
				$ar9[$table]=$this->getiniclass($table);
			}else{
				$ar1=$this->getfunctionset($table);
				$ar2=$this->getfunctionget($table);
				$ar=$this->getfinishclass($table);
				$ar3=$this->getsetclass($table);
				$ar4=$this->getnewobjectclass($table);
				$ar5=$this->getfunctionbuscaid($table);
				$ar6=$this->getfunctiondel($table);
				$ar7=$this->getfunctionbusca($table);
				$ar8=$this->getconstructclass($table);
				$ar9=$this->getiniclass($table);
			}
			$retorno=array();
			$retorno=array_merge_recursive($ar,$retorno);
			$retorno=array_merge_recursive($ar1,$retorno);
			$retorno=array_merge_recursive($ar2,$retorno);
			$retorno=array_merge_recursive($ar3,$retorno);
			$retorno=array_merge_recursive($ar4,$retorno);
			$retorno=array_merge_recursive($ar5,$retorno);
			$retorno=array_merge_recursive($ar6,$retorno);
			$retorno=array_merge_recursive($ar7,$retorno);
			$retorno=array_merge_recursive($ar8,$retorno);
			$retorno=array_merge_recursive($ar9,$retorno);
			if(strlen($table)>0){
				$retorno=(
					isset($retorno[$table])
						&&
					is_array($retorno[$table])
				)
				?implode("",$retorno[$table]):implode("",$retorno);
			}else{
				$tmp=array();
				foreach ($retorno as $class =>$arrai){
					$tmp[$class]=implode("",$arrai);
				}
				$retorno=$tmp;
			}
			return $retorno;
		}
		
		function grava($table=false){
			if($table!=false){
				$content=$this->getclass($table);
				$content=is_string($content)?$content:$content[$table];
				exec("mkdir -p ".$this->pasta_to_save."/class");
				exec("chmod 777 ".$this->pasta_to_save."/class");
				if($fp=fopen($this->pasta_to_save."/class/".$table.".php","w+")){
					if(fwrite($fp,"<?php
".$content."
?>")){
						fclose($fp);
						exec("chmod 777 ".$this->pasta_to_save."/class/".$table.".php");
						return 0;
					}
					return 1;
				}
				return 1;
			}else{
				$retorno=$this->getclass();
				$x=0;
				foreach ($retorno as $class =>$content){
					exec("mkdir -p ".$this->pasta_to_save."/class");
					exec("chmod 777 ".$this->pasta_to_save."/class");
					if($fp=fopen($this->pasta_to_save."/class/".$class.".php","w+")){
						if(fwrite($fp,"<?php
".$content."
?>")){
							fclose($fp);
							exec("chmod 777 ".$this->pasta_to_save."/class/".$class.".php");
						}else $x+=1;
					}else $x+=1;
				}
				if($x>0) return $x;
				else return 0;
			}
		}
	}
/*$cr=new geradordeclasse("localhost","user","senha","banco",false,"NOME DO PROJETO","AUTOR <autor@dominio.com>","/pasta para salvar os dados");
echo "<pre>";
print_r($cr->grava());
echo "<pre>";*/
?>
