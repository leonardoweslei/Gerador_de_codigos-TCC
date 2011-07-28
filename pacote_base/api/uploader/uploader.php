<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Flash Upload Test</title>
<script type="text/javascript" src="http://www.shiguenori.com/jquery/jquery-1.3.1.js"></script>
<script type="text/javascript" src="jquery.fileupload.js"></script>
<script type="text/javascript">
$(document).ready(function() {
   $("#arquivo").fileUpload({
      'uploader': 'uploader.swf',
      'cancelImg': 'cancel.png',
      'folder': 'uploaded',
      'script': 'upload.php',
      'fileDesc': 'Image Files',
      'fileExt': '*.jpg;*.jpeg;*.gif;*.png',
      'multi': true,
      'scriptData' : {'variavel':'upok'}
   });
});
</script>
<style>
/* CSS PARA ESTILIZAR A BARRA DE PROGRESSO */
body {
   font: 12px/18px Arial, Helvetica, sans-serif;
}
.fileUploadQueueItem {
   font: 11px Verdana, Geneva, sans-serif;
   background-color: #F5F5F5;
   border: 3px solid #E5E5E5;
   margin-top: 5px;
   padding: 10px;
   width: 300px;
}
.fileUploadQueueItem .cancel {
   float: right;
}
.fileUploadProgress {
   background-color: #FFFFFF;
   border-top: 1px solid #808080;
   border-left: 1px solid #808080;
   border-right: 1px solid #C5C5C5;
   border-bottom: 1px solid #C5C5C5;
   margin-top: 10px;
   width: 100%;
}
.fileUploadProgressBar {
   background-color: #0099FF;
}
</style>
 
</head>
<body>
 
<h1>JQuery FileUpload - Exemplo</h1>
 
<h2>Envio multiplo, autostart e apenas imagens</h2>
<p><input name="arquivo" id="arquivo" type="file" />
	<a href="javascript:$('#arquivo').fileUploadStart()">Começar upload</a> | <a href="javascript:$('#arquivo').fileUploadClearQueue()">Limpar Fila</a>
</p>
 
 
</body>
</html>
