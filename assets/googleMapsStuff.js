var googleKey = '';

var map;
var service;
var finalAddresses = [];
var addressBlocks = [];

function placeMatches(latitude, longitude, queryTerm, radiusSize) {
  if (!latitude && !longitude && !queryTerm && !radiusSize) {
    latitude = 36.136091;
    longitude = -78.458099;
    queryTerm = 'harris teeter';
    radiusSize = '24135';
  }

  var pyrmont = new google.maps.LatLng(latitude, longitude);

  map = new google.maps.Map(document.getElementById('map'), {
    center: pyrmont,
    zoom: 15
  });

  var request = {
    location: pyrmont,
    radius: radiusSize,
    query: queryTerm,
  };

  service = new google.maps.places.PlacesService(map);
  service.textSearch(request, callback);

}

function callback(results, status) {
  if (status == google.maps.places.PlacesServiceStatus.OK) {
    var stZipRegEx = /[A-Z]{2} \d{4,}/;
    var modal = false;
    if (document.getElementById('zipModal').classList.contains('show')) {
      modal = true;
    }
    if (modal) {
      var locOptUl = document.querySelector('div#locationOpts ul');
      while (locOptUl.hasChildNodes()) {
        locOptUl.removeChild(locOptUl.firstChild);
      }
    }
    var box = document.getElementById('locationOpts');
    var locationsUl = document.querySelector('div#locationOpts ul');
    if (modal) {
      zip = document.getElementById('modalZip').value;
    } else {
      zip = document.getElementById('updatePage').getAttribute('data-current-zip');
    }
    for (var i = 0; i < results.length; i++) {
      var place = results[i];
      var addParts = place.formatted_address.split(',');
      var addPartsTrim = [];
      for (var j = 0; j < (addParts.length - 1); j++) {
        addPartsTrim.push(addParts[j].trim());
        var addInfo = [];
        var lnByLn = {};
        var addPartsTrimLinesAdded = [];
        var lines = 0;
        for (var k = 0; k < addPartsTrim.length; k++) {
          var regTest = stZipRegEx.test(addPartsTrim[k]);
          var regTest2 = stZipRegEx.test(addPartsTrim[k + 1]);
          if (regTest) {
            var stateZipArr = addPartsTrim[k].split(' ');
            lnByLn.state = stateZipArr[0];
            lnByLn.zip = stateZipArr[1];
            lnByLn.city = addPartsTrim[k - 1];
            addPartsTrimLinesAdded.push(k);
          } else if (regTest2) {
            addPartsTrimLinesAdded.push(k);
          } else {
            if (addPartsTrimLinesAdded.indexOf(k) < 1) {
              var lineNum = 'addrLine' + (k + 1);
              lines++;
              lnByLn[lineNum] = addPartsTrim[k];
            }
          }
        }
        lnByLn.lines = lines;  
      }
      var addressBlock;
      var locationItem = document.createElement('li');
      locationItem.setAttribute('data-rows', lnByLn.lines);
      locationItem.setAttribute('data-zip', lnByLn.zip);
      var carRet = '     '+String.fromCharCode(13);
      if (!lnByLn.lines) {
        addressBlock = (lnByLn.city + ', ' + lnByLn.state + ' ' + lnByLn.zip);
      } else if (lnByLn.lines == 1) {
        addressBlock = (lnByLn.addrLine1 +carRet+ lnByLn.city + ', ' + lnByLn.state + ' ' + lnByLn.zip);
      } else if (lnByLn.lines == 2) {
        addressBlock = (lnByLn.addrLine1 + ', ' + lnByLn.addrLine2 +carRet+ lnByLn.city + ', ' + lnByLn.state + ' ' + lnByLn.zip);
      } else {
        addressBlock = (lnByLn.addrLine1 + ', ' + lnByLn.addrLine2 + ',' + lnByLn.addrLine3 + carRet + lnByLn.city + ', ' + lnByLn.state + ' ' + lnByLn.zip);
      }
      locationItem.innerText = addressBlock;
      locationItem.addEventListener('click', locationSelect);
      locationItem.addEventListener('click', function () {
        document.getElementById('locationOpts').classList.add('hide');
        document.getElementById('locationOpts').removeAttribute('style');
      });
      
      locationsUl.appendChild(locationItem);
    }
    var zipNotice = document.createElement('li');
    zipNotice.innerHTML = 'Current zip: ' + zip + ' <a href="#" id="changeZip">Change Zip</a>';
    zipNotice.id = 'currentZip';
    if (zip.length > 0) {
      document.getElementById('curZipSpan').innerText = zip;
    }
    locationsUl.appendChild(zipNotice);
    box.appendChild(locationsUl);
    document.getElementById('changeZip').addEventListener('click',modalView);
    document.getElementById('changeZip').addEventListener('click',function(){
        document.getElementById('locationOpts').classList.add('hide');
    });
  }
}

function callbackSel(results, status) {
  if (status == google.maps.places.PlacesServiceStatus.OK) {
    var stZipRegEx = /[A-Z]{2} \d{4,}/;
    var modal = false;
    if (document.getElementById('zipModal').classList.contains('show')) {
      modal = true;
    }
    if (modal) {
      var theSelect = document.getElementById('storeTester');
      while (theSelect.childNodes.length > 1){
        theSelect.removeChild(theSelect.lastChild);
      }
    }
    var theSelect = document.getElementById('storeTester');
    if (modal) {
      zip = document.getElementById('modalZip').value;
    } else {
      zip = document.getElementById('updatePage').getAttribute('data-current-zip');
    }
    for (var i = 0; i < results.length; i++) {
      var place = results[i];
      var addParts = place.formatted_address.split(',');
      var addPartsTrim = [];
      for (var j = 0; j < (addParts.length - 1); j++) {
        addPartsTrim.push(addParts[j].trim());
        var addInfo = [];
        var lnByLn = {};
        var addPartsTrimLinesAdded = [];
        var lines = 0;
        for (var k = 0; k < addPartsTrim.length; k++) {
          var regTest = stZipRegEx.test(addPartsTrim[k]);
          var regTest2 = stZipRegEx.test(addPartsTrim[k + 1]);
          if (regTest) {
            var stateZipArr = addPartsTrim[k].split(' ');
            lnByLn.state = stateZipArr[0];
            lnByLn.zip = stateZipArr[1];
            lnByLn.city = addPartsTrim[k - 1];
            addPartsTrimLinesAdded.push(k);
          } else if (regTest2) {
            addPartsTrimLinesAdded.push(k);
          } else {
            if (addPartsTrimLinesAdded.indexOf(k) < 1) {
              var lineNum = 'addrLine' + (k + 1);
              lines++;
              lnByLn[lineNum] = addPartsTrim[k];
            }
          }
        }
        lnByLn.lines = lines;  
      }
      var addressBlock;
      var newOpt = document.createElement('option');
      newOpt.setAttribute('data-rows', lnByLn.lines);
      newOpt.setAttribute('data-zip', lnByLn.zip);
      if (!lnByLn.lines) {
        addressBlock = (lnByLn.city + ', ' + lnByLn.state + ' ' + lnByLn.zip);
      } else if (lnByLn.lines == 1) {
        addressBlock = (lnByLn.addrLine1 + '\n' + lnByLn.city + ', ' + lnByLn.state + ' ' + lnByLn.zip);
      } else if (lnByLn.lines == 2) {
        addressBlock = (lnByLn.addrLine1 + ', ' + lnByLn.addrLine2 + '\n' + lnByLn.city + ', ' + lnByLn.state + ' ' + lnByLn.zip);
      } else {
        addressBlock = (lnByLn.addrLine1 + ', ' + lnByLn.addrLine2 + ',' + lnByLn.addrLine3 + '\n' + lnByLn.city + ', ' + lnByLn.state + ' ' + lnByLn.zip);
      }
      newOpt.innerText = addressBlock;
      newOpt.value = lnByLn.zip;
      theSelect.appendChild(newOpt);
    }
  }
}
