<?php

	function divideDT($valor)
	{
		if(substr_count($valor,"/")<=0)
		{
			$tmp2=explode(" ",$valor);//datetime
			$tmp1=explode(":",$tmp2[1]);//horas
			$tmp0=explode("-",$tmp2[0]);//data
			$tmp[0]=$tmp0[2];//dia
			$tmp[1]=$tmp0[1];//mes
			$tmp[2]=$tmp0[0];//ano
			$tmp[3]=$tmp1[0];//hora
			$tmp[4]=$tmp1[1];//min
			$tmp[5]=$tmp1[2];//sec
		}else
		{
			$valor=str_replace(" as "," ",$valor);//datetime
			$tmp2=explode(" ",$valor);//datetime
			$tmp0=explode("/",$tmp2[0]);//data
			$tmp1=explode(":",$tmp2[1]);//horas
			$tmp[0]=$tmp0[0];//dia
			$tmp[1]=$tmp0[1];//mes
			$tmp[2]=$tmp0[2];//ano
			$tmp[3]=$tmp1[0];//hora
			$tmp[4]=$tmp1[1];//min
			$tmp[5]=$tmp1[2];//sec
		}
		return $tmp;
	}
	
	function selectestado($estado="MG",$nomecampo='estado',$tab=1){
		$estado=strtoupper($estado);
		return str_repeat("\t",$tab).'<select id="'.$nomecampo.'" name="'.$nomecampo.'">
'.str_repeat("\t",$tab+1).'<option value="AC" '.(($estado=="AC")?' selected':'').'>AC</option>
'.str_repeat("\t",$tab+1).'<option value="AL" '.(($estado=="AL")?' selected':'').'>AL</option>
'.str_repeat("\t",$tab+1).'<option value="AM" '.(($estado=="AM")?' selected':'').'>AM</option>
'.str_repeat("\t",$tab+1).'<option value="AP" '.(($estado=="AP")?' selected':'').'>AP</option>
'.str_repeat("\t",$tab+1).'<option value="BA" '.(($estado=="BA")?' selected':'').'>BA</option>
'.str_repeat("\t",$tab+1).'<option value="CE" '.(($estado=="CE")?' selected':'').'>CE</option>
'.str_repeat("\t",$tab+1).'<option value="DF" '.(($estado=="DF")?' selected':'').'>DF</option>
'.str_repeat("\t",$tab+1).'<option value="ES" '.(($estado=="ES")?' selected':'').'>ES</option>
'.str_repeat("\t",$tab+1).'<option value="GO" '.(($estado=="GO")?' selected':'').'>GO</option>
'.str_repeat("\t",$tab+1).'<option value="MA" '.(($estado=="MA")?' selected':'').'>MA</option>
'.str_repeat("\t",$tab+1).'<option value="MG" '.(($estado=="MG")?' selected':'').'>MG</option>
'.str_repeat("\t",$tab+1).'<option value="MS" '.(($estado=="MS")?' selected':'').'>MS</option>
'.str_repeat("\t",$tab+1).'<option value="MT" '.(($estado=="MT")?' selected':'').'>MT</option>
'.str_repeat("\t",$tab+1).'<option value="PA" '.(($estado=="PA")?' selected':'').'>PA</option>
'.str_repeat("\t",$tab+1).'<option value="PB" '.(($estado=="PB")?' selected':'').'>PB</option>
'.str_repeat("\t",$tab+1).'<option value="PE" '.(($estado=="PE")?' selected':'').'>PE</option>
'.str_repeat("\t",$tab+1).'<option value="PI" '.(($estado=="PI")?' selected':'').'>PI</option>
'.str_repeat("\t",$tab+1).'<option value="PR" '.(($estado=="PR")?' selected':'').'>PR</option>
'.str_repeat("\t",$tab+1).'<option value="RJ" '.(($estado=="RJ")?' selected':'').'>RJ</option>
'.str_repeat("\t",$tab+1).'<option value="RN" '.(($estado=="RN")?' selected':'').'>RN</option>
'.str_repeat("\t",$tab+1).'<option value="RO" '.(($estado=="RO")?' selected':'').'>RO</option>
'.str_repeat("\t",$tab+1).'<option value="RR" '.(($estado=="RR")?' selected':'').'>RR</option>
'.str_repeat("\t",$tab+1).'<option value="RS" '.(($estado=="RS")?' selected':'').'>RS</option>
'.str_repeat("\t",$tab+1).'<option value="SC" '.(($estado=="SC")?' selected':'').'>SC</option>
'.str_repeat("\t",$tab+1).'<option value="SE" '.(($estado=="SE")?' selected':'').'>SE</option>
'.str_repeat("\t",$tab+1).'<option value="SP" '.(($estado=="SP")?' selected':'').'>SP</option>
'.str_repeat("\t",$tab+1).'<option value="TO" '.(($estado=="TO")?' selected':'').'>TO</option>
'.str_repeat("\t",$tab).'</select>';
	}
	
	function upload_img($img,$path=_PATH_UPLOAD_){
		$erro = $config = array();
		$arquivo = isset($img) ? $img : FALSE;
		$config["tamanho"] = 1024*1024*5;
		$config["largura"] = 1025;
		$config["altura"]  = 1025;
		
		if($arquivo)
		{ 
		    if(!eregi("^image\/(pjpeg|jpeg|png|gif|bmp)$", $arquivo["type"]) && !eregi("^application\/(octet-stream)$", $arquivo["type"]))
		    {
		        $erro[] = "Arquivo em formato invalido! A imagem deve ser jpg, jpeg, bmp, gif ou png. Envie outro arquivo";
		    }
		    else
		    {
		        if($arquivo["size"] > $config["tamanho"])
		        {
		            $erro[] = "Arquivo em tamanho muito grande! 
				A imagem deve ser de no maximo " . $config["tamanho"] . " bytes. 
				Envie outro arquivo";
		        }
		        
		        // Para verificar as dimensaes da imagem
		        $tamanhos = getimagesize($arquivo["tmp_name"]);
		        
		        // Verifica largura
		        if($tamanhos[0] > $config["largura"])
		        {
		            $erro[] = "Largura da imagem nao deve 
						ultrapassar " . $config["largura"] . " pixels";
		        }
		
		        // Verifica altura
		        if($tamanhos[1] > $config["altura"])
		        {
		            $erro[] = "Altura da imagem nao deve 
						ultrapassar " . $config["altura"] . " pixels";
		        }
		    }
		    
		    // Imprime as mensagens de erro
		    if(sizeof($erro))
		    {
		        foreach($erro as $err)
		        {
		            $msg.=" - " . $err . "<br>";
		        }
				//echo $msg;
		        return 0;
		    }
		    else
		    {
		        // Pega extensao do arquivo
		        preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $arquivo["name"], $ext);
		        $imagem_nome = md5(uniqid(time())) . "." . $ext[1];
		        $imagem_dir = retira_barra(_ROOT_DIR_SITE_."/".$path. $imagem_nome);
		
		        // Faz o upload da imagem
		        move_uploaded_file($arquivo["tmp_name"], $imagem_dir);
				chmod($imagem_dir,0777);
				$func=explode("/",$arquivo["type"]);
				$func=$func[0];
				//$img=imagecreatefrom.$func($imagem_nome);
		        return $imagem_dir;
		    }
		}
		return false;
	}
	
 	function upload_music($music,$path=_PATH_UPLOAD_){
		$erro = $config = array();
		$arquivo = isset($music) ? $music : FALSE;
		$config["tamanho"] = 1024*1024*1000;
        $ext=array_reverse(explode(".",$arquivo['name']));
        $ext=$ext[0];
        $permitido=array("mp3","MP3","mP3","Mp3");
        /*echo "<pre>";
        print_r($arquivo);*/
        if($arquivo)
		{ 
		    if(!eregi("^audio\/(mp3|mpeg|mpg)$", $arquivo["type"]))
		    {
		        $erro[] = "Arquivo em formato invalido! A musica deve ser mp3. Envie outro arquivo";
		    }
		    else
		    {
		        if($arquivo["size"] > $config["tamanho"])
		        {
		            $erro[] = "Arquivo em tamanho muito grande! 
				A musica deve ser de no maximo " . $config["tamanho"] . " bytes. 
				Envie outro arquivo";
		        }
		    }
		    
		    // Imprime as mensagens de erro
		    if(sizeof($erro))
		    {
		        foreach($erro as $err)
		        {
		            $msg.=" - " . $err . "<br>";
		        }
				echo $msg;
		        return 0;
		    }
		    else
		    {
		        // Pega extensao do arquivo
		        preg_match("/\.(mp3){1}$/i", $arquivo["name"], $ext);
		        $music_nome = md5(uniqid(time())) . "." . $ext[1];
		        $music_dir = retira_barra(_ROOT_DIR_SITE_."/".$path. $music_nome);
				// Faz o upload da imagem
		        move_uploaded_file($arquivo["tmp_name"], $music_dir);
				chmod($music_dir,0777);
				$func=explode("/",$arquivo["type"]);
				$func=$func[0];
				//$img=imagecreatefrom.$func($imagem_nome);
		        return $music_dir;
		    }
		}
		return false;
	}
	
 	function upload_video($video,$path=_PATH_UPLOAD_){
		$erro = $config = array();
		$arquivo = isset($video) ? $video : FALSE;
		$config["tamanho"] = 1024*1024*10000;
        $ext=end(explode(".",$arquivo['name']));
        $permitido=array("flv");
        /*echo "<pre>";
        print_r($arquivo);*/
		if($arquivo)
		{ 
		    if(!(eregi("^video\/(flv|x-flv)$", $arquivo["type"]) || eregi("^application\/(flv|x-shockwave-flash)$", $arquivo["type"])))
		    {
		        $erro[] = "Arquivo em formato invalido! O video deve ser flv. Envie outro arquivo";
		    }
		    else
		    {
		        if($arquivo["size"] > $config["tamanho"])
		        {
		            $erro[] = "Arquivo em tamanho muito grande! 
				O video deve ser de no maximo " . $config["tamanho"] . " bytes. 
				Envie outro arquivo";
		        }
		    }
		    
		    // Imprime as mensagens de erro
		    if(sizeof($erro))
		    {
		        foreach($erro as $err)
		        {
		            $msg.=" - " . $err . "<br>";
		        }
				echo $msg;
		        return 0;
		    }
		    else
		    {
		        // Pega extensao do arquivo
		        preg_match("/\.(flv){1}$/i", $arquivo["name"], $ext);
		        $video_nome = md5(uniqid(time())) . "." . $ext[1];
		        $video_dir = retira_barra(_ROOT_DIR_SITE_."/".$path. $video_nome);
		
		        // Faz o upload da imagem
		        move_uploaded_file($arquivo["tmp_name"], $video_dir);
				chmod($video_dir,0777);
				$func=explode("/",$arquivo["type"]);
				$func=$func[0];
				//$img=imagecreatefrom.$func($imagem_nome);
		        return $video_dir;
		    }
		}
		return false;
	}
	

 function thumbimg($valor,$w=false,$h=false,$alt=false,$tab=1,$classe=false){
 			$valoro=$valor;
			if($valor!=NULL && strlen($valor)>0 && is_file(retira_barra($valor))){
				$valor=tradurl($valor);
				$valor=str_repeat("\t",$tab).'<img src="'.$valor.'" width="'.(($w!=false)?$w:'50px').'"'.(($classe!=false)?' class="'.$classe.'"':'').' height="'.(($h!=false)?$h:'50px').'" alt="'.htmlentities(($alt!=false)?$alt:$valor).'" border="0"/>';
			}
			return ($valoro!=NULL && strlen($valoro)>0 && is_file(retira_barra($valoro)))?$valor:"";
 }
 
 function thumbimg2($valor,$w=false,$h=false,$alt=false,$id=false,$tab=1)
 {
			if($valor!=NULL && strlen($valor)>0){
				$valor=str_replace("",_ROOT_SITE_,$valor);
				$valor=str_repeat("\t",$tab).'<img src="'.retira_barra(_ROOT_SITE_.'/redimensionaimg.php').'?arquivo='.encode5t($valor.'&'.(($w!=false)?$w:'50px').'&'.(($h!=false)?$h:'50px').(($w==false || $h==false)?"&S":'&S')).'" alt="'.htmlentities(($alt!=false)?$alt:$valor).'" border="0" '.(($id!=false)?'id="'.$id.'"':'').'/>';
			}
			return $valor;
 }


 function thumbimg3($valor,$w=false,$h=false,$alt=false,$tab=1)
 {
			if($valor!=NULL && strlen($valor)>0){
				$valor=str_replace("",_ROOT_SITE_,$valor);
				$valor=str_repeat("\t",$tab).'<img src="'.retira_barra(_ROOT_SITE_.'/redimensionaimg2.php').'?arquivo='.$valor.'&largura='.(($w!=false)?$w:'50px').'&altura='.(($h!=false)?$h:'50px').'" alt="'.htmlentities(($alt!=false)?$alt:$valor).'" border="0"/>';
			}
			return $valor;
 }

 function link_mus($valor,$texto=false,$id=false,$path=false,$tab=1){
			if($valor!=NULL && strlen($valor)>0){
				$valor=tradurl($valor);
				$valor=''.(($texto!=false)?$texto:tradurl($valor)).'
'.str_repeat("\t",$tab).'<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="220" height="20">
'.str_repeat("\t",$tab+1).'<param name="movie" value="'.retira_barra(_ROOT_SITE_.'/player/playerb.swf?playlist_url='.($path!=false?'.':_ROOT_SITE_).'/track'.($path!=false?'2':'').'.php?hid='.$id.($path!=false?'&path='.$path:'').'&repeat_playlist=true&player_title=homilia em mp3').'" />
'.str_repeat("\t",$tab+1).'<param name="quality" value="high" />
'.str_repeat("\t",$tab+1).'<param name="wmode" value="transparent" />
'.str_repeat("\t",$tab+1).'<embed src="'.retira_barra(_ROOT_SITE_.'/player/playerb.swf?playlist_url='.($path!=false?'.':_ROOT_SITE_).'/track'.($path!=false?'2':'').'.php?hid='.$id.($path!=false?'&path='.$path:'').'&repeat_playlist=true&player_title=homilia em mp3').'" width="220" height="20" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" wmode="transparent">
'.str_repeat("\t",$tab+1).'</embed>
'.str_repeat("\t",$tab).'</object>';
			}
			return $valor;
 }
 
 function link_video($valor,$texto=false,$tab=1){
			if($valor!=NULL && strlen($valor)>0){
				$valor=tradurl($valor);
				$valor='
'.str_repeat("\t",$tab).'<a href="'.tradurl($valor).'" target="_blank" >
'.str_repeat("\t",$tab+1).(($texto!=false)?$texto:tradurl($valor)).'
'.str_repeat("\t",$tab).'</a>';
			}
			return $valor;
 }
 
 function tradurl($valor){
			$valor=retira_barra(str_replace(_ROOT_DIR_SITE_,_ROOT_SITE_,$valor));
			return $valor;
 }
 function tradurl2($valor){
			$valor=retira_barra(str_replace(_ROOT_DIR_SITE_,"",$valor));
			return $valor;
 }
 
	function getfile($vari,$valor=NULL,$tipo=1,$tab=1)
	{
		$tmp='';
		if($tipo == 1 && $valor==NULL)
		{
			$tmp='
'.str_repeat("\t",$tab).'<input type="file" name="'.$vari.'">';
		}
		if($tipo==1 && $valor!=NULL)
		{
			$tmp='
'.str_repeat("\t",$tab).'<span>
'.str_repeat("\t",$tab+1).'<img src="'.tradurl($valor).'" width="50px" height="50px" alt="'.tradurl($valor).'" border="0">
'.str_repeat("\t",$tab+1).'<br>
'.str_repeat("\t",$tab+1).'<a href="javascript:void(0);" onClick="
'.str_repeat("\t",$tab+2).'javascript:{
'.str_repeat("\t",$tab+3).'brows(\''.$vari.'\').name =\''.$vari.'1\';
'.str_repeat("\t",$tab+3).'brows(\''.$vari.'\').id =\''.$vari.'1\';
'.str_repeat("\t",$tab+3).'brows(\''.$vari.'2\').style.display =\'block\';
'.str_repeat("\t",$tab+3).'brows(\''.$vari.'2\').name =\''.$vari.'\';
'.str_repeat("\t",$tab+3).'brows(\''.$vari.'2\').id =\''.$vari.'\';
'.str_repeat("\t",$tab+3).'brows(\'linkfile\').style.display =\'none\';
'.str_repeat("\t",$tab+3).'brows(\'linkfile2\').style.display =\'block\';
'.str_repeat("\t",$tab+2).'}
'.str_repeat("\t",$tab+1).'" id="linkfile">
'.str_repeat("\t",$tab+2).'Alterar
'.str_repeat("\t",$tab+1).'</a>
'.str_repeat("\t",$tab+1).'<a href="javascript:void(0);" onClick="
'.str_repeat("\t",$tab+2).'javascript:{
'.str_repeat("\t",$tab+3).'brows(\''.$vari.'\').name =\''.$vari.'2\';
'.str_repeat("\t",$tab+3).'brows(\''.$vari.'\').style.display =\'none\';
'.str_repeat("\t",$tab+3).'brows(\''.$vari.'\').id =\''.$vari.'2\';
'.str_repeat("\t",$tab+3).'brows(\''.$vari.'1\').name =\''.$vari.'\';
'.str_repeat("\t",$tab+3).'brows(\''.$vari.'1\').id =\''.$vari.'\';
'.str_repeat("\t",$tab+3).'brows(\'linkfile2\').style.display =\'none\';
'.str_repeat("\t",$tab+3).'brows(\'linkfile\').style.display =\'block\';
'.str_repeat("\t",$tab+2).'}
'.str_repeat("\t",$tab+1).'" id="linkfile2" style="display:none;">
'.str_repeat("\t",$tab+2).'Cancelar
'.str_repeat("\t",$tab+1).'</a>
'.str_repeat("\t",$tab+1).'<input type="hidden" name="'.$vari.'" value="'.$valor.'" id="'.$vari.'"/>
'.str_repeat("\t",$tab+1).'<input type="file" name="'.$vari.'2" id="'.$vari.'2" style="display:none;"/>
'.str_repeat("\t",$tab).'</span>';
		}
		return $tmp;
	}
	 
	function getfilemusic($vari,$valor=NULL,$tipo=1,$id=false,$tab=1)
	{
		$tmp='';
		if($tipo == 1 && $valor==NULL)
		{
			$tmp='
'.str_repeat("\t",$tab).'<input type="file" name="'.$vari.'">';
		}
		if($tipo==1 && $valor!=NULL)
		{
			$tmp='
'.str_repeat("\t",$tab).'<span>
'.str_repeat("\t",$tab+1).'<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="220" height="20">
'.str_repeat("\t",$tab+2).'<param name="movie" value="'.retira_barra(_ROOT_SITE_.'/player/playerb.swf?playlist_url='._ROOT_SITE_.'/track.php?hid='.$id.'&repeat_playlist=true&player_title=homilia em mp3').'" />
'.str_repeat("\t",$tab+2).'<param name="quality" value="high" />
'.str_repeat("\t",$tab+2).'<param name="wmode" value="transparent" />
'.str_repeat("\t",$tab+2).'<embed src="'.retira_barra(_ROOT_SITE_.'/player/playerb.swf?playlist_url='._ROOT_SITE_.'/track.php?hid='.$id.'&repeat_playlist=true&player_title=homilia em mp3').'" width="220" height="20" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" wmode="transparent">
'.str_repeat("\t",$tab+2).'</embed>
'.str_repeat("\t",$tab+1).'</object>
'.str_repeat("\t",$tab+1).'<br>
'.str_repeat("\t",$tab+1).'<a href="javascript:void(0);" onClick="
'.str_repeat("\t",$tab+2).'javascript:{
'.str_repeat("\t",$tab+3).'brows(\''.$vari.'\').name =\''.$vari.'1\';
'.str_repeat("\t",$tab+3).'brows(\''.$vari.'\').id =\''.$vari.'1\';
'.str_repeat("\t",$tab+3).'brows(\''.$vari.'2\').style.display =\'block\';
'.str_repeat("\t",$tab+3).'brows(\''.$vari.'2\').name =\''.$vari.'\';
'.str_repeat("\t",$tab+3).'brows(\''.$vari.'2\').id =\''.$vari.'\';
'.str_repeat("\t",$tab+3).'brows(\'linkfile\').style.display =\'none\';
'.str_repeat("\t",$tab+3).'brows(\'linkfile2\').style.display =\'block\';
'.str_repeat("\t",$tab+2).'}
'.str_repeat("\t",$tab+1).'" id="linkfile">
'.str_repeat("\t",$tab+2).'Alterar
'.str_repeat("\t",$tab+1).'</a>
'.str_repeat("\t",$tab+1).'<a href="javascript:void(0);" onClick="
'.str_repeat("\t",$tab+2).'javascript:{
'.str_repeat("\t",$tab+3).'brows(\''.$vari.'\').name =\''.$vari.'2\';
'.str_repeat("\t",$tab+3).'brows(\''.$vari.'\').style.display =\'none\';
'.str_repeat("\t",$tab+3).'brows(\''.$vari.'\').id =\''.$vari.'2\';
'.str_repeat("\t",$tab+3).'brows(\''.$vari.'1\').name =\''.$vari.'\';
'.str_repeat("\t",$tab+3).'brows(\''.$vari.'1\').id =\''.$vari.'\';
'.str_repeat("\t",$tab+3).'brows(\'linkfile2\').style.display =\'none\';
'.str_repeat("\t",$tab+3).'brows(\'linkfile\').style.display =\'block\';
'.str_repeat("\t",$tab+2).'}
'.str_repeat("\t",$tab+1).'" id="linkfile2" style="display:none;">
'.str_repeat("\t",$tab+2).'Cancelar
'.str_repeat("\t",$tab+1).'</a>
'.str_repeat("\t",$tab+1).'<input type="hidden" name="'.$vari.'" value="'.$valor.'" id="'.$vari.'"/>
'.str_repeat("\t",$tab+1).'<input type="file" name="'.$vari.'2" id="'.$vari.'2" style="display:none;"/>
'.str_repeat("\t",$tab).'</span>';
		}
		return $tmp;
	}
	 
	function getfilevideo($vari,$valor=NULL,$tipo=1,$tab=1)
	{
		$tmp='';
		if($tipo == 1 && $valor==NULL)
		{
			$tmp='
'.str_repeat("\t",$tab).'<input type="file" name="'.$vari.'">';
		}
		if($tipo==1 && $valor!=NULL)
		{
			$tmp='
'.str_repeat("\t",$tab).'<span>
'.str_repeat("\t",$tab+1).'<a href="'.tradurl($valor).'" target="_blank" >'.tradurl($valor).'</a>
'.str_repeat("\t",$tab+1).'<br>
'.str_repeat("\t",$tab+1).'<a href="javascript:void(0);" onClick="
'.str_repeat("\t",$tab+2).'javascript:{
'.str_repeat("\t",$tab+3).'brows(\''.$vari.'\').name =\''.$vari.'1\';
'.str_repeat("\t",$tab+3).'brows(\''.$vari.'\').id =\''.$vari.'1\';
'.str_repeat("\t",$tab+3).'brows(\''.$vari.'2\').style.display =\'block\';
'.str_repeat("\t",$tab+3).'brows(\''.$vari.'2\').name =\''.$vari.'\';
'.str_repeat("\t",$tab+3).'brows(\''.$vari.'2\').id =\''.$vari.'\';
'.str_repeat("\t",$tab+3).'brows(\'linkfile\').style.display =\'none\';
'.str_repeat("\t",$tab+3).'brows(\'linkfile2\').style.display =\'block\';
'.str_repeat("\t",$tab+2).'}
'.str_repeat("\t",$tab+1).'" id="linkfile">
'.str_repeat("\t",$tab+2).'Alterar
'.str_repeat("\t",$tab+1).'</a>
'.str_repeat("\t",$tab+1).'<a href="javascript:void(0);" onClick="
'.str_repeat("\t",$tab+2).'javascript:{
'.str_repeat("\t",$tab+3).'brows(\''.$vari.'\').name =\''.$vari.'2\';
'.str_repeat("\t",$tab+3).'brows(\''.$vari.'\').style.display =\'none\';
'.str_repeat("\t",$tab+3).'brows(\''.$vari.'\').id =\''.$vari.'2\';
'.str_repeat("\t",$tab+3).'brows(\''.$vari.'1\').name =\''.$vari.'\';
'.str_repeat("\t",$tab+3).'brows(\''.$vari.'1\').id =\''.$vari.'\';
'.str_repeat("\t",$tab+3).'brows(\'linkfile2\').style.display =\'none\';
'.str_repeat("\t",$tab+3).'brows(\'linkfile\').style.display =\'block\';
'.str_repeat("\t",$tab+2).'}
'.str_repeat("\t",$tab+1).'" id="linkfile2" style="display:none;">
'.str_repeat("\t",$tab+2).'Cancelar
'.str_repeat("\t",$tab+1).'</a>
'.str_repeat("\t",$tab+1).'<input type="hidden" name="'.$vari.'" value="'.$valor.'" id="'.$vari.'"/>
'.str_repeat("\t",$tab+1).'<input type="file" name="'.$vari.'2" id="'.$vari.'2" style="display:none;"/>
'.str_repeat("\t",$tab).'</span>';
		}
		return $tmp;
	}
	
	
	function getcalendar($vari,$valor=NULL,$tipo=1,$tab=1)
	{
		$tmp='';
		if(strlen($valor)<=0){
			$valor=($tipo!=1)?date("d/m/Y")." as ".date("H:i:s"):date("d/m/Y");
		}
		if (strstr($_SERVER["HTTP_USER_AGENT"], "MSIE")){
			return '
'.str_repeat("\t",$tab+1).'<input name="'.$vari.'" id="'.$vari.'" type="text" size="'.$Tam.'" maxlenght="'.$Tam.'" value="'.$valor.'" onkeypress="document.minhatecla=returntecla(event);" onkeyup="FData(brows(\''.$vari.'\')'.($tipo==1?'':",1").')" onblur="campodata=\''.$vari.'\';setTimeout(\'validaDT(campodata'.($tipo==1?'':",1").')\',1000);" onkeypress="formatar(this, \''.(($tipo==1)?"##/##/#### as ##:##:##":"##/##/###").'\', event);"/>';
		}
		$TS=($tipo==1)?"false":"[' as ',':']";
		$Tam=($tipo==1)?"10":"22";
			$tmp='
'.str_repeat("\t",$tab).'<span>
'.str_repeat("\t",$tab+1).'<input name="'.$vari.'" id="'.$vari.'" type="text" size="'.$Tam.'" maxlenght="'.$Tam.'" value="'.$valor.'" onkeypress="document.minhatecla=returntecla(event);" onkeyup="FData(brows(\''.$vari.'\')'.($tipo==1?'':",1").')" onblur="campodata=\''.$vari.'\';setTimeout(\'validaDT(campodata'.($tipo==1?'':",1").')\',1000);"/>
'.str_repeat("\t",$tab+1).'<script type="text/javascript">
'.str_repeat("\t",$tab+2).'$(document).ready(
'.str_repeat("\t",$tab+3).'function ()
'.str_repeat("\t",$tab+3).'{ 
'.str_repeat("\t",$tab+4).'$("#'.$vari.'").calendar
'.str_repeat("\t",$tab+4).'({
'.str_repeat("\t",$tab+5).'autoPopUp: \'button\',
'.str_repeat("\t",$tab+5).'buttonImageOnly: true,
'.str_repeat("\t",$tab+5).'buttonImage: \''.retira_barra(_ROOT_SITE_.'/images/calendar2.gif').'\',
'.str_repeat("\t",$tab+5).'buttonText: \'...\',
'.str_repeat("\t",$tab+5).'dateFormat:\'DMY/\',
'.str_repeat("\t",$tab+5).'timeSeparators:'.$TS.',
'.str_repeat("\t",$tab+5).'speed: \'immediate\',
'.str_repeat("\t",$tab+4).'});
'.str_repeat("\t",$tab+3).'});
'.str_repeat("\t",$tab+1).'</script>
'.str_repeat("\t",$tab).'</span>';
		return $tmp;
	}
	
	function wysing2($vari,$valor=NULL,$tab=1)
	{
		/*if (strstr($_SERVER["HTTP_USER_AGENT"], "MSIE")) {
		    echo '<textarea name="'.$vari.'"  size="30" rows="5" cols="25.5">'.$valor."".'</textarea>';
		    return "";
		}*/
		echo str_repeat("\t",$tab);
		$sBasePath =retira_barra(_ROOT_SITE_."/api/fckeditor/");
		$oFCKeditor = new FCKeditor($vari) ;
		$oFCKeditor->BasePath	= $sBasePath ;
		$oFCKeditor->Config['SkinPath'] = $sBasePath . 'editor/skins/office2003/' ;
		$oFCKeditor->Width	= "600px" ;
		$oFCKeditor->Height="400px";
		$oFCKeditor->Value		= $valor."" ;
		$oFCKeditor->Create() ;
	}
	
	function wysing($vari,$valor=NULL,$tab=1)
	{
		echo str_repeat("\t",$tab);
		$CKEditor = new CKEditor();
		// Path to CKEditor directory, ideally instead of relative dir, use an absolute path:
		//   $CKEditor->basePath = '/ckeditor/'
		// If not set, CKEditor will try to detect the correct path.
		$CKEditor->basePath = retira_barra(_ROOT_SITE_."/api/ckeditor/");
		// Create textarea element and attach CKEditor to it.
		$CKEditor->config['width'] = 600;
		$CKEditor->config['Height'] = 400;
		$CKEditor->config['skin'] = 'office2003';
		$CKEditor->editor($vari, $valor."");
	}

	function date2data($valor=false,$sep=""){
		$dt="d/m/Y";
		$hora=false;
		if(substr_count($valor,":")>0 || $sep!=""){
			$hora=true;
			$dt="d/m/Y H:i:s";
		}
		$tmp=divideDT(($valor!=false)?$valor:date($dt));
		return  $tmp[0]."/".$tmp[1]."/".$tmp[2].(($hora==true || $sep!="")?$sep.$tmp[3].":".$tmp[4].":".$tmp[5]:"");
	}
	
	function data2date($valor=false,$sep=" "){
		$dt="Y-m-d";
		$hora=false;
		if(substr_count($valor,":")>0  || $sep!=""){
			$hora=true;
			$dt="Y-m-d H:i:s";
		}
		$tmp=divideDT(($valor!=false)?$valor:date($dt));
		return  $tmp[2]."-".$tmp[1]."-".$tmp[0].(($hora==true || $sep!="")?$sep.$tmp[3].":".$tmp[4].":".$tmp[5]:"");
	}
	function monta_menu($vetor=array(),$menuid="menu_init",$is_menu=true,$tab=1){
		$tmp=($is_menu==true)?"
".str_repeat("\t",$tab)."<div id=\"".$menuid."\"></div>
".str_repeat("\t",$tab)."<script type=\"text/javascript\">
".str_repeat("\t",$tab+1)."var menuvar = [":"";
		foreach ($vetor as $menu){
			if(count($menu)>0){
				$tmp.=(($is_menu!=true)?'_cmSplit,':'')."[".
				((strlen($menu[0])>0)?'"<img src=\"'.$menu[0].'\" width=\"15px\" height=\"15px\"/>"':"null")
				.",".
				((strlen($menu[1])>0)?'"'.$menu[1].'"':'"&nbsp;"')
				.",".
				((strlen($menu[2])>0)?'"'.$menu[2].'"':'null')
				.",".
				(is_array($menu[3])?"".((strlen($menu[1])>0)?'"'.$menu[1].'"':'null').","."".monta_menu($menu[3],$menuid,false)."":((strlen($menu[3])>0)?'"'.$menu[3].'"':"'null'"))."],";
			}
		}
		$tmp.=($is_menu==true)?"];
".str_repeat("\t",$tab+1)."cmDraw ('".$menuid."', menuvar, 'hbr', cmThemeOffice, 'ThemeOffice');
".str_repeat("\t",$tab)."</script>":"";
		return $tmp;
	}
	
	function searchfile($dir=_ROOT_DIR_SITE_,$arquivo=false){
		$ponteiro  = opendir($dir);
		$tmp="";
		while ($nome = readdir($ponteiro)) {
			$tmp=$nome."/";
			//echo ($tmp=="./" || $tmp=="../")." ".$tmp."<br>";
			if (is_file($dir.$nome) && $arquivo==$nome){
				return $dir.$nome;
			}elseif (!is_file($dir.$nome) && is_dir($dir.$nome) && !($tmp=="./" || $tmp=="../")){
				$tmp=searchfile($dir.$nome."/",$arquivo);
				if($tmp!=false){
					return $tmp;
				}
			}elseif (!($tmp=="./" || $tmp=="../")){
				//echo $dir.$nome."<br>";
			}
		}
		return false;
	}
	
        function include_files($url,$path="/"){
        	$url=explode($path,$url);
        	$url=$url[1];
        	$url=explode("?",$url);
        	$url=$url[1];
        	$url2="";
        	if(is_file(_ROOT_SITE_.$path.$url) && substr_count($url,".php")>0 && substr_count($url,"index.php")<=0){
        		include($url);
                exit(0);
        	}elseif(substr_count($url,".php")>0 && substr_count($url,"index.php")<=0){
        		$url=explode(".",$url);
        		$url=$url[0];
        		$url=$url.".php";
        		$url2=searchfile("forms/",$url);
        		if(substr_count($url2,$url)>0){
        			include($url2);
        			exit(0);
        		}
        	}
        }
        function up_file($file,$del=""){
        	if(isset($_FILES[$file])){
				if(strlen($del)>0)@unlink($del);
				$tmp=upload_img($_FILES[$file]);
			}else{
				$tmp=$_POST[$file];
			}
			return $tmp;
        }
        
        function up_file_music($file,$del=""){
        	if(isset($_FILES[$file])){
				if(strlen($del)>0)@unlink($del);
				$tmp=upload_music($_FILES[$file]);
			}else{
				$tmp=$_POST[$file];
			}
			return $tmp;
        }
        
        function up_file_video($file,$del=""){
        	if(isset($_FILES[$file])){
				if(strlen($del)>0)@unlink($del);
				$tmp=upload_video($_FILES[$file]);
			}else{
				$tmp=$_POST[$file];
			}
			return $tmp;
        }
        
	function selectmes($mes=01,$nomecampo='mes',$tab=1){
        $MES_=array(
			1=>"janeiro",
			2=>"fevereiro",
			3=>"marco",
			4=>"abril",
			5=>"maio",
			6=>"junho",
			7=>"julho",
			8=>"agosto",
			9=>"setembro",
			10=>"outubro",
			11=>"novembro",
			12=>"dezembro"
		);
		$tmp='';
		foreach ($MES_ as $num=>$nome){
			$tmp.="
".str_repeat("\t",$tab+1)."<option value=".$num." ".(($mes==$num)?' selected':'').">".$nome."</option>";
		}
		
		return '
'.str_repeat("\t",$tab).'<select id="'.$nomecampo.'" name="'.$nomecampo.'">'.$tmp.'
'.str_repeat("\t",$tab).'</select>';
	}
	function return_mes($mes){
        $MES_=array(
			1=>"janeiro",
			2=>"fevereiro",
			3=>"marco",
			4=>"abril",
			5=>"maio",
			6=>"junho",
			7=>"julho",
			8=>"agosto",
			9=>"setembro",
			10=>"outubro",
			11=>"novembro",
			12=>"dezembro"
		);
		return $MES_[$mes];
	}
	
	function grafico($titulo="titulo",$op=array(30,38,20,10,2),$labels=array("Otimo","Bom","Regular","Ruim","Pessimo"),$color=array("0000ff"),$tab=1)
	{
		return '
'.str_repeat("\t",$tab).'<img src="http://chart.apis.google.com/chart?chs=600x280&chd=t:'.implode(",",$op).'&cht=p3&chl='.implode("%|",$op).'%&chtt='.$titulo.'&chdl='.implode("|",$labels).'&chco='.implode(",",$color).'">';
	}
	
	function backup_sql($mode='d'){
		$host = _HOST_MYSQL_SITE_;//host/endereco do banco
		$user = _USER_MYSQL_SITE_;//usuario acesso
		$password = _PASSWD_MYSQL_SITE_;//password do usuario
		$dir_temp = retira_barra(_ROOT_DIR_SITE_._PATH_UPLOAD_);//diretorio temporario
		$dir_backup = retira_barra(_ROOT_DIR_SITE_._PATH_UPLOAD_);//diretorio que ira guardar os backups
		$database = is_numeric(_DB_MYSQL_SITE_)?"`"._DB_MYSQL_SITE_."`":_DB_MYSQL_SITE_;//especificando uma base para backup
		$mysql_dump = new MYSQL_DUMP();
	    $mysql_dump = new MYSQL_DUMP($host,$user,$password);
	    $sql = $mysql_dump->dumpDB($database); //Takes all database backups
	    $name_back=$dir_backup."dump_"._DB_MYSQL_SITE_."_".date("d-m-Y_H.i.s")."_.sql";
		global $mensagem_system;
	    if($sql==false)
	        $mensagem_system.=$mysql_dump->error()."<br>";
	    if($mode=='d'){
	    	$mysql_dump->download_sql($sql,$name_back);
	    	exit(0);
	    }
	    if($mode=='s')
	    {
	    	$mysql_dump->save_sql($sql,$name_back);
	    	@chmod($name_back,0777);
			if(is_file($name_back))$mensagem_system.="Backup do BD efetuado com sucesso!<br>";
			else $mensagem_system.="Falha ao efetuar Backup do BD!<br>";
	    }
	    if($mode=='p' || $mode=='e')
	    {
	    	//echo nl2br($sql);
			$mensagem_system.="<fieldset style=\"width:800px;\"><legend>C&oacute;digo SQL do banco de dados:</legend><div style=\"width:800px;height:400px;overflow:auto;\">".nl2br(htmlentities($sql))."</div></fieldset>";
	    }
	}
	function backup_data(){
			$name_back=retira_barra(_ROOT_DIR_SITE_._PATH_UPLOAD_."/backup_data_".date("d-m-Y_H.i.s")."_.zip");
			$zipfile=new zipfile();
			$zipfile->addDirContent(_ROOT_DIR_SITE_,1,array(/*"uploads","conf.php","novo","antigo","cgi-bin"*/));
			$zipfile->save($name_back);
			@chmod($name_back,0777);
    		global $mensagem_system;
			if(is_file($name_back))$mensagem_system.="Backup de dados efetuado com sucesso!<br>";
			else $mensagem_system.="Falha ao efetuar Backup de dados!<br>";
	}
	
function delTree($dir){
    $files = glob($dir . '*', GLOB_MARK );
    foreach($files as $file){
        if( substr( $file, -1 ) == '/' )
            delTree( $file );
        else
            unlink( $file );
    }
    if (is_dir($dir)) rmdir( $dir );
    else unlink($dir);
    global $mensagem_system;
    $mensagem_system.="Pasta/Arquivo Deletada!<br>";
}
function precofrete($origem='35300-190',$destino='35300-190',$peso='0.5',$servico='40010')
{
	//script original pego em http://forum.prestashopbr.com/viewtopic.php?f=9&t=322&start=30
	#####################################
	# Codigo dos Servicos dos Correios  #
	#    FRETE PAC = 41106       #
	#    FRETE SEDEX = 40010       #
	#    FRETE SEDEX 10 = 40215       #
	#    FRETE SEDEX HOJE = 40290    #
	#    FRETE E-SEDEX = 81019       #
	#    FRETE MALOTE = 44105       #
	#    FRETE NORMAL = 41017       #
	#   SEDEX A COBRAR = 40045       #
	#####################################
	$servicos=array(
		41106=>"PAC",
		40010=>"SEDEX",
		40215=>"SEDEX 10",
		40290=>"SEDEX HOJE",
		81019=>"E-SEDEX",
		44105=>"MALOTE",
		41017=>"NORMAL",
		40045=>"SEDEX A COBRAR"
	);
	if (strlen($origem)<=0)$origem='35300-190';
	if (strlen($destino)<=0)$destino='35300-190';
	if (strlen($peso)<=0)$peso='0.5';
	if (strlen($servico)<=0 || !in_array($servico,array_keys($servicos)))$servico='40010';
	// Codigo do Servico que deseja calcular, veja tabela acima:
	// CEP de Origem, em geral o CEP da Loja
	// CEP de Destino, voce pode passar esse CEP por GET ou POST vindo de um formulario
	$destino = eregi_replace("([^0-9])","",$destino);
	$origem = eregi_replace("([^0-9])","",$origem);
	// Peso total do pacote em Quilos, caso seja menos de 1Kg, ex.: 300g, coloque 0.300
	// URL de Consulta dos Correios
	$correios = "http://www.correios.com.br/encomendas/precos/calculo.cfm?resposta=xml&servico=".$servico."&cepOrigem=".$origem."&cepDestino=".$destino."&peso=".$peso."&MaoPropria=N&avisoRecebimento=N";
	// Capta as informacoes da pagina dos Correios
	$correios_info = file($correios) or die('error');
	// Processa as informacoes vindas do site dos correios em um Array
	$info=implode("",$correios_info);
//	foreach($correios_info as $info){
		// Busca a informacao do Preco da Postagem
		if(preg_match("/\<servico_nome>(.*)\<\/servico_nome>/",$info,$servico_nome)){
			$servico_nome= $servico_nome[0];
		}
		if(preg_match("/\<preco_postal>(.*)\<\/preco_postal>/",$info,$preco)){
			$preco = $preco[0];
		}
		if(preg_match("/\<uf_destino>(.*)\<\/uf_destino>/",$info,$uf_destino)){
			$uf_destino = $uf_destino[0];
		}
		if(preg_match("/\<uf_origem>(.*)\<\/uf_origem>/",$info,$uf_origem)){
			$uf_origem = $uf_origem[0];
		}
		if(preg_match("/\<cep_destino>(.*)\<\/cep_destino>/",$info,$cep_destino)){
			$cep_destino = $cep_destino[0];
		}
		if(preg_match("/\<cep_origem>(.*)\<\/cep_origem>/",$info,$cep_origem)){
			$cep_origem = $cep_origem[0];
		}
		if(preg_match("/\<codigo>(.*)\<\/codigo>/",$info,$coderro)){
			$coderro = $coderro[0];
		}
		if(preg_match("/\<descricao>(.*)\<\/descricao>/",$info,$errodesc)){
			$errodesc = $errodesc[0];
		}
//	}
		if (strlen($errodesc)>23){
			$erro=array($coderro,$errodesc);
		}else{
			$erro=0;
		}
		return array($servico_nome,$preco." ".$matches[1][6],$uf_origem,$uf_destino,$cep_origem,$cep_destino,$erro);
}

function busca_cep($cep)
{
	$resultado = @file_get_contents('http://republicavirtual.com.br/web_cep.php?cep='.urlencode($cep).'&formato=query_string');
	if(!$resultado){
		$resultado = "&resultado=0&resultado_txt=erro+ao+buscar+cep";
	}
	parse_str($resultado, $retorno); 
	return $retorno;
}

function grava_em_arq($content='',$nomearq,$pasta='include/'){
	$content=is_string($content)?$content:implode("",$content);
	if(!is_dir($pasta))exec("mkdir -p ".$pasta."/");
	if(!is_dir($pasta))exec("chmod 777 ".$this->pasta_to_save."/class");
	if(!is_file(retira_barra($pasta."/".$nomearq))) file_put_contents(retira_barra($pasta."/".$nomearq),'');
	if($fp=fopen(retira_barra($pasta."/".$nomearq),"w+")){
		if(fwrite($fp,$content)){
			fclose($fp);
			exec("chmod 777 ".retira_barra($pasta."/".$nomearq));
			return true;
		}
		return false;
	}
	return false;
}

//function to encrypt the string
function encode5t($str){
	for($i=0; $i<5;$i++)  {
		$str=strrev(base64_encode($str)); //apply base64 first and then reverse the string
	}
	return $str;
}
//function to decrypt the string
function decode5t($str){
	for($i=0; $i<5;$i++){
		$str=base64_decode(strrev($str)); //apply base64 first and then reverse the string}
	}
	return $str;
}

function produtos(){
	if(is_file(retira_barra(_ROOT_DIR_SITE_."/pages/produtos.txt"))){
		$pr=file(retira_barra(_ROOT_DIR_SITE_."/pages/produtos.txt"));
		$produto=array();
		foreach($pr as $un){
			$un=explode(",",$un);
			$produto[]=$un;
		}
		return $produto;
	}
	return array();
}

function clean_html_code($uncleanhtml)
{
    // Set wanted indentation
    $indent = "\t";
    // Set tags that should not indent
    //$no_indent = array ('html', 'head', 'body', 'script');
    $no_indent = array ();
    // Set tags that should not linebreak
    //$no_linebreak = array ('a', 'b', 'em', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'i', 'span', 'strong', 'title');
    $no_linebreak = array ();
    /* STRIP SUPERFLUOUS WHITESPACE */
    // Remove all indentation
    $uncleanhtml = preg_replace("/[\r\n]+[\s\t]+/", "\n", $uncleanhtml);
    // Remove all trailing space
    $uncleanhtml = preg_replace("/[\s\t]+[\r\n]+/", "\n", $uncleanhtml);
    // Remove all blank lines
    $uncleanhtml = preg_replace("/[\r\n]+/", "\n", $uncleanhtml);
    /* INSERT LINE SEPARATORS */
    // Separate 'whitespace-adjacent' tags with newlines, unless they are a pair
    $fixed_uncleanhtml = preg_replace("/>[\s\t]*</", ">\n<", $uncleanhtml);
    $fixed_uncleanhtml = preg_replace("/((<[a-zA-Z]>)|(<[^\/][^>]*[^\/>]>))\n(<\/)/U", "\${1}\${4}", $fixed_uncleanhtml);
    // Separate closing Javascript brackets with newlines
    $fixed_uncleanhtml = preg_replace("/\}[\s\t]*\}/", "}\n}", $fixed_uncleanhtml);
    /* FIX 'HANGING' TAGS */
    // Insert newlines before 'hanging' closing tags (ie. <p>\nSome text</p>\n)
    $fixed_uncleanhtml = preg_replace("/(\n[^<\n]*[^<\n\s\t])[\s\t]*(<\/[^>\n]+>[^\n]*\n)/U", "\${1}\n\${2}", $fixed_uncleanhtml);
    // Insert newlines after 'hanging' opening tags (ie. <p>Some text\n</p>)
    $fixed_uncleanhtml = preg_replace("/((<[a-zA-Z]>)|(<[^\/][^>]*[^\/]>))[\s\t]*([^\s\t(<\/)\n][^(<\/)\n]*\n)/", "\${1}\n\${4}", $fixed_uncleanhtml);
    /* HANDLE THE NO_LINEBREAK LIST */
    // Remove newlines after opening tags from our no_linebreak list (unless they are self-closing)
    $fixed_uncleanhtml = preg_replace("/(<(" . implode('|', $no_linebreak) . ")((\s*>)|(\s[^>]*[^\/]>)))\n/U", "\${1}", $fixed_uncleanhtml);
    // Remove newlines before closing tags from our no_linebreak list
    $fixed_uncleanhtml = preg_replace("/\n(<\/(" . implode('|', $no_linebreak) . ")[\s\t]*>)/U", "\${1}", $fixed_uncleanhtml);
    /* OK, READY TO INDENT */
    $uncleanhtml_array = explode("\n", $fixed_uncleanhtml);
    // Sets no indentation
    $indentlevel = 0;
    foreach ($uncleanhtml_array as $uncleanhtml_key=>$currentuncleanhtml)
    {
        $replaceindent = "";
        // Sets the indentation from current indentlevel
        for ($o = 0; $o < $indentlevel; $o++)
        {
            $replaceindent .= $indent;
        }
        // If self-closing tag, simply apply indent
        if (preg_match("/<(.+)\/>/", $currentuncleanhtml))
        {
            $cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
        }
        // If doctype declaration, simply apply indent
        else if (preg_match("/<!(.*)>/", $currentuncleanhtml))
        {
            $cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
        }
        // If opening AND closing tag on same line, simply apply indent
        else if (preg_match("/<[^\/](.*)>/", $currentuncleanhtml) && preg_match("/<\/(.*)>/", $currentuncleanhtml))
        {
            $cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
        }
        // If closing HTML tag AND not a tag from the no_indent list, or a closing JavaScript bracket (with no opening bracket on the same line), decrease indentation and then apply the new level
        else if ((preg_match("/<\/(.*)>/", $currentuncleanhtml) && !preg_match("/<\/(".implode('|', $no_indent).")((>)|(\s.*>))/", $currentuncleanhtml)) || preg_match("/^\}{1}[^\{]*$/", $currentuncleanhtml))
        {
            $indentlevel--;
            $replaceindent = "";
            for ($o = 0; $o < $indentlevel; $o++)
            {
                $replaceindent .= $indent;
            }
            $cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
        }
        // If opening HTML tag AND not a stand-alone tag AND not a tag from the no_indent list, or opening JavaScript bracket (with no closing bracket first), increase indentation and then apply new level
        else if ((preg_match("/<[^\/](.*)>/", $currentuncleanhtml) && !preg_match("/<(link|meta|base|br|img|hr)(.*)>/", $currentuncleanhtml) && !preg_match("/<(" . implode('|', $no_indent) . ")((>)|(\s.*>))/", $currentuncleanhtml)) || preg_match("/^[^\{\}]*\{[^\}]*$/", $currentuncleanhtml))
        {
            $cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
            $indentlevel++;
            $replaceindent = "";
            for ($o = 0; $o < $indentlevel; $o++)
            {
                $replaceindent .= $indent;
            }
        }
        // If both a closing and an opening JavaScript bracket (like in a condensed else clause), decrease indentation on this line only
        else if (preg_match("/^[^\{\}]*\}[^\{\}]*\{[^\{\}]*$/", $currentuncleanhtml))
        {
            $indentlevel--;
            $replaceindent = "";
            for ($o = 0; $o < $indentlevel; $o++)
            {
                $replaceindent .= $indent;
            }
            $cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
            // Reset indent to previous level
            $indentlevel++;
            $replaceindent .= $indent;
        }
        else
        // Else, only apply indentation
        {
            $cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
        }
    }
    // Return single string separated by newline
    return implode("\n", $cleanhtml_array);
}
	function IsCPF($cpf)
{	// Verifiva se o número digitado contém todos os digitos
    $cpf = str_pad(ereg_replace('[^0-9]', '', $cpf), 11, '0', STR_PAD_LEFT);
	
	// Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
    if (strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999')
	{
	return false;
    }
	else
	{   // Calcula os números para verificar se o CPF é verdadeiro
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf{$c} * (($t + 1) - $c);
            }

            $d = ((10 * $d) % 11) % 10;

            if ($cpf{$c} != $d) {
                return false;
            }
        }

        return true;
    }
}
	
?>
