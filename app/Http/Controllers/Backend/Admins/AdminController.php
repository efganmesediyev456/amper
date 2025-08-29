<?php

namespace App\Http\Controllers\Backend\Admins;

use App\DataTables\AdminsDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileUploadHelper;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = Admin::class;
    }

    public function index(AdminsDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.admins.index');
    }

    public function create(){
        return view('backend.pages.admins.create');
    }

    public function store(Request $request){
        try {
            $item = new Admin();
            DB::beginTransaction();
            $data = $request->except('_token','_method');
            
            // Handle password
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
                unset($data['password_confirmation']);
                $data['email_verified_at']=now();
            }
            
            // Handle avatar upload if present
            if ($request->hasFile('avatar')) {
                $data['avatar'] = FileUploadHelper::uploadFile($request->file('avatar'), 'admins/avatars');
            }
            
            $item = $this->mainService->save($item, $data);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.admins.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function edit(Admin $item){
        return view('backend.pages.admins.edit', compact('item'));
    }

    public function update(Request $request, Admin $item){
        try {
            DB::beginTransaction();
            $data = $request->except('_token','_method', 'password_confirmation');
            
            // Handle password (only update if provided)
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']); // Don't update password if not provided
            }
            
            // Handle avatar upload if present
            if ($request->hasFile('avatar')) {
                // Delete old avatar if exists
                if ($item->avatar) {
                    FileUploadHelper::deleteFile($item->avatar);
                }
                $data['avatar'] = FileUploadHelper::uploadFile($request->file('avatar'), 'admins/avatars');
            }

            $data['email_verified_at']=now();

            $item = $this->mainService->save($item, $data);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.admins.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function delete(Admin $item){
        try {
            DB::beginTransaction();
            
            // Delete avatar if exists
            if ($item->avatar) {
                FileUploadHelper::deleteFile($item->avatar);
            }
            
            $item->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Uğurla silindi');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }
}