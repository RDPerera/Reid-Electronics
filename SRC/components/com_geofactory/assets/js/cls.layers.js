function checkBox(a){var b=document.createElement("DIV");b.className="checkboxContainer",b.title=a.title;var c=document.createElement("SPAN");c.role="checkbox",c.className="checkboxSpan";var d=document.createElement("DIV");d.className="blankDiv",d.id=a.id,bDivs.push(a.id);var e=document.createElement("IMG");e.className="blankImg",e.src="http://maps.gstatic.com/mapfiles/mv/imgs8.png","https:"===location.protocol&&(e.src="https://maps.gstatic.com/mapfiles/mv/imgs8.png");var f=document.createElement("LABEL");return f.className="checkboxLabel",f.innerHTML=a.label,d.appendChild(e),c.appendChild(d),b.appendChild(c),b.appendChild(f),google.maps.event.addDomListener(b,"click",function(){document.getElementById(d.id).style.display="block"==document.getElementById(d.id).style.display?"none":"block","block"==document.getElementById(d.id).style.display?layers[a.id].setMap(a.gmap):layers[a.id].setMap(null)}),b}function optionDivCust(a,b){var c;if(b&&"undefined"!=b&&b.length>3)var c=document.getElementById(b);else{var c=document.createElement("DIV");c.className="dropDownItemDiv",c.title=a.title,c.id=a.id,c.innerHTML=a.name,google.maps.event.addDomListener(c,"click",a.action)}return c}function optionDiv(a){var b=document.createElement("DIV");return b.className="dropDownItemDiv",b.title=a.title,b.id=a.id,b.innerHTML=a.name,google.maps.event.addDomListener(b,"click",function(){for(var a in layers)layers[a].setMap(null);for(var a in bDivs)bDivs[a]&&bDivs[a].length>1&&document.getElementById(bDivs[a])&&document.getElementById(bDivs[a]).style&&(document.getElementById(bDivs[a]).style.display="none")}),b}function separator(){var a=document.createElement("DIV");return a.className="separatorDiv",a}function dropDownOptionsDiv(a){var b=document.createElement("DIV");b.className="dropDownOptionsDiv",b.id=a.id;for(var c=0;c<a.items.length;c++)b.appendChild(a.items[c]);return b}function dropDownControl(a){var b=document.createElement("DIV");b.className="gf_container";var c=document.createElement("DIV");c.className="dropDownControl",c.innerHTML=a.name,c.id=a.name;var d=document.createElement("IMG");d.src="http://maps.gstatic.com/mapfiles/arrow-down.png","https:"===location.protocol&&(d.src="https://maps.gstatic.com/mapfiles/arrow-down.png"),d.className="dropDownArrow",c.appendChild(d),b.appendChild(c),b.appendChild(a.dropDown),a.gmap.controls[a.position].push(b),google.maps.event.addDomListener(b,"click",function(){switchPanel("myddOptsDiv",6e3)})}function switchPanel(a,b){"block"==jQuery("#"+a).css("display")?jQuery("#"+a).fadeOut("slow"):jQuery("#"+a).fadeIn("slow"),b>0&&setTimeout(function(){jQuery("#"+a).fadeOut("slow")},b)}var bDivs=[];