function fetchUserData(baseUrl) {
    return {
        isLoading: true,
        userCount: null,  
        error: false,     
        errorMessage: '',


        async init() {
            try {
                const response = await fetch(`${baseUrl}api/count_user.php?n_role=Admin`);
                const data = await response.json();

                if (data.statusCode === 200) {
                    this.userCount = data.data; 
                } else {
                    throw new Error(data.message || 'Failed to fetch data');
                }
            } catch (err) {
                this.error = true;
                this.errorMessage = err.message || 'An unknown error occurred';
            } finally {
                this.isLoading = false;
            }
        }
    };
}