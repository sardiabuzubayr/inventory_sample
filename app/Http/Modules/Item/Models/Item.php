<?php
namespace App\Http\Modules\Item\Models;
use App\Models\CoreModel;

class Item extends CoreModel{
	protected $primaryKey = "id_item";
    protected $guarded = [];
    public $incrementing = true;

    public function __construct(){
    	parent::__construct();
    	$this->table = $this->tb_item;
    }

    public function get_all($keywords, $limit, $page){
        $offset = ($limit * $page) - $limit;
		if($offset <= 0)
			$offset = 0;

        $query = Item::where('nama_item', 'LIKE', "%$keywords%");
        $data = $query->limit($limit)
            ->offset($offset)
            ->orderBy('nama_item', 'asc')
            ->get();
        $total = $query->count();
		
        return array(
			'data'=>$data,
			'total'=>$total
		);
	}

    public function get_one($id_item){
        return Item::where('id_item', $id_item)->first();
    }

    public function save_new_item($data){
        return $this->do_insert($this, $data);
    }

    public function save_edit_item($data, $id_item){
        return $this->do_update($this, $data, ['id_item'=>$id_item]);
    }

    public function delete_item($id_item){
        return $this->do_delete($this, ['id_item'=>$id_item]);
    }
}