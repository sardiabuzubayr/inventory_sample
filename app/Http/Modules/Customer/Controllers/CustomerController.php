<?php
namespace App\Http\Modules\Customer\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Modules\Customer\Models\Customer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CustomerController extends Controller{
    public function __construct(Request $request){
        $this->request = $request;
        $this->model = new Customer();
    }

    public function get_all($limit, $offset){
        $data = $this->model->get_all($this->request->keywords, $limit, $offset);
        return response()->json([
            'error_code'=>0,
            'data'=>$data
        ]);
    }

    public function get_one($id_customer){
        $data = $this->model->get_one($id_customer);
        return response()->json([
            'error_code'=>0,
            'data'=>$data
        ]);
    }

    public function new_item(){
        $validator = Validator::make($this->request->all(), 
        [
            'nama'=>'required',
            'contact'=>'required',
            'alamat'=>'required'
        ]);
        if(!$validator->fails()){
            $fileName = null;
            if($this->request->hasFile('ktp')){
                $file = $this->request->file("ktp");
                $extension = $this->request->file('ktp')->getClientOriginalExtension();
                $fileName = uniqid().'.'.$extension;
                $path = public_path().'/image/customer';
                if(!file_exists($path))
                    mkdir($path);
                if($file->move($path, $fileName)){
                    $data = [
                        'nama'=>$this->request->nama,
                        'contact'=>$this->request->contact,
                        'alamat'=>$this->request->alamat,
                        'diskon'=>$this->request->diskon,
                        'tipe_diskon'=>$this->request->tipe_diskon,
                        'ktp'=>$fileName,
                    ];
                }
            }
            else{
                $data = [
                    'nama'=>$this->request->nama,
                    'contact'=>$this->request->contact,
                    'alamat'=>$this->request->alamat,
                    'diskon'=>$this->request->diskon,
                    'tipe_diskon'=>$this->request->tipe_diskon,
                ];
            }
            if($this->model->save_new_item($data)){
                $response = [
                    'error_code'=>0,
                    'message'=>'Success create new customer'
                ];
            }
            else{
                $response = [
                    'error_code'=>1,
                    'message'=>'Failed to create new customer'
                ];
            }
        }
        else{
            $response = [
                'error_code'=>2,
                'message'=>'Failed to create new customer',
                'errors'=>$validator->errors()
            ];
        }
        return response()->json($response);
    }

    public function edit_item($id_customer){
        $validator = Validator::make($this->request->all(), 
        [
            'nama'=>'required',
            'contact'=>'required',
            'alamat'=>'required'
        ]);
        if(!$validator->fails()){
            if($this->model->save_edit_item($this->request->all(), $id_customer)){
                $response = [
                    'error_code'=>0,
                    'message'=>'Success update customer'
                ];
            }
            else{
                $response = [
                    'error_code'=>1,
                    'message'=>'Failed to update customer'
                ];
            }
        }
        else{
            $response = [
                'error_code'=>2,
                'message'=>'Failed to update customer',
                'errors'=>$validator->errors()
            ];
        }
        return response()->json($response);
    }

    public function update_gambar(){
        $fileName = null;
        if($this->request->hasFile('ktp')){
            $file = $this->request->file("ktp");
            $extension = $this->request->file('ktp')->getClientOriginalExtension();
            $fileName = uniqid().'.'.$extension;
            $path = public_path().'/image/customer';
            if(!file_exists($path))
                mkdir($path);
            if($file->move($path, $fileName)){
                $this->model->save_edit_item(['ktp'=>$fileName], $this->request->id_customer);                
                return response()->json([
                    'error_code'=>0,
                    'message'=>'Sukses update gambar ktp'
                ]);
            }
        }
    }

    public function delete_item($id_customer){
       if($this->model->delete_item($id_customer)){
            $response = [
                'error_code'=>0,
                'message'=>'Success delete customer'
            ];
        }
        else{
            $response = [
                'error_code'=>1,
                'message'=>'Failed to delete customer'
            ];
        }
        
        return response()->json($response);
    }

    public function list_customer(){
        $list = $this->model->list_customer();
        return response()->json([
            'error_code'=>0,
            'data'=>$list
        ]);
    }
}