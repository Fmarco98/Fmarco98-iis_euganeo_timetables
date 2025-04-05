let popups_string = localStorage.getItem('popups');
let popups;
        
window.onload = (e) => {
    if(popups_string) {
        popups = JSON.parse(popups_string)
    } else {
        popups = {};
    }

    for(k in popups) {
        if(popups[k]) {
            try {
                popUpShow(k);
            } catch(e) {}
        }
    }
}

function popUpShow(id) {
    popups[id] = true;
    document.getElementById(id).style.display = 'flex';
    localStorage.setItem('popups', JSON.stringify(popups));
}

function popUpHide(id) {
    popups[id] = false;
    document.getElementById(id).style.display = 'none';
    localStorage.setItem('popups', JSON.stringify(popups));
}