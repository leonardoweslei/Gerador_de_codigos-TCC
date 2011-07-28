// JavaScript Document
function mostraDiv(id)
{
	i = 0
	for(i=1;i<=7;i++)
	{
		document.getElementById('subMenu'+i).style.display='none'
	}
	document.getElementById(id).style.display='block'
}
function preload() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=preload.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function retornaImg() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function trocaImg() { //v3.0
  var i,j=0,x,a=trocaImg.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function retornaImgAba() { //v3.0
  var e,y,b=document.MM_sra; for(e=0;b&&e<b.length&&(y=b[e])&&y.oSrc;e++) y.src=y.oSrc;
}
function trocaImgAba() { //v3.0
  var e,k=0,y,b=trocaImgAba.arguments; document.MM_sra=new Array; for(e=0;e<(b.length-2);e+=3)
   if ((y=MM_findObj(b[e]))!=null){document.MM_sra[k++]=y; if(!y.oSrc) y.oSrc=y.src; y.src=b[e+2];}
}

function RodarFlash(path,variaveis,_width,_height)
{
	document.write('<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="'+_width+'" height="'+_height+'">')
		document.write('<param name="movie" value="'+path+'?'+variaveis+'">')
		document.write('<param name="quality" value="high">')
		document.write('<param name="wmode" value="transparent">')
		document.write('<embed src="'+path+'?'+variaveis+'" width="'+_width+'" height="'+_height+'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" wmode="transparent"></embed>')
	document.write('</object>')
}

var xmlhttp = null;
// Conex„o via XmlHttp
try {
	xmlhttp = new XMLHttpRequest();
} catch(ee) {
	try {
    	xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	} catch(e) {
    	try {
        	xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    	} catch(E) {
        	xmlhttp = false;
    	}
	}
}
	
function mostraConteudo(url, div){
	// Seleciona objeto
	obj_div = document.getElementById(div);
   	// Verifica se existe xmlhttp
   	if (xmlhttp) {		
		xmlhttp.open("GET", url, true);
		xmlhttp.onreadystatechange = function() {
		   	// Verifica estado da requisiÁ„o
		    if (xmlhttp.readyState == 1) {
		    	obj_div.innerHTML = "Aguarde ...";
		    } else if (xmlhttp.readyState == 4) {
		    	// Verifica status da requisiÁ„o
				if (xmlhttp.status == 200) {
					obj_div.innerHTML = xmlhttp.responseText;
				} else {
					obj_div.innerHTML = "Erro ao carregar ...";
				}
			}
		}
   	}
   	xmlhttp.send(null);
}
	
function getRefToDiv(divID,oDoc) {
    if( !oDoc ) { oDoc = document; }
    if( document.layers ) {
        if( oDoc.layers[divID] ) { return oDoc.layers[divID]; } else {
            //repeatedly run through all child layers
            for( var x = 0, y; !y && x < oDoc.layers.length; x++ ) {
                //on success, return that layer, else return nothing
                y = getRefToDiv(divID,oDoc.layers[x].document); }
            return y; } }
    if( document.getElementById ) {
        return document.getElementById(divID); }
    if( document.all ) {
        return document.all[divID]; }
    return false;
}

function escondelayer(layer) {
	myReference = getRefToDiv(layer);
	if (myReference.style) //DOM & proprietary DOM
        myReference.style.visibility = 'hidden';
    else //Netscape
       myReference.visibility = 'hide';
}

function exibelayer(layer) {
	myReference = getRefToDiv(layer);
	if (myReference.style) //DOM & proprietary DOM
        myReference.style.visibility = 'visible';
    else //Netscape
       myReference.visibility = 'show';

}


// FunÁ„o usada para alternar abas selecionadas, apenas efeito visual.
/*var objGuiaSelecionada = new Array();
function selecionaGuia_velho(grupo,nome,cor_ativo,cor_inativo,aumenta_borda){
	objThis = document.getElementById(nome);
	if(objGuiaSelecionada[grupo] != null){
		objGuiaSelecionada[grupo].style.background = cor_ativo;
		objThis.style.background = cor_inativo;
		objThis.style.aumenta_borda = '2px';	
		objGuiaSelecionada[grupo] = objThis;
	}else{
		objThis.style.background = cor_inativo;
		objGuiaSelecionada[grupo] = objThis;
	}
}
*/
// FunÁ„o usada para alternar abas selecionadas, apenas efeito visual.
var objGuiaSelecionada = new Array();
function selecionaGuia(grupo,nome,classeAtivo,classeInativo){
	objThis = document.getElementById(nome);
	if(objGuiaSelecionada[grupo] != null){
		objGuiaSelecionada[grupo].className = classeInativo;
		objThis.className= classeAtivo;
		objGuiaSelecionada[grupo] = objThis;
	}else{
		objThis.style.classNane = classeInativo;
		objGuiaSelecionada[grupo] = objThis;
	}
}

function fecharFoto(){
	objDivMostraFoto = document.getElementById("divMostraFoto");
	objDivMostraFoto.style.display = "none";
	objDivMostraFotoFundo = document.getElementById("divMostraFotoFundo");
	objDivMostraFotoFundo.style.display = 'none';
}

function exibeFoto(nomeFoto){
	var pageSize = getPageSize();
	var pageScroll = getPageScroll();
	var distanciaTop = pageScroll.yScroll;
	imgFotoFull = document.getElementById("fotoFull");
	imgFotoFull.src = nomeFoto;
	objDivMostraFotoFundo = document.getElementById("divMostraFotoFundo");
	objDivMostraFotoFundo.style.width = pageSize.pageWidth;
	objDivMostraFotoFundo.style.height = pageSize.pageHeight;
	objDivMostraFotoFundo.style.display = 'block';
	objDivMostraFoto = document.getElementById("divMostraFoto");
	objDivMostraFoto.style.top = (distanciaTop < 0) ? "0px" : distanciaTop+"px";
	objDivMostraFoto.style.display = 'block';
}

function getPageSize() {
		var xScroll, yScroll;
		if (window.innerHeight && window.scrollMaxY){
			xScroll = document.body.scrollWidth;
			yScroll = window.innerHeight + window.scrollMaxY;
		} else if (document.body.scrollHeight > document.body.offsetHeight){
			xScroll = document.body.scrollWidth;
			yScroll = document.body.scrollHeight;
		} else {
			xScroll = document.body.offsetWidth;
			yScroll = document.body.offsetHeight;
		}
		var windowWidth, windowHeight;
		if (self.innerHeight) {
			windowWidth = self.innerWidth;
			windowHeight = self.innerHeight;
		} else if (document.documentElement && document.documentElement.clientHeight) {
			windowWidth = document.documentElement.clientWidth;
			windowHeight = document.documentElement.clientHeight;
		} else if (document.body) {
			windowWidth = document.body.clientWidth;
			windowHeight = document.body.clientHeight;
		}	
		if(yScroll < windowHeight) pageHeight = windowHeight;
		else pageHeight = yScroll;
		if(xScroll < windowWidth) pageWidth = windowWidth;
		else pageWidth = xScroll;
		arrayPageSize = {pageWidth:pageWidth,pageHeight:pageHeight,windowWidth:windowWidth,windowHeight:windowHeight}
		return arrayPageSize;
	}
	
function getPageScroll(){
	var yScroll;
	if (self.pageYOffset) yScroll = self.pageYOffset;
	else if (document.documentElement && document.documentElement.scrollTop) yScroll = document.documentElement.scrollTop;
	else if (document.body) yScroll = document.body.scrollTop;
	arrayPageScroll = {yScroll:yScroll};
	return arrayPageScroll;
}

function abrirPopup(url,titulo,parametros){
	window.open(url,titulo,parametros);
}

function sorteiaDestaque(arrDestaques){
	if(arrDestaques.length > 0){
		var escolhido = Math.round(Math.random()*(arrDestaques.length-1));
		return arrDestaques[escolhido];
	}else{
		return '';
	}
}

var arrSoteiaUnico = new Array();
function sorteiaUnico(arrUnico){
	do{
		continua = false;
		var escolhido = Math.round(Math.random()*(arrUnico.length-1));
		for(var i=0;i<arrSoteiaUnico.length;i++){
			if(arrSoteiaUnico[i] == escolhido){
				continua = true;
				break;
			}
		}
	} while(continua);
	arrSoteiaUnico.push(escolhido);
	return arrUnico[escolhido];
}

function selecionaAba(arrButtons, arrDivs, idClicado){
	for(var i=0;i<arrButtons.length;i++){
		var elemento = document.getElementById(arrButtons[i]);
		var painel = document.getElementById(arrDivs[i]);

		if(arrButtons[i] == idClicado){
			elemento.style.backgroundPosition = '0 100%';
			painel.style.display = 'block';
		}else{
			elemento.style.backgroundPosition = '';
			painel.style.display = 'none';
		}
	}
}

function setEstacao(aba) {
	document.getElementById(aba).style.backgroundPosition = '0 100%';
}

function exibeComentar(){
	objDiv = document.getElementById("divComentar");
	objDiv.style.display = 'block';
	
	var paginaLeft;
	var paginaTop;
	if(document.documentElement.scrollTop>=0){
		paginaLeft=document.documentElement.scrollLeft;
		paginaTop=document.documentElement.scrollTop;
	}else if(document.body.scrollTop>=0){
		paginaLeft=document.body.scrollLeft;
		paginaTop=document.body.scrollTop;
	}else{
		paginaLeft=window.pageXOffset;
		paginaTop=window.pageYOffset;
	}
	
	paginaTop = (paginaTop - (objDiv.clientHeight / 2));
	paginaLeft = (paginaLeft - (objDiv.clientWidth / 2));
	
	objDiv.style.margin = paginaTop+'px 0px 0px '+paginaLeft+'px';
}

function escondeComentar(){
	objDiv = document.getElementById("divComentar");
	objDiv.style.display = 'none';
}

function getAnchor()
{
    var anchor = window.location.href.slice(window.location.href.indexOf('#') + 1);
    return anchor;
}

function esconder(idbloco,strEsconder,strMostrar,flagTitulo){
	if(document.getElementById(idbloco).style.visibility == 'hidden'){
		document.getElementById(idbloco).style.visibility = 'visible';
		document.getElementById(idbloco).style.display = 'block';
		document.getElementById(idbloco+'link').innerHTML = strEsconder;
		if(flagTitulo == 1){
			document.getElementById(idbloco+'titulo').className = "imprimir";
		}
	}else{
		document.getElementById(idbloco).style.visibility = 'hidden';
		document.getElementById(idbloco).style.display = 'none';
		document.getElementById(idbloco+'link').innerHTML = strMostrar;
		if(flagTitulo == 1){
			document.getElementById(idbloco+'titulo').className = "naoimprimir";
		}
	}
}

function carregaIframePrevisao(){
	selecionadoestados = document.getElementById('previsao_estados').selectedIndex;
	selecionadocidades = document.getElementById('previsao_cidades').selectedIndex;
	estado = retira_acentos(document.getElementById('previsao_estados').options[selecionadoestados].text);
	cidade = retira_acentos(document.getElementById('previsao_cidades').options[selecionadocidades].text);
	idct = document.getElementById('previsao_cidades').options[selecionadocidades].value;
	if( cidade != '' && cidade != undefined && estado != '' && estado != undefined ){
		ender = '/destinoaventura/destinos/index/blocoprevisao/cidade/'+cidade+'/uf/'+estado+'/idct/'+idct;
		
		mostraConteudo(ender,'bloco_iframe_previsao');
	}else{
		alert('escolha o estado e cidade para ver a previs„o do tempo');
	}
}

function retira_acentos(palavra) {
	com_acento = "·‡„‚‰ÈËÍÎÌÏÓÔÛÚıÙˆ˙˘˚¸Á¡¿√¬ƒ…» ÀÕÃŒœ”“’÷‘⁄Ÿ€‹«";
	sem_acento = "aaaaaeeeeiiiiooooouuuucAAAAAEEEEIIIIOOOOOUUUUC";
	nova = "";
	for(i=0;i<palavra.length;i++){
		if (com_acento.search(palavra.substr(i,1))>=0) {
			nova+=sem_acento.substr(com_acento.search(palavra.substr(i,1)),1);
		}else{
			nova+=palavra.substr(i,1);
		}
	}
	return nova;
}

function buscamunicipio(){
	municipio = retira_acentos(document.getElementById('inputmunicipio').value);
	if(municipio != '' || municipio != undefined){
		ender = '/destinoaventura/destinos/index/blocoprevisao/municipio/'+municipio;
		mostraConteudo(ender,'bloco_iframe_previsao');
		return false;
	}else{
		alert('Digite um municÌpio ou selecione o estado e a cidade nas listas.');
		return false;
	}
}

function getConteudoJason(url,evento){
	// Seleciona objeto
   	// Verifica se existe xmlhttp
   	if (xmlhttp) {
		xmlhttp.open("GET", url, true);
		xmlhttp.onreadystatechange = function() {
		   	// Verifica estado da requisiÁ„o
		    if (xmlhttp.readyState == 1) {
		    	arrImagens = "aguarde";
		    } else if (xmlhttp.readyState == 4) {
		    	// Verifica status da requisiÁ„o
				if (xmlhttp.status == 200) {
					arrImagens = eval(xmlhttp.responseText);
					setTimeout(evento, 0);
				} else {
					arrImagens = "erro";
				}
			}
		}
   	}
   	xmlhttp.send(null);
}

function chamaVoidAjax(url){
	// Seleciona objeto
   	// Verifica se existe xmlhttp
   	if (xmlhttp) {
		xmlhttp.open("GET", url, true);
		xmlhttp.onreadystatechange = function() {}
   	}
   	xmlhttp.send(null);
}

var parceiroBannerQuadrado = false;
var parceiroBannerSky = false;