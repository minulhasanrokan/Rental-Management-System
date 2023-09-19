<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\UserConfig;

class UserConfgiDataServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $data = UserConfig::where('delete_status',0)
            ->where('status',1)
            //->where('id',1)
            ->first();

        $user_config_data = array();

        if($data!=''){

            $user_config_data['normal_user_type']=$data->normal_user_type;
            $user_config_data['owner_user_type']=$data->owner_user_type;
            $user_config_data['tenant_user_type']=$data->tenant_user_type;
            $user_config_data['add_by']=$data->add_by;
            $user_config_data['edit_by']=$data->edit_by;
            $user_config_data['delete_by']=$data->delete_by;
            $user_config_data['id']=$data->id;
            $user_config_data['edit_status']=$data->edit_status;
            $user_config_data['delete_status']=$data->delete_status;
            $user_config_data['system_bg_image']=$data->system_bg_image;
        }
        else{
            $user_config_data['normal_user_type']='';
            $user_config_data['owner_user_type']='';
            $user_config_data['tenant_user_type']='';
            $user_config_data['add_by']='';
            $user_config_data['edit_by']='';
            $user_config_data['delete_by']='';
            $user_config_data['id']='';
            $user_config_data['edit_status']='';
            $user_config_data['delete_status']='';
            $user_config_data['system_bg_image']='';
        }

        View::share('user_config_data', $user_config_data);
    }
}
