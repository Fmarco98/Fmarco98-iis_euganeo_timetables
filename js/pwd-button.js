
function getPwdInputs() {
    //Ottengo solo gli input di tipo password
    var arr = [];
    var input = Array.from(document.getElementsByTagName("input"));
    input.forEach(entry => {
        if(entry.type === "password") {
            arr.push(entry)
        }
    });
    return arr;
}

// Liste di bottoni e password
let pwdButtons = Array.from(document.getElementsByClassName("pwd-button"));
let pwdEntries = getPwdInputs();

pwdButtons.forEach(button => {
    //Azione al click dei bottoni password
    button.index = pwdButtons.indexOf(button);
    button.addEventListener("click", (event) => {
        if(!this.classList.contains("active")) {
            pwdEntries[this.index].type = "text";
            this.classList.add("active");
        } else {
            pwdEntries[this.index].type = "password";
            this.classList.remove("active");
        }
    });
});