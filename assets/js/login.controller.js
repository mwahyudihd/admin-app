function loginForm(baseUrl) {
    return {
        email: '',
        password: '',
        rememberMe: false,
        async submitLogin() {
            try {
                const response = await fetch(`${baseUrl}api/login.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        email: this.email,
                        password: this.password,
                        remember_me: this.rememberMe
                    })
                });

                const data = await response.json();

                // Mengecek status code dari response
                if (!response.ok) {
                    // Jika status bukan 200, simpan error di sessionStorage
                    sessionStorage.setItem('status', 'error');
                    sessionStorage.setItem('msg', `${data.message || 'Tidak ada pesan kesalahan.'}`);
                    // Redirect ke halaman login dengan pesan error
                    return window.location.replace(baseUrl);
                }

                if (data.statusCode == 200 || data.statusCode < 400) {
                    return window.location.replace(`${baseUrl}dashboard.php`);
                } else {
                    sessionStorage.setItem('status', 'error');
                    sessionStorage.setItem('msg', `${data.message}`);
                    return window.location.replace(baseUrl);
                }
            } catch (error) {
                sessionStorage.setItem('status', 'error');
                sessionStorage.setItem('msg', `Terjadi kesalahan: ${error.message}`);
                return window.location.replace(baseUrl);
            }
        }
    };
}