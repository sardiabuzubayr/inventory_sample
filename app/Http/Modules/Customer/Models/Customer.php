<?php
namespace App\Http\Modules\Customer\Models;
use App\Models\CoreModel;

class Customer extends CoreModel{
	protected $primaryKey = "id_customer";
    protected $guarded = [];
    public $incrementing = true;

    public function __construct(){
    	parent::__construct();
    	$this->table = $this->tb_customer;
    }

    public function get_all($keywords, $limit, $page){
        $offset = ($limit * $page) - $limit;
		if($offset <= 0)
			$offset = 0;

        $query = Customer::where('nama', 'LIKE', "%$keywords%");
        $data = $query->limit($limit)
            ->offset($offset)
            ->orderBy('nama', 'asc')
            ->get();
        $total = $query->count();
		
        return array(
			'data'=>$data,
			'total'=>$total
		);
	}

    public function get_one($id_customer){
        return Customer::where('id_customer', $id_customer)->first();
    }

    public function save_new_item($data){
        return $this->do_insert($this, $data);
    }

    public function save_edit_item($data, $id_customer){
        return $this->do_update($this, $data, ['id_customer'=>$id_customer]);
    }

    public function delete_item($id_customer){
        return $this->do_delete($this, ['id_customer'=>$id_customer]);
    }

    public function list_customer(){
        return Customer::orderBy('nama', 'desc')->get();
    }
}