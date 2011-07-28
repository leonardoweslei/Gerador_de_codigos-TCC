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
                var $dictionary=array();
                var $types=array();
                function geradordeform($host,$user,$password,$db=false,$table=false,$project="Projeto criado pelo gerador de classes",$author="Leonardo Weslei Diniz <leonardoweslei@gmail.com>",$pasta_to_save="doc/",$dict=array(),$types=array()){
                        $this->user             = $user;
                        $this->password         = $password;
                        $this->host             = $host;
                        $this->db                       = $db;
                        $this->table    = $table;
                        $this->project  = $project;
                        $this->author   = $author;
                        $this->pasta_to_save= $pasta_to_save."/";
                        $this->dictionary       = $dict;
                        $this->types       = $types;
                        $this->servidor         = new DataBase
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
                function traducao($palavra){
                        return isset($this->dictionary[$palavra])?$this->dictionary[$palavra]:$palavra;
                }
                
                function traduz_campo($atributo,$action,$complemento="$",$complemento2="")
                {
                	if(!isset($this->types[$atributo]))return "";
                	else $type=$this->types[$atributo];
                	switch ($action)
                	{
                		case "list":
                			{
                				switch ($type)
                				{
			                		case "img":
			                			{
			                				return 'thumbimg('.$complemento.$atributo.$complemento2.')';
			                			}
			                		break;
			                		case "date":
			                			{
			                				return 'date2data('.$complemento.$atributo.')';
			                			}
			                		break;
			                		case "datetime":
			                			{
			                				return 'date2data('.$complemento.$atributo.'," as ")';
			                			}
			                		break;
			                		case "dateh":
			                			{
			                				return 'date2data('.$complemento.$atributo.')';
			                			}
			                		break;
			                		case "hidden":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "datetimeh":
			                			{
			                				return 'date2data('.$complemento.$atributo.'," as ")';
			                			}
			                		break;
			                		case "music":
			                			{
			                				return 'link_mus('.$complemento.$atributo.$complemento2.')';
			                			}
			                		break;
			                		case "video":
			                			{
			                				return 'link_video('.$complemento.$atributo.$complemento2.')';
			                			}
			                		break;
			                		case "mes":
			                			{
			                				return 'return_mes('.$complemento.$atributo.$complemento2.')';
			                			}
			                		break;
			                		case "estado":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "html":
			                			{
			                				return 'substr('.$complemento.$atributo.',0,150).(strlen('.$complemento.$atributo.')>150?\'...\':\'\')';
			                			}
			                		break;
			                		case "money":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "passwd":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "cep":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "tel":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "cpf":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "cnpj":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "text":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "int":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "email":
			                			{
			                				return '';
			                			}
			                		break;
			                		default:
			                			return '';
			                			break;
                				}
                			}
                		break;
                		case "edit":
                			{
                				switch ($type)
                				{
			                		case "img":
			                			{
			                				return 'up_file(\''.$atributo.'\''.$complemento2.$atributo.')';
			                			}
			                		break;
			                		case "date":
			                			{
			                				return 'data2date('.$complemento.')';
			                			}
			                		break;
			                		case "datetime":
			                			{
			                				return 'data2date('.$complemento.'," ")';
			                			}
			                		break;
			                		case "dateh":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "hidden":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "datetimeh":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "music":
			                			{
			                				return 'up_file_music(\''.$atributo.'\''.$complemento2.$atributo.')';
			                			}
			                		break;
			                		case "video":
			                			{
			                				return 'up_file_video(\''.$atributo.'\''.$complemento2.$atributo.')';
			                			}
			                		break;
			                		case "mes":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "estado":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "html":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "money":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "passwd":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "cep":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "tel":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "cpf":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "cnpj":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "text":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "int":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "email":
			                			{
			                				return '';
			                			}
			                		break;
			                		default:
			                			return '';
			                			break;
                				}
                			}
                		break;
                		case "fedit":
                			{
                				switch ($type)
                				{
			                		case "img":
			                			{
			                				return '<?=getfile("'.$atributo.'",$'.$atributo.');?>';
			                			}
			                		break;
			                		case "date":
			                			{
			                				return '<?=getcalendar("'.$atributo.'",$'.$atributo.',1);?>';
			                			}
			                		break;
			                		case "datetime":
			                			{
			                				return '<?=getcalendar("'.$atributo.'",date2data($'.$atributo.'," as "),2);?>';
			                			}
			                		break;
			                		case "dateh":
			                			{
			                				return '<input type="hidden" value="<?=$'.$atributo.';?>" name="'.$atributo.'"/>';
			                			}
			                		break;
			                		case "hidden":
			                			{
			                				return '<input type="hidden" value="<?=$'.$atributo.';?>" name="'.$atributo.'"/>';
			                			}
			                		break;
			                		case "datetimeh":
			                			{
			                				return '<input type="hidden" value="<?=$'.$atributo.';?>" name="'.$atributo.'"/>';
			                			}
			                		break;
			                		case "music":
			                			{
			                				return '<?=getfilemusic("'.$atributo.'",$'.$atributo.');?>';
			                			}
			                		break;
			                		case "video":
			                			{
			                				return '<?=getfilevideo("'.$atributo.'",$'.$atributo.');?>';
			                			}
			                		break;
			                		case "mes":
			                			{
			                				return '<?=selectmes($'.$atributo.',"'.$atributo.'");?>';
			                			}
			                		break;
			                		case "estado":
			                			{
			                				return '<?=selectestado($'.$atributo.',"'.$atributo.'");?>';
			                			}
			                		break;
			                		case "html":
			                			{
			                				return '<?=wysing("'.$atributo.'",$'.$atributo.');?>';
			                			}
			                		break;
			                		case "money":
			                			{
			                				return '<input name="'.$atributo.'" type="text" maxlength="10" size="10" value="<?=strlen($'.$atributo.')>0?$'.$atributo.':"0,00";?>" onkeypress="return(fmoney(this,\'.\',\',\',event));"/>';
			                			}
			                		break;
			                		case "passwd":
			                			{
			                				return '<input name="'.$atributo.'" type="passwd" maxlength="255" size="255" value="<?=$'.$atributo.';?>"/>';
			                			}
			                		break;
			                		case "cep":
			                			{
			                				return '<input name="'.$atributo.'" type="text" maxlength="9" size="9" value="<?=$'.$atributo.';?>" onkeypress="formatar(this, \'#####-###\', event);"/>';
			                			}
			                		break;
			                		case "tel":
			                			{
			                				return '<input name="'.$atributo.'" type="text" maxlength="13" size="13" value="<?=$'.$atributo.';?>" onkeypress="formatar(this, \'(##)####-####\', event);"/>';
			                			}
			                		break;
			                		case "cpf":
			                			{
			                				return '<input name="'.$atributo.'" type="text" maxlength="14" size="14" value="<?=$'.$atributo.';?>" onkeypress="formatar(this, \'###.###.###-##\', event);"/>';
			                			}
			                		break;
			                		case "cnpj":
			                			{
			                				return '<input name="'.$atributo.'" type="text" maxlength="18" size="18" value="<?=$'.$atributo.';?>" onkeypress="formatar(this, \'##.###.###/####-##\', event);"/>';
			                			}
			                		break;
			                		case "text":
			                			{
			                				return '<textarea name="'.$atributo.'"  maxlenght="'.$complemento.'" rows="5" cols="30"><?=$'.$atributo.'?></textarea>';
			                			}
			                		break;
			                		case "int":
			                			{
			                				return '<input name="'.$atributo.'" type="text" maxlength="'.$complemento.'" size="'.$complemento.'" value="<?=$'.$atributo.';?>" onkeypress="formatar(this, \''.str_repeat("#",$complemento).'\', event);"/>';
			                			}
			                		break;
			                		case "email":
			                			{
			                				return '';
			                			}
			                		break;
			                		default:
			                			return '';
			                			break;
                				}
                			}
                		break;
                		case "new":
                			{
                				switch ($type)
                				{
			                		case "img":
			                			{
			                				return 'up_file(\''.$atributo.'\')';
			                			}
			                		break;
			                		case "date":
			                			{
			                				return 'data2date('.$complemento.')';
			                			}
			                		break;
			                		case "datetime":
			                			{
			                				return 'data2date('.$complemento.'," ")';
			                			}
			                		break;
			                		case "dateh":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "hidden":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "datetimeh":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "music":
			                			{
			                				return 'up_file_music(\''.$atributo.'\')';
			                			}
			                		break;
			                		case "video":
			                			{
			                				return 'up_file_video(\''.$atributo.'\')';
			                			}
			                		break;
			                		case "mes":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "estado":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "html":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "money":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "passwd":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "cep":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "tel":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "cpf":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "cnpj":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "text":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "int":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "email":
			                			{
			                				return '';
			                			}
			                		break;
			                		default:
			                			return '';
			                			break;
                				}
                			}
                		break;
                		case "fnew":
                			{
                				switch ($type)
                				{
			                		case "img":
			                			{
			                				return '<?=getfile("'.$atributo.'",$'.$atributo.');?>';
			                			}
			                		break;
			                		case "date":
			                			{
			                				return '<?=getcalendar("'.$atributo.'",$'.$atributo.',1);?>';
			                			}
			                		break;
			                		case "datetime":
			                			{
			                				return '<?=getcalendar("'.$atributo.'",date2data($'.$atributo.'," as "),2);?>';
			                			}
			                		break;
			                		case "dateh":
			                			{
			                				return '<input type="hidden" value="<?=$'.$atributo.';?>" name="'.$atributo.'"/>';
			                			}
			                		break;
			                		case "hidden":
			                			{
			                				return '<input type="hidden" value="<?=$'.$atributo.';?>" name="'.$atributo.'"/>';
			                			}
			                		break;
			                		case "datetimeh":
			                			{
			                				return '<input type="hidden" value="<?=$'.$atributo.';?>" name="'.$atributo.'"/>';
			                			}
			                		break;
			                		case "music":
			                			{
			                				return '<?=getfilemusic("'.$atributo.'",$'.$atributo.');?>';
			                			}
			                		break;
			                		case "video":
			                			{
			                				return '<?=getfilevideo("'.$atributo.'",$'.$atributo.');?>';
			                			}
			                		break;
			                		case "mes":
			                			{
			                				return '<?=selectmes($'.$atributo.',"'.$atributo.'");?>';
			                			}
			                		break;
			                		case "estado":
			                			{
			                				return '<?=selectestado($'.$atributo.',"'.$atributo.'");?>';
			                			}
			                		break;
			                		case "html":
			                			{
			                				return '<?=wysing("'.$atributo.'",$'.$atributo.');?>';
			                			}
			                		break;
			                		case "money":
			                			{
			                				return '<input name="'.$atributo.'" type="text" maxlength="10" size="10" value="<?=strlen($'.$atributo.')>0?$'.$atributo.':"0,00";?>" onkeypress="return(fmoney(this,\'.\',\',\',event));"/>';
			                			}
			                		break;
			                		case "passwd":
			                			{
			                				return '<input name="'.$atributo.'" type="passwd" maxlength="255" size="255" value="<?=$'.$atributo.';?>"/>';
			                			}
			                		break;
			                		case "cep":
			                			{
			                				return '<input name="'.$atributo.'" type="text" maxlength="9" size="9" value="<?=$'.$atributo.';?>" onkeypress="formatar(this, \'#####-###\', event);"/>';
			                			}
			                		break;
			                		case "tel":
			                			{
			                				return '<input name="'.$atributo.'" type="text" maxlength="13" size="13" value="<?=$'.$atributo.';?>" onkeypress="formatar(this, \'(##)####-####\', event);"/>';
			                			}
			                		break;
			                		case "cpf":
			                			{
			                				return '<input name="'.$atributo.'" type="text" maxlength="14" size="14" value="<?=$'.$atributo.';?>" onkeypress="formatar(this, \'###.###.###-##\', event);"/>';
			                			}
			                		break;
			                		case "cnpj":
			                			{
			                				return '<input name="'.$atributo.'" type="text" maxlength="18" size="18" value="<?=$'.$atributo.';?>" onkeypress="formatar(this, \'##.###.###/####-##\', event);"/>';
			                			}
			                		break;
			                		case "text":
			                			{
			                				return '<textarea name="'.$atributo.'"  maxlenght="'.$complemento.'" rows="5" cols="30"><?=$'.$atributo.'?></textarea>';
			                			}
			                		break;
			                		case "int":
			                			{
			                				return '<input name="'.$atributo.'" type="text" maxlength="'.$complemento.'" size="'.$complemento.'" value="<?=$'.$atributo.';?>" onkeypress="formatar(this, \''.str_repeat("#",$complemento).'\', event);"/>';
			                			}
			                		break;
			                		case "email":
			                			{
			                				return '';
			                			}
			                		break;
			                		default:
			                			return '';
			                			break;
                				}
                			}
                		break;
                		case "del":
                			{
                				
                				switch ($type)
                				{
			                		case "img":
			                			{
			                				return 'if(strlen('.$complemento.$atributo.')>0)@unlink('.$complemento.$atributo.');';
			                			}
			                		break;
			                		case "date":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "datetime":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "dateh":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "hidden":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "datetimeh":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "music":
			                			{
			                				return 'if(strlen('.$complemento.$atributo.')>0)@unlink('.$complemento.$atributo.');';
			                			}
			                		break;
			                		case "video":
			                			{
			                				return 'if(strlen('.$complemento.$atributo.')>0)@unlink('.$complemento.$atributo.');';
			                			}
			                		break;
			                		case "mes":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "estado":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "html":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "money":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "passwd":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "cep":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "tel":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "cpf":
			                			{
			                				return '';
			                			}
			                		break;
			                		case "cnpj":
			                			{
			                				return '';
			                			}
			                		break;
			                		default:
			                			return '';
			                			break;
                				}
                			}
                		break;
                		case "valida":
                			{
                				
                				switch ($type)
                				{
			                		case "img":
			                			{
			                				return 'if(gN(\''.$atributo.'\')[0].value.length<=0 || gN(\''.$atributo.'\')[0].value.length=="")msg+="<br>'.$this->traducao($atributo).' - arquivo inv&aacute;lido!";';
			                			}
			                		break;
			                		case "date":
			                			{
			                				return 'if(!IsData(gN(\''.$atributo.'\')[0].value) || gN(\''.$atributo.'\')[0].value.length=="")msg+="<br>'.$this->traducao($atributo).' - data inv&aacute;lida!";';
			                			}
			                		break;
			                		case "datetime":
			                			{
			                				return 'if(!IsDataTime(gN(\''.$atributo.'\')[0].value) || gN(\''.$atributo.'\')[0].value.length=="")msg+="<br>'.$this->traducao($atributo).' - data e hora inv&aacute;lida!";';
			                			}
			                		break;
			                		case "dateh":
			                			{
			                				return 'if(!IsData(gN(\''.$atributo.'\')[0].value))msg+="<br>'.$this->traducao($atributo).' - data inv&aacute;lida!";';
			                			}
			                		break;
			                		case "hidden":
			                			{
			                				return 'if(gN(\''.$atributo.'\')[0].value.length<=0 || gN(\''.$atributo.'\')[0].value.length=="")msg+="<br>'.$this->traducao($atributo).' - valor inv&aacute;lido!";';
			                			}
			                		break;
			                		case "datetimeh":
			                			{
			                				return 'if(!IsDataTime(gN(\''.$atributo.'\')[0].value) || gN(\''.$atributo.'\')[0].value.length=="")msg+="<br>'.$this->traducao($atributo).' - data e hora inv&aacute;lida!";';
			                			}
			                		break;
			                		case "music":
			                			{
			                				return 'if(gN(\''.$atributo.'\')[0].value.length<=0 || gN(\''.$atributo.'\')[0].value.length=="")msg+="<br>'.$this->traducao($atributo).' - arquivo inv&aacute;lido!";';
			                			}
			                		break;
			                		case "video":
			                			{
			                				return 'if(gN(\''.$atributo.'\')[0].value.length<=0 || gN(\''.$atributo.'\')[0].value.length=="")msg+="<br>'.$this->traducao($atributo).' - arquivo inv&aacute;lido!";';
			                			}
			                		break;
			                		case "mes":
			                			{
			                				return 'if(gN(\''.$atributo.'\')[0].value.length<=0 || gN(\''.$atributo.'\')[0].value.length=="")msg+="<br>'.$this->traducao($atributo).' - op&ccedil;&atilde;o inv&aacute;lido!";';
			                			}
			                		break;
			                		case "estado":
			                			{
			                				return 'if(gN(\''.$atributo.'\')[0].value.length<=0 || gN(\''.$atributo.'\')[0].value.length=="")msg+="<br>'.$this->traducao($atributo).' - op&ccedil;&atilde;o inv&aacute;lido!";';
			                			}
			                		break;
			                		case "html":
			                			{
			                				return 'if(gN(\''.$atributo.'\')[0].value.length<=0 || gN(\''.$atributo.'\')[0].value.length=="")msg+="<br>'.$this->traducao($atributo).' - valor inv&aacute;lido!";';
			                			}
			                		break;
			                		case "money":
			                			{
			                				return 'if(!IsMoney(gN(\''.$atributo.'\')[0].value) || gN(\''.$atributo.'\')[0].value.length=="")msg+="<br>'.$this->traducao($atributo).' - valor inv&aacute;lido!";';
			                			}
			                		break;
			                		case "passwd":
			                			{
			                				return 'if(gN(\''.$atributo.'\')[0].value.length<=0 || gN(\''.$atributo.'\')[0].value.length=="")msg+="<br>'.$this->traducao($atributo).' - valor inv&aacute;lido!";';
			                			}
			                		break;
			                		case "cep":
			                			{
			                				return 'if(!IsNumVal(gN(\''.$atributo.'\')[0].value) || gN(\''.$atributo.'\')[0].value.length=="")msg+="<br>'.$this->traducao($atributo).' - número inv&aacute;lido!";';
			                			}
			                		break;
			                		case "tel":
			                			{
			                				return 'if(!IsTel(gN(\''.$atributo.'\')[0].value) || gN(\''.$atributo.'\')[0].value.length=="")msg+="<br>'.$this->traducao($atributo).' - telefone inv&aacute;lido!";';
			                			}
			                		break;
			                		case "cpf":
			                			{
			                				return 'if(!IsCPF(gN(\''.$atributo.'\')[0].value) || gN(\''.$atributo.'\')[0].value.length=="")msg+="<br>'.$this->traducao($atributo).' - CPF inv&aacute;lido!";';
			                			}
			                		break;
			                		case "cnpj":
			                			{
			                				return 'if(!IsCNPJ(gN(\''.$atributo.'\')[0].value) || gN(\''.$atributo.'\')[0].value.length=="")msg+="<br>'.$this->traducao($atributo).' - CNPJ inv&aacute;lido!";';
			                			}
			                		break;
			                		case "text":
			                			{
			                				return 'if(gN(\''.$atributo.'\')[0].value.length<=0 || gN(\''.$atributo.'\')[0].value.length=="")msg+="<br>'.$this->traducao($atributo).' - valor inv&aacute;lido!";';
			                			}
			                		break;
			                		case "int":
			                			{
			                				return 'if(!IsNumVal(gN(\''.$atributo.'\')[0].value) || gN(\''.$atributo.'\')[0].value.length=="")msg+="<br>'.$this->traducao($atributo).' - número inv&aacute;lido!";';
			                			}
			                		break;
			                		case "email":
			                			{
			                				return 'if(!IsEmail(gN(\''.$atributo.'\')[0].value) || gN(\''.$atributo.'\')[0].value.length=="")msg+="<br>'.$this->traducao($atributo).' - email inv&aacute;lido!";';
			                			}
			                		break;
			                		default:
			                				return 'if(gN(\''.$atributo.'\')[0].value.length<=0 || gN(\''.$atributo.'\')[0].value.length=="")msg+="<br>'.$this->traducao($atributo).' - valor inv&aacute;lido!";';
			                			break;
                				}
                			}
                		break;
                	}
                }
                function getformnew($table=false){
                        $info=$this->dbinfo();
                        $functions=array();
                        foreach($info as $tablei=>$fields){
                        	$funcao_valida=array();
                        		foreach($fields as $key=>$datafield){
                        			if($datafield["Null"]=="NO")
                        			{
                        				$funcao_valida[]=$this->traduz_campo($key,"valida");
                        			}
                        		}
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
        /*require_once(\"../conf.php\");*/".implode("",$dep);
                                $tmp=array();
                                $tmp[]="<?php
        \$title=\"Criar novo registro de ".$this->traducao($tablei).":\";
        include(\"head.php\");
$dep
$post
$mult
?>
<script type=\"text/javascript\">
	
	function showmsg(m){gE('msgform').innerHTML=m;}
	function validafm()
	{
		var msg=\"\";
		showmsg(\"\");
		".implode("
		",$funcao_valida)."
		if(msg.length>0)
		{
			showmsg(\"Erro:\"+msg);
			return false;
		}
		return true;
	}
</script>
                                        <form action=\"new_$tablei.php\" method=\"POST\" name=\"new_$tablei\" enctype=\"multipart/form-data\" onsubmit=\"return validafm();\">
                                                <table class=\"form_new_reg\">";
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
                                    	$input2=$this->traduz_campo($key,'fnew',$tam);
                                        if ($datafield['Key']!="PRI" && $datafield['Key']!="MUL" && $this->types[$key]!='hidden' && $this->types[$key]!='dateh' && $this->types[$key]!='datetimeh')
                                        {
                                                $tmp[]="
                                            <tr>
                                                <td>
                                                                        ".$this->traducao($key).":
                                                </td>
                                                <td>
                                                                        ".(strlen($input2)>0?$input2:$input)."
                                                </td>
                                            </tr>";
                                        }else if ($datafield['Key']=="MUL" && $this->types[$key]!='hidden' && $this->types[$key]!='dateh' && $this->types[$key]!='datetimeh')
                                        {
                                                $tmp[]="
                                            <tr>
                                                <td>
                                                                        ".$this->traducao($key).":
                                                </td>
                                                <td>
                                                                        <select name=\"$key\"><option value=\"\">Selecione</option><?=\$tmp_$key;?></select>
                                                </td>
                                            </tr>";
                                        }else if ($this->types[$key]=='hidden' || $this->types[$key]=='dateh' || $this->types[$key]=='datetimeh')
                                        {
                                        	$tmp[]=(strlen($input2)>0?$input2:$input);
                                        }else
                                        {
                                        	$tmp[]=$input;
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
										<div id=\"msgform\"></div>
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
                        		$funcao_valida=array();
                        		foreach($fields as $key=>$datafield){
                        			if($datafield["Null"]=="NO")
                        			{
                        				$funcao_valida[]=$this->traduz_campo($key,"valida");
                        			}
                        		}
                                $get=$this->getifsattr("PRI",$tablei,"
        \$ATTRIBUTE_HERE=\$_POST['ATTRIBUTE_HERE'];
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
        require_once(\"../conf.php\");".$dep.$dep2;
                                $tmp=array();
                                $tmp[]="<?php
        \$title=\"Editar ".$this->traducao($tablei).":\";
        include(\"head.php\");
$dep
$get
$post
$mult
?>
<script type=\"text/javascript\">
	
	function showmsg(m){gE('msgform').innerHTML=m;}
	function validafm()
	{
		var msg=\"\";
		showmsg(\"\");
		".implode("
		",$funcao_valida)."
		if(msg.length>0)
		{
			showmsg(\"Erro:\"+msg);
			return false;
		}
		return true;
	}
</script>
                                        <form action=\"edit_$tablei.php\" method=\"POST\" name=\"edit_$tablei\" enctype=\"multipart/form-data\" onsubmit=\"return validafm();\">
                                                <table class=\"form_edit_reg\">";
                                foreach($fields as $key=>$datafield)
                                {
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
                                    	$input2=$this->traduz_campo($key,'fedit',$tam);
                                        if ($datafield['Key']!="PRI" && $datafield['Key']!="MUL" && $this->types[$key]!='hidden' && $this->types[$key]!='dateh' && $this->types[$key]!='datetimeh')
                                        {
                                                $tmp[]="
                                            <tr>
                                                <td>
                                                                        ".$this->traducao($key).":
                                                </td>
                                                <td>
                                                                        ".(strlen($input2)>0?$input2:$input)."
                                                </td>
                                            </tr>";
                                        }else if ($datafield['Key']=="MUL" && $this->types[$key]!='hidden' && $this->types[$key]!='dateh' && $this->types[$key]!='datetimeh')
                                        {
                                                $tmp[]="
                                            <tr>
                                                <td>
                                                                        ".$this->traducao($key).":
                                                </td>
                                                <td>
                                                                        <select name=\"$key\"><option value=\"\">Selecione</option><?=\$tmp_$key;?></select>
                                                </td>
                                            </tr>";
                                        }else if ($this->types[$key]=='hidden' || $this->types[$key]=='dateh' || $this->types[$key]=='datetimeh')
                                        {
                                        	$tmp[]=(strlen($input2)>0?$input2:$input);
                                        }else
                                        {
                                        	$tmp[]=$input;
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
										<div id=\"msgform\"></div>
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
                        foreach($info as $tablei=>$fields)
                    	{
                    			$get="";
                    			$post="";
                                foreach($fields as $key=>$datafield)
                                {
                                    $cpt=explode(")",$datafield["Type"]);
                                    $cpt=$cpt[0];
                                    $cpt=explode(" ",$cpt);
                                    $cpt=$cpt[0];
                                    $cpt=explode("(",$cpt);
                                    $tipo=$cpt[0];
                                    $tam=$cpt[1];
                                	$input2=$this->traduz_campo($key,'new',"\$_GET['".$key."']");
                                	$input3=$this->traduz_campo($key,'new',"\$_POST['".$key."']");
	                                $get.="\n\t\$".$key."=".(strlen($input2)>0?$input2:"\$_GET['".$key."']").";";
	                                $post.="\n\t\$".$key."=".(strlen($input3)>0?$input3:"\$_POST['".$key."']").";";
                                }
                                $param=$this->getifsattr("",$tablei,"\$ATTRIBUTE_HERE");
                                $param=implode(",",$param);
                                $ifs=$this->getifsattr("PRI",$tablei,"
        \$tmp_ATTRIBUTE_T_HERE=new ATTRIBUTE_T_HERE($param);
        \$tmp_ATTRIBUTE_T_HERE=\$tmp_ATTRIBUTE_T_HERE->newATTRIBUTE_T_HERE();
        if(is_numeric(\$tmp_ATTRIBUTE_T_HERE)){
                echo \"
                <script type=\\\"text/javascript\\\">
                        //window.opener.document.location.reload();
                        window.alert(\\\"Dados Salvos!\\\");
						location.href='listing_ATTRIBUTE_T_HERE.php';
                        //window.close();
                </script>
                \";
        }else{
                echo \"
                <script type=\\\"text/javascript\\\">
                        window.alert(\\\"N&atilde;o foi possivel salvar os dados!\\\");
                        //history.go(-1);
						location.href='listing_ATTRIBUTE_T_HERE.php';
                        //window.close();
                </script>
                \";
                //include(\"form_new_".$tablei.".php\");
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
        require_once(\"../conf.php\");".$dep.$dep2;
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
                        		
                    			$get="";
                    			$post="";
                                foreach($fields as $key=>$datafield)
                                {
                                    $cpt=explode(")",$datafield["Type"]);
                                    $cpt=$cpt[0];
                                    $cpt=explode(" ",$cpt);
                                    $cpt=$cpt[0];
                                    $cpt=explode("(",$cpt);
                                    $tipo=$cpt[0];
                                    $tam=$cpt[1];
                                	$input2=$this->traduz_campo($key,'edit',"\$_GET['".$key."']",",\$tmp0_".$tablei."->");
                                	$input3=$this->traduz_campo($key,'edit',"\$_POST['".$key."']",",\$tmp0_".$tablei."->");
	                                $get.="\n\t\$".$key."=".(strlen($input2)>0?$input2:"\$_GET['".$key."']").";";
	                                $post.="\n\t\$".$key."=".(strlen($input3)>0?$input3:"\$_POST['".$key."']").";";
                                }
                                $param=$this->getifsattr("",$tablei,"\$ATTRIBUTE_HERE");
                                $param=implode(",",$param);
                                $init=$this->getifsattr("PRI",$tablei,"
        \$tmp0_ATTRIBUTE_T_HERE=new ATTRIBUTE_T_HERE(\$_POST['ATTRIBUTE_HERE']);
        \$tmp0_ATTRIBUTE_T_HERE->searchATTRIBUTE_T_HEREforkey(\$_POST['ATTRIBUTE_HERE']);");
                                $ifs=$this->getifsattr("PRI",$tablei,"
        \$tmp_ATTRIBUTE_T_HERE=new ATTRIBUTE_T_HERE();
        \$tmp_ATTRIBUTE_T_HERE=\$tmp_ATTRIBUTE_T_HERE->setATTRIBUTE_T_HERE($param);
        if(\$tmp_ATTRIBUTE_T_HERE==1){
                echo \"
                <script type=\\\"text/javascript\\\">
                        //window.opener.document.location.reload();
                        window.alert(\\\"Dados alterados!\\\");
						location.href='listing_ATTRIBUTE_T_HERE.php';
                        //window.close();
                </script>
                \";
        }else{
                echo \"
                <script type=\\\"text/javascript\\\">
                        window.alert(\\\"N&atilde;o foi possivel alterar os dados!\\\");
                        //history.go(-1);
						location.href='listing_ATTRIBUTE_T_HERE.php';
                        //window.close();
                </script>
                \";
                //include(\"form_edit_".$tablei.".php\");
        }
        ");
                                $ifs=implode(",",$ifs);
                                $init=implode(",",$init);
                                $dep=$this->getifsattr("MUL",$tablei,"
        conf_require(\"/class/ATTRIBUTE_T_HERE.php\");");
                                $dep=implode("",$dep);
                                $dep2=$this->getifsattr("PRI",$tablei,"
        conf_require(\"/class/ATTRIBUTE_T_HERE.php\");");
                                $dep2=implode("",$dep2);
                                $dep="
        require_once(\"../conf.php\");".$dep.$dep2;
                                $tmp=array();
                                $tmp[]="<?php
$dep
$init
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
                        	
                    			$get="";
                    			$post="";
                                foreach($fields as $key=>$datafield)
                                {
                                    $cpt=explode(")",$datafield["Type"]);
                                    $cpt=$cpt[0];
                                    $cpt=explode(" ",$cpt);
                                    $cpt=$cpt[0];
                                    $cpt=explode("(",$cpt);
                                    $tipo=$cpt[0];
                                    $tam=$cpt[1];
                                	$input2=$this->traduz_campo($key,'del',"\$tmp_".$tablei."->");
                                	$input3=$this->traduz_campo($key,'del',"\$tmp_".$tablei."->");
	                                $get.=(strlen($input2)>0?"\n\t".$input2:"");
	                                $post.=(strlen($input3)>0?"\n\t".$input3:"");
                                }
                                $ifs=$this->getifsattr("PRI",$tablei,"
        \$dados=isset(\$_POST['ATTRIBUTE_HERE'])?\$_POST['ATTRIBUTE_HERE']:(isset(\$_GET['ATTRIBUTE_HERE'])?\$_GET['ATTRIBUTE_HERE']:array());
        \$erros=0;
        foreach(\$dados as \$ATTRIBUTE_HERE){
                if(strlen(\$ATTRIBUTE_HERE)>0){
                        \$tmp_ATTRIBUTE_T_HERE=new ATTRIBUTE_T_HERE(\$ATTRIBUTE_HERE);
                        \$tmp_ATTRIBUTE_T_HERE->searchATTRIBUTE_T_HEREforkey(\$ATTRIBUTE_HERE);
                        $post
                        \$tmp_ATTRIBUTE_T_HERE=\$tmp_ATTRIBUTE_T_HERE->delATTRIBUTE_T_HERE(\$ATTRIBUTE_HERE);
                        if(\$tmp_ATTRIBUTE_T_HERE==false){
                                \$erros++;
                        }
                }
        }
        if(\$erros==0){
                echo \"
                <script type=\\\"text/javascript\\\">
                        window.opener.document.location.reload();
                        window.alert(\\\"Registro(s) Apagado(s)!\\\");
                        window.close();
                        //window.history.go(-1);
                </script>
                \";
        }else{
                echo \"
                <script type=\\\"text/javascript\\\">
                        window.alert(\\\"Nao foi possivel apagar registro(s)\\\\nOuveram \".\$erros.\" erros!\\\");
                        window.close();
                        //window.history.go(-1);
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
        require_once(\"../conf.php\");".$dep.$dep2;
                                $tmp=array();
                                $post="";
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
                        $param="";
                        $param2="";
                        foreach($info as $tablei=>$fields){
                                $param=$this->getifsattr("",$tablei,"\$_GET['ATTRIBUTE_HERE'],");
                                $param=implode("",$param);
                                $param2=$param."\$order.\$order2";
                                $param.='$order.$order2,$lim.",".$_MAX_RESULT_TO_PAGE_';
                                $get=$this->getelementochave($tablei,"all");
                                $total_campos=count($get)+1;
                                $id=$this->getelementochave($tablei,"PRI");
                                $id=$id[0];
                                $dep=$this->getifsattr("MUL",$tablei,"
        conf_require(\"/class/ATTRIBUTE_T_HERE.php\");");
                                $dep="
        require_once(\"../conf.php\");".implode("",$dep);
                                $depp=$this->getifsattr("PRI",$tablei,"
        conf_require(\"/class/ATTRIBUTE_T_HERE.php\");");
                                $depp=implode("",$depp);
                                $tmp=array();
                                $tmp2="";
                                $tmp3="";
                                $tmp3="";
                                foreach($fields as $key=>$datafield){
                                        $tmp2.='
                                                        <option value="'.$key.'"<?=(isset($_GET[\''.$key.'\'])?" selected":"");?>>'.$this->traducao($key).'</option>';
                                        $tmp3.='
        $_ADD_PAGING2_.=isset($_GET[\''.$key.'\'])?"&'.$key.'=".$_GET[\''.$key.'\']:"";
        $queryb.=isset($_GET[\''.$key.'\'])?$_GET[\''.$key.'\']:"";';
                                }
                                $tmp[]='<?
        $title="Listagem de '.$this->traducao($tablei).'(s)";
        include("head.php");
        $_ADD_PAGING2_="";
        $queryb="";
        '.$dep.'
        '.$tmp3.'
        '.$depp.'
        $'.$tablei.'s=new '.$tablei.'();
        $error="";
        $_ADD_PAGING_=isset($_GET[\'order\'])?"&order=".$_GET[\'order\']:"";
        $_ADD_PAGING_.=isset($_GET[\'order2\'])?"&order2=".$_GET[\'order2\']:"";
        $order=isset($_GET[\'order\'])?$_GET[\'order\']:"'.$id.'";
        $order2=isset($_GET[\'order2\'])?$_GET[\'order2\']:" ASC";
        $_MAX_RESULT_TO_PAGE_ = 10;
        $_MAX_PAGES_SHOW_ = 10;
        $_PAGE_NOW_ = $_GET["pg"];
        if(strlen($_PAGE_NOW_)==0){
            $_PAGE_NOW_ = 1;  
        }
        $_PAGE_NOW_=$_PAGE_NOW_-1;
        $lim=$_PAGE_NOW_*$_MAX_RESULT_TO_PAGE_;
        $dados=$'.$tablei.'s->search'.$tablei.'('.$param.');
        $dados2=$'.$tablei.'s->search'.$tablei.'('.$param2.');
    $_COUNTER_RESULTS_=0;// Declaração da pagina inicial  
?>

                                <input type="text" name="s" id="s" value="<?=$queryb;?>">
                                <select name="b" id="b">
                                '.$tmp2.'
                                </select>
                                <input type="button" onclick="location.href=\'?\'+brows(\'b\').value+\'=\'+brows(\'s\').value;" value="Buscar">
                                <form name="form_listing_'.$tablei.'" action="del_'.$tablei.'.php" method="post" enctype="multipart/form-data" target="_blank">
                                        <table width="100%" border="0" cellpadding="5" cellspacing="1" class="listing_reg">
                                                <tr class="listing_title"> 
                                                        <td width="'.(100/($total_campos)).'%">
                                                                <div align="center"><font  onClick="marcaTudo(document.form_listing_'.$tablei.',\''.$id.'[]\');">X</font></div>
                                                        </td>';
                                foreach($fields as $key=>$datafield){
                                        $cpt=explode(")",$datafield["Type"]);
                                        $cpt=$cpt[0];
                                        $cpt=explode(" ",$cpt);
                                        $cpt=$cpt[0];
                                        $cpt=explode("(",$cpt);
                                        $tipo=$cpt[0];
                                        $tam=$cpt[1];
                                        $tmp[]='
                                                        <td width="'.(100/($total_campos)).'%">
                                                                <strong>
                                                                        <font  size="2" face="Verdana, Arial, Helvetica, sans-serif"><?="<a style=\"text-decoration:none;\" href=\"?order='.$key.'&order2=".(($order=="'.$key.'" &&$order2==" ASC")?" DESC":" ASC")."\">";?>'.$this->traducao($key).'<?="</a>";?></font>
                                                                </strong>
                                                        </td>';
                                }
                                $tmp[]='	
                                                </tr>
<?
        $_CSS=0;
    foreach($dados as $'.$tablei.'){
                echo \'
                                <tr class="\'.((($_CSS%2)==0)?"listing_row_1":"listing_row_2").\'">
                                        <td width="'.(100/($total_campos)).'%">
                                                <strong>
                                                        <input type="checkbox" name="'.$id.'[]" value="\'.$'.$tablei.'->'.$id.'.\'">
                                                </strong>
                                        </td>';
                                foreach($fields as $key=>$datafield){
                                        $cpt=explode(")",$datafield["Type"]);
                                        $cpt=$cpt[0];
                                        $cpt=explode(" ",$cpt);
                                        $cpt=$cpt[0];
                                        $cpt=explode("(",$cpt);
                                        $tipo=$cpt[0];
                                        $tam=$cpt[1];
                                    	$input=$this->traduz_campo($key,'list','$'.$tablei.'->');
                                        $tmp[]='
                                        <td width="'.(100/($total_campos)).'%">
                                                <font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                                                '.(($key==$id)?'<a href="javascript:void(0);" onclick="location.href=\\\'form_edit_'.$tablei.'.php?'.$id.'=\'.$'.$tablei.'->'.$id.'.\'\\\';" alt="editar">':'').'
                                                \'.'.(strlen($input)>0?$input:'$'.$tablei.'->'.$key).'.\''.(($key==$id)?'</a>':'').'
                                                </font>
                                        </td>';
                                }
                                $tmp[]='
                                </tr>\';
                $_CSS++;
                $_COUNTER_RESULTS_++;
        }
        if ($_COUNTER_RESULTS_==0){
                echo \'
                                        <tr> 
                                                <td colspan="'.($total_campos+1).'"  class="listing_row_1" align="center">
                                                        <font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                                                                Sem registros!
                                                        </font>
                                                </td>
                                </tr>\';
        }else{
                $_TOTAL_RESULTS_=count($dados2);
                // Calculando pagina anterior  
            $_PAGE_NOW_ = $_PAGE_NOW_ + 1;  
            $_PREVIOUS_PAGE_ = $_PAGE_NOW_ - 1;  
                // Calculando pagina posterior  
            $_NEXT_PAGE_ = $_PAGE_NOW_ + 1;
        $de  = ($_COUNTER_RESULTS_==$_MAX_RESULT_TO_PAGE_) ? (($_PAGE_NOW_)*$_MAX_RESULT_TO_PAGE_): $_TOTAL_RESULTS_;
                $ini = ($_COUNTER_RESULTS_>0)? (($_PAGE_NOW_-1)*$_MAX_RESULT_TO_PAGE_)+1 : 0 ;
                $_TOTAL_PAGES_ = ceil($_TOTAL_RESULTS_/$_MAX_RESULT_TO_PAGE_);
                $__PAGING__= "Exibindo de resultado de ".$ini." a ".$de."&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$_TOTAL_RESULTS_." Registro(s)".(($_TOTAL_PAGES_>1)?"&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;$_TOTAL_PAGES_ Paginas&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;":"");
    if($_TOTAL_PAGES_ > 1 ){
        // Mostragem de pagina  
        if($_PREVIOUS_PAGE_ > 0) {
           $__PAGING__.= "<b><a style=\"text-decoration:none;\" href=\"?pg=1".$_ADD_PAGING_.$_ADD_PAGING2_."\" title=\"primeira\"><font size=\"1\"><<</font></a>&nbsp;&nbsp;&nbsp;";  
           $__PAGING__.= "<b><a style=\"text-decoration:none;\" href=\"?pg=$_PREVIOUS_PAGE_".$_ADD_PAGING_.$_ADD_PAGING2_."\" title=\"anterior\"><font size=\"1\"><</font></a>&nbsp;&nbsp;";  
        }  
        $totalreg=array();
        // Listando as paginas  
        for($_COUNTER_RESULTS_=1;$_COUNTER_RESULTS_ <= $_TOTAL_PAGES_;$_COUNTER_RESULTS_++){
            if($_COUNTER_RESULTS_<=($_PAGE_NOW_<=($_MAX_PAGES_SHOW_/2)?10:($_PAGE_NOW_+($_MAX_PAGES_SHOW_/2))) && $_COUNTER_RESULTS_>=($_PAGE_NOW_<=($_MAX_PAGES_SHOW_/2)?1:($_PAGE_NOW_-($_MAX_PAGES_SHOW_/2)))
                ){
                        if($_COUNTER_RESULTS_ != $_PAGE_NOW_){  
                       $__PAGING__.="  <a style=\"text-decoration:none;\" href=\"?pg=".($_COUNTER_RESULTS_)."".$_ADD_PAGING_.$_ADD_PAGING2_."\" title=\"Pagina #{$_COUNTER_RESULTS_}\">$_COUNTER_RESULTS_</a>&nbsp;";  
                    } else{  
                        $__PAGING__.="  <strong title=\"Pagina Atual\">[".$_COUNTER_RESULTS_."]</strong>&nbsp;";  
                    }
            }
            $totalreg[$_COUNTER_RESULTS_]=(($_MAX_RESULT_TO_PAGE_*$_COUNTER_RESULTS_)>$_TOTAL_RESULTS_)?($_MAX_RESULT_TO_PAGE_-(($_MAX_RESULT_TO_PAGE_*$_COUNTER_RESULTS_)-$_TOTAL_RESULTS_)):4;
        }  
        if($_NEXT_PAGE_ <= $_TOTAL_PAGES_) {
           $__PAGING__.="   <a style=\"text-decoration:none;\" href=\"?pg=$_NEXT_PAGE_".$_ADD_PAGING_.$_ADD_PAGING2_."\" title=\"proxima\"><font size=\"1\">></font></a>&nbsp;&nbsp;</b>";  
           $__PAGING__.="   <a style=\"text-decoration:none;\" href=\"?pg=$_TOTAL_PAGES_".$_ADD_PAGING_.$_ADD_PAGING2_."\" title=\"ultima\"><font size=\"1\">>></font></a></b>";  
        }  
    }
        }
?>
                                                <tr class="listing_title"> 
                                                        <td height="9" colspan="'.($total_campos+1).'">
                                                                <strong>
                                                                        <font  size="2" face="Verdana, Arial, Helvetica, sans-serif"><?=$__PAGING__;?></font>
                                                                </strong>
                                                        </td>
                                                </tr>
                                                <tr class="listing_title"> 
                                                        <td height="9" colspan="'.($total_campos+1).'" align="center">
                                                                <strong>
                                                                        <font  size="2" face="Verdana, Arial, Helvetica, sans-serif">
                                                                        <input type="submit" value="excluir" name="apaga">
                                                                        <input type="button" value="Novo" name="novo" onclick="location.href=\'form_new_'.$tablei.'.php\';">
                                                                        </font>
                                                                </strong>
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

                function grava($table=false,$allinone=false){
                        if($table!=false){
                                $x=0;
                                $content=$this->getforms($table);
                                $content=is_string($content)?$content:$content[$table];
                                foreach ($content as $arq=>$form){
                                        $form=is_string($form)?$form:implode("",$form);
                                        exec("mkdir -p ".$this->pasta_to_save."/forms/".(($allinone!=false)?$table:"")."");
                                        exec("chmod -R 777 ".$this->pasta_to_save."/forms/".(($allinone!=false)?$table:"")."");
                                        if($fp=fopen($this->pasta_to_save."/forms/".(($allinone!=false)?$table."/":"").$arq.".php","w+")){
                                                if(fwrite($fp,"".$form."")){
                                                        fclose($fp);
                                                                exec("chmod 777 ".$this->pasta_to_save."/forms/".(($allinone!=false)?$table."/":"").$arq.".php");
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
                                                exec("mkdir -p ".$this->pasta_to_save."/forms/".(($allinone!=false)?$table."/":""));
                                                exec("chmod -R 777 ".$this->pasta_to_save."/forms/".(($allinone!=false)?$table."/":""));
                                                if($fp=fopen($this->pasta_to_save."/forms/".(($allinone!=false)?$table."/":"").$arq.".php","w+")){
                                                        if(fwrite($fp,"".$content."")){
                                                                fclose($fp);
                                                                exec("chmod 777 ".$this->pasta_to_save."/forms/".(($allinone!=false)?$table."/":"").$arq.".php");
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
