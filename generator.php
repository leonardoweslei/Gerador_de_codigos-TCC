<?php
ini_set('max_execution_time', 900); // Onde 60 é o tempo em segundos
require ('class/database.php');
require ('class/geradordeclasse.php');
require ('class/geradordedoc.php');
require ('class/geradordeform.php');
require('class/zip.lib.php');

function delTree($dir){
    $files = glob($dir . '*', GLOB_MARK );
    foreach($files as $file){
        if( substr( $file, -1 ) == '/' )
            delTree( $file );
        else
            unlink( $file );
    }
    if (is_dir($dir)) rmdir( $dir );
} 
if(
	strlen($_POST['host'])<=0 ||
    strlen($_POST['user'])<=0 ||
    strlen($_POST['passwd'])<=0 ||
    strlen($_POST['db'])<=0
   )
{
	include("index.php");
	exit(0);
}

$database =@ new DataBase(
    $_POST['host'],
    $_POST['user'],
    $_POST['passwd'],
    $_POST['db']
);
if(!@$database->get_db_info())
{
	include("index.php");
	exit(0);
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<title>Gerador de classes versao 1.0</title>
		<script type="text/javascript">
			<!--
			function selectall(campo,opt) {
				var aselect = campo;
				var aselectLen = aselect.length;
				for(i = 0; i < aselectLen; i++) {
					aselect.options[i].selected = opt;
				}
			}
			-->
		</script> 
	</head>
	<body>
		<center>
			<fieldset style="width: 600px;">
				<legend>
					<font face="Arial" size="3">
						<b>
							Gerador de classes versao 1.0
						</b>
					</font>
				</legend>
				<br/>
				<form name="todo" action="generator.php" method="post" enctype="multipart/form-data">
					<fieldset style="width: 200px;">
						<legend>
							<font face="Arial" size="3">
								<b>Tables</b>
							</font>
						</legend>
					<?
					$tabelas=array();
					$tablelist =$database->get_db_info();
					?>
					<a href="javascript:void(0);" onclick="selectall(document.getElementById('tables'),true);">todos</a><br>
					<a href="javascript:void(0);" onclick="selectall(document.getElementById('tables'),false);">nenhum</a><br>
					<select multiple="multiple" name="tables[]" size="<?=count($tablelist);?>" id="tables">
						<?
						$dict="";
						$types="";
						foreach ($tablelist as $table=>$arrai)
						{
								$dict.="\n".$table."=".$table.",\n";
								$types.="\n";
							foreach ($arrai as $campo=>$data)
							{
								$dict.=$data['Field']."=".$data['Field'].",\n";
								$types.=$data['Field']."=,\n";
							}
						echo "
						<option value=\"".$table."\">".$tabelas[] = $table."</option>";
						}
				?>
					</select>
				</fieldset>
				<br/>
				<fieldset style="width: 300px;">
					<legend>
						<b>Gerar:</b>
					</legend>
					<a href="javascript:void(0);" onclick="selectall(document.getElementById('ger'),true);">todos</a><br>
					<a href="javascript:void(0);" onclick="selectall(document.getElementById('ger'),false);">nenhum</a><br>
					<select name="ger[]" multiple="multiple" size="3" id="ger">
						<option value="f">Forms</option>
						<option value="c">Classes</option>
						<option value="d">Docs</option>
					</select>
					<br>
					Gerar todos os formularios em uma pasta separada?
					Sim<input type="radio" name="allinone" value="s"><br>
					N&atilde;o<input type="radio" name="allinone" value="n">
					Ambos<input type="radio" name="allinone" value="a" checked>
					<br>
					Gerar o diagrama xmi do banco de dados?
					Sim<input type="radio" name="d2" value="s" checked><br>
					N&atilde;o<input type="radio" name="d2" value="n">
				</fieldset>
				<br/>
				<br/>
				<fieldset style="width: 400px;">
					<legend>
						<b>Dicionario:</b>
					</legend>
					<br>
					Exemplo:uid=Codigo,uname=Nome<br>
					<textarea name="dict" rows="6" cols="40"><?=isset($_POST['dict'])?$_POST['dict']:$dict;?></textarea>
				</fieldset>
				<br/>
				<br/>
				<fieldset style="width: 400px;">
					<legend>
						<b>Tipos de dados:</b>
					</legend>
					<br>
					Exemplo:img=img,data=date,codigo=cpf<br>
					<textarea name="types" rows="6" cols="40"><?=isset($_POST['types'])?$_POST['types']:$types;?></textarea>
				</fieldset>
				<br/>
				<fieldset style="width: 300px;">
					<legend>
						<b>Dados do Projeto:</b>
					</legend>
					<b>Nome do Projeto:</b>
					<br>
					<input type="text" name="project" id="project" value="<?=$_POST['project'];?>">
					<br>
					<b>Nome do autor:</b>
					<br>
					<input type="text" name="autor" id="autor" value="<?=$_POST['autor'];?>">
					<input type="hidden" name="host" value="<?=$database->host;?>">
					<input type="hidden" name="user" value="<?=$database->user;?>">
					<input type="hidden" name="passwd" value="<?=$database->password;?>">
					<input type="hidden" name="db" value="<?=$database->db;?>">
				</fieldset>
					<br>
					<input type="submit" value="gerar" name="gerar">
				<br>
<?php
if($_POST["gerar"]) //submit form
{
	echo '
				<fieldset style="width: 90%;">
					<legend>
						<b>Geraçao concluida!</b>
					</legend>
					<div align="center" style="text-align:left;width:90%;overflow:auto;">';
	$user=$_POST['user'];
	$passwd=$_POST['passwd'];
	$db=$_POST['db'];
	$host=$_POST['host'];
	$tables=$_POST['tables'];
	$allinone=($_POST['allinone']!="n")?"s":false;
	$allinone2=($_POST['allinone']=="a")?true:false;
	$project=$_POST['project'];
	$autor=$_POST['autor'];
	$d2=$_POST['d2'];
	$dict=$_POST['dict'];
	$dict=explode(",",$dict);
	$dictt=array(
		/*"agenda"=>"agenda",
		"aid"=>"Codigo:",
		"auid"=>"Autor",
		"alocal"=>"Local",
		"acidade"=>"Cidade",
		"aestado"=>"Estado",
		"adataehora"=>"Data do evento",
		"aobs"=>"Obs.",
		"coments"=>"comentario",
		"cid"=>"Codigo",
		"ccid"=>"Resposta a",
		"ctable"=>"Tabela de referencia",
		"ctid"=>"Campo referenciado",
		"cuid"=>"Autor",
		"ctext"=>"Conteudo",
		"cdatahora"=>"Data de criação",
		"entries"=>"entrada",
		"eid"=>"Codigo",
		"etype"=>"Tipo",
		"euid"=>"Autor",
		"edatahora"=>"Data de criação",
		"etitle"=>"Titulo",
		"etext"=>"Conteudo",
		"gallery"=>"galeria",
		"gid"=>"Codigo",
		"guid"=>"Autor",
		"gdatatime"=>"Data de criação",
		"gcapa"=>"Capa",
		"gtitle"=>"Titulo",
		"gdesc"=>"Descrição",
		"menu"=>"menu",
		"mid"=>"Codigo",
		"muid"=>"Autor",
		"mtext"=>"Titulo",
		"murl"=>"Url",
		"mativado"=>"Ativado",
		"mordem"=>"Posição no menu",
		"msub"=>"Sub-Menu de origem",
		"module_page"=>"modulo",
		"mpid"=>"Codigo",
		"mppid"=>"Codigo da Pagina",
		"mpmid"=>"Codigo do modulo",
		"modules"=>"modulo",
		"mid"=>"Codigo",
		"mtitle"=>"Titulo",
		"mcontent"=>"Conteudo",
		"murl"=>"Url",
		"page"=>"pagina",
		"pid"=>"Codigo",
		"puid"=>"Autor",
		"ptitle"=>"Titulo",
		"pcontent"=>"Conteudo",
		"purl"=>"Url",
		"photo_entries"=>"foto",
		"pid"=>"Codigo",
		"peid"=>"Entrada de origem",
		"purl"=>"Url",
		"pcoment"=>"Comentario",
		"photos"=>"foto",
		"pid"=>"Codigo",
		"pgid"=>"Galeria de origem",
		"purl"=>"Url",
		"pcoment"=>"Comentario",
		"users"=>"usuario",
		"uid"=>"Codigo",
		"utype"=>"Tipo",
		"unome"=>"Nome",
		"udatanasc"=>"Data de nascimento",
		"uendereco"=>"Endereco",
		"ucomplemento"=>"Complemento",
		"ubairro"=>"Bairro",
		"ucidade"=>"Cidade",
		"uestado"=>"Estado",
		"ucep"=>"Cep",
		"upais"=>"Pais.",
		"uemail"=>"Email",
		"utel"=>"Tel.",
		"ucel"=>"Cel.",
		"usexo"=>"Sexo",
		"ulogin"=>"Login",
		"usenha"=>"Senha",
		"udatacad"=>"Data de cadastro",
		"uativado"=>"Ativado"*/
		);
	$tmp=array();
	foreach ($dict as $palavras){
		$tmp=explode("=",$palavras);
		$campo1=trim($tmp[0]);
		$campo2=str_replace("\n","",str_replace("\t","",$tmp[1]));
		$dictt[$campo1]=htmlentities($campo2);
		$tmp=array();
		$campo1=$campo2="";
	}
	$dict=$dictt;
	
	$types=$_POST['types'];
	$types2=$types=explode(",",$types);
	$types=$tmp=array();
	foreach ($types2 as $palavras){
		$tmp=explode("=",$palavras);
		$campo1=trim($tmp[0]);
		$campo2=str_replace("\n","",str_replace("\t","",$tmp[1]));
		$types[$campo1]=htmlentities($campo2);
		$tmp=array();
		$campo1=$campo2="";
	}
	
	/*echo "<pre>";
	print_r($dict);
	print_r($dictt);*/
	$gerar=implode("",$_POST['ger']);
	$name=ereg_replace("[^a-zA-Z0-9_.]", "",$project);
	if(@mkdir("projetos/".$name) || @opendir("projetos/".$name)){
		$erro=0;
		$errod=0;
		$errod2=0;
		$errof=0;
		$erroc=0;
		foreach ($tables as $tab){
			if(substr_count($gerar,'c')>0){
				$classes=new geradordeclasse($host,$user,$passwd,$db,$tab,$project,$autor,"projetos/".$name);
				echo "Criando classe para a tabela <b>{$tab}</b>:";
				$erroc=$classes->grava($tab);
				echo $erroc==0?"<font color=\"green\">OK</font>":"<font color=\"red\">ERRO</font>";
				echo "<br>";
			flush();
			}
			if(substr_count($gerar,'f')>0){
				$form=new geradordeform($host,$user,$passwd,$db,$tab,$project,$autor,"projetos/".$name,$dict,$types);
				echo "Criando arquivos de gerencia para a tabela <b>{$tab}</b>:";
				$errof=$form->grava($tab,$allinone);
				if($allinone2==true)$errof=$form->grava($tab);
				echo $errof==0?"<font color=\"green\">OK</font>":"<font color=\"red\">ERRO</font>";
				echo "<br>";
			flush();
			}
			if(substr_count($gerar,'d')>0){
				$doc=new geradordedoc($host,$user,$passwd,$db,$tab,$project,$autor,"projetos/".$name);
				echo "Criando diagramas uml para a tabela <b>{$tab}</b>:";
				$errod=$doc->grava($tab);
				echo $errod==0?"<font color=\"green\">OK</font>":"<font color=\"red\">ERRO</font>";
				echo "<br>";
			flush();
			}
			$erro+=$erroc+$errod+$errof;
			echo "<hr>";
		}
		if($d2=="s"){
			$doc=new geradordedoc($host,$user,$passwd,$db,false,$project,$autor,"projetos/".$name);
			echo "Criando diagramas uml para o banco de dados <b>{$db}</b>:";
			$errod=$doc->grava2();
			echo $errod2==0?"<font color=\"green\">OK</font>":"<font color=\"red\">ERRO</font>";
			echo "<br>";
			flush();
		}
		$erro+=$erroc+$errod+$errof+$errod2;
		if($erro>0){
			echo "<font color=\"red\">PASTA SEM PERMI&Ccedil;&Otilde;ES!<br>POR FAVOR D&Ecirc; PERMI&Ccedil;&Otilde;ES DE LEITURA E ESCRITA NA PASTA PROJETOS</font>";
			flush();
		}else{
			@unlink("projetos/".$name."/".$name.".zip");
			$zipfile=new zipfile();
			$zipfile->addDirContent("projetos/".$name."/",1);
			$zipfile->save("projetos/tmp/".$name.".zip");
  			
  			echo '<a href="'."projetos/tmp/".$name.".zip".'">Download</a>';
			exec("chmod -R 777 "."projetos/".$name."/");
			exec("chmod 777 "."projetos/tmp/".$name.".zip");
  			delTree("projetos/".$name."/");
		}
	}else{
		echo "<font color=\"red\">PASTA SEM PERMI&Ccedil;&Otilde;ES!<br>POR FAVOR D&Ecirc; PERMI&Ccedil;&Otilde;ES DE LEITURA E ESCRITA NA PASTA PROJETOS</font>";
			flush();
	}
	echo "
					</div>
				</fieldset>";
}//end if
?>
				</form>
			</fieldset>
		</center>
	</body>
</html>
