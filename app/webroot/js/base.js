function addEvent(obj, evType, fn, useCapture){

    if (obj.addEventListener){
        obj.addEventListener(evType, fn, useCapture);
        return true;
    } else if (obj.attachEvent){
        var r = obj.attachEvent("on"+evType, fn);
        return r;
    } else {
        alert("Handler could not be attached");
    }
}

function setStyle(element, sclass) {

    element.setAttribute('class', sclass);
    element.setAttribute('className', sclass); /* for IE*/

}


var xmlHttp;


function GetData(url, hnd){


    try
    {
        // Firefox, Opera 8.0+, Safari
        xmlHttp=new XMLHttpRequest();
    }
    catch (e)
    {
        // Internet Explorer
        try
        {
            xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch (e)
        {
            try
            {
                xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch (e)
            {
                alert("Your browser does not support AJAX!");
                return false;
            }
        }
    }


    if (xmlHttp.overrideMimeType) {
    //     xmlHttp.overrideMimeType('text/html');
    }





    xmlHttp.open('GET', url, true);

    xmlHttp.onreadystatechange=new Function('if(xmlHttp.readyState==4){ ' + hnd + '(xmlHttp.responseText);}');

    xmlHttp.send(null);
}


function getCookie(c_name)
{
    if (document.cookie.length>0)
    {
        c_start=document.cookie.indexOf(c_name + "=");
        if (c_start!=-1)
        {
            c_start=c_start + c_name.length+1;
            c_end=document.cookie.indexOf(";",c_start);
            if (c_end==-1) c_end=document.cookie.length;
            return unescape(document.cookie.substring(c_start,c_end));
        }
    }
    return "";
}

function setCookie(c_name,value)
{
    var exdate=new Date();
    exdate.setDate(exdate.getDate()+365);
    document.cookie=c_name+ "=" +escape(value)+";expires="+exdate.toGMTString();
}



/*
Konvertuoja masyva i JSON'a ir atvirksciai
*/

var JSON = {
    "Import":function(what){


        return eval( '(' + what + ')')

    },

    "Export":function(what){
        var str = '{';

        var t= false;

        for( p in what){

            if( t ) str+=','

            t = true;
            str+= '"' + escape( p ) + '":"'+ escape( what[p] ) +'"';

        }

        str+= '}'
        return str;

    }

}

function getFORMpart( m ){
    var str= '';

    for( a in m.childNodes ){
        if(m.childNodes[a].tagName == 'DIV'){

            str+=getFORMpart( m.childNodes[a] )

        }
        else if( (m.childNodes[a].tagName == 'INPUT'|| m.childNodes[a].tagName == 'SELECT') || m.childNodes[a].tagName == 'TEXTAREA'){

            str+= m.childNodes[a].name + '=' + escape( m.childNodes[a].value )+ '&';

        }
    }

    return str;
}

function getFORMparm( id ){

    var m = document.getElementById( id );

    return getFORMpart( m );

}

function ParseFormEr( er ){

    var m;
    var cnt = 0;

    for( a in er){

        m = document.getElementById( er[a]['id'] );
        m.innerHTML = '';
        m.innerHTML =  er[a]['txt'];
        if(er[a]['txt']!=''){
            cnt++;
        }
    }

    return cnt;
}



function LoadURLPOST(url, formid, handler){
    var what = getFORMparm(formid)

    try
    {
        // Firefox, Opera 8.0+, Safari
        xmlHttp=new XMLHttpRequest();
    }
    catch (e)
    {
        // Internet Explorer
        try
        {
            xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch (e)
        {
            try
            {
                xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch (e)
            {
                alert("Your browser does not support AJAX!");
                return false;
            }
        }
    }


    if (xmlHttp.overrideMimeType) {
        xmlHttp.overrideMimeType('text/html');
    }


    xmlHttp.open('POST', url, true);
    xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xmlHttp.setRequestHeader("Content-length", what.length);
    xmlHttp.setRequestHeader("Connection", "close");

    xmlHttp.onreadystatechange=new Function('if(xmlHttp.readyState==4){ '+handler+'( xmlHttp.responseText); }');


    xmlHttp.send(what);
    xmlHttp.send(null);
    return void(0);
}

function userLogin( txt ){

    if(ParseFormEr( JSON.Import(txt))) return;
    go2('vartotojas', 'irasai');
}


function go2( ){
    var url = 'index.php?';
    for(var a=0; a<arguments.length; a++)
        url+= arguments[a] + '/';
    alert(url)
    window.location = url;//TODO: pakeisti

}

function Submit( id ){
    document.getElementById("Form" + id ).submit();
}

