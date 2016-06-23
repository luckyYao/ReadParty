<?php

namespace App\Http\Controllers\Admin;

use DB;
use Session;
use Storage;
use Laravel\Lumen\Routing\Controller;
use Validator;
use Request;

class UserController extends BaseController{

    public function __CONSTRUCT(){
        $userId = Session::get('id');
        if(empty($userId)){
            return view('admin/login');
        }
    }
    /**
     * check session.
     */
    public function check(){

        $sessionData = Session::all();
        if(!isset($sessionData['id'])){
            Session::flush();
            Session::save();
            return $this::jsonResponse(true,'','用户验证失败');
        }
        $userResult = DB::table('zrq_master')
                    ->select('zrq_master.id','zrq_master.user_name','zrq_master.district_id')
                    ->where('zrq_master.id',$sessionData['id'])
                    ->first();
        if(!$userResult){
            return $this::jsonResponse(true,'','用户不存在');
        }                
        if($sessionData['is_master']){
            $district = DB::table('zrq_district')->where('id',$userResult->district_id)->first();
            $userResult->district = $district->name;      
        }                      
        $result = ['id'=>$sessionData['id'],'name'=>$sessionData['name'],'is_master'=>$sessionData['is_master'],'is_admin'=>$sessionData['is_admin'],'district'=>isset($userResult->district)?$userResult->district:''];
        return $this::jsonResponse(false,$result);
    }

    
	/**
     * User login.
     */
    public function login(){
    	$param = Request::all();
        $validator = Validator::make($param,

            [
                'userName' => 'required|string',
                'passWord'  => 'required|string',   
            ]
        );
        if($validator->fails()){
            return $this::jsonResponse(true,$validator->messages(),"paramError");
        }
        $userName = $param['userName'];
        
    	$userResult = DB::select("select id,name,password from admin where name = '".$userName."'");
        if(!$userResult){
    		return $this::jsonResponse(true,'','用户不存在');
    	}
    	$password = $param['passWord'];
        if($password == $userResult[0]->password){
    		Session::put('id',$userResult[0]->id);
    		Session::put('name',$userResult[0]->name);
    		return $this::jsonResponse(false,'','登录成功');
    	}else{
    		return $this::jsonResponse(true,'','密码错误');
    	}
    }



    /**
     * User logout.
     */
    public function logout(Request $request){
        Session::flush();
        Session::save();
        return $this::jsonResponse(false,'','注销成功');
    }

    /**
     * set password.
     */
    public function password($id){
        $data = Request::all();
        if(!$data){
            return $this::jsonResponse(true,'','请输入密码');
        }
        $passNew = $data['passNew'];
        $salt = $this->getRandString(4);
        $password = md5($salt.$passNew);
        DB::table('zrq_user')->where('id',$id)->update(array('password'=>$password,'salt'=>$salt));
        return $this::jsonResponse(false,'','密码修改成功');
    }
 
}
