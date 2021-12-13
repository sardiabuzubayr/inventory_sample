<?php
namespace App\Http\Modules\Sales\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Modules\Sales\Models\Sales;
use App\Http\Modules\Sales\Models\SalesDetail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class SalesController extends Controller{
    public function __construct(Request $request){
        $this->request = $request;
        $this->model = new Sales();
    }

    public function get_all($limit, $offset){
        $data = $this->model->get_all($this->request->keywords, $limit, $offset);
        return response()->json([
            'error_code'=>0,
            'data'=>$data
        ]);
    }

    public function get_one($id_item){
        $data = $this->model->get_one($id_item);
        return response()->json([
            'error_code'=>0,
            'data'=>$data
        ]);
    }

    public function save_transaction(){
        $validator = Validator::make($this->request->all(), 
        [
            'id_customer'=>'required',
            'total_diskon'=>'required',
            'total_bayar'=>'required|numeric|gt:0',
            'list_item'=>function($attribute, $value, $fail){
                if(!is_array(json_decode($value, true)))
                    $fail('Item tidak boleh kosong');
            }
        ]);
        if(!$validator->fails()){
            if($this->model->save_transaction($this->request->all())){
                $response = [
                    'error_code'=>0,
                    'message'=>'Success create new transaction'
                ];
            }
            else{
                $response = [
                    'error_code'=>1,
                    'message'=>'Failed to create new transaction, check item stock'
                ];
            }
        }
        else{
            $response = [
                'error_code'=>2,
                'message'=>'Failed to create new transaction',
                'errors'=>$validator->errors()
            ];
        }
        return response()->json($response);
    }

    public function delete_item($kode_transaksi){
       if($this->model->delete_item($kode_transaksi)){
            $response = [
                'error_code'=>0,
                'message'=>'Success delete item'
            ];
        }
        else{
            $response = [
                'error_code'=>1,
                'message'=>'Failed to delete item'
            ];
        }
        
        return response()->json($response);
    }

    public function get_detail($kode_transaksi){
        $detail = new SalesDetail();
        $data = $detail->get_detail($kode_transaksi);
        return response()->json([
            'error_code'=>0,
            'data'=>$data
        ]);
    }
}