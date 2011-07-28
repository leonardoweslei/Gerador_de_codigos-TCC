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
// Conex�o via XmlHttp
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
	
function mostraConteudo(url, div) {
	// Seleciona objeto
	obj_div = document.getElementById(div);
   	// Verifica se existe xmlhttp
   	if (xmlhttp) {		
		xmlhttp.open("GET", url, true);
		xmlhttp.onreadystatechange = function() {
		   	// Verifica estado da requisi��o
		    if (xmlhttp.readyState == 1) {
		    	obj_div.innerHTML = "Aguarde ...";
		    } else if (xmlhttp.readyState == 4) {
		    	// Verifica status da requisi��o
				if (xmlhttp.status == 200) {
					obj_div.innerHTML = xmlhttp.responseText;
					arrItensValor = xmlhttp.responseText.split(";");
					atualizaDisplayCesta(arrItensValor[1],arrItensValor[0]);
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

controleCestaVazia = 0;

function atualizaimpresso(idProdutosEventosFormatos)
{
	qtd = document.getElementById("pr["+idProdutosEventosFormatos+"][qtd]").value;
	preco = parseFloat(document.getElementById("pr["+idProdutosEventosFormatos+"][preco]").value);
	if (!somente_numero(qtd)) {
		qtd = 0;
		document.getElementById("pr["+idProdutosEventosFormatos+"][qtd]").value = 0;
	}
	controleCestaVazia = 1;

	subtotal = document.getElementById("pr["+idProdutosEventosFormatos+"][subtotal]");
	subtotal.innerHTML = (preco*qtd).toFixed(2).replace(".", ",");
}

function somente_numero(strNumero) {
	var expressao = /^\d+$/
  	if (!strNumero.match(expressao)) {
  		alert("Digite uma quantidade v�lida");
  		return false;
  	}
  	return true;
}

function atualizadigital(idProdutosEventosFormatos, preco) {
	subtotal = document.getElementById("arrProdutos["+idProdutosEventosFormatos+"][subtotal]");
	if (document.getElementById("arrProdutos["+idProdutosEventosFormatos+"][qtd]").checked) {
	  	subtotal.innerHTML = preco.toFixed(2).replace(".", ",");
	} else {
	  	subtotal.innerHTML = '0,00';
	}
	controleCestaVazia = 1;
}

function validaCesta(){
	if(controleCestaVazia > 0){ 
		return true;
	}else{
		alert('Selecione o(s) formato(s) e quantidade(s) que deseja comprar');
		return false;
	}
}

function extractParamsFromForm(form, elements)
{
	if (!form)
		return '';

	if ( typeof form == 'string' )
		form = document.getElementById(form) || document.forms[form];

	var formElements;
	if (elements)
		formElements = ',' + elements.join(',') + ',';

	var compStack = new Array(); // key=value pairs

	var el;
	for (var i = 0; i < form.elements.length; i++ )
	{
		el = form.elements[i];
		if (el.disabled || !el.name)
		{
			// Don't submit disabled elements.
			// Don't submit elements without name.
			continue;
		}

		if (!el.type)
		{
			// It seems that this element doesn't have a type set,
			// so skip it.
			continue;
		}

		if (formElements && formElements.indexOf(',' + el.name + ',')==-1)
			continue;

		switch(el.type.toLowerCase())
		{
			case 'text':
			case 'password':
			case 'textarea':
			case 'hidden':
			case 'submit':
				compStack.push(encodeURIComponent(el.name) + '=' + encodeURIComponent(el.value));
				break;
			case 'select-one':
				var value = '';
				var opt;
				if (el.selectedIndex >= 0) {
					opt = el.options[el.selectedIndex];
					value = opt.value || opt.text;
				}
				compStack.push(encodeURIComponent(el.name) + '=' + encodeURIComponent(value));
				break;
			case 'select-multiple':
				for (var j = 0; j < el.length; j++)
				{
					if (el.options[j].selected)
					{
						value = el.options[j].value || el.options[j].text;
						compStack.push(encodeURIComponent(el.name) + '=' + encodeURIComponent(value));
					}
				}
				break;
			case 'checkbox':
			case 'radio':
				if (el.checked)
					compStack.push(encodeURIComponent(el.name) + '=' + encodeURIComponent(el.value));
				break;
			default:
			// file, button, reset
			break;
			}
		}
	return compStack.join('&');
}

function exibeCompraRapida(url){
	fecharAvisoCesta();
	mostraConteudo(url,"divCompraRapida");
	objDivCompraRapida = document.getElementById("divCompraRapida");
	objDivCompraRapida.style.display = 'block';
	//paginaTop = document.body.scrollTop;
	paginaTop = getScrollY();
	paginaTop = (paginaTop - 160);
	//paginaLeft = document.body.scrollLeft;
	paginaLeft = getScrollX();
	paginaLeft = (paginaLeft - 376);
	objDivCompraRapida.style.margin = paginaTop+'px 0px 0px '+paginaLeft+'px';
}

function getScrollX() {
  var scrOfX = 0;
  if( typeof( window.pageXOffset ) == 'number' ) {
    scrOfX = window.pageXOffset;
  } else if( document.body && document.body.scrollLeft) {
    scrOfX = document.body.scrollLeft;
  } else if( document.documentElement && document.documentElement.scrollLeft) {
    scrOfX = document.documentElement.scrollLeft;
  }
  return scrOfX;
}

function getScrollY() {
  var scrOfY = 0;
  if( typeof( window.pageYOffset ) == 'number' ) {
    scrOfY = window.pageYOffset;
  } else if( document.body && document.body.scrollTop ) {
    scrOfY = document.body.scrollTop;
  } else if( document.documentElement && document.documentElement.scrollTop ) {
    scrOfY = document.documentElement.scrollTop;
  }
  return scrOfY;
}

function fecharCompraRapida(){
	objDivCompraRapida = document.getElementById("divCompraRapida");
	objDivCompraRapida.innerHTML = "";
	objDivCompraRapida.style.display = "none";
}

function fecharAvisoCesta(){
	objDivAvisoCesta = document.getElementById("divAvisoCesta");
	objDivAvisoCesta.style.display = "none";
}

function exibeAvisoCesta(){
	objDivAvisoCesta = document.getElementById("divAvisoCesta");
	objDivAvisoCesta.style.display = 'block';
	//paginaTop = document.body.scrollTop;
	paginaTop = getScrollY();
	paginaTop = (paginaTop - 55);
	//paginaLeft = document.body.scrollLeft;
	paginaLeft = getScrollX();
	paginaLeft = (paginaLeft - 200);
	objDivAvisoCesta.style.margin = paginaTop+'px 0px 0px '+paginaLeft+'px';
}

// exibe strings randomicas, usado em banners, destaques etc.
function exibe_randomico(valor)
{
	 var qtd_sorteio = valor.length;
	 var sorteio = Math.round(Math.random()*(qtd_sorteio-1));
	 return valor[sorteio];
}