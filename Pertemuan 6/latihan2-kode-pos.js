// data contoh: provinsi > kab/kota > kecamatan : kode pos
var data={
 "Jawa Timur":{
  "Kota Surabaya":{"Gubeng":"60281","Tegalsari":"60261","Genteng":"60275","Sukolilo":"60111"},
  "Kota Malang":{"Klojen":"65111","Lowokwaru":"65141","Blimbing":"65126"},
  "Kab. Sidoarjo":{"Sidoarjo":"61211","Waru":"61256","Taman":"61257"}
 },
 "DKI Jakarta":{
  "Jakarta Pusat":{"Gambir":"10110","Menteng":"10310"},
  "Jakarta Selatan":{"Tebet":"12810","Kebayoran Baru":"12110"}
 },
 "Jawa Barat":{
  "Kota Bandung":{"Coblong":"40132","Bandung Wetan":"40115","Lengkong":"40261"}
 }
};
var prov=document.getElementById("prov"),kota=document.getElementById("kota"),kec=document.getElementById("kec"),hasil=document.getElementById("hasil");

function isi(sel,items,teks){
 sel.innerHTML='<option value="">'+teks+'</option>';
 items.forEach(function(i){var o=document.createElement("option");o.value=o.textContent=i;sel.appendChild(o);});
 sel.disabled=items.length===0;
}
isi(prov,Object.keys(data),"Pilih Provinsi");

prov.onchange=function(){
 hasil.style.display="none";
 isi(kec,[],"Pilih Kecamatan");
 isi(kota,prov.value?Object.keys(data[prov.value]):[],"Pilih Kabupaten / Kota");
};
kota.onchange=function(){
 hasil.style.display="none";
 isi(kec,kota.value?Object.keys(data[prov.value][kota.value]):[],"Pilih Kecamatan");
};
document.getElementById("cari").onclick=function(){
 if(!prov.value||!kota.value||!kec.value){
  hasil.style.display="block";hasil.innerHTML="Lengkapi Provinsi, Kabupaten/Kota, dan Kecamatan.";return;
 }
 var kp=data[prov.value][kota.value][kec.value];
 hasil.style.display="block";
 hasil.innerHTML="<b>Kode Pos: "+kp+"</b><br>Kecamatan: "+kec.value+"<br>Kabupaten/Kota: "+kota.value+"<br>Provinsi: "+prov.value;
};
