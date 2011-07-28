<?php
	require_once("class/database.php");
/**
 * Gerador de Classes
 * @version 1.0 
 *
 */
	class geradordeform
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
		function geradordeform($host,$user,$password,$db=false,$table=false,$project="Projeto criado pelo gerador de classes",$author="Leonardo Weslei Diniz <leonardoweslei@gmail.com>",$pasta_to_save="doc/"){
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
		
		function getformnew($table=false){
			$info=$this->dbinfo();
			$functions=array();
			foreach($info as $tablei=>$fields){
				$get=$this->getelementochave($tablei,"all");
				$get=$this->getifsattr("",$tablei,"\n\t\$ATTRIBUTE_HERE=\$_GET['ATTRIBUTE_HERE'];");
				$get=implode("",$get);
				$post=$this->getifsattr("",$tablei,"\n\t\$ATTRIBUTE_HERE=\$_POST['ATTRIBUTE_HERE'];");
				$post=implode("",$post);
				$mult=$this->getifsattr("MUL",$tablei,"
	\$array_ATTRIBUTE_HERE= new ATTRIBUTE_T_HERE();
	\$array_ATTRIBUTE_HERE= \$array_ATTRIBUTE_HERE->searchATTRIBUTE_T_HERE();
	\$tmp_ATTRIBUTE_HERE=array();
	foreach(\$array_ATTRIBUTE_HERE as \$unit_ATTRIBUTE_HERE){
		\$sele=(\$ATTRIBUTE_HERE==\$unit_ATTRIBUTE_HERE->SUB_ATTRIBUTE_HERE)?\" select\":\"\";
		\$tmp_ATTRIBUTE_HERE[]=\"<option value=\\\"\".\$unit_ATTRIBUTE_HERE->SUB_ATTRIBUTE_HERE.\"\\\" \$sele>\$unit_ATTRIBUTE_HERE->SUB_ATTRIBUTE_HERE</option>\";
	}
	\$tmp_ATTRIBUTE_HERE=implode(\"\",\$tmp_ATTRIBUTE_HERE);
	");
				$mult=implode("",$mult);
				$dep=$this->getifsattr("MUL",$tablei,"
	conf_require(\"/class/ATTRIBUTE_T_HERE.php\");");
				$dep="
	require_once(\"conf.php\");".implode("",$dep);
				$tmp=array();
				$tmp[]="<?php
	include(\"head.php\");
$dep
$post
$mult
?>
					<form action=\"new_$tablei.php\" method=\"POST\" name=\"new_$tablei\" enctype=\"multipart/form-data\">
						<table>
				            <tr>
				                <td colspan=\"2\">
									Novo(a) $tablei
				                </td>
				            </tr>";
				foreach($fields as $key=>$datafield){
					$cpt=explode(")",$datafield["Type"]);
					$cpt=$cpt[0];
					$cpt=explode(" ",$cpt);
					$cpt=$cpt[0];
					$cpt=explode("(",$cpt);
					$tipo=$cpt[0];
					$tam=$cpt[1];
					switch($tipo){
						case "varchar":
						case "char":
							$max=is_numeric($tam)?" maxlenght=\"".$tam."\"":"";
							$input="<input name=\"$key\" type=\"text\" $max size=\"30\" value=\"<?=$$key?>\"/>";
							break;
						case "date":
							$input="<input name=\"$key\" type=\"text\" size=\"30\" maxlenght=\"10\" value=\"<?=$$key?>\"/>";
							break;
						case "text":
							$max=is_numeric($tam)?" maxlenght=\"".$tam."\"":"";
							$input="<textarea name=\"$key\" $max size=\"30\" rows=\"5\" cols=\"25.5\"><?=$$key?></textarea>";
							break;
						case "datetime":
							$input="<input name=\"$key\" type=\"text\" size=\"30\" maxlenght=\"22\" value=\"<?=$$key?>\"/>";
							//2009-11-11 as 12:00:00
							break;
						case "int":
							$input="<input name=\"$key\" type=\"text\" size=\"30\" maxlenght=\"22\" value=\"<?=$$key?>\"/>";
							break;
						case "float":
							$input="<input name=\"$key\" type=\"text\" size=\"30\" maxlenght=\"22\" value=\"<?=$$key?>\"/>";
							break;
						case "enum":
							$tam=explode(",",$tam);
							$tmp2=array();
							foreach($tam as $o=>$op){
								$op=str_replace("'","",$op);
								$op=str_replace("\"","",$op);
								$tmp2[]="<option value=\"$op\" <?=($$key=='$op')?'selected':'';?>>$op</option>";
							}
							$input="<select name=\"$key\">\n".implode("\n",$tmp2)."\n</select>";
							break;
						default:
							$input="<input name=\"$key\" type=\"text\" size=\"30\" maxlenght=\"22\" value=\"<?=$$key?>\"/>";
							break;
					}
					if ($datafield['Key']!="PRI" && $datafield['Key']!="MUL"){
						$tmp[]="
				            <tr>
				                <td>
									$key
				                </td>
				                <td>
									$input
				                </td>
				            </tr>";
					}
					if ($datafield['Key']=="MUL"){
						$tmp[]="
				            <tr>
				                <td>
									$key
				                </td>
				                <td>
									<select name=\"$key\"><?=\$tmp_$key;?></select>
				                </td>
				            </tr>";
					}
				}
				$tmp[]="
				            <tr>
				                <td colspan=\"2\">
									<input type=\"submit\" value=\"Enviar\"/>&nbsp;<input type=\"reset\" value=\"Limpar\"/>
				                </td>
				            </tr>
						</table>
					</form>
					<? include(\"foot.php\");?>";
				$tmp=implode("",$tmp);
				$functions[$tablei]["form_new_".$tablei]=$tmp;
			}
			$info=$functions;
			return (strlen($table)>0)?(!isset($info[$table])?array():$info[$table]):$info;
		}

		function getformedit($table=false){
			$info=$this->dbinfo();
			$functions=array();
			foreach($info as $tablei=>$fields){
				$get=$this->getifsattr("PRI",$tablei,"
	\$ATTRIBUTE_HERE=\$_GET['ATTRIBUTE_HERE'];
	\$ATTRIBUTE_HERE=new ATTRIBUTE_T_HERE(\$ATTRIBUTE_HERE);
	\$ATTRIBUTE_HERE=\$ATTRIBUTE_HERE->searchATTRIBUTE_T_HERE();
	\$ATTRIBUTE_HERE=\$ATTRIBUTE_HERE[0];
	");
				$id=$this->getelementochave($tablei,"PRI");
				$id=$id[0];
				
				$get=implode("",$get);
				$post=array();
				foreach($fields as $key=>$datafield){
					if ($datafield['Key']!="PRI")$post[]="
	\$$key=\$".$id."->$key;
	";
				}
				$post=implode("",$post)."
	\$".$id."=\$".$id."->".$id.";
	";
				$mult=$this->getifsattr("MUL",$tablei,"
	\$array_ATTRIBUTE_HERE= new ATTRIBUTE_T_HERE();
	\$array_ATTRIBUTE_HERE= \$array_ATTRIBUTE_HERE->searchATTRIBUTE_T_HERE();
	\$tmp_ATTRIBUTE_HERE=array();
	foreach(\$array_ATTRIBUTE_HERE as \$unit_ATTRIBUTE_HERE){
		\$sele=(\$ATTRIBUTE_HERE==\$unit_ATTRIBUTE_HERE->SUB_ATTRIBUTE_HERE)?\" select\":\"\";
		\$tmp_ATTRIBUTE_HERE[]=\"<option value=\\\"\".\$unit_ATTRIBUTE_HERE->SUB_ATTRIBUTE_HERE.\"\\\" \$sele>\$unit_ATTRIBUTE_HERE->SUB_ATTRIBUTE_HERE</option>\";
	}
	\$tmp_ATTRIBUTE_HERE=implode(\"\",\$tmp_ATTRIBUTE_HERE);
	");
				$mult=implode("",$mult);
				$dep=$this->getifsattr("MUL",$tablei,"
	conf_require(\"/class/ATTRIBUTE_T_HERE.php\");");
				$dep=implode("",$dep);
				$dep2=$this->getifsattr("PRI",$tablei,"
	conf_require(\"/class/ATTRIBUTE_T_HERE.php\");");
				$dep2=implode("",$dep2);
				$dep="
	require_once(\"conf.php\");".$dep.$dep2;
				$tmp=array();
				$tmp[]="<?php
	include(\"head.php\");
$dep
$get
$post
$mult
?>
					<form action=\"edit_$tablei.php\" method=\"POST\" name=\"edit_$tablei\" enctype=\"multipart/form-data\">
						<table>
				            <tr>
				                <td colspan=\"2\">
									Editar $tablei
				                </td>
				            </tr>";
				foreach($fields as $key=>$datafield){
					$cpt=explode(")",$datafield["Type"]);
					$cpt=$cpt[0];
					$cpt=explode(" ",$cpt);
					$cpt=$cpt[0];
					$cpt=explode("(",$cpt);
					$tipo=$cpt[0];
					$tam=$cpt[1];
					switch($tipo){
						case "varchar":
						case "char":
							$max=is_numeric($tam)?" maxlenght=\"".$tam."\"":"";
							$input="<input name=\"$key\" type=\"text\" $max size=\"30\" value=\"<?=$$key?>\"/>";
							break;
						case "date":
							$input="<input name=\"$key\" type=\"text\" size=\"30\" maxlenght=\"10\" value=\"<?=$$key?>\"/>";
							break;
						case "text":
							$max=is_numeric($tam)?" maxlenght=\"".$tam."\"":"";
							$input="<textarea name=\"$key\" $max size=\"30\" rows=\"5\" cols=\"25.5\"><?=$$key?></textarea>";
							break;
						case "datetime":
							$input="<input name=\"$key\" type=\"text\" size=\"30\" maxlenght=\"22\" value=\"<?=$$key?>\"/>";
							//2009-11-11 as 12:00:00
							break;
						case "enum":
							$tam=explode(",",$tam);
							$tmp2=array();
							foreach($tam as $o=>$op){
								$op=str_replace("'","",$op);
								$op=str_replace("\"","",$op);
								$tmp2[]="<option value=\"$op\" <?=($$key=='$op')?'selected':'';?>>$op</option>";
							}
							$input="<select name=\"$key\">\n".implode("\n",$tmp2)."\n</select>";
							break;
						default:
							break;
					}
					if ($datafield['Key']!="PRI" && $datafield['Key']!="MUL"){
						$tmp[]="
				            <tr>
				                <td>
									$key
				                </td>
				                <td>
									$input
				                </td>
				            </tr>";
					}
					if ($datafield['Key']=="PRI"){
						$tmp[]="
									<input type=\"hidden\" value=\"<?=$$key;?>\" name=\"$key\"/>";
					}
					if ($datafield['Key']=="MUL"){
						$tmp[]="
				            <tr>
				                <td>
									$key
				                </td>
				                <td>
									<select name=\"$key\"><?=\$tmp_$key;?></select>
				                </td>
				            </tr>";
					}
				}
				$tmp[]="
				            <tr>
				                <td colspan=\"2\">
									<input type=\"submit\" value=\"Enviar\"/>&nbsp;<input type=\"reset\" value=\"Limpar\"/>
				                </td>
				            </tr>
						</table>
					</form>
					<? include(\"foot.php\");?>";
				$tmp=implode("",$tmp);
				$functions[$tablei]["form_edit_".$tablei]=$tmp;
			}
			$info=$functions;
			return (strlen($table)>0)?(!isset($info[$table])?array():$info[$table]):$info;
		}
		
		function getformpostnew($table=false){
			$info=$this->dbinfo();
			$functions=array();
			foreach($info as $tablei=>$fields){
				$get=$this->getelementochave($tablei,"all");
				$get=$this->getifsattr("",$tablei,"\n\t\$ATTRIBUTE_HERE=\$_GET['ATTRIBUTE_HERE'];");
				$get=implode("",$get);
				$post=$this->getifsattr("",$tablei,"\n\t\$ATTRIBUTE_HERE=\$_POST['ATTRIBUTE_HERE'];");
				$post=implode("",$post);
				
				$param=$this->getifsattr("",$tablei,"\$ATTRIBUTE_HERE");
				$param=implode(",",$param);
				
				$ifs=$this->getifsattr("PRI",$tablei,"
	\$tmp_ATTRIBUTE_T_HERE=new ATTRIBUTE_T_HERE($param);
	\$tmp_ATTRIBUTE_T_HERE=\$tmp_ATTRIBUTE_T_HERE->newATTRIBUTE_T_HERE();
	if(is_numeric(\$tmp_ATTRIBUTE_T_HERE)){
		echo \"
		<script type=\\\"text/javascript\\\">
			window.opener.document.location.reload();
			window.alert(\\\"Dados Salvos!\\\");
			window.close();
		</script>
		\";
	}else{
		echo \"
		<script type=\\\"text/javascript\\\">
			window.alert(\\\"N&atilde;o foi possivel salvar os dados!\\\");
			//window.close();
		</script>
		\";
		include(\"form_new_".$tablei.".php\");
	}
	");
				$ifs=implode(",",$ifs);
				
				
				$dep=$this->getifsattr("MUL",$tablei,"
	conf_require(\"/class/ATTRIBUTE_T_HERE.php\");");
				$dep=implode("",$dep);
				$dep2=$this->getifsattr("PRI",$tablei,"
	conf_require(\"/class/ATTRIBUTE_T_HERE.php\");");
				$dep2=implode("",$dep2);
				$dep="
	require_once(\"conf.php\");".$dep.$dep2;
				$tmp=array();
				$tmp[]="<?php
$dep
$post
$ifs
?>";
				$tmp=implode("",$tmp);
				$functions[$tablei]["new_".$tablei]=$tmp;
			}
			$info=$functions;
			return (strlen($table)>0)?(!isset($info[$table])?array():$info[$table]):$info;
		}

		function getformpostedit($table=false){
			$info=$this->dbinfo();
			$functions=array();
			foreach($info as $tablei=>$fields){
				$get=$this->getelementochave($tablei,"all");
				$get=$this->getifsattr("",$tablei,"\n\t\$ATTRIBUTE_HERE=\$_GET['ATTRIBUTE_HERE'];");
				$get=implode("",$get);
				$post=$this->getifsattr("",$tablei,"\n\t\$ATTRIBUTE_HERE=\$_POST['ATTRIBUTE_HERE'];");
				$post=implode("",$post);
				
				$param=$this->getifsattr("",$tablei,"\$ATTRIBUTE_HERE");
				$param=implode(",",$param);
				
				$ifs=$this->getifsattr("PRI",$tablei,"
	\$tmp_ATTRIBUTE_T_HERE=new ATTRIBUTE_T_HERE();
	\$tmp_ATTRIBUTE_T_HERE=\$tmp_ATTRIBUTE_T_HERE->setATTRIBUTE_T_HERE($param);
	if(\$tmp_ATTRIBUTE_T_HERE==1){
		echo \"
		<script type=\\\"text/javascript\\\">
			window.opener.document.location.reload();
			window.alert(\\\"Dados alterados!\\\");
			window.close();
		</script>
		\";
	}else{
		echo \"
		<script type=\\\"text/javascript\\\">
			window.alert(\\\"N&atilde;o foi possivel alterar os dados!\\\");
			//window.close();
		</script>
		\";
		include(\"form_edit_".$tablei.".php\");
	}
	");
				$ifs=implode(",",$ifs);
				
				
				$dep=$this->getifsattr("MUL",$tablei,"
	conf_require(\"/class/ATTRIBUTE_T_HERE.php\");");
				$dep=implode("",$dep);
				$dep2=$this->getifsattr("PRI",$tablei,"
	conf_require(\"/class/ATTRIBUTE_T_HERE.php\");");
				$dep2=implode("",$dep2);
				$dep="
	require_once(\"conf.php\");".$dep.$dep2;
				$tmp=array();
				$tmp[]="<?php
$dep
$post
$ifs
?>";
				$tmp=implode("",$tmp);
				$functions[$tablei]["edit_".$tablei]=$tmp;
			}
			$info=$functions;
			return (strlen($table)>0)?(!isset($info[$table])?array():$info[$table]):$info;
		}

		function getformdel($table=false){
			$info=$this->dbinfo();
			$functions=array();
			foreach($info as $tablei=>$fields){
				$ifs=$this->getifsattr("PRI",$tablei,"
	\$ATTRIBUTE_HERE=isset(\$_GET['ATTRIBUTE_HERE'])?\$_GET['ATTRIBUTE_HERE']:false;
	if(\$ATTRIBUTE_HERE!=false){
		\$tmp_ATTRIBUTE_T_HERE=new ATTRIBUTE_T_HERE();
		\$tmp_ATTRIBUTE_T_HERE=\$tmp_ATTRIBUTE_T_HERE->delATTRIBUTE_T_HERE(\$ATTRIBUTE_HERE);
	}
	if(\$tmp_ATTRIBUTE_T_HERE!=false && \$ATTRIBUTE_HERE!=false){
		echo \"
		<script type=\\\"text/javascript\\\">
			window.opener.document.location.reload();
			window.alert(\\\"Registro Apagado!\\\");
			window.close();
		</script>
		\";
	}else{
		echo \"
		<script type=\\\"text/javascript\\\">
			window.alert(\\\"Nao foi possivel apagar registro!\\\");
			window.close();
		</script>
		\";
	}
	");
				$ifs=implode(",",$ifs);
				
				
				$dep=$this->getifsattr("MUL",$tablei,"
	conf_require(\"/class/ATTRIBUTE_T_HERE.php\");");
				$dep=implode("",$dep);
				$dep2=$this->getifsattr("PRI",$tablei,"
	conf_require(\"/class/ATTRIBUTE_T_HERE.php\");");
				$dep2=implode("",$dep2);
				$dep="
	require_once(\"conf.php\");".$dep.$dep2;
				$tmp=array();
				$tmp[]="<?php
$dep
$post
$ifs
?>";
				$tmp=implode("",$tmp);
				$functions[$tablei]["del_".$tablei]=$tmp;
			}
			$info=$functions;
			return (strlen($table)>0)?(!isset($info[$table])?array():$info[$table]):$info;
		}
		
		function getformlisting($table=false){
			$info=$this->dbinfo();
			$functions=array();
			foreach($info as $tablei=>$fields){
				$param=$this->getifsattr("",$tablei,"\$_GET['ATTRIBUTE_HERE'],");
				$param=implode("",$param);
				$param.="\$order.\$order2,\$_GET['limit']";
				$get=$this->getelementochave($tablei,"all");
				$total_campos=count($get);
				$id=$this->getelementochave($tablei,"PRI");
				$id=$id[0];
				$dep=$this->getifsattr("MUL",$tablei,"
	conf_require(\"/class/ATTRIBUTE_T_HERE.php\");");
				$dep="
	require_once(\"conf.php\");".implode("",$dep);
				$depp=$this->getifsattr("PRI",$tablei,"
	conf_require(\"/class/ATTRIBUTE_T_HERE.php\");");
				$depp=implode("",$depp);
				$tmp=array();
				$tmp[]='<?
	$title="Listagem de '.$tablei.' cadastradas";
	include("head.php");
	'.$dep.'
	'.$depp.'
	$'.$tablei.'s=new '.$tablei.'();
	$error="";
	$_ADD_PAGING_=isset($_GET[\'order\'])?"&order=".$_GET[\'order\']:"";
	$_ADD_PAGING_.=isset($_GET[\'order2\'])?"&order2=".$_GET[\'order2\']:"";
	$order=isset($_GET[\'order\'])?$_GET[\'order\']:"'.$id.'";
	$order2=isset($_GET[\'order2\'])?$_GET[\'order2\']:" ASC";
	$dados=$'.$tablei.'s->search'.$tablei.'('.$param.');
    $_COUNTER_RESULTS_=0;// Declaração da pagina inicial  
	$_PAGE_NOW_ = $_GET["pg"];
	if($_PAGE_NOW_ == ""){
	    $_PAGE_NOW_ = "1";  
	}
	// Maximo de registros por pagina  
	$_MAX_RESULT_TO_PAGE_ = 10;
	// Calculando o registro inicial  
	$_PAGE_INIT_ = $_PAGE_NOW_ - 1;
	$_PAGE_INIT_ = $_MAX_RESULT_TO_PAGE_ * $_PAGE_INIT_;
?>
					<table width="100%" border="0" cellpadding="5" cellspacing="1">
						<tr> ';
				foreach($fields as $key=>$datafield){
					$cpt=explode(")",$datafield["Type"]);
					$cpt=$cpt[0];
					$cpt=explode(" ",$cpt);
					$cpt=$cpt[0];
					$cpt=explode("(",$cpt);
					$tipo=$cpt[0];
					$tam=$cpt[1];
					$tmp[]='
							<td width="'.(100/($total_campos+2)).'%" bgcolor="#999900">
								<strong>
									<font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><?="<a style=\"color:#FFF;text-decoration:none;\" href=\"?order='.$key.'&order2=".(($order=="'.$key.'" &&$order2==" ASC")?" DESC":" ASC")."\">";?>'.$key.'<?="</a>";?></font>
								</strong>
							</td>';
				}
				$tmp[]='
							<td width="'.(100/($total_campos+2)).'%" bgcolor="#999900">
								<div align="center"><font color="#FFFFFF">&nbsp;</font></div>
							</td>
							<td width="'.(100/($total_campos+2)).'%" bgcolor="#999900">
								<div align="center">
									<font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif">
										<a href="javascript:void(0);" onclick="window.open(\'form_new_'.$tablei.'.php\',\'new_'.$tablei.'\');"">New</a>
									</font>
								</div>
							</td>
						</tr>
<?
    foreach($dados as $'.$tablei.'){
		if($_COUNTER_RESULTS_>=$_PAGE_INIT_){
	    		echo \'
						<tr> ';
				foreach($fields as $key=>$datafield){
					$cpt=explode(")",$datafield["Type"]);
					$cpt=$cpt[0];
					$cpt=explode(" ",$cpt);
					$cpt=$cpt[0];
					$cpt=explode("(",$cpt);
					$tipo=$cpt[0];
					$tam=$cpt[1];
					$tmp[]='
							<td width="'.(100/($total_campos+2)).'%" bgcolor="#FFFFCC">
								<font size="2" face="Verdana, Arial, Helvetica, sans-serif">\'.$'.$tablei.'->'.$key.'.\'</font>
							</td>';
				}
				$tmp[]='
							<td width="'.(100/($total_campos+2)).'%" bgcolor="#FFFFCC">
								<font size="2" face="Verdana, Arial, Helvetica, sans-serif">
									<a href="javascript:void(0);" onclick="window.open(\\\'form_edit_'.$tablei.'.php?'.$id.'=\'.$'.$tablei.'->'.$id.'.\'\\\',\\\''.$tablei.'\\\');">editar</a>
								</font>
							</td>
							<td width="'.(100/($total_campos+2)).'%" bgcolor="#FFFFCC">
								<font size="2" face="Verdana, Arial, Helvetica, sans-serif">
									<a href="javascript:void(0);" onclick="window.open(\\\'del_'.$tablei.'.php?'.$id.'=\'.$'.$tablei.'->'.$id.'.\'\\\',\\\''.$tablei.'\\\');">deletar</a>
								</font>
							</td>
            			</tr>\';
    }
  		if($_COUNTER_RESULTS_>=($_PAGE_INIT_+$_MAX_RESULT_TO_PAGE_-1)){
  			break;
  		}
  		$_COUNTER_RESULTS_++;
	}
	if ($_COUNTER_RESULTS_==0){
    		echo \'
						<tr> 
							<td colspan="'.($total_campos+1).'" bgcolor="#FFFFCC" align="center">
								<font size="2" face="Verdana, Arial, Helvetica, sans-serif">
									Sem registros!
								</font>
							</td>
            			</tr>\';
	}else{
		$_TOTAL_RESULTS_=count($dados);
		// Calculando pagina anterior  
	    $_PREVIOUS_PAGE_ = $_PAGE_NOW_ - 1;  
		// Calculando pagina posterior  
	    $_NEXT_PAGE_ = $_PAGE_NOW_ + 1;
		$_TOTAL_PAGES_ = ceil($_TOTAL_RESULTS_/$_MAX_RESULT_TO_PAGE_);
		$__PAGING__= "Exibimdo de resultado de ".($_PAGE_INIT_+1)." a ".($_PAGE_NOW_==1?$_COUNTER_RESULTS_+1:(($_COUNTER_RESULTS_<($_MAX_RESULT_TO_PAGE_*$_PAGE_NOW_))?$_COUNTER_RESULTS_:$_PAGE_INIT_))."&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$_TOTAL_RESULTS_." Registros".(($_TOTAL_PAGES_>1)?"&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;$_TOTAL_PAGES_ Paginas&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;":"");
    if($_TOTAL_PAGES_ > 1 ){
        // Mostragem de pagina  
        if($_PREVIOUS_PAGE_ > 0) {
           $__PAGING__.= "<b><a style=\"text-decoration:none;\" href=\"?pg=1".$_ADD_PAGING_."\" title=\"Primeira\"><font size=\"1\"><<</font></a>&nbsp;&nbsp;&nbsp;";  
           $__PAGING__.= "<b><a style=\"text-decoration:none;\" href=\"?pg=$_PREVIOUS_PAGE_".$_ADD_PAGING_."\" title=\"Anterior\"><font size=\"1\"><</font></a>&nbsp;&nbsp;";  
        }  
        // Listando as paginas  
        for($_COUNTER_RESULTS_=1;$_COUNTER_RESULTS_ <= $_TOTAL_PAGES_;$_COUNTER_RESULTS_++){  
            if($_COUNTER_RESULTS_ != $_PAGE_NOW_){  
               $__PAGING__.="  <a style=\"text-decoration:none;\" href=\"?pg=".($_COUNTER_RESULTS_)."".$_ADD_PAGING_."\" title=\"Pagina Atual\">$_COUNTER_RESULTS_</a>&nbsp;";  
            } else {  
                $__PAGING__.="  <strong title=\"Pagina {$_COUNTER_RESULTS_}\">".$_COUNTER_RESULTS_."</strong>&nbsp;";  
            }  
        }  
        if($_NEXT_PAGE_ <= $_TOTAL_PAGES_) {
           $__PAGING__.="   <a style=\"text-decoration:none;\" href=\"?pg=$_NEXT_PAGE_".$_ADD_PAGING_."\" title=\"próxima\"><font size=\"1\">></font></a>&nbsp;&nbsp;</b>";  
           $__PAGING__.="   <a style=\"text-decoration:none;\" href=\"?pg=$_TOTAL_PAGES_".$_ADD_PAGING_."\" title=\"Ultima\"><font size=\"1\">>></font></a></b>";  
        }  
    }
	}
?>

						<tr> 
							<td height="9" bgcolor="#999900" colspan="'.($total_campos+1).'">
								<strong>
									<font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><?=$__PAGING__;?></font>
								</strong>
							</td>
							<td width="'.(100/($total_campos+2)).'%" bgcolor="#999900">
								<div align="center">
									<font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif">
										<a href="javascript:void(0);" onclick="window.open(\'form_new_'.$tablei.'.php\',\'new_'.$tablei.'\');"">New</a>
									</font>
								</div>
							</td>
						</tr>
					</table>
					<? include("foot.php");?>';
				$tmp=implode("",$tmp);
				$functions[$tablei]["listing_".$tablei]=$tmp;
			}
			$info=$functions;
			return (strlen($table)>0)?(!isset($info[$table])?array():$info[$table]):$info;
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
					if ($datafield['Key']=="MUL"&& $mod=="MUL"){
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
							$table_r=($table_rp)?$table_rp:$table;
							$tmp[$key]=str_replace("ATTRIBUTE_HERE",$key,str_replace("SUB_ATTRIBUTE_HERE",$subkey,str_replace("ATTRIBUTE_T_HERE",$table_r,$cond_r)));
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
		
		function getforms($table=""){
			$ar1=array();
			$ar2=array();
			$ar3=array();
			$ar4=array();
			$ar5=array();
			$ar6=array();
			if(strlen($table)>0){
				$ar1[$table]=$this->getformnew($table);
				$ar2[$table]=$this->getformedit($table);
				$ar3[$table]=$this->getformpostnew($table);
				$ar4[$table]=$this->getformpostedit($table);
				$ar5[$table]=$this->getformdel($table);
				$ar6[$table]=$this->getformlisting($table);
			}else{
				$ar1=$this->getformnew($table);
				$ar2=$this->getformedit($table);
				$ar3=$this->getformpostnew($table);
				$ar4=$this->getformpostedit($table);
				$ar5=$this->getformdel($table);
				$ar6=$this->getformlisting($table);
			}
			$retorno=array();
			$retorno=array_merge_recursive($ar1,$retorno);
			$retorno=array_merge_recursive($ar2,$retorno);
			$retorno=array_merge_recursive($ar3,$retorno);
			$retorno=array_merge_recursive($ar4,$retorno);
			$retorno=array_merge_recursive($ar5,$retorno);
			$retorno=array_merge_recursive($ar6,$retorno);
			/*if(strlen($table)>0){
				$retorno=implode("",$retorno);
			}else{
				$tmp=array();
				foreach ($retorno as $class =>$arrai){
					$tmp[$class]=(is_array($arrai))?implode("",$arrai):$arrai;
				}
				$retorno=$tmp;
			}*/
			return $retorno;
		}
				
		function grava($table=false){
			if($table!=false){
				$x=0;
				$content=$this->getforms($table);
				$content=is_string($content)?$content:$content[$table];
				foreach ($content as $arq=>$form){
					$form=is_string($form)?$form:implode("",$form);
					exec("mkdir -p ".$this->pasta_to_save."/forms/".$table."");
					exec("chmod -R 777 ".$this->pasta_to_save."/forms/".$table."");
					if($fp=fopen($this->pasta_to_save."/forms/".$table."/".$arq.".php","w+")){
						if(fwrite($fp,"".$form."")){
							fclose($fp);
								exec("chmod 777 ".$this->pasta_to_save."/forms/".$table."/".$arq.".php");
						}else{
							$x+=1;
						}
					}else{
						$x+=1;
					}
				}
				exec("chmod -R 777 ".$this->pasta_to_save."/forms/");
				if($x>0) return $x;
				else return 0;
			}else{
				$retorno=$this->getforms();
				$x=0;
				foreach ($retorno as $table=>$aray){
					foreach ($aray as $arq=>$content){
						exec("mkdir -p ".$this->pasta_to_save."/forms/".$table."/");
						exec("chmod -R 777 ".$this->pasta_to_save."/forms/".$table."/");
						if($fp=fopen($this->pasta_to_save."/forms/".$table."/".$arq.".php","w+")){
							if(fwrite($fp,"".$content."")){
								fclose($fp);
								exec("chmod 777 ".$this->pasta_to_save."/forms/".$table."/".$arq.".php");
							}else $x+=1;
						}else $x+=1;
					}
				}
				exec("chmod -R 777 ".$this->pasta_to_save."/forms/");
				if($x>0) return $x;
				else return 0;
			}
		}
		
	}
/*$cr=new geradordeform("localhost","user","senha","banco",false,"NOME DO PROJETO","AUTOR <autor@dominio.com>","/pasta para salvar os dados");
echo "<pre>
";
/*$tmp=$cr->getforms("users");
print_r($tmp);*/
/*print_r($cr->grava());
//print_r($cr->return);
echo "
<pre>";*/
?>
