<?php
namespace App\Http\Modules\Sales\Models;
use App\Models\CoreModel;
use Exception;
use Illuminate\Support\Facades\DB;

class Sales extends CoreModel{
	protected $primaryKey = "kode_transaksi";
    protected $guarded = [];

    public function __construct(){
    	parent::__construct();
    	$this->table = $this->tb_sales;
    }

    public function customer(){
        return $this->belongsTo(MODULE_PATH.'\Customer\Models\Customer', 'id_customer', 'id_customer');
    }

    public function get_all($keywords, $limit, $page){
        $offset = ($limit * $page) - $limit;
		if($offset <= 0)
			$offset = 0;

        $query = Sales::with(['customer'=>function($q){
            $q->select('id_customer', 'nama', 'tipe_diskon');
        }])
        ->where('kode_transaksi', 'LIKE', "%$keywords%")
        ->orWhereHas('customer', function($q) use($keywords)  {
            $q->where('nama', 'LIKE', "%$keywords%");
        });

        $data = $query->limit($limit)
            ->offset($offset)
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();
        $total = $query->count();
		
        return array(
			'data'=>$data,
			'total'=>$total
		);
	}

    public function save_transaction($data){
        DB::beginTransaction();
        $kode_transaksi = rand(0000000000, 9999999999);
        if($this->do_insert($this, [
            'kode_transaksi'=>$kode_transaksi,
            'id_customer'=>$data['id_customer'],
            'total_bayar'=>$data['total_bayar'],
            'total_diskon'=>$data['total_diskon']
        ])){
            $list_item  = [];
            foreach(json_decode($data['list_item'], true) as $row){
                $list_item[] = [
                    'kode_transaksi'=>$kode_transaksi,
                    'id_item'=>$row['id_item'],
                    'qty'=>$row['qty'],
                    'total_harga'=>$row['qty']*$row['harga_satuan']
                ];
            }
            try{
                $insert = DB::table($this->tb_sales_detail)->insert($list_item);
                DB::commit();
                return $insert;
            }
            catch(Exception $e){
                DB::rollBack();
                return false;
            }
            
        }
        return false;
    }

    public function get_one($kode_transaksi){
        return Sales::where('kode_transaksi', $kode_transaksi)->first();
    }
    
    public function delete_item($kode_transaksi){
        return $this->do_delete($this, ['kode_transaksi'=>$kode_transaksi]);
    }
}