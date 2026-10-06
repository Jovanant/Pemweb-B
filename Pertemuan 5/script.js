const $=id=>document.getElementById(id), PER=5, KEY="its_sms_v3";
const seed=[["5025261003","Andi Pratama","Informatika","andi"],["5025261012","Siti Aisyah","Sistem Informasi","siti"],["5025261027","Budi Santoso","Teknik Komputer","budi"],["5025261034","Nina Marlina","Manajemen","nina"],["5025261041","Rizky Pratama","Informatika","rizky"],["5025261058","Dewi Lestari","Sistem Informasi","dewi"],["5025261063","Fajar Nugroho","Teknik Komputer","fajar"],["5025261076","Intan Permata","Manajemen","intan"],["5025261085","Bagas Wicaksono","Informatika","bagas"],["5025261091","Maya Kusuma","Sistem Informasi","maya"],["5025261104","Hendra Wijaya","Teknik Komputer","hendra"]]
 .map(([nim,nama,jur,e])=>({nim,nama,jur,email:e+"@mail.com"}));
let data=load(), page=1, editing=null;
function load(){try{const s=localStorage.getItem(KEY);if(s)return JSON.parse(s)}catch(e){}return seed.map(x=>({...x}))}
function persist(){try{localStorage.setItem(KEY,JSON.stringify(data))}catch(e){}}
const esc=s=>s.replace(/[&<>"']/g,c=>({"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#39;"}[c]));
const ico=n=>`<svg><use href="#${n}"/></svg>`;

document.querySelectorAll("nav a").forEach(a=>a.onclick=()=>{
  document.querySelectorAll("nav a").forEach(x=>x.classList.toggle("active",x===a));
  document.querySelectorAll(".view").forEach(v=>v.classList.toggle("show",v.id===a.dataset.v));
  $("total").textContent=data.length;
});

function render(){
  const q=$("q").value.trim().toLowerCase();
  const f=data.filter(d=>!q||[d.nim,d.nama,d.jur,d.email].some(v=>v.toLowerCase().includes(q)));
  const pages=Math.max(1,Math.ceil(f.length/PER)); if(page>pages)page=pages;
  const s=(page-1)*PER, rows=f.slice(s,s+PER);
  $("tb").innerHTML=rows.length?rows.map((d,i)=>`<tr><td>${s+i+1}</td><td>${esc(d.nim)}</td><td>${esc(d.nama)}</td><td>${esc(d.jur)}</td><td>${esc(d.email)}</td><td><div class="act"><button class="ic e" title="Edit" onclick="edit('${esc(d.nim)}')">${ico("edit")}</button><button class="ic d" title="Hapus" onclick="del('${esc(d.nim)}')">${ico("trash")}</button></div></td></tr>`).join(""):`<tr><td colspan="6" class="empty">Data tidak ditemukan</td></tr>`;
  let p=`<button ${page===1?"disabled":""} onclick="go(${page-1})">«</button>`;
  for(let i=1;i<=pages;i++)p+=`<button class="${i===page?"on":""}" onclick="go(${i})">${i}</button>`;
  p+=`<button ${page===pages?"disabled":""} onclick="go(${page+1})">»</button>`;
  $("pager").innerHTML=p;
}
function go(n){page=n;render()}
$("q").oninput=()=>{page=1;render()};

function clearErr(){["eNim","eNama","eJur","eEmail"].forEach(i=>$(i).textContent="")}
function validate(){
  clearErr();let ok=true;
  const nim=$("nim").value.trim(),nama=$("nama").value.trim(),jur=$("jur").value,email=$("email").value.trim();
  if(!/^\d{10}$/.test(nim)){$("eNim").textContent="NRP harus berupa 10 angka";ok=false}
  else if(data.some(d=>d.nim===nim&&d.nim!==editing)){$("eNim").textContent="NRP sudah terdaftar";ok=false}
  if(nama.length<3){$("eNama").textContent="Nama minimal 3 karakter";ok=false}
  if(!jur){$("eJur").textContent="Pilih jurusan";ok=false}
  if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)){$("eEmail").textContent="Format email tidak valid";ok=false}
  return ok?{nim,nama,jur,email}:null;
}
function resetForm(){["nim","nama","email"].forEach(i=>$(i).value="");$("jur").value="";editing=null;clearErr();$("formTitle").textContent="Form Student"}
$("save").onclick=()=>{
  const v=validate();if(!v)return;
  if(editing){data[data.findIndex(d=>d.nim===editing)]=v}
  else{data.push(v);page=Math.ceil(data.length/PER)}
  persist();resetForm();$("q").value="";render();
};
$("cancel").onclick=resetForm; $("reset").onclick=resetForm;
function edit(nim){
  const d=data.find(x=>x.nim===nim);if(!d)return;
  editing=nim;$("nim").value=d.nim;$("nama").value=d.nama;$("jur").value=d.jur;$("email").value=d.email;
  $("formTitle").textContent="Edit Student";clearErr();window.scrollTo({top:0,behavior:"smooth"});
}
function del(nim){
  const d=data.find(x=>x.nim===nim);
  if(d&&confirm("Hapus data "+d.nama+"?")){data=data.filter(x=>x.nim!==nim);persist();if(editing===nim)resetForm();render()}
}
render();
