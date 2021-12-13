<?php
namespace App\Http\Modules\Sales\Models;
use App\Models\CoreModel;

class SalesDetail extends CoreModel{
	protected $primaryKey = "id_detail";
    protected $guarded = [];
    public $incrementing = true;

    public function item(){
        return $this->belongsTo(MODULE_PATH.'\Item\Models\Item', 'id_item', 'id_item');
    }

    public function __construct(){
    	parent::__construct();
    	$this->table = $this->tb_sales_detail;
    }

    public function get_detail($kode_transaksi){
        return SalesDetail::where('kode_transaksi', $kode_transaksi)->with('item')->get();
    }
}