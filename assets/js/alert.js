
const showAlert = (title, message, icon, confirmBtn = true) => Swal.fire({
    title: title,
    text: message,
    icon: icon,
    showConfirmButton: confirmBtn
});

const confirmAlert = (icon, title, message, isConfirm, isNotConfirm = "") => Swal.fire({
  title: `${title}`,
  icon: `${icon}`,
  text: `${message}`,
  showCancelButton: true,
  confirmButtonText: "Logout"
}).then((result) => {
  /* Read more about isConfirmed, isDenied below */
  if (result.isConfirmed) {
    Swal.fire("logout!", "", "success").then(() => {
        return isConfirm();
    });
  } else {
    isNotConfirm();
  }
});

const setRemove = (id, base) => Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!"
}).then((result) => {
    if (result.isConfirmed) {
        fetch(`${base}api/delete_user.php?id_user=${id}`, {
            method: 'GET', // Menggunakan method GET
        })
        .then(response => response.json())
        .then(data => {
            if (data.status == 200) {
                sessionStorage.setItem('status', 'deleted');
                showAlert("Deleted!", "Your user data has been deleted.", "success", false);
                setTimeout(() => {
                    window.location.href = `${base}data_users.php`;
                }, 1000);
            } else {
              sessionStorage.setItem("status", "error");
              showAlert("Error!", "There was an error deleting the user.", "error");
            }
        })
        .catch(error => {
          sessionStorage.setItem("status", "error");
          showAlert("Error!", "There was an error with the request.", "error")
        });
    }
});