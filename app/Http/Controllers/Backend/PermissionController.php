<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\PermissionServiceInterface as PermissionService;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Repositories\Interfaces\PermissionRepositoryInterface as PermissionRepository;
use Illuminate\Support\Facades\App;

class PermissionController extends Controller
{

    protected $permissionService;
    protected $permissionRepository;
    public function __construct(
        PermissionService $permissionService,
        PermissionRepository $permissionRepository
    )
    {

        $this->permissionService = $permissionService;
        $this->permissionRepository = $permissionRepository;
        
    }

    public function index(Request $request)
    {

        $permissions = $this->permissionService->paginate($request);


        $config = $this->indexConfig();
  
        $config['seo'] = __('messages.permission');


      
        $template = 'backend.permission.index';

        return view('backend.dashboard.layout', compact('template', 'config', 'permissions'));
    }


    public function create(){


        $config = $this->configData();
        $config['method']= 'create';
        $template = 'backend.permission.store';
        $config['seo'] = __('messages.permission');
        return view('backend.dashboard.layout', compact('template', 'config'));

    }

    public function store(StorePermissionRequest $request){
        
        if($this->permissionService->create()) {
            return redirect()->route('permission.index')->with('success', 'Thêm mới bản ghi thành công');
        }else {
            return redirect()->route('permission.index')->with('error', 'Thêm mới bản ghi không thành công.Hãy thử lại');
 
        }
    }


    public function edit($id) {


        $permission = $this->permissionRepository->edit($id);
  
        $config = $this->configData();

        $config['method']= 'edit';

        $template = 'backend.permission.store';
        $config['seo'] = __('messages.permission');
        return view('backend.dashboard.layout', compact('template', 'config', 'permission'));
       
    }

    public function update(UpdatePermissionRequest $request, $id){
      
        if($this->permissionService->update( $id, $request)) {
            return redirect()->route('permission.index')->with('success', 'Cập nhật bản ghi thành công');
        }else {
            return redirect()->route('permission.index')->with('error', 'Cập nhật bản ghi không thành công.Hãy thử lại');
 
        }

    }

    public function delete($id) {
        $permission = $this->permissionRepository->edit($id);

        
        $template = 'backend.permission.delete';
        $config['seo'] = __('messages.permission');
        return view('backend.dashboard.layout', compact('template', 'config', 'permission'));
    }

    public function destroy($id) {
        if($this->permissionService->delete($id)) {
            return redirect()->route('permission.index')->with('success', 'Xóa bản ghi thành công');
        }else {
            return redirect()->route('permission.index')->with('error', 'Xóa bản ghi không thành công.Hãy thử lại');
 
        }
    }

    private function configData() {
        return [
           
            'js' => [
                'backend/plugin/ckfinder/ckfinder.js',
                'backend/library/finder.js'
            ]
        ];
    }

    private function indexConfig() {
        return [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',

            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'

            ],
            'model' => 'Permission'
        ];
  
    }

   
  
}
