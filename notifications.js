function showToast(message, type) {
    const toast = document.getElementById("toast");
    toast.innerText = message;
    toast.style.display = "block";
    toast.style.backgroundColor = (type === 'error') ? "#e74c3c" : "#27ae60";
    
    setTimeout(() => { toast.style.display = "none"; }, 4000);
}

window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.has('msg')) {
        showToast("✅ " + urlParams.get('msg'), 'success');
    }
    if(urlParams.has('error')) {
        const err = urlParams.get('error');
        let errMsg = "An error occurred.";
        if (err === 'user_exists') errMsg = "Error: Username is already registered!";
        if (err === 'db_error') errMsg = "Database error occurred!";
        showToast("🚨 " + errMsg, 'error');
    }
}