//js feature detection
document.documentElement.setAttribute('style','--customTry: rgb(0,205,0)');
var customTrial = document.createElement('div');
customTrial.setAttribute('style','color: var(--customTry)');
customTrial.id = 'customDiv';
customTrial.style.height = '10vh';
customTrial.style.justifyContent = 'space-evenly';
document.body.appendChild(customTrial);
if(window.getComputedStyle(document.getElementById('customDiv')).getPropertyValue('color') == 'rgb(0, 205, 0)'){
    document.body.classList.remove('noCustProp');
}
if(document.getElementById('customDiv').style.height == '10vh'){
    document.body.classList.remove('noVH');
}
if(document.getElementById('customDiv').style.justifyContent == 'space-evenly'){
    document.body.classList.remove('noSpEv');
}  
document.body.removeChild(document.getElementById('customDiv'));

//universal listeners
document.getElementById('burgerBtn').addEventListener('click',toggleNav);
document.getElementById('closeBtn').addEventListener('click',toggleNav);
document.getElementById('navModal').addEventListener('click',toggleNav);
if(document.getElementById('subMenuCaret')){
    document.getElementById('subMenuCaret').addEventListener('click',function(e){
        e.target.classList.toggle('expanded');
        e.target.classList.toggle('collapsed');
        document.getElementById('profSubmenu').classList.toggle('displayed');
    });
}

if(document.getElementById('profLink')){
    document.getElementById('profLink').addEventListener('click',function(e){
        e.preventDefault();
        document.getElementById('profForm').submit();
    });
    document.getElementById('logOutLink').addEventListener('click',function(e){
        e.preventDefault();
        document.getElementById('logoutForm').submit();
    });
}

//listeners & other actions by page
if(document.getElementsByTagName('form')[0] && !document.getElementById('resetProc')){
    if (document.getElementsByTagName('form')[0].name=='reg'||document.getElementsByTagName('form')[0].name=='signin') {
        document.forms.item(0).addEventListener("submit", formValidate);
    }
}

if(document.getElementById('search-page')){
    // document.querySelector('form.search').addEventListener('submit',searchFormSub);
    document.addEventListener('click',suggestionClick);    
    document.addEventListener('click',function(e){
        if((e.target.tagName!=='LI'||e.target.id=='search-term-input') && document.getElementById('suggestions').classList.contains('active')){
            document.getElementById('suggestions').classList.remove('active');
        }
    });
    document.getElementById('search-term-input').addEventListener('keyup',loadSearchSuggestions);    
    document.addEventListener('click',reloadSuggestions);
    if(document.getElementById('change-zip')){
        document.getElementById('change-zip').addEventListener('click',zipChange);
    }    
    document.getElementById('search-form').addEventListener('submit',function(){
        document.getElementById('user-zip').removeAttribute('disabled');
    })
    var selectionMade = false;
    document.getElementById('search-form').addEventListener('submit',function(e){
        if(document.getElementById('user-zip').value.length<1 || document.getElementById('search-term-input').value.length<1){
            e.preventDefault();
        }
    });
    document.addEventListener('keydown',function(e){
        if(e.key == 'Enter'){
            e.preventDefault();
        }
    });  
}

if(document.getElementById('brand-matches')){
    var links = document.querySelectorAll('li a');
    for(var i = 0; i<links.length; i++){
        links[i].addEventListener('click',function(e){
            e.preventDefault();
            document.getElementById('subcategory_id').value=e.target.getAttribute('data-subcatid');
            document.getElementById('organic').value=e.target.getAttribute('data-organic');
            document.getElementById('subbrand_id').value=e.target.getAttribute('data-subbrandid');
            document.getElementById('brand-match-form').submit();
        });
    }    
}

if(document.getElementById('subcat-view')){
    document.getElementById('shelfCompTool').setAttribute('style','height: '+document.body.getBoundingClientRect().height+'px;');
    if(document.getElementById('all-brands-form')){
        document.getElementById('show-all-brands-link').addEventListener('click',function(){
            document.getElementById('all-brands-form').submit();
        });
    }
    if(document.querySelector('form#show-brand')){
        if(document.querySelector('table.detail-table td.brand-cell a')){
            var links = document.querySelectorAll('table.detail-table td.brand-cell a');
        } else {
            var links = document.querySelectorAll('a[data-matchtype]');
        }
        for(var i = 0; i<links.length; i++){
            links[i].addEventListener('click',function(e){
                document.getElementById('matchIdInput').value=e.target.getAttribute('data-matchid');
                document.getElementById('matchTypeInput').value=e.target.getAttribute('data-matchtype');
                document.getElementById('searchTerm').value = e.target.innerText;
                document.getElementById('show-brand').submit();
            });
        }   
    }
    if(document.getElementById('favoriteBtn')){
        var faveInfo = {
            userID: '',
            brandID: '',
            subbrandID: '',
            subcategoryID: '',
            organic: ''
        };
        if(document.querySelector('a[data-user-id]')){
            faveInfo.userID = document.getElementById('favoriteBtn').getAttribute('data-user-id');
        }
        if(document.querySelector('input[name=brandID]')){
            faveInfo.brandID = document.querySelector('input[name=brandID]').value;
        }
        if(document.querySelector('input[name=subbrandID]')){
            faveInfo.subbrandID = document.querySelector('input[name=subbrandID]').value;
        }
        if(document.querySelector('input[name=subcatID]')){
            faveInfo.subcategoryID = document.querySelector('input[name=subcatID]').value;
        } else if(document.querySelector('input[name=subCat]')){
            faveInfo.subcategoryID = document.querySelector('input[name=subCat]').value;
        }
        if(document.querySelector('input[name=organic]')){
            faveInfo.organic = document.querySelector('input[name=organic]').value;
        }
        function addFave(){            
            var xml = new XMLHttpRequest();
            xml.onload = function(){
                if(this.status == 200){
                    document.querySelector('a[data-user-id]').setAttribute('data-fave-id',JSON.parse(this.responseText));
                    document.querySelector('a[data-user-id]').setAttribute('data-favorite','1');
                    document.querySelector('a[data-user-id]').innerText='Remove from Favorites';
                }
            };
            xml.open('POST','./assets/ajax.php',true);
            xml.setRequestHeader('Content-type','application/x-www-form-urlencoded');
            xml.send('userID='+faveInfo.userID+'&brandID='+faveInfo.brandID+'&subbrandID='+faveInfo.subbrandID+'&subcategoryID='+faveInfo.subcategoryID+'&organic='+faveInfo.organic+'&task=addFavorite');
        }
        function removeFave(){
            var faveID = document.querySelector('a[data-user-id]').getAttribute('data-fave-id');
            var xml = new XMLHttpRequest();
            xml.onload = function(){
                if(this.status == 200){
                    document.querySelector('a[data-user-id]').setAttribute('data-fave-id','');
                    document.querySelector('a[data-user-id]').setAttribute('data-favorite','');
                    document.querySelector('a[data-user-id]').innerText='Add to Favorites';
                }
            };
            xml.open('POST','./assets/ajax.php',true);
            xml.setRequestHeader('Content-type','application/x-www-form-urlencoded');
            xml.send('userID='+faveInfo.userID+'&faveID='+faveID+'&task=removeFavorite');
        }
        function faveAction(e){
            e.preventDefault();
            var faveStatus = e.target.getAttribute('data-favorite');
            if(faveStatus == 1){
                removeFave();
            } else {
                addFave();
            }
        }        
        document.getElementById('favoriteBtn').addEventListener('click',faveAction);
    }
    var box = document.getElementById('shelfCompTool');
    var innerBox = document.getElementById('shelfCompTool').firstElementChild;
    var compBtn = document.getElementById('calcCompare');
    var priceInfo = {genLo:innerBox.dataset.genLo, genMed:innerBox.dataset.genMed, genHi:innerBox.dataset.genHi, brandLo:innerBox.dataset.brandLo, brandMed:innerBox.dataset.brandMed, brandHi:innerBox.dataset.brandHi};
    function clearResults(){
        if(document.getElementById('genResult')){
            document.getElementById('genResult').parentElement.removeChild(document.getElementById('genResult'));
        }
        if(document.getElementById('brandResult')){
            document.getElementById('brandResult').parentElement.removeChild(document.getElementById('brandResult'));
        }
    }

    function compClose(){
        box.classList.toggle('hide');
        box.classList.toggle('show');
        document.getElementById('resUnitPrice').innerText = '';
        document.getElementById('compPrice').value = '';
        document.getElementById('compSz').value = '';
        clearResults();
    }
    if(document.getElementById('shelfComBtn')){
        document.getElementById('shelfComBtn').addEventListener('click',function(e){        
            var scrollUpperBound = Math.floor(innerBox.getBoundingClientRect().top/2);
            compClose();
            setTimeout(function(){scrollTo(0,scrollUpperBound);},5);    
        });   
    }
    
    document.getElementById('compClose').addEventListener('click',compClose);

    box.addEventListener('click',function(e){
        if(e.target.id == 'shelfCompTool'){
            compClose();
        }        
    });
    compBtn.addEventListener('click',function(){
        clearResults();
        var theUnitPrice = (document.getElementById('compPrice').value/document.getElementById('compSz').value).toFixed(3);
        document.getElementById('resUnitPrice').innerText = 'The unit price: $'+theUnitPrice+'/'+innerBox.getAttribute('data-unit');

        if(!priceInfo.genLo==''){
            var genResult = document.createElement('div');
            genResult.id = 'genResult';            
            var genUnitPriceStatus = '';
            if(theUnitPrice < priceInfo.genLo){
                genUnitPriceStatus = 'excellent';                
            } else if (theUnitPrice>=priceInfo.genLo && theUnitPrice<=priceInfo.genMed){
                genUnitPriceStatus = 'good';
            } else if (theUnitPrice>=priceInfo.genMed && theUnitPrice<=priceInfo.genHi){
                genUnitPriceStatus = 'high';
            } else if (theUnitPrice>priceInfo.genHi){
                genUnitPriceStatus = 'very high';
            }
            genResult.classList.add(genUnitPriceStatus.replace(' ',''));
            genResult.innerHTML = '<div class="symbol"></div><span class="capitalize">'+genUnitPriceStatus+'</span> price for store brand';
            document.getElementById('results').appendChild(genResult);
        }

        if(!priceInfo.brandLo==''){
            var brandResult = document.createElement('div');
            brandResult.id = 'brandResult';  
            var brandUnitPriceStatus = '';
            if(theUnitPrice < priceInfo.brandLo){
                brandUnitPriceStatus = 'excellent';
            } else if (theUnitPrice>=priceInfo.brandLo && theUnitPrice<=priceInfo.brandMed){
                brandUnitPriceStatus = 'good';
            } else if (theUnitPrice>=priceInfo.brandMed && theUnitPrice<=priceInfo.brandHi){
                brandUnitPriceStatus = 'high';
            } else if (theUnitPrice>priceInfo.brandHi){
                brandUnitPriceStatus = 'very high';
            } 
            brandResult.classList.add(brandUnitPriceStatus.replace(' ',''));
            brandResult.innerHTML = '<div class="symbol"></div><span class="capitalize">'+brandUnitPriceStatus+'</span> price for brand name';
            document.getElementById('results').appendChild(brandResult);
        }
    });
}

if(document.getElementById(('updatePage'))){
    document.body.classList.add('hideOverflowX');
    document.getElementById('storeLocation').setAttribute('data-init-y',document.getElementById('storeLocation').getBoundingClientRect().top);
    document.getElementById('numberCheckModal').setAttribute('style','height: '+document.body.getBoundingClientRect().height+'px;');
    // var currentZip;
    // var currentZipCoords;
    var script = document.createElement('script');
    script.setAttribute('src','includes/zipInfo.js');
    var apiScript = document.createElement('script');
    apiScript.setAttribute('src','https://maps.googleapis.com/maps/api/js?key='+googleKey+'&libraries=places');    
    document.getElementsByTagName('head')[0].appendChild(script);
    document.getElementsByTagName('head')[0].appendChild(apiScript);
    var main = document.getElementById('updatePage');
    var brandSelect = document.getElementById('brandSelect');
    var subbrandSelect = document.getElementById('subbrandSelect');
    var orgSelect = document.getElementById('organic');
    if(orgSelect.getAttribute('data-organic-default')==0){
        orgSelect[0].setAttribute('selected',true);
    } else {
        orgSelect[1].setAttribute('selected',true);
    }
    if(main.getAttribute('data-update-type')=='branded'){
        var brandOptions = brandSelect.options;
        var matchID = main.getAttribute('data-brand-id');
        var optMatch;
        for(var opt in brandOptions){
            if(!optMatch){
                if(brandOptions[opt].value==matchID){
                    optMatch = opt;
                }
            }
        }
        brandOptions[optMatch].setAttribute('selected','true');
        if(main.hasAttribute('data-subbrand-id')){
            var subbrandID = main.getAttribute('data-subbrand-id');
        }
        loadSubbrandOpts();
        brandSelect.addEventListener('input',loadSubbrandOpts);
    } else if(main.getAttribute('data-update-type')=='unbranded'){
        brandSelect.addEventListener('input',loadSubbrandOpts);
    }
    document.querySelector('select[name="storeName"]').addEventListener('input',function(){
        document.getElementById('storeLocation').removeAttribute('disabled');
    });
    
    document.getElementById('storeName').addEventListener('change',loadStoreLocations2);
    document.getElementById('storeLocation').addEventListener('click',revealLocations);

   document.getElementById('updateForm').addEventListener('submit', function (e) {    
    var formNodes = e.target.childNodes;
    for (node in formNodes){
        if((formNodes[node].nodeName=='SELECT' && formNodes[node].hasAttribute('required')) || (formNodes[node].nodeName=='TEXTAREA' && formNodes[node].hasAttribute('required'))){
            if(formNodes[node].value.length==0){
                e.preventDefault();
                var completionAlert = 'All available fields required'; 
                var completionAlertPara = document.createElement('p');
                completionAlertPara.innerText = completionAlert;
                completionAlertPara.classList.add('errorAlert');
                e.target.insertAdjacentElement('afterbegin',completionAlertPara);
            }
        }
    }

       if (isNaN(document.querySelector('input[name="size"]').value) || isNaN(document.querySelector('input[name="price"]').value)) {
           e.preventDefault();
           if (isNaN(document.querySelector('input[name="size"]'))) {
               var sizeAlert = 'Enter a valid number value for size';
               var szAlertPara = document.createElement('p');
               szAlertPara.innerText = sizeAlert;
               szAlertPara.classList.add('errorAlert');
               document.querySelector('input[name="size"]').insertAdjacentElement('afterend', szAlertPara);
           }
           if (isNaN(document.querySelector('input[name="price"]'))) {
               var priceAlert = 'Enter a valid number value for price';
               var priceAlertPara = document.createElement('p');
               priceAlertPara.innerText = priceAlert;
               priceAlertPara.classList.add('errorAlert');
               document.querySelector('input[name="price"]').insertAdjacentElement('afterend', priceAlertPara);
           }
       }
       if(document.getElementById('updatePage').getAttribute('data-minquant')!=='null' || document.getElementById('updatePage').getAttribute('data-maxquant')!=='null' || document.getElementById('updatePage').getAttribute('data-minpr')!=='null' || document.getElementById('updatePage').getAttribute('data-maxpr')!=='null'){
        var minQuan = parseFloat(document.getElementById('updatePage').getAttribute('data-minquant'));
        var maxQuan = parseFloat(document.getElementById('updatePage').getAttribute('data-maxquant'));
        var minPr = parseFloat(document.getElementById('updatePage').getAttribute('data-minpr'));
        var maxPr = parseFloat(document.getElementById('updatePage').getAttribute('data-maxpr'));
        var quanInput = parseFloat(document.querySelector('input[name="size"]').value);
        var priceInput = parseFloat(document.querySelector('input[name="price"]').value);
        if((quanInput<minQuan || quanInput>(maxQuan*2)) && (priceInput<minPr || priceInput>(maxPr*2))){
            e.preventDefault();
            toggleModal();
            document.getElementById('numberCheckMsg').innerHTML = '<h2>Size & Price Check</h2><p>Is this quantity correct?</p><p class="numDisplay">'+quanInput+'</p><p>Is this price correct?</p><p class="numDisplay">$'+priceInput.toFixed(2)+'</p><button id="yesBtn">Yes</button><button id="changeBtn">Change Info</buttton>';
        } else if (quanInput<minQuan || quanInput>(maxQuan*2)){
            e.preventDefault();
            toggleModal();
            document.getElementById('numberCheckMsg').innerHTML = '<h2>Size Check</h2><p>Is this quantity correct?</p><p class="numDisplay">'+quanInput+'</p><button id="yesBtn">Yes</button><button id="changeBtn">Change Info</buttton>';
        } else if(priceInput<minPr || priceInput>(maxPr*2)){
            e.preventDefault();
            toggleModal();
            document.getElementById('numberCheckMsg').innerHTML = '<h2>Price Check</h2><p>Is this price correct?</p><p class="numDisplay">$'+priceInput.toFixed(2)+'</p><button id="yesBtn">Yes</button><button id="changeBtn">Change Info</buttton>';
        } 
       }

   });
    document.addEventListener('click',function(e){
        //hiding modal area if transparent area clicked OR if cancel btn clicked
        if((e.target.id == 'zipModal' && e.target.id != 'zipModalContent') || e.target.id == 'modalCancelBtn'){
            modalView();
        } else if(e.target.id == 'modalSaveBtn'){            
            if(document.getElementById('modalZip').value.length >= 3) {
                currentZip = document.getElementById('modalZip').value;
                document.getElementById('updatePage').setAttribute('data-current-zip',currentZip);
                loadStoreLocations2();                
                modalView();
                document.getElementById('modalZip').value = '';
            }
        }
    });
    document.addEventListener('click',function(e){
        if(e.target.id=='yesBtn'){
            document.getElementById('updateForm').submit();
        } else if (document.body.classList.contains('hideOverflowY') && (e.target.id==='numberCheckModal' || e.target.id==='changeBtn')){
            toggleModal();
        }
    });
    var theSubcat = document.querySelector('input[name="subCat"]');    
    document.addEventListener('DOMContentLoaded',function(){
        var xml = new XMLHttpRequest();
        xml.onload = function(){
            if(this.status==200){
                var minMaxQuan = JSON.parse(this.responseText);
                document.getElementById('updatePage').setAttribute('data-minQuant',minMaxQuan.min);
                document.getElementById('updatePage').setAttribute('data-maxQuant',minMaxQuan.max);
                document.getElementById('updatePage').setAttribute('data-minPr',minMaxQuan.minPr);
                document.getElementById('updatePage').setAttribute('data-maxPr',minMaxQuan.maxPr);
            }
        };
        xml.open('GET','./assets/ajax.php?task=subcatQuantities&subcatID='+theSubcat.value,true);
        xml.send();
    });
    function toggleModal(){
        document.body.classList.toggle('hideOverflowY');
        document.getElementById('numberCheckModal').classList.toggle('show');
        document.getElementById('numberCheckModal').classList.toggle('hide');
    }
}

if(document.getElementById(('searchTerms'))){
    var matchLinks = document.querySelectorAll('a[data-match-id]');    
    for(var i = 0; i<matchLinks.length; i++){
        matchLinks[i].addEventListener('click',function(e){
            e.preventDefault();
            document.getElementById('matchID').value = e.target.getAttribute('data-match-id');
            document.getElementById('matchType').value = e.target.getAttribute('data-match-type');
            document.getElementById('search-form').submit();
        });
    }
}

if(document.getElementById(('settings'))){
    var links = document.querySelectorAll('main a');
    for (var i = 0; i<links.length; i++){
        links[i].addEventListener('click',linkAction);
    }    
    function linkAction(e) {
        e.preventDefault();
        var theHref = e.target.href;
        var theSpan;
        var theForm;
        if (theHref.indexOf('#change') > 0) {
            theSpan = e.target.parentElement.firstElementChild.nextElementSibling;
            theForm = theSpan.nextElementSibling;
            if (theForm.classList.contains('hide')) {
                theForm.firstElementChild.setAttribute('placeholder',theSpan.innerText);
                theSpan.classList.add('hide');
                theForm.classList.remove('hide');
                theForm.firstChild.removeAttribute('disabled');
                e.target.classList.add('hide');
                e.target.nextElementSibling.classList.remove('hide');
            }
        } else if (theHref.indexOf('#save') > 0) {
            var containerDiv = e.target.parentElement;
            theSpan = containerDiv.parentElement.firstElementChild.nextElementSibling;
            theForm = theSpan.nextElementSibling;
            var theInputField = theForm.firstElementChild;
            var inputName = theInputField.name;
            saveChanges(inputName, theInputField.value, theSpan, theForm);
        } else if (theHref.indexOf('#cancel') > 0) {
            var containerDiv = e.target.parentElement;
            theSpan = containerDiv.parentElement.firstElementChild.nextElementSibling;
            theForm = theSpan.nextElementSibling;
            theSpan.classList.remove('hide');
            theForm.classList.add('hide');
            theForm.firstChild.setAttribute('disabled', true);
            containerDiv.classList.add('hide');
            containerDiv.previousSibling.classList.remove('hide');
        }
    }
    function saveChanges(input,newVal,span, form){
        var action;
        if(input.toLowerCase().indexOf('email')>0){
            action = 'email';
        } else if(input.toLowerCase().indexOf('zip')>0){
            action = 'zip';
        } else if(input.toLowerCase().indexOf('radius')>0){
            action = 'radius';
        }
        var xml = new XMLHttpRequest();
        xml.onload = function(){
            if(this.status == 200){
                var linkDiv = form.parentElement.lastElementChild;
                var changeLink = linkDiv.previousElementSibling;
                span.innerText = newVal;
                form.firstElementChild.setAttribute('disabled',true);
                form.classList.add('hide');
                span.classList.remove('hide');
                linkDiv.classList.add('hide');
                changeLink.classList.remove('hide');
            }
        };
        xml.open('POST','./assets/ajax.php',true);
        xml.setRequestHeader('Content-type','application/x-www-form-urlencoded');
        xml.send('action='+action+'&value='+newVal+'&task=saveChanges');
    }
}

if(document.getElementById(('favorites'))){
    var form = document.getElementById('viewFave');
    var matchID = document.querySelector('form#viewFave input[name=matchID]');
    var matchType = document.querySelector('form#viewFave input[name=matchType]');
    var organic = document.querySelector('form#viewFave input[name=organic]');
    var searchTerm = document.querySelector('form#viewFave input[name=searchTerm]');
    var task = document.querySelector('form#viewFave input[name=task]');
    var subcatID = document.querySelector('form#viewFave input[name=subcategory_id]');
    var faveLinks = document.querySelectorAll('#faveLinks a');
    for(var i=0; i<faveLinks.length; i++){
        faveLinks[i].addEventListener('click',function(e){            
            e.preventDefault();
            var group = e.target.getAttribute('data-match-type');
            var selectedLink = e.target;
            if(group=='b' || group=='sb'){
                organic.value = e.target.getAttribute('data-organic');
                subcatID.value = e.target.getAttribute('data-subcat-id');
                task.value = 'brand-match-select';
                if(group=='b'){
                    form.removeChild(form.lastElementChild);
                    document.querySelector('form#viewFave input[name=brand_id]').value = e.target.getAttribute('data-match-id');
                } else {
                    document.querySelector('form#viewFave input[name=subbrand_id]').value = e.target.getAttribute('data-match-id');
                    document.querySelector('form#viewFave input[name=brand_id]').value = e.target.getAttribute('data-brand-id');
                }
            } else {
                task.value = 'search-form-sub';
                if(document.getElementsByName('subbrand_id')[0]){
                    document.getElementsByName('subbrand_id')[0].parentElement.removeChild(document.getElementsByName('subbrand_id')[0]);
                }
                if(document.getElementsByName('subcategory_id')[0]){
                    document.getElementsByName('subcategory_id')[0].parentElement.removeChild(document.getElementsByName('subcategory_id')[0]);
                }
                if(document.getElementsByName('brand_id')[0]){
                    document.getElementsByName('brand_id')[0].parentElement.removeChild(document.getElementsByName('brand_id')[0]);
                }
                if(selectedLink.getAttribute('data-organic')==1){
                    organic.value = 1;
                } else {
                    organic.parentElement.removeChild(organic);
                }                
            }            
            matchID.value = selectedLink.getAttribute('data-match-id');
            matchType.value = selectedLink.getAttribute('data-match-type');            
            var theTerm = selectedLink.innerText;
            if(theTerm.indexOf(', organic')>=1){
                theTerm = theTerm.replace(', organic','');
            }
            if(theTerm.indexOf('(all brands)')>=1){
                theTerm = theTerm.replace('(all brands)','');
            }
            theTerm = theTerm.trim();
            searchTerm.value = theTerm;
            form.submit();
        });
    }
}

if(document.getElementById(('updates'))){
    function getUpdates(){
        var xml = new XMLHttpRequest();
        var table = document.getElementsByTagName('table')[0];
        var offset = parseInt(table.getAttribute('data-update-offset'));
        var counter = parseInt(table.getAttribute('data-update-offset'))+1;
        var div = document.getElementsByClassName('tableContainer')[0];
        xml.onload = function(){
            if(this.status==200){
                if(parseInt(table.getAttribute('data-total-updates'))>0){
                    div.classList.add('nonEmpty'); 
                    table.classList.remove('hide');                                       
                } else {
                    div.classList.add('empty'); 
                    var para = document.createElement('p');
                    para.innerText = 'No updates submitted yet';
                    table.parentElement.appendChild(para);                    
                }
                if(offset==0 && parseInt(table.getAttribute('data-total-updates'))>50){
                    addButton();
                }                              
                table.setAttribute('data-update-offset',offset+50);
                if((parseInt(table.getAttribute('data-update-offset'))>=parseInt(table.getAttribute('data-total-updates')) && parseInt(table.getAttribute('data-total-updates'))!=0)){
                    if(document.getElementById('moreResults')){
                        document.getElementById('moreResults').setAttribute('disabled',true);
                    }
                }                  
                var tableBody = document.getElementsByTagName('tbody')[0];                
                var results = JSON.parse(this.responseText);
                for(var i = 0; i<results.length; i++){
                    var row = createRow(results[i]['item_id']);
                    var counterCell = createCell(counter);                
                    var itemCell = createCell(results[i]['category_name'],results[i]['subcategory_name'],results[i]['item_organic']);
                    var brandCell = createCell(results[i]['brand_name'],results[i]['subbrand_name']);
                    var sizeCell = createCell(results[i]['item_size'],results[i]['subcategory_unit']);
                    var priceCell = createCell('$'+results[i]['item_price']);
                    var storeCell = createCell(results[i]['store_name']+', '+results[i]['store_zip']);
                    var addedCell = createCell(results[i]['item_added']);
                    appendEm(row,counterCell,itemCell,brandCell,sizeCell,priceCell,storeCell,addedCell);
                    tableBody.appendChild(row);
                    counter++;
                }                
            }
        };
        xml.open('GET','./assets/ajax.php?offset='+offset+'&task=updates',true);
        xml.send();
    }
    function getUpdatesTotal(){
        var xml = new XMLHttpRequest();
        xml.onload = function(){
            if(this.status==200){
                var table = document.getElementsByTagName('table')[0];
                table.setAttribute('data-total-updates',this.responseText);
            }
        };
        xml.open('GET','./assets/ajax.php?task=updatesTotal',true);
        xml.send();
    }
    function addButton(){
        var button = document.createElement('button');
        button.id = 'moreResults';
        button.innerText = 'More Results';
        button.addEventListener('click',function(){
            getUpdates();
        });
        document.getElementsByTagName('table')[0].parentElement.appendChild(button);
    }
    function createRow(itemID){
        var row = document.createElement('tr');
        row.setAttribute('data-item-id',itemID);
        return row;
    }
    function createCell(){
        var cell = document.createElement('td');
        if(arguments.length==1){
            cell.innerText = arguments[0];
        } else if (arguments.length==2){
            if(arguments[0]==null){
                cell.innerText = '';
            } else if(isNaN(arguments[0])){
                if (arguments[1]==null){
                    cell.innerText = arguments[0];
                } else {
                    cell.innerText = arguments[0]+', '+arguments[1];
                }
            } else {
                if((parseFloat(arguments[0])-Math.floor(parseFloat(arguments[0])))==0){
                    cell.innerText = parseInt(arguments[0])+' '+arguments[1];
                } else {
                    cell.innerText = parseFloat(arguments[0])+' '+arguments[1];
                }
            }            
        } else if (arguments.length == 3) {
            if(arguments[2]==0){
                cell.innerText = arguments[0]+', '+arguments[1];
            } else {
                cell.innerText = arguments[0]+', '+arguments[1]+', organic';
            }
        }       
        return cell;
    }
    function appendEm(){
        var parent = arguments[0];
        for(var i=1; i<arguments.length; i++){
            parent.appendChild(arguments[i]);
        }
    }
    document.addEventListener('DOMContentLoaded',function(e){
        getUpdatesTotal();
        getUpdates();
    });
}

if(document.getElementById(('signIn'))){
    document.getElementsByTagName('form')[1].addEventListener('submit',function(e){
        e.preventDefault();
        if(document.querySelector('input[name=email]').value.length>0){
            document.querySelector('input[name=email2').value = document.querySelector('input[name=email]').value;
        }
        e.target.submit();
    });    
}

if(document.getElementById(('resetProc'))){
    var errorPara = document.createElement('p');
    errorPara.id = 'errorPara';
    errorPara.style = 'color: red; font-weight: bold;';
    document.getElementById('pwInput').addEventListener('keyup',function(e){
        if(e.target.value.length>=8){
            document.getElementById('confPwInput').disabled = false;
        }
    });
    document.getElementById('pwInput').addEventListener('blur',function(e){
        var pwField = e.target;
        var upperChar = false;
        var specialChar = false;        
        for(var i=0; i<pwField.value.length; i++){
            //caps: 65-90
            //symbols: 33-47, 58-64, 91-96, 123-126
            //numbers: 48-57
            if(!upperChar){
                if(pwField.value.charCodeAt(i)>=65 && pwField.value.charCodeAt(i)<=90){
                    upperChar = true;
                }
            }
            if(!specialChar){
                if((pwField.value.charCodeAt(i)>=33 && pwField.value.charCodeAt(i)<=64) || (pwField.value.charCodeAt(i)>=91 && pwField.value.charCodeAt(i)<=96) || (pwField.value.charCodeAt(i)>=123 && pwField.value.charCodeAt(i)<=126)){
                    specialChar = true;
                }
            }
        }
        if(!upperChar || !specialChar){
            errorPara.innerText = 'Password must have at least 1 uppercase letter and at least 1 number or symbol';
            document.getElementsByTagName('p')[0].insertAdjacentElement('afterend',errorPara);
            document.getElementById('resetSub').disabled = true;
        } else {
            if(document.getElementById('errorPara')){
                document.getElementById('errorPara').parentElement.removeChild(errorPara);
                document.getElementById('resetSub').disabled = false;
            }
        }
    });
    document.getElementsByTagName('form')[0].addEventListener('submit',function(e){
        e.preventDefault();
        if(document.getElementById('pwInput').value != document.getElementById('confPwInput').value){            
            errorPara.innerText = 'Passwords don\'t match';
            document.getElementsByTagName('p')[0].insertAdjacentElement('afterend',errorPara);
        } else {
            document.getElementsByTagName('form')[0].submit();
        }
    });
}

//functions

function formValidate(e) {
    var formType = e.target.name;
    var formErrors = [];
    var main = document.getElementsByTagName('main')[0];
    var email = document.getElementById('emailInput');
    var password = document.getElementById('pwInput');
    var validForm = true;

    if (!email.validity.valid || email.value.length < 1 || email.value.indexOf('@') < 0) {
        formErrors.push('Valid e-mail address');
        validForm = false;
    }

    if (formType === 'reg') {
        var zip = document.getElementById('zipInput').value;

        if (!password.validity.valid) {
            formErrors.push('Valid password');
            validForm = false;
        } else if (password.value.length < 8) {
            formErrors.push('Password too short');
            validForm = false;
        } else {
            var specialChar = false;
            var pwLength = password.value.length;
            for (var i = 0; i < pwLength; i++) {
                if (!specialChar) {
                    if (password.value.charCodeAt(i) < 97 || password.value.charCodeAt(i) > 122) {
                        specialChar = true;
                    }
                }
            }
            if (!specialChar) {
                formErrors.push('Password needs a special character');
                validForm = false;
            }
        }

        if (!zip) {
            formErrors.push('Zip code');
            validForm = false;
        } else if (zip < 1001 || zip > 99950) {
            formErrors.push('Valid US Zip Code');
            validForm = false;
        }

        var confPassword = document.getElementById('confPwInput');
        if (!confPassword || confPassword.value.length < 1) {
            formErrors.push('Re-enter your password');
            validForm = false;
        } else if (password.value !== confPassword.value) {
            formErrors.push('Passwords must match');
            validForm = false;
        }
    }

    if (!validForm) {
        e.preventDefault();
        var errorPara = document.createElement('p');
        errorPara.innerText = 'Uh oh! We need some more info:';
        var errors = document.createElement('ul');
        for (error in formErrors) {
            var item = document.createElement('li');
            item.innerText = formErrors[error];
            errors.appendChild(item);
        }
        var errorBlock = document.createElement('div');
        errorBlock.appendChild(errorPara);
        errorBlock.appendChild(errors);
        errorBlock.className = 'formSubErrors';

        if (document.getElementsByClassName('formSubErrors')[0]) {
            main.removeChild(document.getElementsByClassName('formSubErrors')[0]);
        }

        main.insertBefore(errorBlock, document.forms.item(0));
    }
}

function loadSubcatOpts(){
    var category = document.getElementById('category').value;
    var xml = new XMLHttpRequest();
    var results;
    xml.onload = function(){
        var form =  document.querySelector('form.search');
        if(this.status == 200){
            results = JSON.parse(this.responseText);
            var selectEl = document.createElement('select');
            selectEl.id='subcategories';
            selectEl.setAttribute('name','subcategory');
            var blankOpt = document.createElement('option');
            blankOpt.setAttribute('value','');
            selectEl.appendChild(blankOpt);
            for(var opt in results){
                var newOpt = document.createElement('option');
                newOpt.setAttribute('value',results[opt]['subcategory_id']);
                newOpt.setAttribute('data-unit',results[opt]['subcategory_unit']);
                var newOptName = document.createTextNode(results[opt]['subcategory_name']);
                newOpt.appendChild(newOptName);
                selectEl.appendChild(newOpt);
            }     
            form.replaceChild(selectEl,document.getElementById('subcategories'));
        }
    };
    xml.open('GET','./assets/ajax.php?category_id='+category+'&task=subcats',true);
    xml.send();
}

function searchFieldInput(){
    document.getElementById('search-term').value = document.getElementById('search-term-input').value;
}

function searchFormSub(){
    document.getElementById('subcat-name').value=document.getElementById('subcategories').options[document.getElementById('subcategories').selectedIndex].text;
    document.getElementById('cat-name').value=document.getElementById('category').options[document.getElementById('category').selectedIndex].text;
    document.getElementById('subcat-unit').value=document.getElementById('subcategories').options[document.getElementById('subcategories').selectedIndex].getAttribute('data-unit');
}

function loadBrandOpts(){
    var results;
    var category = document.getElementById('category').getAttribute('data-cat-id');
    var xml = new XMLHttpRequest();
    xml.onload = function(){
        if(this.status == 200){
            results = JSON.parse(this.responseText);
            var selectEl = document.createElement('select');
            selectEl.id='brandSelect';
            selectEl.setAttribute('name','brand');
            var blankOpt = document.createElement('option');
            blankOpt.setAttribute('disabled','true');
            selectEl.appendChild(blankOpt);
            for(var opt in results){
                var newOpt = document.createElement('option');
                newOpt.setAttribute('value',results[opt]['brand_id']);
                var newOptName = document.createTextNode(results[opt]['brand_name']);
                newOpt.appendChild(newOptName);
                selectEl.appendChild(newOpt);
            }
            var brandCell = document.createElement('td');
            brandCell.appendChild(selectEl);     
            document.getElementById('brandRow').replaceChild(brandCell,document.getElementById('brandSelectCell'));
        }
    };
    xml.open('GET','./assets/ajax.php?category_id='+category+'&task=update',true);
    xml.send();
}

function loadSearchSuggestions(){
    var term = document.getElementById('search-term-input').value;
    if((!selectionMade && term.length>1) || selectionMade){
        var results;    
    var xml = new XMLHttpRequest();
    xml.onload = function(){
        if(this.status==200){
            results = JSON.parse(this.responseText);
            var brandMatches = [];
            var subcatMatches = [];
            var cats = [];
            var categories = ['Baby', 'Baking & Seasoning', 'Beer & Wine', 'Beverage', 'Bread', 'Breakfast', 'Canned & Packaged', 'Cleaning', 'Condiments & Sauces', 'Dairy', 'Deli', 'Ethnic', 'Frozen', 'Grains, Pasta, Potatoes', 'Meat & Seafood', 'Miscellaneous', 'Paper & Plastics', 'Personal Care & Cosmetics', 'Pet', 'Produce', 'Snacks & Candy'];
            for (var opt in results){
                if(results[opt]['type']=='b' || results[opt]['type']=='sb'){
                    brandMatches.push(results[opt]);
                } else {
                    subcatMatches.push(results[opt]);
                    if(parseInt(cats.indexOf(results[opt]['type'].substr(results[opt]['type'].indexOf('/')+1)))<0){
                        parseInt(cats.push(results[opt]['type'].substr(results[opt]['type'].indexOf('/')+1)));
                    }                    
                }
            }
            var main = document.getElementsByTagName('main')[0];
            var form = document.getElementById('search-form');
            var suggestionDiv = document.getElementById('suggestions');
            suggestionDiv.setAttribute('style','top:'+(document.getElementById('search-term-input').getBoundingClientRect().bottom-document.getElementById('search-page').getBoundingClientRect().top-parseFloat(getComputedStyle(document.getElementById('search-page'))['border-top-width']))+'px; width:'+document.getElementById('search-term-input').offsetWidth+'px;');

            var uls = document.querySelectorAll('main#search-page ul');
            var ulIDs = [];
            for(var ul in uls){
                ulIDs.push(uls[ul].id);
            }
            for(var id in ulIDs){
                if(ulIDs[id]){
                    suggestionDiv.removeChild(document.getElementById(ulIDs[id]));
                }
            }

            var ulBrands = document.createElement('ul');
            ulBrands.id = 'brandGrp';
            var brandHeader = document.createElement('li');
            brandHeader.className = 'groupHeader';
            brandHeader.innerText = 'Related Brands';
            ulBrands.appendChild(brandHeader);
            for(var brand in brandMatches){
                var li = document.createElement('li');
                li.setAttribute('data-matchid', brandMatches[brand]['id']);
                li.setAttribute('data-matchtype', brandMatches[brand]['type']);
                li.innerText = brandMatches[brand]['name'];
                ulBrands.appendChild(li);
            }
            suggestionDiv.appendChild(ulBrands);

            for(var cat in cats){
                var ulCat = document.createElement('ul');
                ulCat.id = 'cat'+(cats[cat]);
                var catHeader = document.createElement('li');
                catHeader.className = 'groupHeader';
                catHeader.innerText = categories[cats[cat]-1];
                ulCat.appendChild(catHeader);
                for(var subcat in subcatMatches){
                    if(subcatMatches[subcat]['type'].substr(subcatMatches[subcat]['type'].indexOf('/')+1)==cats[cat]){
                        var subcatLi = document.createElement('li');
                        subcatLi.setAttribute('data-matchid', subcatMatches[subcat]['id']);
                        subcatLi.setAttribute('data-matchtype', subcatMatches[subcat]['type']);
                        subcatLi.innerText = subcatMatches[subcat]['name'];
                        ulCat.appendChild(subcatLi);
                    }
                }
                suggestionDiv.appendChild(ulCat);
                suggestionDiv.className='active';
            }  
            form.replaceChild(suggestionDiv,document.getElementById('suggestions')); 
        }        
    };
    xml.open('GET','./assets/ajax.php?term='+term+'&task=search',true);
    xml.send();
    }    
}

function suggestionClick(e){
    if(e.target.tagName == 'LI' && !e.target.classList.contains('groupHeader')){
        document.getElementById('suggestions').classList.remove('active');
        document.getElementById('search-term-input').value = e.target.innerText;
        document.getElementById('matchID').value=e.target.getAttribute('data-matchid');
        document.getElementById('matchType').value=e.target.getAttribute('data-matchtype');
        selectionMade = true;
    }
}

function reloadSuggestions(e){
    if(e.target.id=='search-term-input' && selectionMade){
        document.getElementById('suggestions').classList.add('active');
    }
}

function zipChange(){
    var zipField = document.getElementById('user-zip');
    var changeSpan = document.getElementById('change-link');
    var saveCancelSpan = document.getElementById('save-cancel-links');
    var saveLink = document.getElementById('save-zip');
    var cancelLink = document.getElementById('cancel-zip');
    cancelLink.setAttribute('data-oldzip',zipField.value);
    changeSpan.classList.add('hide');
    changeSpan.setAttribute('aria-hidden','true');
    saveCancelSpan.classList.remove('hide');
    saveCancelSpan.setAttribute('aria-hidden','false');
    zipField.removeAttribute('disabled');
    zipField.value='';
    saveLink.onclick = function(){
        if(zipField.value=='' || parseInt(zipField.value)<501 || parseInt(zipField.value)>99929){
            zipField.value = cancelLink.getAttribute('data-oldzip');
        }
        zipField.setAttribute('disabled',true);
        changeSpan.classList.remove('hide');
        saveCancelSpan.classList.add('hide');
        changeSpan.setAttribute('aria-hidden','false');
        saveCancelSpan.setAttribute('aria-hidden','true');
    };
    cancelLink.onclick = function(){
        zipField.value = cancelLink.getAttribute('data-oldzip');
        zipField.setAttribute('disabled',true);
        changeSpan.classList.remove('hide');
        saveCancelSpan.classList.add('hide');
        changeSpan.setAttribute('aria-hidden','false');
        saveCancelSpan.setAttribute('aria-hidden','true');
    };
}

function loadSubbrandOpts(){
    var results;
    var category = document.getElementById('updatePage').getAttribute('data-category-id');
    var brandSelect = document.getElementById('brandSelect');
    var brand = brandSelect.options[brandSelect.options.selectedIndex].value;
    if(main.hasAttribute('data-subbrand-id')){
        var subbrandID = main.getAttribute('data-subbrand-id');
    }
    var xml = new XMLHttpRequest();
    xml.onload = function(){
        if(this.status == 200){
            results = JSON.parse(this.responseText);
            var selectEl = document.getElementById('subbrandSelect');
            if(selectEl.options.length > 1) {
                for(var i = 1; i < selectEl.options.length; i++){
                    selectEl.removeChild(selectEl.options[i]);
                }
            }
            if(results.length > 0){
                for(var opt in results){
                    var newOpt = document.createElement('option');
                    newOpt.setAttribute('value',results[opt]['subbrand_id']);
                    if(newOpt.value==subbrandID){
                        newOpt.setAttribute('selected',true);
                    }
                    var newOptName = document.createTextNode(results[opt]['subbrand_name']);
                    newOpt.appendChild(newOptName);
                    selectEl.appendChild(newOpt);
                }
                selectEl.removeAttribute('disabled');
                // selectEl.setAttribute('required',true); 
            } else {
                selectEl.setAttribute('disabled','true');
            }
        }
    };
    xml.open('GET','./assets/ajax.php?categoryID='+category+'&brandID='+brand+'&task=subbrands',true);
    xml.send();
}

function loadStoreLocations(e){
    var goodResults = [];
    var resultAddresses = [];
    var results;
    var allInfo;
    var addressBlocks = [];

    var storeName = document.getElementById('storeName').options[document.getElementById('storeName').selectedIndex].text.toLowerCase().replace(' ','%20');
    var xml = new XMLHttpRequest();
    var currentZip;
    var modal = false;
    if(document.getElementById('zipModal').classList.contains('show')){
        modal = true;
    }
    if(modal){
        currentZip = document.getElementById('modalZip').value;
    } else {
        currentZip = document.getElementById('updatePage').getAttribute('data-current-zip');
    }
    var box = document.getElementById('locationOpts');

    xml.onload = function(){
        if(this.status == 200){
            allInfo = JSON.parse(this.responseText);
            results = allInfo['results'];
            for(var i=0; i<results.length; i++){
                if(results[i]['types'].indexOf('supermarket')>=0){
                    goodResults.push(results[i]);
                } 
            }
            for(var i=0; i<goodResults.length; i++){
                var addressParts = goodResults[i]['formatted_address'].split(',');
                
                if(addressParts.length==4){                    
                    var ln2 = '';
                    var city = addressParts[1].trim();
                    var stZip = addressParts[2].trim().split(' ');
                } else if(addressParts.length==5) {
                    var ln2 = addressParts[1].trim();
                    var city = addressParts[2].trim();
                    var stZip = addressParts[3].trim().split(' ');
                }
                var ln1 = addressParts[0].trim();
                var state = stZip[0];
                var zip = stZip[1];
                resultAddresses.push({'line1':ln1, 'line2':ln2, 'city':city, 'state':state, 'zip':zip});
                var addressBlock = ln1+'\n';
                if(ln2.length>0){
                    addressBlock += (ln2+'\n');
                }
                addressBlock +=city+', '+state+' '+zip;
                addressBlocks.push(addressBlock);
            }
            if(modal){
                var locOptUl = document.querySelector('div#locationOpts ul');
                while(locOptUl.hasChildNodes()){
                    locOptUl.removeChild(locOptUl.firstChild);
                }
            }
            var locationsUl = document.querySelector('div#locationOpts ul');     
            for(var i=0; i<addressBlocks.length; i++){
                var locationItem = document.createElement('li');
                var optionTest = document.createElement('option');
                if(resultAddresses[i]['line2']==''){
                    locationItem.setAttribute('data-rows','2');
                } else {
                    locationItem.setAttribute('data-rows','3');
                }
                locationItem.setAttribute('data-zip',resultAddresses[i]['zip']);     
                optionTest.value = resultAddresses[i]['zip'];                     
                locationItem.innerText = addressBlocks[i];
                locationItem.addEventListener('click',locationSelect);
                locationItem.addEventListener('click',function(){
                    document.getElementById('locationOpts').classList.add('hide');
                });
                locationsUl.appendChild(locationItem);
            }
            var zipNotice = document.createElement('li');
            zipNotice.innerHTML = 'Current zip: '+currentZip+' <a href="#" id="changeZip">Change Zip</a>';
            zipNotice.id = 'currentZip';
            if(currentZip.length > 0){
                document.getElementById('curZipSpan').innerText = currentZip;
            }
            locationsUl.appendChild(zipNotice);
            box.appendChild(locationsUl);
        }
        document.getElementById('changeZip').addEventListener('click',modalView);
        document.getElementById('changeZip').addEventListener('click',function(){
            document.getElementById('locationOpts').classList.add('hide');
        });
    }    
    xml.open('GET','https://maps.googleapis.com/maps/api/place/textsearch/json?radius=24135&key='+googleKey+'&location=36.136091,-78.458099+&query='+storeName,true);
    xml.send();
}

function revealLocations(){
    var field = document.getElementById('storeLocation');
    var box = document.getElementById('locationOpts');
    box.classList.toggle('hide');
    var boxW = field.offsetWidth;
    var boxT = field.getAttribute('data-init-y') - box.offsetHeight;
    var boxL = field.getBoundingClientRect().x;
    var innerBox = document.querySelector("div#locationOpts ul");
    box.style = 'width: '+boxW+'px; top:'+boxT+'px; left:'+boxL+'px;';
    box.classList.remove('hide');
    innerBox.scrollTop = 0;
}

function locationSelect(e){
    var locOpts = e.target.parentElement.parentElement;
    locOpts.removeAttribute('style');
    locOpts.classList.toggle('hide');
    var field = document.getElementById('storeLocation');
    if(field.hasAttribute('class')){
        field.removeAttribute('class');
    }                
    var zip = e.target.getAttribute('data-zip');
    var rows = parseInt(e.target.getAttribute('data-rows'))+1;
    var locName = e.target.innerText;
    locName = locName.replace('     ',String.fromCharCode(13));
    if(rows == 2 || !rows){
        var lineClass = 'twoLine';
    } else if (rows == 3) {
        var lineClass = 'threeLine';
    } else {
        var lineClass = 'multiLine';
    }
    field.setAttribute('class',lineClass);
    field.setAttribute('rows',rows);
    field.innerHTML = locName;
    document.getElementById('storeZip').value = zip;
}

function modalView(){
    document.getElementById('zipModal').classList.toggle('hide');
    document.getElementById('zipModal').classList.toggle('show');
}

// loadStoreLocations 2.0

function getZipCoords(){

}

function loadStoreLocations2(e) {
    var locOptUl = document.querySelector('div#locationOpts ul');
      while (locOptUl.hasChildNodes()) {
        locOptUl.removeChild(locOptUl.firstChild);
      }
    var prevSelectedLocation = document.getElementById('storeLocation');
    while(prevSelectedLocation.hasChildNodes()){
        prevSelectedLocation.removeChild(prevSelectedLocation.firstChild);
    }
    var zip;
    var modal = false;
    if (document.getElementById('zipModal').classList.contains('show')) {
        modal = true;
    }
    if (modal) {
        zip = document.getElementById('modalZip').value;
    } else {
        zip = document.getElementById('updatePage').getAttribute('data-current-zip');
    }
    var latLonXML = new XMLHttpRequest();
    latLonXML.onload = function () {
        if (this.status == 200) {            
            var result = JSON.parse(latLonXML.responseText);
            
            // items needed for location result function
            // need latitude, longitude, storeName, search radius
            var coords = {lat: result['lat'], lon: result['lon']};
            var theStoreName = document.getElementById('storeName').options[document.getElementById('storeName').selectedIndex].text.toLowerCase();

            placeMatches(coords.lat, coords.lon, theStoreName, '24135');           
          
        }
    }
    latLonXML.open('GET', './includes/zipLatLon.php?zip=' + zip, true);
    latLonXML.send();
}

function toggleNav(){
    document.getElementsByTagName('nav')[0].classList.toggle('collapsed');
    document.getElementsByTagName('nav')[0].classList.toggle('displayed');
    document.getElementById('navModal').classList.toggle('collapsed');
    document.getElementById('navModal').classList.toggle('displayed');
    if(document.getElementById('navModal').hasAttribute('style')){
        document.getElementById('navModal').removeAttribute('style');
    } else {
        document.getElementById('navModal').style.height = document.body.offsetHeight+'px';
    }    
}