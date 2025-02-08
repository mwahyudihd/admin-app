function redirect(){
    return {
        base: 'http://localhost/admin-ulbi/',
        getTo(to){
            window.location.replace(`${this.base}${to}`);
        }
    }
}