<?
//	include('../conf.php');
	conf_require("/class/database.php");
	conf_require("/class/configuracao.php");
	class pagination
	{
		var $markup_var				= "pagina";
		var $add_pagging			= array();
		var $max_results			= 5;
		var $max_pages				= 9;
		var $page_now				= 0;
		var $page_next				= 0;
		var $page_prev				= 0;
		var $total_pages			= 0;
		var $total_results			= 0;
		var $total_results_in_page	= 0;
		var $url					= "";
		var $table					= "";
		var $where					= "";
		var $separators=array("?","&","=");
		var $marker=array("<<","<",">",">>","[","]");
		var $addinlink=array('style="text-decoration:none;"');
		function pagination($table,$add_pagging,$where="",$url=false,$markup="pg",$maxr=10,$maxp=7,$separators=array("?","&","="),$markers=array("<<","<",">",">>","[","]"),$addinlink=array('style="text-decoration:none;"'))
		{
			$this->markup_var				= $markup;
			$this->addinlink				= $addinlink;
			$this->url						= $url;
			$this->max_pages				= $maxp;
			$this->max_results				= $maxr-1;
			$this->where					= $where;
			$this->table					= $table;
			$this->add_pagging				= $add_pagging;
			$this->page_now					= $this->get_page_now();
			$this->total_results			= $this->get_total_results();
            $this->page_prev 				= $this->page_now - 1;
            $this->page_next 				= $this->page_now + 1;
			$this->total_results_in_page	= $this->get_total_results_in_page($this->page_now);
            $this->total_pages = ceil($this->total_results/$this->max_results);
			if($this->page_now>$this->total_pages)
				$this->page_now				=1;
			$this->separators				= $separators;
			$this->marker					= $markers;
		}
		function get_page_now()
		{
			return isset($_GET[$this->markup_var])? ($_GET[$this->markup_var]) : 1;
		}
		
		function get_total_results()
		{
			$config	= new Configuracao();
			$servidor = new DataBase
			(
				$config->host,
				$config->user,
				$config->passwd,
				$config->bd
			);
			$query = "SELECT count(*) FROM ".$this->table.(strlen($this->where)>0?" WHERE ".$this->where:'');
			$resultado = $servidor->run_query($query);
			$total = mysql_fetch_array($resultado);
			$total=$total[0];
			return $total;
		}
		function get_total_results_in_page($page)
		{
			
			if((($this->page_now)*$this->max_results) > $this->total_results)
				return $this->total_results%$this->max_results;
			return $this->max_results;
		}
		function forgeUrl($page)
		{
			$out=$this->separators[0].$this->markup_var.$this->separators[2].$page;
			if(is_array($this->add_pagging))
				$keys=array_keys($this->add_pagging);
			for($i=0; $i<sizeof($keys); ++$i)
			{
				$out.=$this->separators[1].$keys[$i].$this->separators[2].$this->add_pagging[$keys[$i]];
			}
			return $out;
		}
		
		function getUrl($page)
		{
			return $this->url.$this->forgeUrl($page);
		}
		function pagination_simple()
		{
			return $this->pagination_advanced(1);
		}
		function getInterval($page,$mode=false)
		{
			for($i=1;$i<=round($this->total_pages/$this->max_pages);$i++)
			{
				if(($i*$this->max_pages)+$this->max_pages>=$page && $page<(($i*$this->max_pages)+1))
				{
					//echo "<br><b>".(($i*$this->max_pages)+$this->max_pages)." ".((($i*$this->max_pages)+1))." ".$i." ".$page."</b><br>";
					//return $i;
					if($mode!=false)
					{
						return (($i-1)*$this->max_pages)+$this->max_pages;
					}else{
						return (($i-1)*$this->max_pages);
					}
				}
			}
			//echo "<br><b>".(($i*$this->max_pages)+$this->max_pages)." ".((($i*$this->max_pages)+1))." ".$i." ".$page."</b><br>";
			return 0;
		}
		function pagination_advanced($is_simple=false)
		{
			
            $pagination="";
            $pagination_add="";
			if($is_simple==false)
			{
				$de=(
					($this->total_results_in_page==$this->max_results)
					?
						(
						($this->page_now)*$this->max_results)
					:
						$this->total_results
					);
	            $ini=
	            	(
	            	$this->total_results_in_page>0
	            	?
	            		(($this->page_now-1)*$this->max_results)+1
	        		:
	            		0
	        		);
	            $pagination_add= "Exibindo de resultado de ".$ini." a ".$de."&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$this->total_results." Registro(s)";
	            $pagination_add.= (($this->total_pages>1)?"&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$this->total_pages." Paginas&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;":"")." ".$this->getInterval($this->page_now)." ".$this->getInterval($this->page_now,1);//." ".(round(($this->total_pages%$this->page_now)))." ".(round($this->total_pages/(($this->total_pages-$this->page_now)+round($this->max_pages/2)))*$this->max_pages);
			}
		    if($this->total_pages > 1)
		    {
		        if($this->page_prev>0)
		        {
		           $pagination_add.= "<b><a ".implode(" ",$this->addinlink)." href=\"".$this->getUrl(1)."\" title=\"primeira\"><font size=\"1\">".$this->marker[0]."</font></a></b>&nbsp;";  
		           $pagination_add.= "<b><a ".implode(" ",$this->addinlink)." href=\"".$this->getUrl($this->page_prev)."\" title=\"anterior\"><font size=\"1\">".$this->marker[1]."</font></a></b>&nbsp;";  
		        }else
		        {
		           $pagination_add.= "<b class=\"pdisabled\"><font size=\"1\">".$this->marker[0]."</font></b>&nbsp;";  
		           $pagination_add.= "<b class=\"pdisabled\"><font size=\"1\">".$this->marker[1]."</font></b>&nbsp;";  
		        }
		        $totalreg=array();
		        
		        
		        
		        
		        
				if($this->page_now<$this->max_pages)
				{
					for($i=1; $i<=$this->max_pages && $i<=$this->total_pages; $i++)
					{
	                	if($i == $this->page_now)
	                	{
                			$pagination.="<b title=\"Pagina Atual\" class=\"pselected\">".$this->marker[4].$i.$this->marker[5]."</b>&nbsp;";
                		}else
                		{
	                		$pagination.="<b><a ".implode(" ",$this->addinlink)." href=\"".$this->getUrl($i)."\" title=\"Pagina #".($i)."\">".$i."</a></b>&nbsp;";
            			}
					}
				}
				else if($this->page_now>=($this->total_pages-round($this->max_pages/2)))
				{
					for($i=($this->total_pages-$this->max_pages)+1; $i<=$this->total_pages; $i++)
					{
	                	if($i == $this->page_now)
	                	{
                			$pagination.="<b title=\"Pagina Atual\" class=\"pselected\">".$this->marker[4].$i.$this->marker[5]."</b>&nbsp;";
                		}else
                		{
	                		$pagination.="<b><a ".implode(" ",$this->addinlink)." href=\"".$this->getUrl($i)."\" title=\"Pagina #".($i)."\">".$i."</a></b>&nbsp;";
            			}
					}
				}
				else if($this->getInterval($this->page_now)>0 && $this->page_now>(1+$this->getInterval($this->page_now)) && $this->page_now<($this->getInterval($this->page_now,1)))
				{
					for($i=$this->getInterval($this->page_now)+1; $i<=$this->getInterval($this->page_now,1); $i++)
					{
	                	if($i == $this->page_now)
	                	{
                			$pagination.="<b title=\"Pagina Atual\" class=\"pselected\">".$this->marker[4].$i.$this->marker[5]."</b>&nbsp;";
                		}else
                		{
	                		$pagination.="<b><a ".implode(" ",$this->addinlink)." href=\"".$this->getUrl($i)."\" title=\"Pagina #".($i)."\">".$i./*"-".($this->getInterval($this->page_now,1)).*/"</a></b>&nbsp;";
            			}
					}
				}
				else if($this->getInterval($this->page_now)>0 && $this->page_now>($this->getInterval($this->page_now)) && $this->page_now<($this->getInterval($this->page_now,1)))
				{
					for($i=$this->getInterval($this->page_now); $i<$this->getInterval($this->page_now,1); $i++)
					{
	                	if($i == $this->page_now)
	                	{
                			$pagination.="<b title=\"Pagina Atual\" class=\"pselected\">".$this->marker[4].$i.$this->marker[5]."</b>&nbsp;";
                		}else
                		{
	                		$pagination.="<b><a ".implode(" ",$this->addinlink)." href=\"".$this->getUrl($i)."\" title=\"Pagina #".($i)."\">".$i."</a></b>&nbsp;";
            			}
					}
				}
				else 
				{
					$init= $this->page_now-round($this->max_pages/2)+1;
					for($j=1,$i=$init; $i<=$this->total_pages && $j<=$this->max_pages; $i++,$j++)
					{
	                	if($i == $this->page_now)
	                	{
                			$pagination.="<b title=\"Pagina Atual\" class=\"pselected\">".$this->marker[4].$i.$this->marker[5]."</b>&nbsp;";
                		}else
                		{
	                		$pagination.="<b><a ".implode(" ",$this->addinlink)." href=\"".$this->getUrl($i)."\" title=\"Pagina #".($i)."\">".$i."</a></b>&nbsp;";
            			}
					}
				}
		        $pagination=$pagination_add.$pagination;
		        $pagination_add="";
		        if($this->page_next <= $this->total_pages)
		        {
		           $pagination_add.="<b><a ".implode(" ",$this->addinlink)." href=\"".$this->getUrl($this->page_next)."\" title=\"proxima\"><font size=\"1\">".$this->marker[2]."</font></a></b>&nbsp;";  
		           $pagination_add.="<b><a ".implode(" ",$this->addinlink)." href=\"".$this->getUrl($this->total_pages)."\" title=\"ultima\"><font size=\"1\">".$this->marker[3]."</font></a></b>";  
		        }else{
		           $pagination_add.="<b class=\"pdisabled\"><font size=\"1\">".$this->marker[2]."</font></b>&nbsp;";  
		           $pagination_add.="<b class=\"pdisabled\"><font size=\"1\">".$this->marker[3]."</font></b>";  
		        }
		        $pagination.=$pagination_add;
			}
			return $pagination;
	}
}
/*$pagination=new pagination('permissions',array('teste'=>$_GET['pg']),"pid <100","","pg",2,6);
echo "<pre>".print_r($pagination,1)."</pre>";
echo $pagination->pagination_advanced();*/
?>