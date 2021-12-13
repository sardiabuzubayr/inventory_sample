<?php
namespace App\Http\Modules\Item\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Modules\Item\Models\Item;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ItemController extends Controller{
    public function __construct(Request $request){
        $this->request = $request;
        $this->model = new Item();
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

    public function new_item(){
        $validator = Validator::make($this->request->all(), 
        [
            'nama_item'=>'required',
            'unit'=>'required',
            'stok'=>'required|numeric|gt:0',
            'harga_satuan'=>'required|numeric|gt:0'
        ]);
        if(!$validator->fails()){
            // barang barang
            $fileName = null;
            if($this->request->hasFile('barang')){
                $file = $this->request->file("barang");
                $extension = $this->request->file('barang')->getClientOriginalExtension();
                $fileName = uniqid().'.'.$extension;
                $path = public_path().'/image/item';
                if(!file_exists($path))
                    mkdir($path);
                if($file->move($path, $fileName)){
                    $data = [
                        'nama_item'=>$this->request->nama_item,
                        'unit'=>$this->request->unit,
                        'stok'=>$this->request->stok,
                        'harga_satuan'=>$this->request->harga_satuan,
                        'barang'=>$fileName,
                    ];
                }
            }
            else{
                $data = [
                    'nama_item'=>$this->request->nama_item,
                    'unit'=>$this->request->unit,
                    'stok'=>$this->request->stok,
                    'harga_satuan'=>$this->request->harga_satuan
                ];
            }
            if($this->model->save_new_item($data)){
                $response = [
                    'error_code'=>0,
                    'message'=>'Success create new item'
                ];
            }
            else{
                $response = [
                    'error_code'=>1,
                    'message'=>'Failed to create new item'
                ];
            }
        }
        else{
            $response = [
                'error_code'=>2,
                'message'=>'Failed to create new item',
                'errors'=>$validator->errors()
            ];
        }
        return response()->json($response);
    }

    public function edit_item($id_item){
        $validator = Validator::make($this->request->all(), 
        [
            'nama_item'=>'required',
            'unit'=>'required',
            'stok'=>'required|numeric|gt:0',
            'harga_satuan'=>'required|numeric|gt:0'
        ]);
        if(!$validator->fails()){
            if($this->model->save_edit_item($this->request->all(), $id_item)){
                $response = [
                    'error_code'=>0,
                    'message'=>'Success update item'
                ];
            }
            else{
                $response = [
                    'error_code'=>1,
                    'message'=>'Failed to update item'
                ];
            }
        }
        else{
            $response = [
                'error_code'=>2,
                'message'=>'Failed to update item',
                'errors'=>$validator->errors()
            ];
        }
        return response()->json($response);
    }

    public function update_gambar(){
        $fileName = null;
        if($this->request->hasFile('barang')){
            $file = $this->request->file("barang");
            $extension = $this->request->file('barang')->getClientOriginalExtension();
            $fileName = uniqid().'.'.$extension;
            $path = public_path().'/image/item';
            if(!file_exists($path))
                mkdir($path);
            if($file->move($path, $fileName)){
                $data = [
                    'barang'=>$fileName,
                ];
                $this->model->save_edit_item(['barang'=>$fileName], $this->request->id_item);                
                return response()->json([
                    'error_code'=>0,
                    'message'=>'Sukses update gambar barang'
                ]);
            }
        }
    }

    public function delete_item($id_item){
       if($this->model->delete_item($id_item)){
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

    public function search($keywords){
        $data = $this->model->get_all($keywords, 10, 0);
        return response()->json([
            'error_code'=>0,
            'data'=>$data['data']
        ]);
    }
}