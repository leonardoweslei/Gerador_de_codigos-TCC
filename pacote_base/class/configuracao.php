<?php
	/**
	 * Class Configuracao
	 * Guarda dados de configuracao do banco de dados
	 * 
	 * @author	 Leonardo Weslei Diniz
	 * @since 	 29/07/2009
	 * @package	 user
	 *
	 */
	class Configuracao
	{
		var $host	= NULL;
		var $user	= NULL;
		var $passwd = NULL;
		var $bd		= NULL;
		function	Configuracao
		(
			$host	= false,
			$user	= false,
			$passwd = false,
			$bd		= false
		)
		{
			$this->host		= ($host==false)?_HOST_MYSQL_SITE_:$host;
			$this->user		= ($user==false)?_USER_MYSQL_SITE_:$user;
			$this->passwd	= ($passwd==false)?_PASSWD_MYSQL_SITE_:$passwd;
			$this->bd		= ($bd==false)?_DB_MYSQL_SITE_:$bd;
		}
	};

?>
