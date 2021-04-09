const form = document.querySelector(".typing-area"),
inputField = form.querySelector(".input-field"),
sendBtn = form.querySelector("button"),
chatBox = document.querySelector(".chat-box");

form.onsubmit = (e) => {
    e.preventDefault(); //preventing from submitting form
}

sendBtn.onclick = () => {
    let xhr = new XMLHttpRequest(); //creating XML object
    xhr.open("POST", "php/insert-chat.php", true);
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE){
            if (xhr.status === 200){
                inputField.value = ""; //once message is inserted into database leave blank input field
                scrollToBottom();
            }
        }
    }
    //we have to send form data through ajax to php
    let formData  = new FormData(form); //new formData object
    xhr.send(formData); //sending formData to php
}

chatBox.onmouseenter = () => {
    chatBox.classList.add("active");
}
chatBox.onmouseleave = () => {
    chatBox.classList.remove("active");
}

setInterval(() => {
    //AJAX

    let xhr = new XMLHttpRequest(); //creating XML object
    xhr.open("POST", "php/get-chat.php", true);
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE){
            if (xhr.status === 200){
                let data = xhr.response;
                chatBox.innerHTML = data;
                if (!chatBox.classList.contains("active")){ //if active class dont contain the scroll to bottom
                    scrollToBottom();
                }
            }
        }
    }
    //we have to send form data through ajax to php
    let formData  = new FormData(form); //new formData object
    xhr.send(formData); //sending formData to php
},500); //this function will run frequently after 500ms

function scrollToBottom() {
    chatBox.scrollTop = chatBox.scrollHeight;
}