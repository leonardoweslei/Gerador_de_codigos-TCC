<?php
ini_set('max_execution_time', 900); // Onde 60 é o tempo em segundos
ini_set("memory_limit", "512M");
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
	$action="generator2.php";
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
	$action="generator2.php";
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
<?php
if(strlen($_POST)>0) //submit form
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
	$project=$_POST['project'];
	$autor=$_POST['autor'];
	$name=ereg_replace("[^a-zA-Z0-9_.]", "",$project);
	$tab=false;
	$allinone='s';
	if(@mkdir("projetos/".$name) || @opendir("projetos/".$name)){
		$erro=0;
		$errod=0;
		$errod2=0;
		$errof=0;
		$erroc=0;
			$classes=new geradordeclasse($host,$user,$passwd,$db,$tab,$project,$autor,"projetos/".$name);
			echo "Criando classes para  banco de dados <b>{$db}</b>:";
			$erroc=$classes->grava();
			echo $erroc==0?"<font color=\"green\">OK</font>":"<font color=\"red\">ERRO</font>";
			echo "<br>";
			$form=new geradordeform($host,$user,$passwd,$db,$tab,$project,$autor,"projetos/".$name,$dict);
			echo "Criando arquivos de gerencia para  banco de dados <b>{$db}</b>:";
			$errof=$form->grava(false,$allinone);
			$errof=$form->grava(false,false);
			echo $errof==0?"<font color=\"green\">OK</font>":"<font color=\"red\">ERRO</font>";
			echo "<br>";
			$erro+=$erroc+$errod+$errof;
			echo "<hr>";
			$doc=new geradordedoc($host,$user,$passwd,$db,false,$project,$autor,"projetos/".$name);
			echo "Criando diagramas uml para o banco de dados <b>{$db}</b>:";
			$errod=$doc->grava2();
			echo $errod2==0?"<font color=\"green\">OK</font>":"<font color=\"red\">ERRO</font>";
			echo "<br>";
		$erro+=$erroc+$errod+$errof+$errod2;
		if($erro>0){
			echo "<font color=\"red\">PASTA SEM PERMI&Ccedil;&Otilde;ES!<br>POR FAVOR D&Ecirc; PERMI&Ccedil;&Otilde;ES DE LEITURA E ESCRITA NA PASTA PROJETOS</font>";
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
	}
	echo "
					</div>
				</fieldset>";
}//end if
?>