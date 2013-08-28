var XMLHttpReq;
var res;
var type;
function creatXMLHttpRequest(){
	if(window.XMLHttpRequest){
		XMLHttpReq = new XMLHttpRequest();
	}else if(window.ActiveXObject){
		try{
			XMLHttpReq = new ActiveXObject("Msxml2.XMPHTTP");
		} catch (e) {
			XMLHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
}

function sendRequest(url){
	creatXMLHttpRequest();
	XMLHttpReq.open("GET",url,true);
	XMLHttpReq.onreadystatechange = processResponse;
	XMLHttpReq.send(null);
}

function processResponse(){
	if (XMLHttpReq.readyState == 4){
		if(XMLHttpReq.status ==200){
			res = eval(XMLHttpReq.responseText);
			document.getElementById(type).length = 1;
			for(i = 0;i<res.length;i++){
				document.getElementById(type).options[i+1] = new Option(res[i],res[i]);
			}
		}
	}
}
