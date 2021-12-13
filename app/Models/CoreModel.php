<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CoreModel extends Model{
	// master table
	protected $tb_item 			= "item";
	protected $tb_customer 		= "customer";
	protected $tb_sales 		= "sales";
	protected $tb_sales_detail 	= "sales_detail";
	
	public $incrementing = false;
    protected $guarded  = [];
	public $timestamps = false;

	protected function do_insert($model, $data, $with_bool_return = true){
		try{
			$insert = new $model();
			$insert->fill($data);
			$insert->save();
			if(!$with_bool_return)
				return $insert;
			return true;
		}
		catch(\Exception $e){
			$this->get_error($data, $e);
		}
	}
	
	protected function do_update($model, $data, $params){
		try{
			$model::where($params)
	      		->update($data);
			return true;
		}
		catch(\Exception $e){
			$this->get_error($data, $e);
		}
	}

	protected function do_delete($model, $params){
		try{
			$model::where($params)
	      		->delete();
			return true;
		}
		catch(\Exception $e){
			$this->get_error([], $e);
		}
	}

	protected function do_upsert($model, $data, $uniqueBy, $updateField){
		try{
			$model::upsert($data, $uniqueBy, $updateField);
			return true;
		}
		catch(\Exception $e){
			$this->get_error($data, $e);
		}
	}

	protected function do_order_by($order_by, $DB){
		if(!empty($order_by)){				
			$order_by_string = "";
			foreach ($order_by as $row) {
				if(!empty($row['kolom']))
					$order_by_string.=$row['kolom'].' '.$row['sort_as'].',';
			}
			$order_by_string = substr($order_by_string, 0, strlen($order_by_string)-1);
			$DB->orderByRaw(DB::raw($order_by_string));
		}
	}

	public function get_error(array $data, $error){
		$message = $error->getMessage();
			if(!empty($message)){
				if (strpos($message, 'duplicate key') !== false)
				    $message = 'Terjadi duplikasi data, cek terlebih dahulu';
				
				elseif(strpos($message, 'Cannot add or update a child row') !== false){
					$message = 'Gagal menambah atau mengubah data karena terdapat field yang saling berelasi namun nilai yang dimasukkan diluar data tersebut <br/>'.(env('APP_ENV')=='local'?json_encode($data):'');
				}
				elseif(strpos($message, 'Duplicate entry') !== false){
					$message = 'Gagal menambah atau mengubah data karena data yang dikirim sudah pernah dimasukkan sebelumnya';
				}
				elseif(strpos($message, 'Cannot delete or update a parent row: a foreign key') !== false){
					$message = 'Gagal menghapus karena data sudah digunakan di tabel lain';
				}

				$response = [
					'error_code'=>1,
					'message'=>$message,
					'data'=>$data,
				];

				header('Content-Type: application/json');
				echo json_encode($response);	
				exit();
		}
	}

	protected function clean_filters($filters){
		if(!is_array($filters))
			return;
			
		foreach($filters as $key=>$val){
			if(isset($filters[$key]) && ($filters[$key] == '' || $filters[$key] == -1)){
				unset($filters[$key]);
			}
		}
		return $filters;
	}
}