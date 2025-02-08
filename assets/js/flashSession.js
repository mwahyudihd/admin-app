let sessionFlash = sessionStorage.getItem("status");
if (sessionFlash && sessionFlash == "success") {
    showAlert("Berhasil!", "Data berhasil ditambahkan!", "success", false);
    setTimeout(() => {
        sessionStorage.removeItem("status");
    }, 1000);
}

if(sessionFlash && sessionFlash == "deleted"){
    showAlert("Data terhapus!", "Data telah berhasil dihapus!", "success");
    setTimeout(() => {
        sessionStorage.removeItem("status");
    }, 1000);
}

if (sessionFlash && sessionFlash == "error") {
    showAlert("Ops!", "Data gagal dimuat!", "error");
    setTimeout(() => {
        sessionStorage.removeItem("status");
    }, 1000);
}