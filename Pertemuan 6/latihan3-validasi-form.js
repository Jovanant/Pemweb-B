var emailRegex=/^[^\s@]+@[^\s@]+\.[^\s@]+$/;
function $(id){return document.getElementById(id);}

$("reg").addEventListener("submit",function(e){
 e.preventDefault();
 var valid=true;
 function cek(idErr,kondisi,pesan){$(idErr).textContent=kondisi?"":pesan;if(!kondisi)valid=false;}
 cek("e1",$("name").value.trim()!=="","Name must be filled out");
 cek("e2",$("addr").value.trim()!=="","Address must be filled out");
 cek("e3",emailRegex.test($("mail").value.trim()),"Enter a valid email address");
 cek("e4",$("pass").value.length>=6,"Password must be at least 6 characters");
 cek("e5",$("course").value!=="","Please select a course");
 cek("e6",$("college").value.trim()!=="","College name must be filled out");
 cek("e7",$("agree").checked,"You must agree to the information");
 $("okreg").textContent=valid?"Registration successful!":"";
});
$("rst").onclick=function(){for(var i=1;i<=7;i++)$("e"+i).textContent="";$("okreg").textContent="";};

$("emailForm").addEventListener("submit",function(e){
 e.preventDefault();
 var v=$("email").value.trim();
 if(emailRegex.test(v)){$("eemail").textContent="";$("okemail").textContent="Valid email address!";}
 else{$("okemail").textContent="";$("eemail").textContent="Invalid email address!";}
});
