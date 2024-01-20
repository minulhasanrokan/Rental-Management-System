<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use DB;

class CheckRoute
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user_session_data = session()->all();

        $this->check_session($user_session_data);

        if(isset($user_session_data[config('app.app_session_name')])){

            $last_active_time_Key = config('app.app_session_name').'.last_active_time';

            session()->put($last_active_time_Key, time());
        }

        if(!isset($user_session_data[config('app.app_session_name')]) || $user_session_data[config('app.app_session_name')]['password_change_status']==0){

            session()->flush();
            //session()->regenerate();

            $customHeader = 'X_DATA_TOKEN';

            if (!isset($_SERVER['HTTP_'.$customHeader])){
                
                return redirect('/login');
            }

            echo 'Session Expire';

            die;
        }
        else if($user_session_data[config('app.app_session_name')]['super_admin_status']!=1){

            $route_name = request()->route()->getName();

            $user_id = $user_session_data[config('app.app_session_name')]['id'];

            $user_right_data = DB::table('right_details as a')
                ->join('user_rights as b', 'b.r_id', '=', 'a.id')
                ->select('a.id  as r_id')
                ->where('a.status',1)
                ->where('b.user_id',$user_id)
                ->where('a.r_route_name',$route_name)
                ->where('a.delete_status',0)
                ->get()->toArray();

            if(empty($user_right_data)){

                $customHeader = 'X_DATA_TOKEN';

                if (!isset($_SERVER['HTTP_'.$customHeader])){
                    
                    return redirect('/dashboard');
                }

                echo 'Right Not Found';

                die;
            }
        }

        return $next($request);
    }

    protected function check_session($user_session_data){

        if(isset($user_session_data[config('app.app_session_name')])){

            $last_active_time = $user_session_data[config('app.app_session_name')]['last_active_time'];
            $user_session_time = $user_session_data[config('app.app_session_name')]['user_session_time'];

            if((time() - $last_active_time) > $user_session_time){

                session()->flush();
                //session()->regenerate();
            }
        }
    }
}
