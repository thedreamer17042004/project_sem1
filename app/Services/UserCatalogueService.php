<?php

namespace App\Services;

use App\Services\Interfaces\UserCatalogueServiceInterface;
use App\Repositories\Interfaces\UserCatalogueRepositoryInterface as UserCatalogueRepository;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

/**
 * Class UserCatalogueService
 * @package App\Services
 */
class UserCatalogueService implements UserCatalogueServiceInterface
{
    protected $userCatalogueRepository;
    protected $userRepository;
    public function __construct(UserCatalogueRepository $userCatalogueRepository, UserRepository $userRepository)
    {
        $this->userCatalogueRepository = $userCatalogueRepository;
        $this->userRepository = $userRepository;
    }

    public function paginate($request)
    {

        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = ($request->integer('publish'));

        $perpage = $request->integer('perpage');

        $usersCatalogue = $this->userCatalogueRepository->pagination(
            $this->paginateSelect(),
            $condition,
            [],
            $perpage,
            ['path' => '/user/catalogue/index'],
            ['users']
        );

        return $usersCatalogue;
    }
    public function create()
    {
        DB::beginTransaction();
        try {

            $payload = request()->except(['_token', 'send']);


            $user = $this->userCatalogueRepository->create($payload);


            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();

            echo $e->getMessage();
            die();
            return false;

        }
    }


    public function update($id, $request)
    {
        DB::beginTransaction();
        try {

            $payload = request()->except(['_token', 'send']);


            $user = $this->userCatalogueRepository->update($id, $payload);


            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();

            echo $e->getMessage();
            die();
            return false;
        }
    }
    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $user = $this->userCatalogueRepository->forceDelete($id);


            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();

            echo $e->getMessage();
            die();
            return false;
        }
    }


    public function setPermission($request) 
    {

        DB::beginTransaction();
        try {

           $permissions = $request->input('permission');
           $userCatalogue = $this->userCatalogueRepository->all(['permissions']);
      

           
           if(isset($permissions)) {
            foreach($permissions as $key => $val) {

                $userCatalogue = $this->userCatalogueRepository->findIdUpdate($key);
                $userCatalogue->permissions()->detach();
                $userCatalogue->permissions()->sync($val);
    
               }
           }
            else {
                DB::table('user_catalogue_permission')->delete();
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();

            echo $e->getMessage();
            die();
            return false;
        }
        
       

    }

    public function updateStatus($post = [])
    {
        DB::beginTransaction();
        try {

            $payload[$post['field']] = (($post['value'] == 1) ? 2 : 1);

            $user = $this->userCatalogueRepository->update($post['modeId'], $payload);
            $this->changeUserStatus($post,  $payload[$post['field']]);


            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();

            echo $e->getMessage();
            die();
            return false;
        }
    }
    public function updateStatusAll($post = [])
    {
        DB::beginTransaction();
        try {

            $payload[$post['field']] = $post['value'];

            $flag = $this->userCatalogueRepository->updateByWhereIn('id', $post['id'], $payload);
            $this->changeUserStatus($post,  $payload[$post['field']]);


            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();

            echo $e->getMessage();
            die();
            return false;
        }
    }


    private function changeUserStatus($post, $value)
    {

        DB::beginTransaction();
        try {

            $array = [];

            if (isset($post['modeId'])) {
                $array[] = $post['modeId'];
            } else {
                $array = $post['id'];
            }

            $payload[$post['field']] = $value;


            $flag = $this->userRepository->updateByWhereIn('user_catalogue_id', $array, $payload);


            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();

            echo $e->getMessage();
            die();
            return false;
        }
    }
    private function convertBirthDayDate($birthday = '')
    {
        $carbonDate = Carbon::createFromFormat('Y-m-d', $birthday);
        $birthday = $carbonDate->format('Y-m-d H:i:s');
        return $birthday;
    }

    private function paginateSelect()
    {
        return
            [
                'id',
                'name',
                'description',
                'publish'
            ];
    }
}
