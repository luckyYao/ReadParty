<?php

namespace App\Http\Controllers\Front;

use DB;
use Hash;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Response;
use Request;
use Session;
use Validator;

class UserController extends BaseController
{	
    /**
     * @api {post} /store
     * @apiVersion 
     * @apiName 
     * @apiGroup 
     * @apiPermission app
     *
     * @apiDescription 修改密码
     *
     * @apiParam {Number} phone *用户账号.*
     *
     * @apiError paramError 参数错误.
     * @apiSampleRequest http://timepicker.cn:92/api/activity/1/enroll
     */ 
    public function store()
    {
        $param = Request::all();
        $userData = [
            "id"    => $param['id'],
            "name"  => $param['name'],
            "icon"  => "http://source.timepicker.cn".$param['icon'],
            "phone" => $param['phone'],
        ];
        $userExist = DB::table('ac_user')->where('id',$param['id'])->first();
        if($userExist){
            $userResult = DB::table('ac_user')->where('id',$param['id'])->update($userData);
        }else{
            $userResult = DB::table('ac_user')->insert($userData);
        }
        return $this->jsonResponse(false,$userResult);
    }
    
    /**
     * @api {post} /store
     * @apiVersion 
     * @apiName 
     * @apiGroup 
     * @apiPermission app
     *
     * @apiDescription 修改密码
     *
     * @apiParam {Number} phone *用户账号.*
     *
     * @apiError paramError 参数错误.
     * @apiSampleRequest http://timepicker.cn:92/api/activity/1/enroll
     */
	public function login(Request $request)
	{	
        $param  = Request::all();
        $phone = $param['phone'];
        $passWord = $param['pwd'];
        if(!$phone){
            return $this::jsonResponse(true,'','账号不能为空');
        }
        $userResult = DB::select("select id,pwd from user where phone = '".$phone."'");
        if(!$userResult){
            return $this::jsonResponse(true,'','用户不存在');
        }
        if(!$passWord){
            return $this::jsonResponse(true,'','请输入密码');
        }
        if(Hash::check($passWord,$userResult[0]->pwd)){
            Session::put('id',$userResult[0]->id);
            Session::put('name',$phone);
            Session::save();
            return $this::jsonResponse(false,'','登录成功');
        }else{
            return $this::jsonResponse(true,'','密码错误');
        }
	}

    /**
     * @api {post} /pass
     * @apiVersion 
     * @apiName 
     * @apiGroup 
     * @apiPermission app
     *
     * @apiDescription 修改密码
     *
     * @apiParam {Number} phone *用户账号.*
     *
     * @apiError paramError 参数错误.
     * @apiSampleRequest http://timepicker.cn:92/api/activity/1/enroll
     */ 
    public function pass()
    {
        $param = Request::all();
        $phone = $param['phone'];
        $passWord = $param['pwd'];
        $passWordNew = Hash::make($param['pwd_new']);

        $userResult = DB::table('user')
                    ->where('user.phone',$phone)
                    ->select('user.id','user.pwd')
                    ->first();
        if(empty($userResult))
            return $this::jsonResponse(true,'','用户不存在');
        elseif (!Hash::check($passWord,$userResult->pwd)) 
            return $this::jsonResponse(true,'','密码错误');
        else{
            $result = DB::table('user')
                ->where('phone', $phone)
                ->update(['pwd'=>$passWordNew]);
            return $this::jsonResponse(false,'','修改成功');
        }
    }
}