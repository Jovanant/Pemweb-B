// data rekomendasi nama (nama - NIM)
var data=[
 {n:"Ahmad Fauzi",nim:"5115100001"},{n:"Andi Pratama",nim:"5115100002"},
 {n:"Anisah Putri",nim:"5115100003"},{n:"Budi Santoso",nim:"5115100004"},
 {n:"Bayu Adjie",nim:"5115100005"},{n:"Citra Lestari",nim:"5115100006"},
 {n:"Dewi Mardani",nim:"5115100007"},{n:"Dimas Aryo",nim:"5115100008"},
 {n:"Eka Putri",nim:"5115100009"},{n:"Fajar Nugroho",nim:"5115100010"},
 {n:"Farza Nurifan",nim:"5115100011"},{n:"Hendry Wiranto",nim:"5115100012"},
 {n:"Rizky Maulana",nim:"5115100013"},{n:"Siti Aisyah",nim:"5115100014"}
];
var nama=document.getElementById("nama"), saran=document.getElementById("saran");

// saat user mengetik huruf -> muncul rekomendasi nama yang cocok
nama.addEventListener("input",function(){
 var k=nama.value.trim().toLowerCase();
 saran.innerHTML="";
 if(k===""){saran.style.display="none";return;}
 var cocok=data.filter(function(d){return d.n.toLowerCase().indexOf(k)===0;});
 if(cocok.length===0){saran.style.display="none";return;}
 cocok.forEach(function(d){
  var el=document.createElement("div");
  el.textContent=d.n+" ("+d.nim+")";
  el.onclick=function(){nama.value=d.n;document.getElementById("nim").value=d.nim;saran.style.display="none";};
  saran.appendChild(el);
 });
 saran.style.display="block";
});
document.addEventListener("click",function(e){if(e.target!==nama)saran.style.display="none";});

document.getElementById("f").addEventListener("submit",function(e){
 e.preventDefault();
 var err=document.getElementById("err"),ok=document.getElementById("ok");
 ok.textContent="";
 if(!nama.value.trim()||!document.getElementById("nim").value.trim()||!document.getElementById("mk").value||!document.getElementById("dosen").value){
  err.textContent="Semua kolom wajib diisi.";return;
 }
 err.textContent="";
 ok.textContent="Registrasi berhasil: "+nama.value+" ("+document.getElementById("nim").value+") - "+document.getElementById("mk").value+" - "+document.getElementById("dosen").value;
});
document.getElementById("reset").onclick=function(){saran.style.display="none";document.getElementById("err").textContent="";document.getElementById("ok").textContent="";};
