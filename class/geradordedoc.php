<?php
	require_once("class/database.php");
/**
 * Gerador de Classes
 * @version 1.0 
 *
 */
	class geradordedoc
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
		var $types=array();
		var $ref=array();
		var $class=array();
		var $lists=array();
		var $associations=array();
		var $depends=array();
		var $x=0;
		var $y=10;
		var $cont=0;
		var $info=false;
		function geradordedoc($host,$user,$password,$db=false,$table=false,$project="Projeto criado pelo gerador de classes",$author="Leonardo Weslei Diniz <leonardoweslei@gmail.com>",$pasta_to_save="doc/")
		{
			$this->user 		= $user;
			$this->password		= $password;
			$this->host 		= $host;
			$this->db			= $db;
			$this->table 	= $table;
			$this->project 	= $project;
			$this->author 	= $author;
			$this->pasta_to_save= $pasta_to_save."/";
			$this->servidor 	= new DataBase
			(
				$this->host,
				$this->user,
				$this->password,
				$this->db
			);
		}
		
		function dbinfo($table=false){
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
		
		function count(){
			$this->cont+=1;
			return $this->cont;
		}
		
		function counter($array){
			$x=0;
			foreach ($array as $array=>$content){
				$x+=is_array($content)?$this->counter($content):1;
			}
			return $x;
		}
		
		function search($pal){
			$x=0;
			if(isset($this->ref[$pal])){
				return $this->ref[$pal];
			}
			return false;
		}
		
		function criatipo($tipo){
			if($this->search($tipo)==false){
				$this->types[$tipo]='<UML:Class isSpecification="false" isLeaf="false" visibility="public" namespace="Logical View" xmi.id="'.($this->count()).'" isRoot="false" isAbstract="false" name="'.$tipo.'" />';
				$this->ref[$tipo]=$this->cont;
//				echo $tipo."<br>	";
//				echo $this->cont;
//				echo "<br>\n";
				return $this->cont;
			}
			return $this->search($tipo);
		}
		
		function getfunctionget($table=false){
			$info=$this->dbinfo();
			$functions=array();
			foreach($info as $tablei=>$fields){
				$tmp=array();
				foreach($fields as $key=>$datafield){
					$cpt=explode(" ",$datafield["Type"]);
					$cpt=$cpt[0];
					$cpt=explode("(",$cpt);
					$cpt=$cpt[0];
					$cpt=($datafield["Key"]=="MUL")?"mixed (".$cpt." or object ".$datafield["Ref"]["table_r"].")":($datafield["Key"]=="PRI")?"mixed (".$cpt." ou object ".$tablei.")":$cpt;
					$cpt=$this->criatipo($cpt);
					$this->lists[$tablei][]='<listitem open="0" type="815" id="'.($this->cont+1).'" />';
					$tmp[]='
						<UML:Operation isSpecification="false" isLeaf="false" visibility="public" xmi.id="'.($this->count()).'" isRoot="false" isAbstract="false" isQuery="false" name="'.("get".$key).'" >
							<UML:BehavioralFeature.parameter>
								<UML:Parameter kind="return" xmi.id="'.$this->count().'" type="'.$cpt.'" />
							</UML:BehavioralFeature.parameter>
						</UML:Operation>';
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
					$cpt=explode("(",$cpt);
					$cpt=$cpt[0];
					if ($datafield['Key']=="MUL")$cpt="mixed (".$cpt." or object ".$datafield["Ref"]["table_r"].")";
					if ($datafield['Key']=="PRI")$cpt="mixed (".$cpt." or object ".$tablei.")";
					$cpt=$this->criatipo($cpt);
					$params[]='
								<UML:Parameter isSpecification="false" visibility="private" xmi.id="'.$this->count().'" value="false" type="'.$cpt.'" name="'.($key).'" />';
				}
				$params=implode("",$params);
				$tmp=array();
				$cpt=$this->criatipo("int");
				$this->lists[$tablei][]='<listitem open="0" type="815" id="'.($this->cont+1).'" />';
				$tmp[]='
						<UML:Operation isSpecification="false" isLeaf="false" visibility="public" xmi.id="'.($this->count()).'" isRoot="false" isAbstract="false" isQuery="false" name="'.("set".$tablei).'" >
							<UML:BehavioralFeature.parameter>
								<UML:Parameter kind="return" xmi.id="'.$this->count().'" type="'.$cpt.'" />
								'.$params.'
							</UML:BehavioralFeature.parameter>
						</UML:Operation>';
				$functions[$tablei]=$tmp;
			}
			$info=$functions;
			return ($table)?(!isset($info[$table])?array():$info[$table]):$info;
		}
		
		function getfunctiondel($table=""){
			$info=$this->dbinfo();
			$functions=array();
			foreach($info as $tablei=>$fields){
				$tmp=array();
				$id=$this->getelementochave($tablei);
				$id=$id[0];
				$params=array();
				$cpt=explode(" ",$fields[$id]["Type"]);
				$cpt=$cpt[0];
				$cpt=explode("(",$cpt);
				$cpt=$cpt[0];
				$id=$fields[$id];
				$tid=explode(" ",$id["Type"]);
				$tid=$tid[0];
				$tid=explode("(",$tid);
				$tid=$tid[0];
				$tid="mixed (".$tid."ou object ".$tablei.")";
				$id=$id["Field"];
				$tid=$this->criatipo($tid);
				$cpt=$this->criatipo("void");
				$this->lists[$tablei][]='<listitem open="0" type="815" id="'.($this->cont+1).'" />';
				$tmp[]='
						<UML:Operation isSpecification="false" isLeaf="false" visibility="public" xmi.id="'.($this->count()).'" isRoot="false" isAbstract="false" isQuery="false" name="'.("del".$tablei).'" >
							<UML:BehavioralFeature.parameter>
								<UML:Parameter kind="return" xmi.id="'.$this->count().'" type="'.$cpt.'" />
								<UML:Parameter isSpecification="false" visibility="private" xmi.id="'.$this->count().'" value="false" type="'.$tid.'" name="'.($id).'" />
							</UML:BehavioralFeature.parameter>
						</UML:Operation>';
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
					$cpt=explode("(",$cpt);
					$cpt=$cpt[0];
					if ($datafield['Key']=="MUL")$cpt="mixed (".$cpt." or object ".$datafield["Ref"]["table_r"].")";
					if ($datafield['Key']=="PRI")$cpt="mixed (".$cpt." or object ".$tablei.")";
					$cpt=$this->criatipo($cpt);
					$params[]='
								<UML:Parameter isSpecification="false" visibility="private" xmi.id="'.$this->count().'" value="false" type="'.$cpt.'" name="'.($key).'" />';
				}
				$tmp=array();
				$params=implode("",$params);
				$id=$this->getelementochave($tablei);
				$id=$id[0];
				$id=$fields[$id];
				$tid=explode(" ",$id["Type"]);
				$tid=$tid[0];
				$tid=explode("(",$tid);
				$tid=$tid[0];
				$id=$id["Field"];
				$cpt=$this->criatipo($tid);
				$this->lists[$tablei][]='<listitem open="0" type="815" id="'.($this->cont+1).'" />';
				$tmp[]='
						<UML:Operation isSpecification="false" isLeaf="false" visibility="public" xmi.id="'.($this->count()).'" isRoot="false" isAbstract="false" isQuery="false" name="'.("new".$tablei).'" >
							<UML:BehavioralFeature.parameter>
								<UML:Parameter kind="return" xmi.id="'.$this->count().'" type="'.$cpt.'" />
								'.$params.'
							</UML:BehavioralFeature.parameter>
						</UML:Operation>';
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
				$params2=array();
				foreach($fields as $key=>$datafield){
					$cpt=explode(" ",$datafield["Type"]);
					$cpt=$cpt[0];
					$cpt=explode("(",$cpt);
					$cpt=$cpt[0];
					if ($datafield['Key']=="MUL")$cpt="mixed (".$cpt." or object ".$datafield["Ref"]["table_r"].")";
					if ($datafield['Key']=="PRI")$cpt="mixed (".$cpt." or object ".$tablei.")";
					$params[]='
								<UML:Parameter isSpecification="false" visibility="private" xmi.id="'.$this->count().'" value="false" type="'.$this->criatipo($cpt).'" name="'.($key).'" />';
					$params2[]='
								<UML:Parameter isSpecification="false" visibility="private" xmi.id="'.$this->count().'" value="false" type="'.$this->criatipo($cpt).'" name="'.($key).'" />';
				}
				$tmp=array();
				$params=implode("",$params);
				$params2=implode("",$params2);
				$this->lists[$tablei][]='<listitem open="0" type="815" id="'.($this->cont+1).'" />';
				$this->lists[$tablei][]='<listitem open="0" type="815" id="'.($this->cont+2).'" />';
				$tmp[]='
						<UML:Operation isSpecification="false" isLeaf="false" visibility="public" xmi.id="'.($this->count()).'" isRoot="false" isAbstract="false" isQuery="false" name="'.("__construct").'" >
							<UML:BehavioralFeature.parameter>
								'.$params.'
							</UML:BehavioralFeature.parameter>
						</UML:Operation>'.'
						<UML:Operation isSpecification="false" isLeaf="false" visibility="public" xmi.id="'.($this->count()).'" isRoot="false" isAbstract="false" isQuery="false" name="'.($tablei).'" >
							<UML:BehavioralFeature.parameter>
								'.$params2.'
							</UML:BehavioralFeature.parameter>
						</UML:Operation>';
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
				$tid=explode("(",$tid);
				$tid=$tid[0];
				$tid="mixed (".$tid." ou object ".$tablei.")";
				$id=$id["Field"];
				foreach($fields as $key=>$datafield){
					$cpt=explode(" ",$datafield["Type"]);
					$cpt=$cpt[0];
					$cpt=explode("(",$cpt);
					$cpt=$cpt[0];
					if ($datafield['Key']=="MUL") {
						$cpt="mixed (".$cpt." or object ".$datafield["Ref"]["table_r"].")";
					}else{
						$ifs=$condid;
					}
					if ($datafield['Key']!="PRI"){
						$this->lists[$tablei][]='<listitem open="0" type="815" id="'.($this->cont+1).'" />';
						$tmp[]='
						<UML:Operation isSpecification="false" isLeaf="false" visibility="public" xmi.id="'.($this->count()).'" isRoot="false" isAbstract="false" isQuery="false" name="'.("set".$key).'" >
							<UML:BehavioralFeature.parameter>
								<UML:Parameter kind="return" xmi.id="'.$this->count().'" type="'.$this->criatipo("int").'" />
								<UML:Parameter isSpecification="false" visibility="private" xmi.id="'.$this->count().'" value="false" type="'.$this->criatipo($tid).'" name="'.($id).'" />
								<UML:Parameter isSpecification="false" visibility="private" xmi.id="'.$this->count().'" value="false" type="'.$this->criatipo($cpt).'" name="'.($key).'" />
							</UML:BehavioralFeature.parameter>
						</UML:Operation>';
					}
				}
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
				$cpt=explode("(",$cpt);
				$cpt=$cpt[0];
				$id=$fields[$id];
				$tid=explode(" ",$id["Type"]);
				$tid=$tid[0];
				$tid=explode("(",$tid);
				$tid=$tid[0];
				$tid="mixed (".$tid."ou object ".$tablei.")";
				$id=$id["Field"];
				$cpt=$this->criatipo("object ".$tablei);
				$tid=$this->criatipo($tid);
				$this->lists[$tablei][]='<listitem open="0" type="815" id="'.($this->cont+1).'" />';
				$tmp[]='
						<UML:Operation isSpecification="false" isLeaf="false" visibility="public" xmi.id="'.($this->count()).'" isRoot="false" isAbstract="false" isQuery="false" name="'.("search".$tablei."forkey").'" >
							<UML:BehavioralFeature.parameter>
								<UML:Parameter kind="return" xmi.id="'.$this->count().'" type="'.$cpt.'" />
								<UML:Parameter isSpecification="false" visibility="private" xmi.id="'.$this->count().'" value="false" type="'.$tid.'" name="'.($id).'" />
							</UML:BehavioralFeature.parameter>
						</UML:Operation>';
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
					$cpt=explode("(",$cpt);
					$cpt=$cpt[0];
					if ($datafield['Key']=="MUL"){
						$cpt2=explode(" ",$datafield["Ref"]["table_r"]);
						$cpt2=$cpt2[0];
						$cpt2=explode("(",$cpt2);
						$cpt2=$cpt2[0];
						$cpt="mixed (".$cpt." or object ".$cpt2.")";
					}
					if ($datafield['Key']=="PRI")$cpt="mixed (".$cpt." or object ".$tablei.")";
					$cpt=$this->criatipo($cpt);
					$params[]='
								<UML:Parameter isSpecification="false" visibility="private" xmi.id="'.$this->count().'" value="false" type="'.$cpt.'" name="'.($key).'" />';
				}
				$tmp=array();
				$params=implode("",$params);
				$cpt=$this->criatipo("array object ".$tablei);
				$this->lists[$tablei][]='<listitem open="0" type="815" id="'.($this->cont+1).'" />';
				$tmp[]='
						<UML:Operation isSpecification="false" isLeaf="false" visibility="public" xmi.id="'.($this->count()).'" isRoot="false" isAbstract="false" isQuery="false" name="'.("search".$tablei).'" >
							<UML:BehavioralFeature.parameter>
								<UML:Parameter kind="return" xmi.id="'.$this->count().'" type="'.$cpt.'" />
								'.$params.'
							</UML:BehavioralFeature.parameter>
						</UML:Operation>';
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
					$cpt=explode("(",$cpt);
					$cpt=$cpt[0];
					if ($datafield['Key']=="MUL")$cpt="mixed (".$cpt." or object ".$datafield["Ref"]["table_r"].")";
					if ($datafield['Key']=="PRI")$cpt="mixed (".$cpt." or object ".$tablei.")";
					$params[]='
				<UML:Attribute visibility="public" isSpecification="false" xmi.id="'.$this->count().'" type="'.$this->criatipo($cpt).'" initialValue="NULL" name="'.($key).'" />';
				}
				$ifp=$this->getifsattr("MUL",$tablei,"\n //require_once(\"{$this->pasta_to_save}ATTRIBUTE_T_HERE.php\");");
				$params=implode("",$params);
				$ifp=implode("",$ifp);
				$ifp.="\n //require_once(\"{$this->pasta_to_save}database.php\");";
				$ifp.="\n //require_once(\"{$this->pasta_to_save}configuracao.php\");";
				$tmp=array();
				$all=$this->getelementochave($tablei,"all");
				$tmp='<?xml version="1.0" encoding="UTF-8"?>
<XMI	verified="false" xmi.version="1.2" timestamp="'.date("Y-m-d")."T".date("H:i:s").'" xmlns:UML="http://schema.omg.org/spec/UML/1.3" >
	<XMI.header>
		<XMI.documentation>
			<XMI.exporter>umbrello uml modeller http://uml.sf.net</XMI.exporter>
			<XMI.exporterVersion>1.5.8</XMI.exporterVersion>
			<XMI.exporterEncoding>UnicodeUTF8</XMI.exporterEncoding>
		</XMI.documentation>
		<XMI.metamodel xmi.version="1.3" href="UML.xml" xmi.name="UML" />
	</XMI.header>
	<XMI.content>
		<UML:Model isSpecification="false" isAbstract="false" isLeaf="false" xmi.id="m1" isRoot="false" name="'.($this->db).'" >
			<UML:Namespace.ownedElement>
				<UML:Stereotype visibility="public" isSpecification="false" namespace="m1" isAbstract="false" isLeaf="false" isRoot="false" xmi.id="folder" name="folder" />
				<UML:Model stereotype="folder" visibility="public" isSpecification="false" namespace="m1" isAbstract="false" isLeaf="false" isRoot="false" xmi.id="Logical View" name="Logical View" >
					<UML:Namespace.ownedElement>
						<UML:Class visibility="public" isSpecification="false" namespace="Logical View" isAbstract="false" isLeaf="false" isRoot="false" xmi.id="'.$this->count().'" name="'.$tablei.'" >
							<UML:Classifier.feature>'.$params;
				$this->class[$tablei]=$this->cont;
				$functions[$tablei]=$tmp;
			}
			$info=$functions;
			return ($table)?(!isset($info[$table])?array():$info[$table]):$info;
		}
		
		function init_cl(){
			return '<?xml version="1.0" encoding="UTF-8"?>
<XMI	verified="false" xmi.version="1.2" timestamp="'.date("Y-m-d")."T".date("H:i:s").'" xmlns:UML="http://schema.omg.org/spec/UML/1.3" >
	<XMI.header>
		<XMI.documentation>
			<XMI.exporter>umbrello uml modeller http://uml.sf.net</XMI.exporter>
			<XMI.exporterVersion>1.5.8</XMI.exporterVersion>
			<XMI.exporterEncoding>UnicodeUTF8</XMI.exporterEncoding>
		</XMI.documentation>
		<XMI.metamodel xmi.version="1.3" href="UML.xml" xmi.name="UML" />
	</XMI.header>
	<XMI.content>
		<UML:Model isSpecification="false" isAbstract="false" isLeaf="false" xmi.id="m1" isRoot="false" name="'.($this->db).'" >
			<UML:Namespace.ownedElement>
				<UML:Stereotype visibility="public" isSpecification="false" namespace="m1" isAbstract="false" isLeaf="false" isRoot="false" xmi.id="folder" name="folder" />
				<UML:Model stereotype="folder" visibility="public" isSpecification="false" namespace="m1" isAbstract="false" isLeaf="false" isRoot="false" xmi.id="Logical View" name="Logical View" >
					<UML:Namespace.ownedElement>';
		}
		
		function dep(){
			$info=$this->dbinfo();
			$functions=array();
			foreach($info as $tablei=>$fields){
				foreach($fields as $key=>$datafield){
					if ($datafield['Key']=="MUL"){
						$table=$datafield["Ref"]["table_r"];
						$table_de=$this->class[$table];
						$table_para=$this->class[$tablei];
						$this->associations[]='<UML:Dependency visibility="public" isSpecification="false" namespace="Logical View" supplier="'.$table_de.'" xmi.id="'.$this->count().'" client="'.$table_para.'" name="" />';
						$this->depends[]='<assocwidget indexa="1" indexb="1" widgetaid="'.$table_para.'" linecolor="none" totalcounta="2" xmi.id="'.$this->cont.'" widgetbid="'.$table_de.'" totalcountb="2" type="502" linewidth="none" ></assocwidget>';
					}/*
					if ($datafield['Key']=="PRI"){
						$table_de=$table_para=$this->class[$tablei];
						$this->associations[]='<UML:Dependency visibility="public" isSpecification="false" namespace="Logical View" supplier="'.$table_de.'" xmi.id="'.$this->count().'" client="'.$table_para.'" name="" />';
						$this->depends[]='<assocwidget indexa="1" indexb="1" widgetaid="'.$table_para.'" linecolor="none" totalcounta="2" xmi.id="'.$this->cont.'" widgetbid="'.$table_de.'" totalcountb="2" type="502" linewidth="none" ></assocwidget>';
					}*/
				}
			}
		}
		
		function getiniclass2($table=false){
			$info=$this->dbinfo();
			$functions=array();
			foreach($info as $tablei=>$fields){
				$params=array();
				foreach($fields as $key=>$datafield){
					$cpt=explode(" ",$datafield["Type"]);
					$cpt=$cpt[0];
					$cpt=explode("(",$cpt);
					$cpt=$cpt[0];
					if ($datafield['Key']=="MUL"){
						$cpt="mixed (".$cpt." or object ".$datafield["Ref"]["table_r"].")";
					}
					if ($datafield['Key']=="PRI"){
						$cpt="mixed (".$cpt." or object ".$tablei.")";
					}
					$params[]='
				<UML:Attribute visibility="public" isSpecification="false" xmi.id="'.$this->count().'" type="'.$this->criatipo($cpt).'" initialValue="NULL" name="'.($key).'" />';
					$this->lists[$tablei][]='<listitem open="0" type="814" id="'.($this->cont).'" />';
				}
				$ifp=$this->getifsattr("MUL",$tablei,"\n //require_once(\"{$this->pasta_to_save}ATTRIBUTE_T_HERE.php\");");
				$params=implode("",$params);
				$ifp=implode("",$ifp);
				$ifp.="\n //require_once(\"{$this->pasta_to_save}database.php\");";
				$ifp.="\n //require_once(\"{$this->pasta_to_save}configuracao.php\");";
				$tmp=array();
				$all=$this->getelementochave($tablei,"all");
				$tmp='
						<UML:Class visibility="public" isSpecification="false" namespace="Logical View" isAbstract="false" isLeaf="false" isRoot="false" xmi.id="'.$this->count().'" name="'.$tablei.'" >
							<UML:Classifier.feature>'.$params;
				$this->class[$tablei]=$this->cont;
				$functions[$tablei]=$tmp;
			}
			$info=$functions;
			return ($table)?(!isset($info[$table])?array():$info[$table]):$info;
		}
		
		function getfinishclass2($table=false){
			$info=$this->dbinfo();
			$functions=array();
			foreach($info as $tablei=>$fields){
				$params=array();
				$tmp=array();
				$tmp='
							</UML:Classifier.feature>
						</UML:Class>';
				$functions[$tablei]=$tmp;
			}
			$info=$functions;
			return ($table)?(!isset($info[$table])?array():$info[$table]):$info;
		}
		
		function meio_cl(){
			$info=$this->dbinfo();
			$functions=array();
			$tmp=array();
			$flag=0;
			$width=500;
			foreach($info as $tablei=>$fields){
				if($this->x==0){
					$x=20;
					$this->x=1;
					$yyy=$y=$this->getelementochave($tablei,"all");
					$y=((count($y)*15)*3)+(7*15);
					$y2=$y;
					$y2=((count($yyy)+1)*15);//somente mostrando atributos comente para mostrar o tamanho total
					$y=$this->y+$flag+30;
					$this->y=$y;
				}else{
					$x=$width+50;
					$this->x=0;
					$yyy=$yx=$this->getelementochave($tablei,"all");
					$yx=((count($yx)*15)*3)+(7*15);
					$yx=((count($yyy)+1)*15);//somente mostrando atributos comente para mostrar o tamanho total
					if($yx>$y2){
						$y2=$yx;
					}
				}
				$flag=$y2;
				$tmp[]='
									<classwidget linecolor="none" usesdiagramfillcolor="1" linewidth="none" showoperations="0" usesdiagramusefillcolor="1" showpubliconly="0" showpackage="1" x="'.$x.'" showattsigs="601" showstereotype="1" y="'.$y.'" showattributes="1" font="DejaVu Sans,9,-1,0,75,0,0,0,0,0" width="'.$width.'" isinstance="0" usefillcolor="1" fillcolor="none" xmi.id="'.$this->class[$tablei].'" showscope="1" height="'.$y2.'" showopsigs="601" />';
			}
			return implode("",$tmp);
		}
		
		function finish_cl(){
				$params=array();
				$this->dep();
				$types=implode("
				 						",$this->types);
				$lists="";
				$info=$this->dbinfo();
				$depends="
								".implode("
								",$this->depends)."
								";
				$associ="
					".implode("
					",$this->associations)."
					";
				foreach($info as $tablei=>$fields){
					$lists.="
					".'<listitem open="0" type="813" id="'.$this->class[$tablei].'" >
					'.implode("
						",$this->lists[$tablei])."
					</listitem>";
				}
				$tmp=$associ.'
			      <UML:Package stereotype="folder" visibility="public" isSpecification="false" namespace="Logical View" isAbstract="false" isLeaf="false" isRoot="false" xmi.id="Datatypes" name="Datatypes" >
			       <UML:Namespace.ownedElement>'.$types.'
					</UML:Namespace.ownedElement>
      			 </UML:Package>
					</UML:Namespace.ownedElement>
					<XMI.extension xmi.extender="umbrello" >
						<diagrams>
							<diagram showopsig="1" linecolor="#ff0000" snapx="10" showattribassocs="1" snapy="10" linewidth="0" showattsig="1" showpackage="1" showstereotype="1" name="'.($this->db).'" font="DejaVu Sans,9,-1,0,50,0,0,0,0,0" canvasheight="645" canvaswidth="1126" localid="" snapcsgrid="0" showgrid="0" showops="1" usefillcolor="1" fillcolor="#ffff00" zoom="100" xmi.id="'.($this->count()).'" documentation="" showscope="1" snapgrid="0" showatts="1" type="1" >
								<widgets>
									'.$this->meio_cl().'
								</widgets>
								<messages/>
								<associations>
								'.$depends.'
								</associations>
							</diagram>
						</diagrams>
					</XMI.extension>
				</UML:Model>
				<UML:Model stereotype="folder" visibility="public" isSpecification="false" namespace="m1" isAbstract="false" isLeaf="false" isRoot="false" xmi.id="Use Case View" name="Use Case View" >
					<UML:Namespace.ownedElement/>
				</UML:Model>
				<UML:Model stereotype="folder" visibility="public" isSpecification="false" namespace="m1" isAbstract="false" isLeaf="false" isRoot="false" xmi.id="Component View" name="Component View" >
					<UML:Namespace.ownedElement/>
				</UML:Model>
				<UML:Model stereotype="folder" visibility="public" isSpecification="false" namespace="m1" isAbstract="false" isLeaf="false" isRoot="false" xmi.id="Deployment View" name="Deployment View" >
					<UML:Namespace.ownedElement/>
				</UML:Model>
				<UML:Model stereotype="folder" visibility="public" isSpecification="false" namespace="m1" isAbstract="false" isLeaf="false" isRoot="false" xmi.id="Entity Relationship Model" name="Entity Relationship Model" >
					<UML:Namespace.ownedElement/>
				</UML:Model>
			</UML:Namespace.ownedElement>
		</UML:Model>
	</XMI.content>
	<XMI.extensions xmi.extender="umbrello" >
			<docsettings viewid="'.($this->cont).'" uniqueid="'.($this->count()).'" documentation="" />
			<listview>
				<listitem open="0" type="800" label="Views" >
					<listitem open="0" type="801" id="Logical View" >
						<listitem open="1" type="807" id="'.($this->db).'" label="'.($this->db).'" />
						'.$lists.'
					</listitem>
				</listitem>
			</listview>
			<codegeneration>
			<codegenerator language="PHP5" />
			</codegeneration>
	</XMI.extensions>
</XMI>';
			return $tmp;
		}
		
		function getfinishclass($table=false){
			$info=$this->dbinfo();
			$functions=array();
			foreach($info as $tablei=>$fields){
				$params=array();
				$types=implode("\n 						",$this->types);
				$y=$this->getelementochave($tablei,"all");
				$y=((count($y)*15)*3)+(7*15);
				$tmp='
							</UML:Classifier.feature>
						</UML:Class>
				      <UML:Package stereotype="folder" visibility="public" isSpecification="false" namespace="Logical View" isAbstract="false" isLeaf="false" isRoot="false" xmi.id="Datatypes" name="Datatypes" >
				       <UML:Namespace.ownedElement>'.$types.'
						</UML:Namespace.ownedElement>
	      			 </UML:Package>
					</UML:Namespace.ownedElement>
					<XMI.extension xmi.extender="umbrello" >
						<diagrams>
							<diagram showopsig="1" linecolor="#ff0000" snapx="10" showattribassocs="1" snapy="10" linewidth="0" showattsig="1" showpackage="1" showstereotype="1" name="'.($this->db).'" font="DejaVu Sans,9,-1,0,50,0,0,0,0,0" canvasheight="645" canvaswidth="1126" localid="" snapcsgrid="0" showgrid="0" showops="1" usefillcolor="1" fillcolor="#ffff00" zoom="100" xmi.id="'.($this->count()).'" documentation="" showscope="1" snapgrid="0" showatts="1" type="1" >
								<widgets>
									<classwidget linecolor="none" usesdiagramfillcolor="1" linewidth="none" showoperations="1" usesdiagramusefillcolor="1" showpubliconly="0" showpackage="1" x="20" showattsigs="601" showstereotype="1" y="10" showattributes="1" font="DejaVu Sans,9,-1,0,75,0,0,0,0,0" width="500" isinstance="0" usefillcolor="1" fillcolor="none" xmi.id="'.$this->class[$tablei].'" showscope="1" height="'.$y.'" showopsigs="601" />
								</widgets>
								<messages/>
								<associations/>
								</diagram>
						</diagrams>
					</XMI.extension>
				</UML:Model>
				<UML:Model stereotype="folder" visibility="public" isSpecification="false" namespace="m1" isAbstract="false" isLeaf="false" isRoot="false" xmi.id="Use Case View" name="Use Case View" >
					<UML:Namespace.ownedElement/>
				</UML:Model>
				<UML:Model stereotype="folder" visibility="public" isSpecification="false" namespace="m1" isAbstract="false" isLeaf="false" isRoot="false" xmi.id="Component View" name="Component View" >
					<UML:Namespace.ownedElement/>
				</UML:Model>
				<UML:Model stereotype="folder" visibility="public" isSpecification="false" namespace="m1" isAbstract="false" isLeaf="false" isRoot="false" xmi.id="Deployment View" name="Deployment View" >
					<UML:Namespace.ownedElement/>
				</UML:Model>
				<UML:Model stereotype="folder" visibility="public" isSpecification="false" namespace="m1" isAbstract="false" isLeaf="false" isRoot="false" xmi.id="Entity Relationship Model" name="Entity Relationship Model" >
					<UML:Namespace.ownedElement/>
				</UML:Model>
			</UML:Namespace.ownedElement>
		</UML:Model>
	</XMI.content>
	<XMI.extensions xmi.extender="umbrello" >
			<docsettings viewid="'.($this->cont).'" uniqueid="'.($this->count()).'" documentation="" />
			<listview>
				<listitem open="0" type="800" label="Views" >
					<listitem open="0" type="801" id="Logical View" >
						<listitem open="1" type="807" id="'.($this->db).'" label="'.($this->db).'" />
					</listitem>
				</listitem>
			</listview>
			<codegeneration>
			<codegenerator language="PHP5" />
			</codegeneration>
	</XMI.extensions>
</XMI>';
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
								
		function getclasstd($table=""){
			$ar=array();
			$ar1=array();
			$ar2=array();
			$ar3=array();
			$ar4=array();
			$ar5=array();
			$ar6=array();
			$ar7=array();
			$ar8=array();
			$ar0=array();
			if(strlen($table)>0){
				$ar1[$table]=$this->getfunctionset($table);
				$ar2[$table]=$this->getfunctionget($table);
				$ar0[$table]=$this->getfunctiondel($table);
				$ar3[$table]=$this->getsetclass($table);
				$ar4[$table]=$this->getnewobjectclass($table);
				$ar5[$table]=$this->getfunctionbuscaid($table);
				$ar6[$table]=$this->getfunctionbusca($table);
				$ar7[$table]=$this->getconstructclass($table);
				$ar8[$table]=$this->getiniclass($table);
				$ar[$table]=$this->getfinishclass($table);
			}else{
				$ar1=$this->getfunctionset($table);
				$ar2=$this->getfunctionget($table);
				$ar0=$this->getfunctiondel($table);
				$ar3=$this->getsetclass($table);
				$ar4=$this->getnewobjectclass($table);
				$ar5=$this->getfunctionbuscaid($table);
				$ar6=$this->getfunctionbusca($table);
				$ar7=$this->getconstructclass($table);
				$ar8=$this->getiniclass2($table);
				$ar=$this->getfinishclass2($table);
			}
			$ar9=$this->finish_cl();
			$ar10=$this->init_cl();
			$retorno=array();
			
			$retorno=array_merge_recursive($ar,$retorno);
			$retorno=array_merge_recursive($ar1,$retorno);
			$retorno=array_merge_recursive($ar2,$retorno);
			$retorno=array_merge_recursive($ar0,$retorno);
			$retorno=array_merge_recursive($ar3,$retorno);
			$retorno=array_merge_recursive($ar4,$retorno);
			$retorno=array_merge_recursive($ar5,$retorno);
			$retorno=array_merge_recursive($ar6,$retorno);
			$retorno=array_merge_recursive($ar7,$retorno);
			$retorno=array_merge_recursive($ar8,$retorno);
			if(strlen($table)>0){
				$retorno=implode("",$retorno[$table]);
			}else{
				$tmp="";
				foreach ($retorno as $class =>$arrai){
					$tmp.=implode("",$arrai);
				}
				$retorno=$ar10.$tmp.$ar9;
			}
			return $retorno;
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
				
				$ar1[$table]=$this->getfunctionset($table);
				$ar2[$table]=$this->getfunctionget($table);
				$ar3[$table]=$this->getfunctiondel($table);
				$ar4[$table]=$this->getsetclass($table);
				$ar5[$table]=$this->getnewobjectclass($table);
				$ar6[$table]=$this->getfunctionbuscaid($table);
				$ar7[$table]=$this->getfunctionbusca($table);
				$ar8[$table]=$this->getconstructclass($table);
				$ar9[$table]=$this->getiniclass($table);
				$ar[$table]=$this->getfinishclass($table);
			}else{
				
				$ar1=$this->getfunctionset();
				$ar2=$this->getfunctionget();
				$ar3=$this->getfunctiondel();
				$ar4=$this->getsetclass();
				$ar5=$this->getnewobjectclass();
				$ar6=$this->getfunctionbuscaid();
				$ar7=$this->getfunctionbusca();
				$ar8=$this->getconstructclass();
				$ar9=$this->getiniclass();
				$ar=$this->getfinishclass();
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
				$retorno=implode("",$retorno[$table]);
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
				exec("mkdir -p ".$this->pasta_to_save."/docs");
				exec("chmod 777 ".$this->pasta_to_save."/docs");
				if($fp=fopen($this->pasta_to_save."/docs/".$table.".xmi","w+")){
					if(fwrite($fp,"".$content."")){
						fclose($fp);
							exec("chmod 777 ".$this->pasta_to_save."/docs/".$table.".xmi");
						return 0;
					}
					return 1;
				}
				return 1;
			}else{
				$retorno=$this->getclass();
				$x=0;
				foreach ($retorno as $class =>$content){
					exec("mkdir -p ".$this->pasta_to_save."/docs/xmi/");
					exec("chmod -R 777 ".$this->pasta_to_save."/docs/");
					if($fp=fopen($this->pasta_to_save."/docs/xmi/".$class.".xmi","w+")){
						if(fwrite($fp,"".$content."")){
							fclose($fp);
							exec("chmod 777 ".$this->pasta_to_save."/docs/xmi/".$class.".xmi");
						}else $x+=1;
					}else $x+=1;
				}
				if($x>0) return $x;
				else return 0;
			}
		}
				
		function grava2(){
			$content=$this->getclasstd();
			exec("mkdir -p ".$this->pasta_to_save."/docs");
			exec("chmod -R 777 ".$this->pasta_to_save."/docs");
			$name=ereg_replace("[^a-zA-Z0-9_.]", "",$this->project);
			if($fp=fopen($this->pasta_to_save."/docs/".$name."_diagrama.xmi","w+")){
				if(fwrite($fp,"".$content."")){
					fclose($fp);
						exec("chmod 777 ".$this->pasta_to_save."/docs/".$name."_diagrama.xmi");
					return 0;
				}
				return 1;
			}
			return 1;
		}
	}
/*$cr=new geradordedoc("localhost","user","senha","bd_leo","user","NOME DO PROJETO","AUTOR <autor@dominio.com>","/pasta para salvar os dados");
echo "<pre>
";
print_r($cr->grava());
print_r($cr->grava2());
echo "
<pre>";*/
?>
