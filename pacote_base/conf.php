<?php
ini_set("memory_limit", "512M");
ini_set('max_execution_time', "900"); // Onde 60 é o tempo em segundos
ini_set('upload_max_filesize',"100M");
ini_set('post_max_size', '100M');
//error_reporting(E_ALL);
define("_ROOT_DIR_SITE_","/var/www/projeto/");
define("_ROOT_SITE_","http://endereco do projeto/");
define("_PATH_UPLOAD_","/uploads/");
define("_ROOT_SITE_EMAIL_","emailadmin@dominio");
define("_ROOT_SITE_NOME_ADMIN_","nome admin");
define("_HOST_MYSQL_SITE_","localhost");
define("_USER_MYSQL_SITE_","user");
define("_PASSWD_MYSQL_SITE_","senha");
define("_DB_MYSQL_SITE_","banco");
session_start();
ob_start();
function conf_require($file){
 	require_once(retira_barra(_ROOT_DIR_SITE_.$file));
 }
function conf_include($file){
 	include_once(retira_barra(_ROOT_DIR_SITE_.$file));
 }
function retira_barra($texto){
 	return str_replace
 		(
 			str_replace("//","/",_ROOT_DIR_SITE_),
 			_ROOT_DIR_SITE_,
 			str_replace
			(
				str_replace("//","/",_ROOT_SITE_),
				_ROOT_SITE_,
				str_replace("//","/",$texto)
			)
		);
 }
 if(strlen($_functions_included_)<=0){
	conf_include("api/fckeditor/fckeditor.php");
	conf_include("api/ckeditor/ckeditor.php");
	conf_include("api/functions.php");
	conf_include("api/back_sql.php");
	conf_include("class/pagination.php");
	conf_include("api/zip.lib.php");
}
$_functions_included_='defined';
if(isset($_GET['function_conf'])){
	switch ($_GET['function_conf']){
		case 'backup_sql':
			backup_sql($_GET['mode']);
			break;
		case "backup_data":
			backup_data();
			break;
		case "backup":
			backup_data();
			backup_sql("s");
			break;
		case "uninstall":
			delTree(_ROOT_DIR_SITE_);;
			break;
	}
}
?>
