function getZipInfo(zip){
    var latLonXML = new XMLHttpRequest();
    
    latLonXML.onload = function(){
        if(this.status == 200){
            var zipLatLon = JSON.parse(this.responseText);
            return 'success, status 200';         
        } else {
            return 'issue, status '+this.status;
        }       
    }
    latLonXML.open('GET','zipLatLon.php?zip='+zip,true);
    latLonXML.send();    
}