function convert(){
    return {
        month: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
        dateConvert(date){
            const isDate = new Date(date);
            const day = isDate.getDate();
            const mon = isDate.getMonth();
            const year = isDate.getFullYear();
            return `${day} ${this.month[mon]} ${year}`;
        }
    }
}