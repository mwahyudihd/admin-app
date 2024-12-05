
const showAlert = (title, message, icon, confirmBtn = true) => Swal.fire({
    title: title,
    text: message,
    icon: icon,
    showConfirmButton: confirmBtn
});
const setRemove = (id, ) => Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!"
}).then((result) => {
    if (result.isConfirmed) {
        fetch(`http://localhost/admin-ulbi/controllers/c_delete_user.php?id_user=${id}`, {
            method: 'GET', // Menggunakan method GET
        })
        .then(response => response.json())
        .then(data => {
            if (data.status == 200) {
                showAlert("Deleted!", "Your user data has been deleted.", "success", false);
                setTimeout(() => {
                    window.location.href = 'http://localhost/admin-ulbi/data_users.php';
                }, 1000);
            } else {
                showAlert("Error!", "There was an error deleting the user.", "error");
            }
        })
        .catch(error => {
            showAlert("Error!", "There was an error with the request.", "error")
        });
    }
});