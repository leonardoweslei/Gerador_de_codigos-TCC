function substr_count(string,substring,start,length)
{
	var c = 0;
	if(start) { string = string.substr(start); }
	if(length) { string = string.substr(0,length); }
	for (var i=0;i<string.length;i++)
	{
  		if(substring == string.substr(i,substring.length))
  		c++;
	 }
	 return c;
}

function explode(f,str)
{
	str=str.split(f);
	return str;
}

function validaDT(f,t)
{
	var d=new Date();
	var str=brows(f).value;
	str=((str.length==0 || str=="")
		?d.getDate()+'/'+d.getMonth()+'/'+d.getFullYear()
		:str);
	var str2=explode(" ",str);
	var data=((typeof str2[0]=='undefined')
		?d.getDate()+'/'+d.getMonth()+'/'+d.getFullYear()
		:str2[0]);
	var hora='';
	data=explode("/",data);
	data[0]=(data.length<1)?"":data[0];
	data[1]=(data.length<2)?"":data[1];
	data[2]=(data.length<3)?"":data[2];
	dia=(data[0]=="" || data[0]==0 || data[0].length==0 || data.length<1)?d.getDate():data[0];
	mes=(data[1]=="" || data[1]==0 || data[1].length==0 || data.length<2)?d.getMonth():data[1];
	ano=(data[2]=="" || data[2]==0 || data[2].length==0 || data.length<3)?d.getFullYear():data[2];
	if(typeof t!='undefined')
	{
		hora=((typeof str2[2]=='undefined')
			?d.getHours()+':'+d.getMinutes()+':'+d.getSeconds()
			:str2[2]);
		hora=explode(":",hora);
		hora[0]=(hora.length<1)?"":hora[0];
		hora[1]=(hora.length<2)?"":hora[1];
		hora[2]=(hora.length<3)?"":hora[2];
		hora2=(hora[0]=="" || hora[0].length==0 || hora.length<1)?d.getHours():hora[0];
		min=(hora[1]=="" || hora[1].length==0 || hora.length<2)?d.getMinutes():hora[1];
		seg=(hora[2]=="" || hora[2].length==0 || hora.length<3)?d.getSeconds():hora[2];
		hora=(
			" as "+
			((hora2<10 && substr_count(hora2,'0')<=0)?'0':'')+
			hora2+
			":"+
			((min<10 && substr_count(min,'0')<=0)?'0':'')+
			min+
			":"+
			((seg<10 && substr_count(seg,'0')<=0)?'0':'')+
			seg
		);
	}
	brows(f).value=(
		((dia<10 && substr_count(dia,'0')<=0)?'0':'')+
		dia+
		'/'+
		((mes<10 && substr_count(mes,'0')<=0)?'0':'')+
		mes+
		'/'+
		((ano.length<=2)?((ano.length==1)?'200':'20'):'')+
		ano+
		hora
	);
	//foca(brows(f));
}

function foca(quem){
	var i=0,j=0, formulario=false,elemento=false;
    for (i=0; i<document.forms.length; i++)
    {
    	for (j=0; j<document.forms[i].elements.length; j++) {
            if (document.forms[i].elements[j] == quem) {
				formulario=i;
				elemento=j;
                break;
            }
        }
        if (formulario != false)
        {
        	break;
        }
    }
    document.forms[formulario].elements[elemento+1].focus();
}

function mktime () {
    // http://kevin.vanzonneveld.net
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: baris ozdil
    // +      input by: gabriel paderni
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: FGFEmperor
    // +      input by: Yannoo
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: jakes
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: Marc Palau
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // *     example 1: mktime(14, 10, 2, 2, 1, 2008);
    // *     returns 1: 1201871402
    // *     example 2: mktime(0, 0, 0, 0, 1, 2008);
    // *     returns 2: 1196463600
    // *     example 3: make = mktime();
    // *     example 3: td = new Date();
    // *     example 3: real = Math.floor(td.getTime()/1000);
    // *     example 3: diff = (real - make);
    // *     results 3: diff < 5
    // *     example 4: mktime(0, 0, 0, 13, 1, 1997)
    // *     returns 4: 883609200
    // *     example 5: mktime(0, 0, 0, 1, 1, 1998)
    // *     returns 5: 883609200
    // *     example 6: mktime(0, 0, 0, 1, 1, 98)
    // *     returns 6: 883609200
 
    var no=0, i = 0, ma=0, mb=0, d = new Date(), dn = new Date(), argv = arguments, argc = argv.length;
 
    var dateManip = {
        0: function (tt){ return d.setHours(tt); },
        1: function (tt){ return d.setMinutes(tt); },
        2: function (tt){ var set = d.setSeconds(tt); mb = d.getDate() - dn.getDate(); return set;},
        3: function (tt){ var set = d.setMonth(parseInt(tt, 10)-1); ma = d.getFullYear() - dn.getFullYear(); return set;},
        4: function (tt){ return d.setDate(tt+mb);},
        5: function (tt){
            if (tt >= 0 && tt <= 69) {
                tt += 2000;
            }
            else if (tt >= 70 && tt <= 100) {
                tt += 1900;
            }
            return d.setFullYear(tt+ma);
        }
        // 7th argument (for DST) is deprecated
    };
 
    for (i = 0; i < argc; i++){
        no = parseInt(argv[i]*1, 10);
        if (isNaN(no)) {
            return false;
        } else {
            // arg is number, let's manipulate date object
            if (!dateManip[i](no)){
                // failed
                return false;
            }
        }
    }
    for (i = argc; i < 6; i++) {
        switch (i) {
            case 0:
                no = dn.getHours();
                break;
            case 1:
                no = dn.getMinutes();
                break;
            case 2:
                no = dn.getSeconds();
                break;
            case 3:
                no = dn.getMonth()+1;
                break;
            case 4:
                no = dn.getDate();
                break;
            case 5:
                no = dn.getFullYear();
                break;
        }
        dateManip[i](no);
    }
 
    return Math.floor(d.getTime()/1000);
}

function subdata(data1,data2)
{
	diferenca_segundos =mktime
	(
		data1[3],
		data1[4],
		data1[5],
		data1[1],
		data1[0],
		data1[2]
	);
	diferenca_segundos-=mktime
	(
		data2[3],
		data2[4],
		data2[5],
		data2[1],
		data2[0],
		data2[2]
	);;
	diferenca_minutos = diferenca_segundos/60;
	diferenca_horas = diferenca_minutos/60;
	return diferenca_segundos;
}
	

function divideDT(valor)
{
	tmp=new Array("","","","","","");
	if(substr_count(valor,"-")>0)
	{
		tmp2=explode(" ",valor);//datetime
		tmp1=explode(":",tmp2[1]);//horas
		tmp0=explode("-",tmp2[0]);//data
		tmp[0]=tmp0[2];//dia
		tmp[1]=tmp0[1];//mes
		tmp[2]=tmp0[0];//ano
		tmp[3]=tmp1[0];//hora
		tmp[4]=tmp1[1];//min
		tmp[5]=tmp1[2];//sec
	}else if(substr_count(valor,"/")>0)
	{
		tmp2=explode(" as ",valor);//datetime
		tmp0=explode("/",tmp2[0]);//data
		tmp1=explode(":",tmp2[1]);//horas
		tmp[0]=tmp0[0];//dia
		tmp[1]=tmp0[1];//mes
		tmp[2]=tmp0[2];//ano
		tmp[3]=tmp1[0];//hora
		tmp[4]=tmp1[1];//min
		tmp[5]=tmp1[2];//sec
	}
	return tmp;
}
	
function brows(nam){
	if (typeof document.getElementById(nam)!='undefined')return(document.getElementById(nam));
	else if (typeof document.all[nam]!='undefined')return(document.all[nam]);
	else if (typeof document.layers[nam]!='undefined')return(document.layers[nam]);
	else return(document.getElementById(nam))
}

function validaComp(novo)
{
	msg="";
	novo=(novo==null)?false:novo;
	ret=true;
	id				=brows('id');
	id				=(id==null)?null:id.value;
	if(novo==false && (id.length==0 || id=="NULL" || id=="" || id==null))
	{
		msg+="\nCodigo vazio.";
		ret=false;
	}
	if(novo==false)
	{
		idnew		=brows('idnew').value;
		if(idnew.length==0 || idnew=="NULL" || idnew=="" || idnew==null)
		{
			idnewdhininew	=brows('idnewdhininew').value;
			if(idnewdhininew.length==0 || idnewdhininew==null || idnewdhininew=="")
			{
				msg+="\nData de inicio para remarca��o vazia.";
				ret=false;
			}
			idnewdhendnew	=brows('idnewdhendnew').value;
			if(idnewdhininew.length==0 || idnewdhininew==null || idnewdhininew=="")
			{
				msg+="\nData de termino para remarca��o vazia.";
				ret=false;
			}
		}
	}
	tipocomp		=brows('tipocomp').value;
	if(tipocomp.length==0 || tipocomp==null || tipocomp=="")
	{
		msg+="\nTipo de compromisso n�o selecionado.";
		ret=false;
	}
	dono			=brows('dono').value;
	if(dono.length==0 || dono==null || dono=="")
	{
		msg+="\nCampo dono vazio.";
		ret=false;
	}
	ativados			=brows('ativados').checked;
	ativadon			=brows('ativadon').checked;
	if(ativados!=true && ativadon!=true)
	{
		msg+="\nAtivado/desativado n�o selecionado.";
		ret=false;
	}
	timealert		=brows('timealert').value;
	if(timealert.length==0 || timealert==null || timealert=="")
	{
		msg+="\nCampo Tempo de alerta vazio.";
		ret=false;
	}
	cumpridos		=brows('cumpridos').checked;
	cumpridon		=brows('cumpridon').checked;
	if(cumpridos!=true && cumpridon!=true)
	{
		msg+="\nCampo cumprido n�o selecionado.";
		ret=false;
	}
	dhini			=brows('dhini').value;
	if(dhini.length==0 || dhini==null || dhini=="")
	{
		msg+="\nData de inicio invalida.";
		ret=false;
	}
	dhend			=brows('dhend').value;
	if(dhend.length==0 || dhend==null || dhend=="")
	{
		msg+="\nData de termino invalida.";
		ret=false;
	}
	obs				=brows('obs').value;
	if(obs.length==0 || obs==null || obs=="")
	{
		msg+="\nCampo Observa��o vazio.";
		ret=false;
	}
	timealert=divideDT(timealert);
	dhini=divideDT(dhini);
	dhend=divideDT(dhend);
	if(subdata(timealert,dhini)>0)
	{
		msg+="\nO tempo de alerta tem que ser menor ou igual a data de inicio do compromisso.";
		ret=false;
	}
	if(subdata(dhini,dhend)>0)
	{
		msg+="\nO data de inicio tem que ser menor que a data de final do compromisso.";
		ret=false;
	}
	if(msg.length>0)
		alert("N�o foi possivel concluir a a��o.\nOuve os seguintes erros:"+msg);
	return ret;
}

function vazio(xxx,msgm,retorno,msg2){
	var argv = arguments, argc = argv.length;
	msg2=(argc<4)?0:msg2;
	str=(msg2==0)?xxx:msg2;
	xxx		=brows(xxx).value;
	if(xxx.length==0 || xxx==null || xxx=="")
	{
		msg+="\nCampo "+str+" vazio.";
		ret=false;
	}
}

function validaUsereContact(tipo,novo)
{
	msg="";
	novo=(novo==null)?false:novo;
	ret=true;
	id				=brows('id');
	id				=(id==null)?null:id.value;
	if(novo==false && id!=null)
	{
		vazio('id',msg,ret);
	}
	vazio('nome',msg,ret);
	if(!isDate(brows('datanasc').value))
	{
		msg+="\nCampo Data de nascimento invalido ou vazio.";
		ret=false;
	}
	if(tipo!='User')
	{
		vazio('dono',msg,ret,"Dono");
	}
	vazio('endereco',msg,ret,"endere�o");
	vazio('bairro',msg,ret);
	vazio('cidade',msg,ret);
	vazio('estado',msg,ret);
	vazio('cep',msg,ret,"caixa postal");
	vazio('pais',msg,ret);
	if(!isEmail(brows('email').value))
	{
		msg+="\nCampo email invalido ou vazio.";
		ret=false;
	}
	vazio('horario',msg,ret,"hor�rio");
	vazio('setor',msg,ret);
	vazio('funcao',msg,ret);
	if(!isDate(brows('dataadm').value))
	{
		msg+="\nCampo Data de admiss�o invalido ou vazio.";
		ret=false;
	}
	vazio('instituicao',msg,ret,"institui��o");
	if(!isDate(brows('feriasdata').value))
	{
		msg+="\nCampo Data de ferias invalido ou vazio.";
		ret=false;
	}
	sexof			=brows('sexof').checked;
	sexom			=brows('sexom').checked;
	if(sexof!=true && sexom!=true)
	{
		msg+="\nCampo sexo n�o selecionado.";
		ret=false;
	}
	if(tipo=='User')
	{
		vazio('login',msg,ret);
		vazio('senha',msg,ret);
	}
	vazio('obs',msg,ret);
	if(msg.length>0)
		alert("N�o foi possivel concluir a a��o.\nOuve os seguintes erros:"+msg);
	return ret;
}


function isDate(value)
{
	value2=new Array(value);
	value=(value.length>10)?explode(" ",value):value2;
	value=explode("/",value[0]);
	if(value[2]<1)
		return false;
	if(value[1]<1 || value[1]>12)
		return false;
	if(value[0]<1)
		return false;
	day		=value[0];
	month	=value[1];
	year	=value[2];
	var date = new Date();
	var blnRet = false;
	var blnDay;
	var blnMonth;
	var blnYear;

	date.setFullYear(year, month -1, day);

	blnDay   = (date.getDate()      == day);
	blnMonth = (date.getMonth()     == month -1);
	blnYear  = (date.getFullYear()  == year);

	if (blnDay && blnMonth && blnYear)
	blnRet = true;

	return blnRet;
}

function isEmail(value)
{
	var s = value;
	if
	(
		(s.indexOf("{")>=0) || (s.indexOf("}")>=0) || (s.indexOf("(")>=0) || (s.indexOf(")")>=0) || (s.indexOf("<")>=0) ||
		(s.indexOf(">")>=0) || (s.indexOf("[")>=0) || (s.indexOf("]")>=0) || (s.indexOf("|")>=0) || (s.indexOf("\"")>=0)||
		(s.indexOf("/")>=0) || (s.indexOf("&")>=0) || (s.indexOf("*")>=0) || (s.indexOf("$")>=0) || (s.indexOf("%")>=0) || 
		(s.indexOf("?")>=0) || (s.indexOf("!")>=0) || (s.indexOf("^")>=0) || (s.indexOf("~")>=0) || (s.indexOf("`")>=0) || 
		(s.indexOf("'")>=0) || (s.indexOf(",")>=0) || (s.indexOf(";")>=0) || (s.indexOf(":")>=0) || (s.indexOf("=")>=0) ||
		(s.indexOf("#")>=0)	|| (s.indexOf("@") < 0) || (s.indexOf("@") != s.lastIndexOf("@"))
	)return false;
	return true;
}

function question(msg,funcao,funcao2)
{
	msg=(typeof msg=='undefined')?"mensagem":msg;
	funcao=(typeof funcao=='undefined')?'void(0)':funcao;
	funcao2=(typeof funcao2=='undefined')?'void(0)':funcao2;
	if(confirm(msg))
	{
		eval(funcao);
		return true;
	}
		eval(funcao2);
		return false;
}

function hamarcados(ch,f){
	var x=0;
	var i=0;
	for(i=0;i<f;i++)
	{
		if(brows(ch+i).checked==true)
		{
			x++;
		}
	}
	return ((x>0)?true:false);
}

function FData2(c)
{
	if(
		!isDigit(c.value[c.value.length-1]) &&
		(
			c.value[c.value.length-1]=='/' ||
			c.value[c.value.length-1]=='-' ||
			c.value[c.value.length-1]==':' ||
			c.value[c.value.length-1]=='~' ||
			c.value[c.value.length-1]=="?" ||
			c.value[c.value.length-1]=='^' ||
			c.value[c.value.length-1]=='`' ||
			c.value[c.value.length-1]=='~' ||
			c.value[c.value.length-1]==';'
		)
	)
	{
		c.value = c.value.substr(0,(c.value.length-1));
		return 1;
	}
	return 0;
}

function FData(c,t)
{
	if(FData2(c)==1){
		return;
	}
	x=0;
	tecla=document.minhatecla;
	if(tecla==38 || tecla==39 || tecla==37 || tecla==8 || tecla==127 || tecla==9 || tecla==2 || tecla==3 || !isDigit(tecla))
	{
		if(c.value.length>=10 && c.value.length<=14 && tecla==8){
			c.value = c.value.substr(0,10);
		}else if(c.value.substr(10,4)!=' as ' && c.value.length>=10 && tecla==8){
			var char=c.value;
			char=char.replace('a','');
			char=char.replace('s','');
			char=char.replace(' ','');
			c.value = char.substr(0,10);
			FData2(c);
		}
		return;
	}else if(c.value.length==2 || (typeof c.value[2]!='undefined' && c.value[2]!='/' && c.value.length>=1))
	{
		c.value = c.value.substr(0,2)+'/'+ c.value.substr(2);
	}else if(c.value.length==5 || (typeof c.value[5]!='undefined' && c.value[5]!='/' && c.value.length>=4)){
		c.value = c.value = c.value.substr(0,5)+'/'+ c.value.substr(5);
	}else if(typeof t!='undefined'){
		if(c.value.length==10 || (typeof c.value[10]!='undefined' && c.value.substr(10,4)!=' as ' && c.value.length>=9)){
			var char=c.value;
			char=char.replace('a','');
			char=char.replace('s','');
			char=char.replace(' ','');
			c.value = char.substr(0,10)+' as '+char.substr(11);
			FData2(c);
		}else if(c.value.length==16 || (typeof c.value[16]!='undefined' && c.value[16]!=':' && c.value.length>=15)){
			c.value = c.value = c.value.substr(0,16)+':'+ c.value.substr(16);
		}else if(c.value.length==19 || (typeof c.value[19]!='undefined' && c.value[19]!=':' && c.value.length>=18)){
			c.value = c.value = c.value.substr(0,19)+':'+ c.value.substr(19);
		}else if(c.value.length>=22){
			c.value = c.value = c.value.substr(0,22);
			foca(c);
		}
	}else if(c.value.length>=10){
		c.value = c.value = c.value.substr(0,10);
		foca(c);
	}
}

function returntecla(evtKeyPress) {
	if( !evtKeyPress ) evtKeyPress = window.event;
	var tecla= 0;
	if(document.all){
           tecla = evtKeyPress.keyCode;
         }else{
		tecla = evtKeyPress.which;
	}
	return tecla;
}

function isDigit(c)
{
	if(typeof document.getElementById!='undefined')
	{
		return (c>=48 && c<=57);
	}
	else
	{
		var c2=String.fromCharCode(c);
		var strValidos = "0123456789"  
		if (strValidos.indexOf(c2) == -1)
		{
			return false;
		}
		return true; 
	}
}

function logout()
{
	window.location.href='?logout=s';
}

function formatar(src, mask, event){
  var i = src.value.length;
  var saida = '#';
  var texto = mask.substring(i);
  var Tecla = event.charCode;
  var ie = event.keyCode;
  
  if (!event) 
    event = window.event;
  var code;
  if (event.keyCode) 
    code = event.keyCode; // IE
  else if (event.which) 
    code = event.which; // Netscape 4.?


  if ((code < 48 || code > 59) && code !=8 &&code !=35 &&code !=36 &&code !=37 && code !=39 &&code !=46&&code !=13&&code !=9){
    event.returnValue = false;
    if (event.which){
       event.preventDefault();
     }
     if (texto.substring(0,1) != saida){
        src.value += texto.substring(0,1);
        return true;
      }
    return false;
  }
  else{
    if (texto.substring(0,1) != saida && code!=8){
         src.value += texto.substring(0,1);
     }
    event.returnValue = true;
    return true;
  }
  
  
}


function verificaCPF(cpf){
	if(cpf.length == 0) {
		return false;
	}
	var dac = "", inicio = 2, fim = 10, soma, digito, i, j;
	for (j=1;j<=2;j++) {
		soma = 0
		for (i=inicio;i<=fim;i++) {
			soma += parseInt(cpf.substring(i-j-1,i-j))*(fim+1+j-i)
		}
		if (j == 2) { soma += 2*digito }
		digito = (10*soma) % 11
		if (digito == 10) { digito = 0 }
		dac += digito
		inicio = 3
		fim = 11
	}
	return (dac == cpf.substring(cpf.length-2,cpf.length))
}

function IsCNPJ(cnpj)
{
  	cnpj=cnpj.replace(/([^0-9])/ig,"");
	var numeros, digitos, soma, i, resultado, pos, tamanho, digitos_iguais;
	digitos_iguais = 1;
	if (cnpj.length < 14 && cnpj.length < 15)
	    return false;
	for (i = 0; i < cnpj.length - 1; i++)
	    if (cnpj.charAt(i) != cnpj.charAt(i + 1))
	          {
	          digitos_iguais = 0;
	          break;
	          }
	if (!digitos_iguais)
	    {
	    tamanho = cnpj.length - 2
	    numeros = cnpj.substring(0,tamanho);
	    digitos = cnpj.substring(tamanho);
	    soma = 0;
	    pos = tamanho - 7;
	    for (i = tamanho; i >= 1; i--)
	          {
	          soma += numeros.charAt(tamanho - i) * pos--;
	          if (pos < 2)
	                pos = 9;
	          }
	    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
	    if (resultado != digitos.charAt(0))
	          return false;
	    tamanho = tamanho + 1;
	    numeros = cnpj.substring(0,tamanho);
	    soma = 0;
	    pos = tamanho - 7;
	    for (i = tamanho; i >= 1; i--)
	          {
	          soma += numeros.charAt(tamanho - i) * pos--;
	          if (pos < 2)
	                pos = 9;
	          }
	    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
	    if (resultado != digitos.charAt(1))
	          return false;
	    return true;
	    }
	else
	    return false;
} 

function IsCPF(cpf)
  {
  	cpf=cpf.replace(/([^0-9])/ig,"");
  var numeros, digitos, soma, i, resultado, digitos_iguais;
  digitos_iguais = 1;
  if (cpf.length < 11)
        return false;
  for (i = 0; i < cpf.length - 1; i++)
        if (cpf.charAt(i) != cpf.charAt(i + 1))
              {
              digitos_iguais = 0;
              break;
              }
  if (!digitos_iguais)
        {
        numeros = cpf.substring(0,9);
        digitos = cpf.substring(9);
        soma = 0;
        for (i = 10; i > 1; i--)
              soma += numeros.charAt(10 - i) * i;
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(0))
              return false;
        numeros = cpf.substring(0,10);
        soma = 0;
        for (i = 11; i > 1; i--)
              soma += numeros.charAt(11 - i) * i;
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(1))
              return false;
        return true;
        }
  else
        return false;
  }

function fmoney(fld, milSep, decSep, e) {   
		var sep = 0;   
		var key = '';   
		var i = j = 0;   
		var len = len2 = 0;   
		var strCheck = '0123456789';   
		var aux = aux2 = '';
		var ie = e.keyCode;
		if (!e) 
			e = window.event;
		var code;
		if (e.keyCode) 
			code = e.keyCode; // IE
		else if (e.which) 
			code = e.which; // Netscape 4.?
		var whichCode=code;
		if (code ==8 || code ==35 || code ==36 || code ==37 ||  code ==39 || code ==46 || code ==13 || code ==9) return true;  // Enter   
		key = String.fromCharCode(whichCode);  // recebe o valor da chave vinda da chave do c�digo
		if (strCheck.indexOf(key) == -1) return false;  // Chave n�o v�lida  
		len = fld.value.length;   
		for(i = 0; i < len; i++)   
		if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break;   
		aux = '';   
		for(; i < len; i++)   
		if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i);   
		aux += key;   
		len = aux.length;   
		if (len == 0) fld.value = '';   
		if (len == 1) fld.value = '0'+ decSep + '0' + aux;   
		if (len == 2) fld.value = '0'+ decSep + aux;   
		if (len > 2) {   
		aux2 = '';   
		for (j = 0, i = len - 3; i >= 0; i--) {   
			if (j == 3) {   
				aux2 += milSep;   
				j = 0;   
			}   
			aux2 += aux.charAt(i);   
			j++;   
		}   
		fld.value = '';   
		len2 = aux2.length;   
		for (i = len2 - 1; i >= 0; i--)   
			fld.value += aux2.charAt(i);   
		fld.value += decSep + aux.substr(len - 2, len);   
	}   
	return false;   
}

function Trim(strTexto)
	{
		// Substitúi os espaços vazios no inicio e no fim da string por vazio.
		return strTexto.replace(/^\s+|\s+$/g, '');
	}

    // Função para validação de CEP.
    function IsCEP(strCEP, blnVazio)
	{
		// Caso o CEP não esteja nesse formato ele é inválido!
		var objER = /^[0-9]{2}\.[0-9]{3}-[0-9]{3}$/;

		strCEP = Trim(strCEP)
		if(strCEP.length > 0)
			{
				if(objER.test(strCEP))
					return true;
				else
					return false;
			}
		else
			return blnVazio;
	}
	function IsData(data)
	{
	 regex = /^((0[1-9]|[12]\d)\/(0[1-9]|1[0-2])|30\/(0[13-9]|1[0-2])|31\/(0[13578]|1[02]))\/\d{4}$/;
	 resultado = regex.exec(data);
	 if(!resultado){
	   return false;
	 }
	 else {
	   return true;
	 }
	}
	
	function IsEmail(email)
	{
	 regex =  /^[\w-]+(\.[\w-]+)*@(([\w-]{2,63}\.)+[A-Za-z]{2,6}|\[\d{1,3}(\.\d{1,3}){3}\])$/;
	 resultado = regex.exec(email);
	 if(!resultado){
	   return false;
	 }
	 else {
	   return true;
	 }
	}
	function IsMoney(money) {
  	 money=money.replace(/([\.])/ig,"");
  	 money=money.replace(/([\,])/ig,".");
	 if(parseFloat(money)>=0)
	 {
	   return true;
	 }
	 else {
	   return false;
	 }
	}
	function IsNumVal(num) {
  	 num=num.replace(/([^0-9])/ig,"");
	 if((num*1)>0){
	   return true;
	 }else {
	   return false;
	 }
	}
	function IsTel(num) {
  	 num=num.replace(/([^0-9])/ig,"");
	 if((num*1)>0 && num.length==10){
	   return true;
	 }else {
	   return false;
	 }
	}
	gE=function(el){return document.getElementById(el);};
	gN=function(el){return document.getElementsByName(el);};
	function number_format( number, decimals, dec_point, thousands_sep )
	{
    var n = number, prec = decimals;
    n = !isFinite(+n) ? 0 : +n;
    prec = !isFinite(+prec) ? 0 : Math.abs(prec);
    var sep = (typeof thousands_sep == "undefined") ? ',' : thousands_sep;
    var dec = (typeof dec_point == "undefined") ? '.' : dec_point;
 
    var s = (prec > 0) ? n.toFixed(prec) : Math.round(n).toFixed(prec); //fix for IE parseFloat(0.55).toFixed(0) = 0;
 
    var abs = Math.abs(n).toFixed(prec);
    var _, i;
 
    if (abs >= 1000) {
        _ = abs.split(/\D/);
        i = _[0].length % 3 || 3;
 
        _[0] = s.slice(0,i + (n < 0)) +
              _[0].slice(i).replace(/(\d{3})/g, sep+'$1');
 
        s = _.join(dec);
    } else {
        s = s.replace('.', dec);
    }
 
    return s;
}