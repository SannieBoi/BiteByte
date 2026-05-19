const input=document.getElementById("photo");
const fileName=document.getElementById("file_name");
const upload=document.getElementById("photo_label");

const MAX_SIZE=2*1024*1024;

input.addEventListener("change",()=>{
    const file=input.files[0];

    if(!file){
        fileName.textContent="No file chosen";
        upload.style.backgroundImage="none";
        return;
    }

    if(file.size>MAX_SIZE){
        alert("File too large (max 2MB)");
        input.value="";
        fileName.textContent="No file chosen";
        upload.style.backgroundImage="none";
        return;
    }

    fileName.textContent=file.name;

    const reader=new FileReader();
    reader.onload=e=>{
        const url=e.target.result;

        upload.style.backgroundImage="url("+url+")";
        upload.style.backgroundSize="cover";
        upload.style.backgroundPosition="center";

        document.getElementById("upload_text").style.display="none";
        fileName.style.display="none";
    };

    reader.readAsDataURL(file);
});