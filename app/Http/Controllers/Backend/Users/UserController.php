<?php

namespace App\Http\Controllers\Backend\Users;

use App\DataTables\UsersDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileUploadHelper;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = User::class;
    }

    public function index(UsersDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.users.index');
    }

    public function create(){
        return view('backend.pages.users.create');
    }

    public function store(Request $request){
        try {
            $item = new User();
            DB::beginTransaction();
            $data = $request->except('_token','_method');
            
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
                unset($data['password_confirmation']);
                $data['email_verified_at']=now();
            }
           
          
            
            $item = $this->mainService->save($item, $data);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.users.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function edit(User $item){
        return view('backend.pages.users.edit', compact('item'));
    }

    public function update(Request $request, User $item){
        try {
            DB::beginTransaction();
            $data = $request->except('_token','_method', 'password_confirmation');
            
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $data['email_verified_at']=now();

            $item = $this->mainService->save($item, $data);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.users.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function delete(User $item){
        try {
            DB::beginTransaction();
            
            if ($item->avatar) {
                FileUploadHelper::deleteFile($item->avatar);
            }
            $item->notifications()->delete();
            $item->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Uğurla silindi');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }
}