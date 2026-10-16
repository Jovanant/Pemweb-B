var data={
 "Jawa Timur":["Surabaya","Malang","Sidoarjo","Gresik","Kediri"],
 "Jawa Tengah":["Semarang","Solo","Magelang","Tegal"],
 "Jawa Barat":["Bandung","Bogor","Bekasi","Cirebon"],
 "DKI Jakarta":["Jakarta Pusat","Jakarta Selatan","Jakarta Barat","Jakarta Timur","Jakarta Utara"],
 "Bali":["Denpasar","Badung","Gianyar","Tabanan"]
};
var prov=document.getElementById("prov"),kota=document.getElementById("kota"),out=document.getElementById("out");
Object.keys(data).forEach(function(p){var o=document.createElement("option");o.value=o.textContent=p;prov.appendChild(o);});

prov.onchange=function(){
 kota.innerHTML='<option value="">-- Pilih Kota --</option>';
 out.textContent="";
 if(!prov.value){kota.disabled=true;return;}
 data[prov.value].forEach(function(k){var o=document.createElement("option");o.value=o.textContent=k;kota.appendChild(o);});
 kota.disabled=false;
};
kota.onchange=function(){out.textContent=kota.value?"Anda memilih: "+kota.value+", "+prov.value:"";};
