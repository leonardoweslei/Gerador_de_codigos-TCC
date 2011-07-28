
function janelaAjuda(url){
	window.open(url, "ajuda_adx", 'toolbar=no, location=no, directories=no, status=no, menubar=yes, scrollbars=yes, copyhistory=no, resizable=yes, width=750, height=500, top=25, left=25');
}

function janelaRel(){
	window.open("about:blank", "relat_adx", 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, copyhistory=no, width=750, height=500, top=25, left=25');
}

function MakeWindow(page,name,wid,hei){
	var left = (screen.width/2)-wid/2;
	var top  = (screen.height/2)-hei/2;
	window.open(page,name,'scrollbars=yes,resizable=yes,maximized=no,width='+wid+',height='+hei+',top='+top+',left='+left);
}

function exibe_msg(msg) {
	if(msg.length>0){
		while(msg.indexOf("\\n")>=0)
			msg = msg.replace("\\n","\n");
		alert(msg);
	}
		//window.open('../main/mostra_msg.php?time='+tmp+'&mensagem='+escape(msg),'msgAdx','menubar=no,toolbar=no,resizable=no,status=no,scrollbars=no,left=270,top=250,width=400,height=210');
}

function taVazio(campo){
	return (campo.value=="" || campo.value.length==0);
}

function erroForm(campo, msg, op){
	alert(msg);
	if(op)
		campo.select();
	else
		campo.focus()
	return false;
}

function confereEmail(email) {
	var s = new String(email);
	// { } ( ) < > [ ] | \ /
	if ((s.indexOf("{")>=0) || (s.indexOf("}")>=0) || (s.indexOf("(")>=0) || (s.indexOf(")")>=0) || (s.indexOf("<")>=0) || (s.indexOf(">")>=0) || (s.indexOf("[")>=0) || (s.indexOf("]")>=0) || (s.indexOf("|")>=0) || (s.indexOf("\"")>=0) || (s.indexOf("/")>=0) )
		return false;
	if (vogalAcentuada(email))
		return false;
	// & * $ % ? ! ^ ~ ` ' "
	if ((s.indexOf("&")>=0) || (s.indexOf("*")>=0) || (s.indexOf("$")>=0) || (s.indexOf("%")>=0) || (s.indexOf("?")>=0) || (s.indexOf("!")>=0) || (s.indexOf("^")>=0) || (s.indexOf("~")>=0) || (s.indexOf("`")>=0) || (s.indexOf("'")>=0) )
		return false;
	// , ; : = #
	if ((s.indexOf(",")>=0) || (s.indexOf(";")>=0) || (s.indexOf(":")>=0) || (s.indexOf("=")>=0) || (s.indexOf("#")>=0) )
		return false;
	// procura se existe apenas um @
	if ( (s.indexOf("@") < 0) || (s.indexOf("@") != s.lastIndexOf("@")) )
		return false;
	// verifica se tem pelo menos um ponto após o @
	if (s.lastIndexOf(".") < s.indexOf("@"))
		return false;
	return true;
}

var chkBox = false;
function marcaTudo(form,obj){
	for(i=0;i<form.elements[obj].length;i++)
		form.elements[obj][i].checked = !chkBox;
	chkBox = !chkBox;
}

// Funções para obter a posição de um objeto na página

function getAbsX(elt) { return (elt.x) ? elt.x : getAbsPos(elt,"Left"); }
	
function getAbsY(elt) { return (elt.y) ? elt.y : getAbsPos(elt,"Top"); }

function getAbsPos(elt,which) {
	 iPos = 0;
	 while (elt != null) {
	  iPos += elt["offset" + which];
	  elt = elt.offsetParent;
	 }
	 return iPos;
}

// Funções para mudança da cor da linha

var marked_row = new Array;
	
function setPointer(theRow, theRowNum, theAction, theDefaultColor, thePointerColor, theMarkColor, theBox)
{
    var theCells = null;

    // 1. Pointer and mark feature are disabled or the browser can't get the
    //    row -> exits
    if ((thePointerColor == '' && theMarkColor == '')
        || typeof(theRow.style) == 'undefined') {
        return false;
    }

    // 2. Gets the current row and exits if the browser can't get it
    if (typeof(document.getElementsByTagName) != 'undefined') {
        theCells = theRow.getElementsByTagName('td');
    }
    else if (typeof(theRow.cells) != 'undefined') {
        theCells = theRow.cells;
    }
    else {
        return false;
    }

    // 3. Gets the current color... 
	
    var rowCellsCnt  = theCells.length;
    var domDetect    = null;
    var currentColor = null;
    var newColor     = null;
    // 3.1 ... with DOM compatible browsers except Opera that does not return
    //         valid values with "getAttribute"
    if (typeof(window.opera) == 'undefined' && typeof(window.netscape) == 'undefined' 
        && typeof(theCells[0].getAttribute) != 'undefined') {
        currentColor = theCells[0].getAttribute('className');
        domDetect    = true;
    }
    // 3.2 ... with other browsers
    else {
        currentColor = theCells[0].className;
        domDetect    = false;
    } // end 3

    // 4. Defines the new color
    // 4.1 Current color is the default one
	
    if (currentColor == ''
        || currentColor.toLowerCase() == theDefaultColor.toLowerCase()) {
        if (theAction == 'over' && thePointerColor != '') {
            newColor              = thePointerColor;
        }
        else if (theAction == 'click' && theMarkColor != '') {
            newColor              = theMarkColor;
            marked_row[theRowNum] = true;
        }
    }
    // 4.1.2 Current color is the pointer one
    else if (currentColor.toLowerCase() == thePointerColor.toLowerCase()
             && (typeof(marked_row[theRowNum]) == 'undefined' || !marked_row[theRowNum])) {
        if (theAction == 'out') {
            newColor              = theDefaultColor;
        }
        else if (theAction == 'click' && theMarkColor != '') {
            newColor              = theMarkColor;
            marked_row[theRowNum] = true;
        }
    }
    // 4.1.3 Current color is the marker one
    else if (currentColor.toLowerCase() == theMarkColor.toLowerCase()) {
        if (theAction == 'click') {
            newColor              = (thePointerColor != '')
                                  ? thePointerColor
                                  : theDefaultColor;
            marked_row[theRowNum] = (typeof(marked_row[theRowNum]) == 'undefined' || !marked_row[theRowNum])
                                  ? true
                                  : null;
        }
    } // end 4

    // 5. Sets the new color...
    if (newColor) {
        var c = null;
        // 5.1 ... with DOM compatible browsers except Opera
        if (domDetect) {
            for (c = 0; c < rowCellsCnt; c++) {
                theCells[c].setAttribute('className', newColor, 0);
            } // end for
			if(theBox)
				theBox.setAttribute('className',newColor,0);
        }
        // 5.2 ... with other browsers
        else {
            for (c = 0; c < rowCellsCnt; c++) {
                theCells[c].className = newColor;
            }
			if(theBox)
				theBox.className = newColor;
        }
    } // end 5
    return true;
} // end of the 'setPointer()' function

<!-----------------------------!>
function isNum( caractere )
{
	var strValidos = "0123456789"  
	if ( strValidos.indexOf( caractere ) == -1 ) 
	return false;
	return true; 
}
  
function validaTecla(campo, event)   
{ 
  
var BACKSPACE= 8; 
  
var key; 
  
var tecla;   
  
CheckTAB=true; 
  
if(navigator.appName.indexOf("Netscape")!= -1) 
  
tecla= event.which; 
  
else 
  
tecla= event.keyCode; 
  

key = String.fromCharCode(tecla); 
  
if ( tecla == 13 ) 
  
return false; 
  
if ( tecla == BACKSPACE || tecla ==0) 
  
return true; 
  
return ( isNum(key)); 
  
}
