<?php
	//require_once("class/database.php");
ini_set('max_execution_time', 900); // Onde 60 é o tempo em segundos
	require_once("database.php");
/**
 * Gerador de Classes
 * 
 * @version 2.0 
 *
 */
	class generic
	{
		/**
		 * Usuario do banco de dados
		 *
		 * @var string
		 */
		var $usuario					= 'root';
		/**
		 * senha do banco de dados
		 *
		 * @var string
		 */
		var $senha						= '123';
		/**
		 * endereco do banco de dados
		 *
		 * @var string
		 */
		var $endereco					= 'localhost';
		/**
		 * nome do banco de dados
		 *
		 * @var unknown_type
		 */
		var $bancodedados				= '';
		/**
		 * tabela do banco de dados
		 *
		 * @var unknown_type
		 */
		var $tabela						= '';
		/**
		 * Nome do projeto a ser criado
		 *
		 * @var string
		 */
		var $nome_do_projeto			= '__DB__ - Projeto criado pelo gerador de classes';
		/**
		 * Array que armazena todos os autores do projeto
		 *
		 * @var array
		 */
		var $autores					= array("Leonardo Weslei Diniz <leonardoweslei@gmail.com>");
		/**
		 * Objeto que interage com o banco de dados
		 *
		 * @var resource
		 */
		var $servidor					= false;
		/**
		 * Pasta para salvar as classes geradas
		 *
		 * @var string
		 */
		var $pasta_para_salvar_dados	= 'class/';
		/**
		 * Array que possui todas as informações resgatadas do banco de dados
		 *
		 * @var mixed
		 */
		var $informacao_do_banco						= false;
		/**
		 * Array que possui todas as informações ja geradas 
		 *
		 * @var mixed
		 */
		var $informacao_gerada							= array();
		/**
		 * Construtor do gerador
		 * Copia os parametros recebidos para os atributos do objeto
		 *
		 * @param string $endereco
		 * @param string $usuario
		 * @param string $senha
		 * @param string $bancodedados
		 * @param string $tabela
		 * @param string $nome_do_projeto
		 * @param array  $autores
		 * @param string $pasta_para_salvar_dados
		 * @return generic
		 */
		function generic
		(
			$endereco,
			$usuario,
			$senha,
			$bancodedados,
			$tabela						= false,
			$nome_do_projeto			= false,
			$autores					= false,
			$pasta_para_salvar_dados	= false
		)
		{
			$this->usuario					= $usuario;
			$this->senha					= $senha;
			$this->endereco					= $endereco;
			$this->bancodedados				= $bancodedados;
			$this->tabela					= self::is_val($tabela)?$tabela:false;
			$this->nome_do_projeto			= self::is_val($nome_do_projeto,$this->nome_do_projeto);
			$this->autores					= self::is_val($autores,$this->autores);
			$this->pasta_para_salvar_dados	= self::is_val($pasta_para_salvar_dados."/",$this->pasta_para_salvar_dados);
			if
			(
				self::is_val($this->endereco)
				&&
				self::is_val($this->usuario)
				&&
				self::is_val($this->senha)
				&&
				self::is_val($this->bancodedados)
			){
				$this->servidor					= new DataBase
				(
					$this->endereco,
					$this->usuario,
					$this->senha,
					$this->bancodedados
				);
			}
			else{
				$this->servidor=false;
			}
		}
		
		/**
		 * Verifica se o valor e valido.
		 * Se for valido, retorna $valor do contrario retorna $valo2 
		 *
		 * @param mixed $valor
		 * @param mixed $valo2
		 * @return mixed
		 */
		function is_val($valor,$valo2=false){
			return 
			(
				$valo2==false?
				($valor!=false && $valor!=NULL && !empty($valor) && $valor!=""):
				$valo2
			);
		}
		
		/**
		 * Normaliza as informacoes recebidas do banco de dados e coloca em $this->informacao_do_banco
		 *
		 * @param string $table
		 * @return array
		 */
		function pega_informacao_do_banco_de_dados
		(
			$table=false
		)
		{
			$table=(($table==false)?$this->tabela:$table);
			if(!$this->informacao_do_banco)//verifica se ja pegou informacoes anteriormente
			{
				$informacao_do_banco				= $this->servidor->get_db_info();
				$informacao_do_banco_atualizada		= array();
				foreach($informacao_do_banco as $tabela=>$campo_da_tabela)//array onde o indice e o nome da tabela e os values um array  com os campos
				{
					if(is_array($campo_da_tabela))
					{
						$campos	= array();
						foreach($campo_da_tabela as $dado=>$valor)//array onde o indice e um numero e o values dados do campo, por isso a necessidade de atualizar os dados
						{
							$campos[$valor['Field']]=$valor;
							if($valor['Key']=="MUL")
							{
								$campos[$valor['Field']]["Ref"]=$this->servidor->get_field_ref($this->bancodedados,$tabela,$valor['Field']);//pegando referencia dos campos estrangeiros das chaves estrangeiras
							}
						}
						$informacao_do_banco_atualizada[$tabela]=$campos;
					}
				}
				$this->informacao_do_banco=$informacao_do_banco=$informacao_do_banco_atualizada;
				return 
				(
					($table!=false)
					?
						(
							!isset($informacao_do_banco[$table])
							?
								array()
							:
								array($table=>$informacao_do_banco[$table])
						)
					:
						$informacao_do_banco
				);
			}
			$informacao_do_banco=$this->informacao_do_banco;
			return 
			(
				($table!=false)
				?
					(
						!isset($informacao_do_banco[$table])
						?
							array()
						:
							array($table=>$informacao_do_banco[$table])
					)
				:
					$informacao_do_banco
			);
		}
		
		/**
		 * Pega todos os campos de acordo com o seu tipo(PRI-chaves primarias,MUL-chaves estrangeiras,ALL-todos os campos)
		 *
		 * @param string $table
		 * @param string $tipo
		 * @return array
		 */
		function pega_campos_por_tipo
		(
			$table	= false,
			$tipo	= "PRI"
		)
		{
			$table=(($table==false)?$this->tabela:$table);
			$informacao		= $this->pega_informacao_do_banco_de_dados();
			$campos_chave	= array();
			foreach($informacao as $tabela=>$campos)
			{
				$tmp	= array();
				foreach($campos as $nome_do_campo=>$dados_do_campo)
				{
					if ($dados_do_campo['Key']=="PRI" && $tipo=="PRI")
					{
						$tmp[]	= $nome_do_campo;
					}elseif($dados_do_campo['Key']=="MUL"&& $tipo=="MUL")
					{
						$tmp[]	= $nome_do_campo;
					}elseif($tipo!="MUL" && $tipo!="PRI")
					{
						$tmp[]	= $nome_do_campo;
					}
				}
				$campos_chave[$tabela]=$tmp;
			}
			$informacao=$campos_chave;
			return 
			(
				($table!=false)
				?
					(
						!isset($informacao[$table])
						?
							array()
						:
							array($table=>$informacao[$table])
					)
				:
					$informacao
			);
		}
		
		/**
		 * Substitui dependencias em codigo.
		 * Retorna blocos de codigos de acordo com o tipo selecionado
		 *
		 * @param string $tipo
		 * @param string $table
		 * @param string $bloco_de_codigo
		 * @param string $chave_estrangeira
		 * @param string $tabela_da_chave_estrangeira
		 * @return array
		 */
		function substitui_dependencias
		(
			$tipo							= "",
			$table							= false,
			$bloco_de_codigo					,
			$chave_estrangeira				= false,
			$tabela_da_chave_estrangeira	= false,
			$chave_do_array	= false
		)
		{
			$table=(($table==false)?$this->tabela:$table);
			$informacao=$this->pega_informacao_do_banco_de_dados($table);
			$codigos=array();
			$contador_e=0;
			foreach($informacao as $tabela=>$campos)
			{
				$tmp=array();
				$contador_l=0;
				foreach($campos as $nome_do_campo=>$dados_do_campo)
				{
					
					if(strlen($tipo)>0 && ($tipo=="PRI" || $tipo=="MUL"))
					{
						if ($dados_do_campo['Key']=="PRI" && $tipo=="PRI")
						{
							
							$chave_estrangeira		= 
							(
									($chave_estrangeira)
								?
									$chave_estrangeira
								:
									$dados_do_campo['Field']
							);
							$tmp[$nome_do_campo]	= str_replace
							(
								"__ATRIBUTO__",
								$nome_do_campo,
								str_replace
								(
									"__ATRIBUTO_ESTRANGEIRO__",
									$chave_estrangeira,
									$bloco_de_codigo
								)
							);
						}
						if ($dados_do_campo['Key']=="MUL" && $tipo=="MUL")
						{
							$chave_estrangeira		=
							(
									($chave_estrangeira)
								?
									$chave_estrangeira
								:
									$dados_do_campo['Ref']["field_r"]
							);
							$tabela_da_chave_estrangeira= 
							(
									($tabela_da_chave_estrangeira)
								?
									$tabela_da_chave_estrangeira
								:
									$dados_do_campo['Ref']["table_r"]
							);
							$tmp[$nome_do_campo]	= str_replace
							(
								"__ATRIBUTO__",
								$nome_do_campo,
								str_replace
								(
									"__ATRIBUTO_ESTRANGEIRO__",
									$chave_estrangeira,
									str_replace
									(
										"__TABELA_DO_ATRIBUTO_ESTRANGEIRO__",
										$tabela_da_chave_estrangeira,
										$bloco_de_codigo
									)
								)
							);
						}
					}elseif($tipo!="PRI" && $tipo!="MUL")
					{
						$chave_estrangeira		=
						(
								($chave_estrangeira)
							?
								$chave_estrangeira
							:
								$nome_do_campo
						);
						$tmp[$nome_do_campo]	= str_replace
						(
							"__ATRIBUTO__",
							$nome_do_campo,
							str_replace
							(
								"__ATRIBUTO_ESTRANGEIRO__",
								$chave_estrangeira,
								$bloco_de_codigo
							)
						);
					}
					$tmp[$nome_do_campo]	= str_replace
					(
						"__TIPO_DO_ATRIBUTO__",
						$dados_do_campo['Type'],
						$tmp[$nome_do_campo]
					);
					$tmp[$nome_do_campo]	= str_replace
					(
						"__TABELA__",
						$tabela,
						$tmp[$nome_do_campo]
					);
					$tmp[$nome_do_campo]	= str_replace
					(
						"__CONTADOR_L__",
						$contador_l,
						$tmp[$nome_do_campo]
					);
					$tmp[$nome_do_campo]	= str_replace
					(
						"__CONTADOR_E__",
						$contador_e,
						$tmp[$nome_do_campo]
					);
					$tmp[$nome_do_campo]	= str_replace
					(
						"__CLASSE__",
						$tabela,
						$tmp[$nome_do_campo]
					);
					
					$tmp[$nome_do_campo]	= str_replace
					(
						"__DATE_I__",
						date("d/m/Y"),
						$tmp[$nome_do_campo]
					);
					$tmp[$nome_do_campo]	= str_replace
					(
						"__DATE_F__",
						date("d/m/Y"),
						$tmp[$nome_do_campo]
					);
					$tmp[$nome_do_campo]	= str_replace
					(
						"__HORA_I__",
						date("H:i:s"),
						$tmp[$nome_do_campo]
					);
					$tmp[$nome_do_campo]	= str_replace
					(
						"__HORA_F__",
						date("H:i:s"),
						$tmp[$nome_do_campo]
					);
					$tmp[$nome_do_campo]	= str_replace
					(
						"__PROJETO__",
						$this->nome_do_projeto,
						$tmp[$nome_do_campo]
					);
					$tmp[$nome_do_campo]	= str_replace
					(
						"__AUTOR__",
						(is_array($this->autores)?implode(",",$this->autores):$this->autores),
						$tmp[$nome_do_campo]
					);
					$tmp[$nome_do_campo]	= str_replace
					(
						"__DB__",
						$this->bancodedados,
						$tmp[$nome_do_campo]
					);
					$tmp[$nome_do_campo]	= str_replace
					(
						"__BD__",
						$this->bancodedados,
						$tmp[$nome_do_campo]
					);
					if(strlen($tmp[$nome_do_campo])<=0)unset($tmp[$nome_do_campo]);
					if($chave_do_array!=false){
						$nomet=explode("function ",$tmp[$nome_do_campo]);
						$nomet=$nomet[1];
						$nomet=explode("(",$nomet);
						$nomet=$nomet[0];
						//echo $nomet."\n";
						$tmp[$nomet]=$tmp[$nome_do_campo];
						unset($tmp[$nome_do_campo]);
					}
					$contador_l++;
				}
					$contador_e++;
				$codigos[$tabela]=$tmp;
			}
			$informacao=$codigos;
			return 
			(
				($table)
				?
					(
						!isset($informacao[$table])
						?
							array()
						:
							array($table=>$informacao[$table])
					)
				:
					$informacao
			);
		}
		
		/**
		 * Salva dados que estão em $this->informacao_gerada em arquivos
		 *
		 * @param string $table
		 * @param string $ext
		 * @param string $ini
		 * @param string $end
		 * @return boolean
		 */
		function salva_dados($table=false,$ext='.php',$ini="<?\n",$end="\n?\>"){
			$table=(($table==false)?$this->tabela:$table);
			if($table!=false){
				$content=$this->informacao_gerada;
				$content=is_string($content)?$content:$content[$table];
				$content=is_array($content)?implode('',$content):$content;
				return $this->salva_em_arquivo($ini.$content.$end,$table.$ext);
			}else{
				$retorno=$this->informacao_gerada;
				$x=0;
				foreach ($retorno as $class =>$content){
					exec("mkdir -p ".$this->pasta_to_save."/class");
					exec("chmod 777 ".$this->pasta_to_save."/class");
					$content=is_array($content)?implode('',$content):$content;
					if(!$this->salva_em_arquivo($ini.$content.$end,$class.$ext)){
						$x+=1;
					}
				}
				if($x>0) return false;
				else return true;
			}
		}
		
		/**
		 * Retorna array de strings de dados gerados
		 *
		 * @param string $table
		 * @param string $ext
		 * @param string $ini
		 * @param string $end
		 * @return boolean
		 */
		function retorna_dados($table=false,$ini="<?\n",$end="\n?\>"){
			$table=(($table==false)?$this->tabela:$table);
			$data=array();
			if($table!=false){
				$content=$this->informacao_gerada[$table];
				$content=is_string($content)?$content:$content[$table];
				/*print_r($content);
				echo count($content);*/
				$content=is_array($content)?implode('',$content):$content;
				$data[$table]=$ini.$content.$end;
			}else{
				$retorno=$this->informacao_gerada;
				$x=0;
				foreach ($retorno as $class =>$content){
					$content=is_array($content)?implode('',$content):$content;
					$data[$class]=$ini.$content.$end;
				}
			}
			return $data;
		}
		
		function salva_em_arquivo($conteudo='',$arqnome=false){
			if($arqnome!=false && strlen($conteudo)>0){
				if(!is_dir($this->pasta_to_save)){
					exec("mkdir -p ".$this->pasta_to_save);
					exec("chmod 777 ".$this->pasta_to_save);
				}
				if($fp=fopen($this->pasta_to_save."/".$arqnome,"w+")){
					if(fwrite($fp,$conteudo)){
						fclose($fp);
						exec("chmod 777 ".$this->pasta_to_save.$arqnome);
						return true;
					}
					return false;
				}
				return false;
			}
		}
		
		function gera_codigo_para_todos_campos($codigo,$tipo="ALL",$save=false,$table=false){//$nome,$comentario=array(),
			$table=(($table==false)?$this->tabela:$table);
			$info=$this->pega_informacao_do_banco_de_dados($table);
			$codigo_temp=array();
			foreach($info as $tabela=>$dados_do_campo){
				$tmp=array();
				$tmp=$this->substitui_dependencias($tipo,$tabela,$codigo,false,false,$save);
				if (!is_array($this->informacao_gerada[$tabela]))
				{
					$this->informacao_gerada[$tabela]=array();
					/*echo "oi\n";
					echo count($this->informacao_gerada[$tabela])."\n";*/
				}
				$codigo_temp[$tabela]=array();
				if($save!=false)
				{
					$this->informacao_gerada[$tabela]=array_merge_recursive($tmp,$this->informacao_gerada[$tabela]);
				}
				else{
					$codigo_temp[$tabela]=$tmp[$tabela];
				}
			}
			if($save==false)return $codigo_temp;
		}
		
		function gera_codigo_para_tabela($codigo,$table=false){
			$table=(($table==false)?$this->tabela:$table);
			$info=$this->pega_informacao_do_banco_de_dados($table);
			$codigo_temp=array();
			preg_match_all('/__I_PARA_TODOS_OS_ATRIBUTOS__(.*)__F_PARA_TODOS_OS_ATRIBUTOS__/',$codigo,$gerar_codigo_campos);
			$gerar_codigo_campos1=$gerar_codigo_campos[0];
			$gerar_codigo_campos2=$gerar_codigo_campos[1];
			$gerar_codigo_campos=array();
			foreach ($gerar_codigo_campos2 as $cont=>$codigo_tmp){
				preg_match_all('/__I_DO_TIPO__(.*)__F_DO_TIPO__/',$codigo_tmp,$tmp_code);
				preg_match_all('/__I_SEPARADOR__(.*)__F_SEPARADOR__/',$codigo_tmp,$tmp_sep);
				$gerar_codigo_campos[$cont]=array();
				$gerar_codigo_campos[$cont][0]=trim(strlen($tmp_code[1][0])>0?$tmp_code[1][0]:'ALL');
				$gerar_codigo_campos[$cont][1]=strlen($tmp_sep[1][0])>0?$tmp_sep[1][0]:';';
				$gerar_codigo_campos[$cont][2]=$gerar_codigo_campos1[$cont];
				$gerar_codigo_campos[$cont][3]=trim(str_replace($tmp_code[0][0],"",str_replace($tmp_sep[0][0],"",$gerar_codigo_campos2[$cont])));
			}
			//print_r($gerar_codigo_campos);
			foreach($info as $tabela=>$dados_de_campos){
				$params=array();
				$tmp_code=array();
				$codigo_tmp=$codigo;
				foreach($gerar_codigo_campos as $substituicoes){
					$tmp_code=$this->gera_codigo_para_todos_campos($substituicoes[3],$substituicoes[0],false,$tabela);
					//print_r($tmp_code);
					$tmp_code=implode($substituicoes[1],$tmp_code[$tabela]);
					$tmp_code=strlen($tmp_code)>0?$tmp_code:" ";
					/*echo "\n\n";
					echo $codigo_tmp;
					echo "\n\n";
					echo $substituicoes[2];
					echo "\n\n";
					echo $tmp_code;
					echo "\n\n";
					echo $substituicoes[0];
					echo "\n\n";*/
					$codigo_tmp=str_replace($substituicoes[2],$tmp_code,$codigo_tmp);
					//print_r($tmp_code);
				}
				$codigo_tmp=str_replace("__NL__","\n",$codigo_tmp);
				//echo $codigo_tmp;
				$tmp=array();
				$tmp=$this->substitui_dependencias('ALL',$tabela,$codigo_tmp,false,false,1);
				if (!is_array($this->informacao_gerada[$tabela]))
				{
					$this->informacao_gerada[$tabela]=array();
					/*echo "oi\n";
					echo count($this->informacao_gerada[$tabela])."\n";*/
				}
				$this->informacao_gerada[$tabela]=array_merge_recursive($tmp,$this->informacao_gerada[$tabela]);
			}
		}
	}
echo "<pre>";
$cr=new generic("localhost","user","senha","banco",false,"NOME DO PROJETO","AUTOR <autor@dominio.com>","/pasta para salvar os dados");
$codigo="
		/**
		 * Retorna o valor do atributo __ATRIBUTO__ da classe __TABELA__
		 *
		 * @author Gerador de classes <leonardoweslei@gmail.com>
		 * @since  ".date("d/m/Y")." ".date("H:i:s")."
		 * @final  ".date("d/m/Y")." ".date("H:i:s")."
		 * @package 
		 * @subpackage __TABELA__
		 * @name get__ATRIBUTO__
		 * @version 1.0
		 * @return __TIPO_DO_ATRIBUTO__
		 * @access public
		 */
		function get__ATRIBUTO__( )
		{
			return \$this->__ATRIBUTO__;
	 	} // end of member function get__ATRIBUTO__
	 	";
$codigo2="
		/**
		 * Retorna o valor do atributo __ATRIBUTO__ da classe __TABELA__
		 *
		 * @author Gerador de classes <leonardoweslei@gmail.com>
		 * @since  ".date("d/m/Y")." ".date("H:i:s")."
		 * @final  ".date("d/m/Y")." ".date("H:i:s")."
		 * @package 
		 * @subpackage __TABELA__
		 * @name get__ATRIBUTO__2
		 * @version 1.0
		 * @return __TIPO_DO_ATRIBUTO__
		 * @access public
		 */
		function get__ATRIBUTO__2( )
		{
			return \$this->__ATRIBUTO__;
	 	} // end of member function get__ATRIBUTO__
	 	";
$teste=array();
$teste="
__I_PARA_TODOS_OS_ATRIBUTOS__ \$__ATRIBUTO__=false; __F_PARA_TODOS_OS_ATRIBUTOS__

__I_PARA_TODOS_OS_ATRIBUTOS__  \$__ATRIBUTO__=NULL; __F_PARA_TODOS_OS_ATRIBUTOS__

//jfjfjfjfjf

__I_PARA_TODOS_OS_ATRIBUTOS__ __I_DO_TIPO__ PRI __F_DO_TIPO__ \$__ATRIBUTO__=false; __F_PARA_TODOS_OS_ATRIBUTOS__

get__ATRIBUTO__(__I_PARA_TODOS_OS_ATRIBUTOS____I_DO_TIPO__ ALL __F_DO_TIPO____I_SEPARADOR__, __F_SEPARADOR__\$__ATRIBUTO__=false__F_PARA_TODOS_OS_ATRIBUTOS__)";
$tmp="
\t\t/**
\t\t * Altera o objeto __TABELA__
\t\t *
\t\t * @author Gerador de classes
\t\t * @author __AUTOR__
\t\t * @since  __DATE_I__ __HORA_I__
\t\t * @final  __DATE_F__ __HORA_F__
\t\t * @package __PROJETO__
\t\t * @subpackage __TABELA__
\t\t * @name set__TABELA__
\t\t * @version 1.0__I_PARA_TODOS_OS_ATRIBUTOS__ __I_DO_TIPO__ALL__F_DO_TIPO__ __NL__\t\t * @param __TIPO_DO_ATRIBUTO__ \$__ATRIBUTO__ __F_PARA_TODOS_OS_ATRIBUTOS__
\t\t * @return int
\t\t * @access public
\t\t */
\t\tfunction set__TABELA__
\t\t(__I_PARA_TODOS_OS_ATRIBUTOS__ __I_DO_TIPO__ PRI __F_DO_TIPO__ __I_SEPARADOR__, __F_SEPARADOR____NL__\t\t\t\$__ATRIBUTO__=false __F_PARA_TODOS_OS_ATRIBUTOS__
\t\t)
\t\t{__I_PARA_TODOS_OS_ATRIBUTOS__ __I_DO_TIPO__ PRI __F_DO_TIPO__ __NL__\t\t\tif(\$__ATRIBUTO__!=false)__NL__\t\t\t{__NL__\t\t\t\tif(is_object(\$__ATRIBUTO__))__NL__\t\t\t\t{__NL__\t\t\t\t\t\$__ATRIBUTO__=\$__ATRIBUTO__->__ATRIBUTO_ESTRANGEIRO__;__NL__\t\t\t\t}__NL__\t\t\t\t\$this->__ATRIBUTO__=\$__ATRIBUTO__;__NL__\t\t\t} __F_PARA_TODOS_OS_ATRIBUTOS__
__I_PARA_TODOS_OS_ATRIBUTOS__ __I_DO_TIPO__ MUL __F_DO_TIPO____NL__\t\t\tif(\$__ATRIBUTO__!=false)__NL__\t\t\t{__NL__\t\t\t\tif(is_object(\$__ATRIBUTO__))__NL__\t\t\t\t{__NL__\t\t\t\t\t\$__ATRIBUTO__=\$__ATRIBUTO__->__ATRIBUTO_ESTRANGEIRO__;__NL__\t\t\t\t}__NL__\t\t\t\t\$this->__ATRIBUTO__=\$__ATRIBUTO__;__NL__\t\t\t} __F_PARA_TODOS_OS_ATRIBUTOS__
\t\t\t\$config	= new Configuracao();
\t\t\t\$servidor = new DataBase
\t\t\t(
\t\t\t\t\$config->host,
\t\t\t\t\$config->user,
\t\t\t\t\$config->passwd,
\t\t\t\t\$config->bd
\t\t\t);
\t\t\t\$campos = array
\t\t\t(
\t\t\t\t__I_PARA_TODOS_OS_ATRIBUTOS____I_SEPARADOR__, __F_SEPARADOR__\"__ATRIBUTO__\"__F_PARA_TODOS_OS_ATRIBUTOS____NL__\t\t\t);
\t\t\t\$valores = array
\t\t\t(\t\t\t\t__I_PARA_TODOS_OS_ATRIBUTOS____I_SEPARADOR__, __F_SEPARADOR__ __NL__\t\t\t\t\"\\\"\". \$this->__ATRIBUTO__.\"\\\"\"__F_PARA_TODOS_OS_ATRIBUTOS__
\t\t\t);
__I_PARA_TODOS_OS_ATRIBUTOS__ __I_DO_TIPO__PRI__F_DO_TIPO____NL__\t\t\t\$condicao = \" __ATRIBUTO__= \".\"\\\"\".\$this->__ATRIBUTO__.\"\\\"\";__F_PARA_TODOS_OS_ATRIBUTOS__
\t\t\tif(\$servidor->update(\"`__TABELA__`\", \$campos, \$valores, \$condicao) == 1)
\t\t\t{
\t\t\t\t\$servidor->close_database();
\t\t\t\t\$servidor = \"\";
\t\t\t\treturn 1;
\t\t\t}else
\t\t\t{
\t\t\t\t\$servidor->close_database();
\t\t\t\t\$servidor = \"\";
\t\t\t\treturn 0;
\t\t\t}
\t\t} // end of member function set__TABELA__
";
$tmp="
<?
	
/**
 * classe responsavel pelo tratamenro de dados da tabela __TABELA__
 *
 * @author 		__AUTOR__
 * @since 		__DATE_F__
 * @version 	1.0
 * @name 		__TABELA__.php
 * @abstract 	classe responsavel pelo tratamenro de dados da tabela __TABELA__
 */
	class __TABELA__ extends ADX
	{
		__I_PARA_TODOS_OS_ATRIBUTOS__		/**__NL__		* @var __TIPO_DO_ATRIBUTO__ __NL__		* @abstract equivalente ao campo __ATRIBUTO__ da tabela __TABELA____NL__		*/__NL__		var \$__ATRIBUTO__;__I_SEPARADOR____NL__		__F_SEPARADOR____F_PARA_TODOS_OS_ATRIBUTOS__
		
		/**
		 * @name __TABELA__
		 * @abstract Construtor da classe __TABELA__
		 * @author __AUTOR__
		 __I_PARA_TODOS_OS_ATRIBUTOS__ __I_DO_TIPO__ PRI __F_DO_TIPO__ __I_SEPARADOR____NL____F_SEPARADOR__		 * @param __TIPO_DO_ATRIBUTO__ \$__ATRIBUTO____F_PARA_TODOS_OS_ATRIBUTOS__
		 */
		function __TABELA__(__I_PARA_TODOS_OS_ATRIBUTOS__ __I_DO_TIPO__ PRI __F_DO_TIPO__ __I_SEPARADOR__, __F_SEPARADOR__\$__ATRIBUTO__=false __F_PARA_TODOS_OS_ATRIBUTOS__)
		{
			ADX::ADX();
			if (__I_PARA_TODOS_OS_ATRIBUTOS__ __I_DO_TIPO__ PRI __F_DO_TIPO__ __I_SEPARADOR__ && __F_SEPARADOR__\$__ATRIBUTO____F_PARA_TODOS_OS_ATRIBUTOS__)
			{
				\$this->carrega___TABELA__(__I_PARA_TODOS_OS_ATRIBUTOS__ __I_DO_TIPO__ PRI __F_DO_TIPO__ __I_SEPARADOR__ , __F_SEPARADOR__\$__ATRIBUTO____F_PARA_TODOS_OS_ATRIBUTOS__);
			}
		}
		"/*
		__I_PARA_TODOS_OS_ATRIBUTOS__var \$__ATRIBUTO__;__I_SEPARADOR____NL__		__F_SEPARADOR____F_PARA_TODOS_OS_ATRIBUTOS__
		function p___TABELA__(__I_PARA_TODOS_OS_ATRIBUTOS__ __I_DO_TIPO__ ALL __F_DO_TIPO__ __I_SEPARADOR__, __F_SEPARADOR__\$__ATRIBUTO__=false __F_PARA_TODOS_OS_ATRIBUTOS__)
		{
			__I_PARA_TODOS_OS_ATRIBUTOS____I_DO_TIPO__ MUL __F_DO_TIPO__if ( is_object(\$__ATRIBUTO__))__NL__			{__NL__				\$__ATRIBUTO__=\$__ATRIBUTO__->__ATRIBUTO_ESTRANGEIRO__;__NL__			}__NL__		}__F_PARA_TODOS_OS_ATRIBUTOS__*/
		."
		/**
		 * @name carrega___TABELA__
		 * @abstract Pesquisa um registro na tabela __TABELA__ e o grava nos atributos
		 * @author __AUTOR__
		 __I_PARA_TODOS_OS_ATRIBUTOS__ __I_DO_TIPO__ PRI __F_DO_TIPO__ __I_SEPARADOR____NL____F_SEPARADOR__		 * @param __TIPO_DO_ATRIBUTO__ \$__ATRIBUTO____F_PARA_TODOS_OS_ATRIBUTOS__
		 * @return void
		 */
		function carrega___TABELA__(__I_PARA_TODOS_OS_ATRIBUTOS__ __I_DO_TIPO__ PRI __F_DO_TIPO__ __I_SEPARADOR__, __F_SEPARADOR__\$__ATRIBUTO__=false __F_PARA_TODOS_OS_ATRIBUTOS__)
		{
			\$sql=\"SELECT * FROM __TABELA__ WHERE __I_PARA_TODOS_OS_ATRIBUTOS__ __I_DO_TIPO__ PRI __F_DO_TIPO__ __I_SEPARADOR__ AND __F_SEPARADOR____ATRIBUTO__=\$__ATRIBUTO__ __F_PARA_TODOS_OS_ATRIBUTOS__\";
			\$result=\$this->consulta(\$sql);
			\$atributos=\$result->fetchRow();
			__I_PARA_TODOS_OS_ATRIBUTOS____I_SEPARADOR__ __NL__			__F_SEPARADOR__\$this->__ATRIBUTO__=isset(\$atributos['__ATRIBUTO__'])?\$atributos['__ATRIBUTO__']:\$atributos[__CONTADOR_L__]; __F_PARA_TODOS_OS_ATRIBUTOS__
		}
		/**
		 * @name todos_registros___TABELA__
		 * @abstract retorna os registros de acordo com os argumentos passados
		 * @author __AUTOR__
		 __I_PARA_TODOS_OS_ATRIBUTOS__ __I_DO_TIPO__ ALL __F_DO_TIPO__ __I_SEPARADOR__ __F_SEPARADOR__ __NL__		 * @param __TIPO_DO_ATRIBUTO__ \$__ATRIBUTO____F_PARA_TODOS_OS_ATRIBUTOS__
		 * @param string \$order
		 * @param string \$limit
		 * @param string \$adicional_para_consulta
		 * @return array
		 */
		function todos_registros___TABELA__(__I_PARA_TODOS_OS_ATRIBUTOS__ __I_DO_TIPO__ ALL __F_DO_TIPO__ __I_SEPARADOR__, __F_SEPARADOR__\$__ATRIBUTO__=false __F_PARA_TODOS_OS_ATRIBUTOS__,\$order=false,\$limit=false,\$adicional_para_consulta=false)
		{
			\$sql2=\"\";
			\$sql=\"SELECT * FROM __TABELA__\";
			__I_PARA_TODOS_OS_ATRIBUTOS____I_DO_TIPO__ ALL __F_DO_TIPO____I_SEPARADOR__ __NL__			__F_SEPARADOR__\$sql2.=\$__ATRIBUTO__!=false?((strlen(\$sql2)<=0?\" Where \":\" AND \").\"__ATRIBUTO__=\$__ATRIBUTO__\"):\"\";__F_PARA_TODOS_OS_ATRIBUTOS__
			\$sql.=\$adicional_para_consulta==false?\"\":\" \".\$adicional_para_consulta;
			\$sql.=\$sql2;
			\$sql.=\$order==false?\"\":\" ORDER BY \".\$order;
			\$sql.=\$limit==false?\"\":\" Limit \".\$limit;
			\$result=\$this->consulta(\$sql);
			\$dados=array();
			for(\$i=0;\$i<mysql_numrows(\$result->result);\$i++)
				\$dados[]=\$result->fetchRow();
			return \$dados;
		}

		/**
		 * @name grava
		 * @abstract Grava um registro na __TABELA__
		 * @author __AUTOR__
		 * @return void
		 */
		function grava()
		{
			if(__I_PARA_TODOS_OS_ATRIBUTOS__ __I_DO_TIPO__ PRI __F_DO_TIPO__ __I_SEPARADOR__ && __F_SEPARADOR__empty(\$this->__ATRIBUTO__)__F_PARA_TODOS_OS_ATRIBUTOS__)
			{
				\$sql=\"INSERT INTO __TABELA__ 
					VALUES
					(
						__I_PARA_TODOS_OS_ATRIBUTOS__ __I_DO_TIPO__ ALL __F_DO_TIPO__ __I_SEPARADOR__ , __F_SEPARADOR__'\$this->__ATRIBUTO__'__F_PARA_TODOS_OS_ATRIBUTOS__
					)\";
			}
			else 
			{
				\$sql=\"UPDATE  __TABELA__
					SET
						__I_PARA_TODOS_OS_ATRIBUTOS__ __I_DO_TIPO__ ALL __F_DO_TIPO__ __I_SEPARADOR__ , __F_SEPARADOR____ATRIBUTO__='\$this->__ATRIBUTO__'__F_PARA_TODOS_OS_ATRIBUTOS__
					WHERE __I_PARA_TODOS_OS_ATRIBUTOS__ __I_DO_TIPO__ PRI __F_DO_TIPO__ __I_SEPARADOR__ AND __F_SEPARADOR____ATRIBUTO__='\$this->__ATRIBUTO__'__F_PARA_TODOS_OS_ATRIBUTOS__\";
			}
			\$this->consulta(\$sql);
		}
		/**
		 * @name exclui
		 * @abstract apaga um registro na __TABELA__
		 * @author __AUTOR__
		 * @return void
		 */
		function exclui()
		{
			\$sql=\"DELETE FROM __TABELA__ WHERE __I_PARA_TODOS_OS_ATRIBUTOS__ __I_DO_TIPO__ PRI __F_DO_TIPO__ __I_SEPARADOR__ AND __F_SEPARADOR____ATRIBUTO__='\$this->__ATRIBUTO__'__F_PARA_TODOS_OS_ATRIBUTOS__\";
			\$this->consulta(\$sql);
		}
		
	}
?>";*/
/*$teste="
jfjfjfjfjf
__I_PARA_TODOS_OS_ATRIBUTOS__testando1235__F_PARA_TODOS_OS_ATRIBUTOS__";
//ereg('__I_PARA_TODOS_OS_ATRIBUTOS__(.*)__F_PARA_TODOS_OS_ATRIBUTOS__',$teste2,$teste);*/
$tabelas=array("tabela1");
$code=array();
foreach ($tabelas as $tab)
{
	$cr->gera_codigo_para_tabela($tmp,$tab);
	highlight_string($tmpcode=implode("",$cr->retorna_dados($tab," "," ")));
	$code[]=$tmpcode;
	$cr->salva_em_arquivo($tmpcode,'/tmp/'.$tab.'.php');
}

?>
