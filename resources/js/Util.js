import Moment from 'moment'

var func = {
    methods:{            
        tgl_normal(tgl){
            return Moment(tgl).format("YYYY-MM-DD");
        },
        tgl_indo(tgl){
            return Moment(tgl).format("DD-MM-YYYY");
        },
        MyDate(tgl){
            var dates = Moment
            dates.updateLocale('en', {
                weekdays : [
                    "Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu"
                ],
                months : [
                    "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli",
                    "Agustus", "September", "Oktober", "November", "Desember"
                ],

            });
            return dates(tgl);
        },
        dateLong(tgl){
            if(tgl)
                return this.MyDate(tgl).format("DD MMMM YYYY - H:mm:ss");
            return "-"
        },
        moneyFormat(amount){
            return amount.toLocaleString('in-ID', { style: 'currency', currency: 'IDR' })
        }
    }
}

export default func
