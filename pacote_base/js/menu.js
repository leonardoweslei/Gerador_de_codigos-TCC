
		var myMenu =
		[
			[null,'Home','index2.php',null,'Home'],
			_cmSplit,

			[null, 'Produtos', null, null, 'Cadastro de Produtos',
				['<img src="images/categories.png" />', 'Adicionar/Editar', 'prod_add_edit.php', '', 'Adicionar/Editar'],
				['<img src="images/categories.png" />', 'Produtos Destaque', 'prod_add_destaque.php', '', 'Produtos Destaque'],
				['<img src="images/categories.png" />', 'Categorias', 'cat_add.php', '', 'Subcategorias'],
				['<img src="images/categories.png" />', 'Próximos Leilões', 'proximosleiloes.php', '', 'Adicionar/Editar'],
				],
			_cmSplit,

       			[null,'Clientes',null,null,'Listagem de Clientes',
  				['<img src="images/config_002.png" />','Editar Clientes','edit_clientes.php','','Editar Clientes'],
   			],
            _cmSplit,
			
			 	[null,'Pacotes',null,null,'',
  				['<img src="images/config_002.png" />','Cadastrar','cad_pacotes.php','','Cadastrar'],
   			],
            _cmSplit,
			
				[null,'Banners',null,null,'',
  				['<img src="images/config_002.png" />','Cadastrar','cad_banners.php','','Cadastrar'],
   			],
            _cmSplit,
			
			[null,'Administração',null,null,'Configurações e Permissão do Sistema',
  				['<img src="images/config_002.png" />','Usuário','user.php','','Cadastro de Usuário'],
   			],
			_cmSplit,
		];
		cmDraw ('myMenuID', myMenu, 'hbr', cmThemeOffice, 'ThemeOffice');
