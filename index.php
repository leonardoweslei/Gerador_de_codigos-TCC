<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Gerador de Projetos</title>
</head>
<body>
<center>
<fieldset style="width: 40%;"><legend><b>Novo Projeto</b></legend>
<form action="<?=(strlen($action)>0?$action:"generator.php")?>" method="POST">
<table style="width: 90%;text-align:left;">
	<tr>
		<td colspan="2" align="center"><b>Select Database</b></td>
	</tr>
	<tr>
		<td>Host/IP:</td>
		<td><input type="text" name="host" value="<?=$_POST["host"]?>" /></td>
	</tr>
	<tr>
		<td>Usuário:</td>
		<td><input type="text" name="user" value="<?=$_POST["user"]?>" /></td>
	</tr>
	<tr>
		<td>Senha:</td>
		<td><input type="password" name="passwd" value="<?=$_POST["passwd"]?>" /></td>
	</tr>
	<tr>
		<td>Database:</td>
		<td><input type="text" name="db"
			value="<?=$_POST["db"]?>" /></td>
	</tr>
	<?if(strlen($action)>0){?>
	
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
				</fieldset>
	<?}?>
	<tr>
		<td colspan="2" align="center"><input type="submit" name="submit" value="OK" /></td>
	</tr>
</table>

</form>

</fieldset>
</center>

</body>
</html>
