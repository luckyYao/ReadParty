<?php

namespace App\Http\Controllers\Front;

use DB;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Response;
use Request;
use Session;
use Validator;

class NewsController extends BaseController
{	
	public function index()
	{	
        $result = DB::table('news')
            ->join('user', 'news.user_id', '=', 'user.id')
            ->where('news.is_delete',0)
            ->where('news.is_show',1)
            ->select('news.*','user.name as user_name','user.icon as user_pic')
            ->orderBy('news.create_at','DESC')
            ->get();
        return view("front/news/index",['result'=>$result]);
	}

    public function store()
    {   
        $param = Request::all();
        // 获取用户信息
        $token = !empty($_SESSION['token'])?$_SESSION['token']:'';
        $userInfo = $this->tokenUserInfo($token);
        $user_exits =  DB::table('user')->where('school_id',$userInfo->school_id)->select('*')->first();
        if (empty($user_exits)) {
            $user_id = DB::table('user')
                ->insertGetId([
                    'name' => $userInfo->name,
                    'sex'    => $userInfo->sex,
                    'icon' => $userInfo->icon, 
                    'school_id' => $userInfo->school_id, 
                    'description' => $userInfo->description, 
                    'email' => $userInfo->email, 
                    'phone' => $userInfo->phone,
                    'is_actived' => $userInfo->is_actived,
                    'is_verified' => $userInfo->is_verified
                    ]);
        }else{
            $user_id = $user_exits->id;
        };
        $id = DB::table('news')->insertGetId(
            ['content' => $param['content'],
             'user_id' => $user_id
            ]
        );
        if ($id) {
            return $this->jsonResponse(false,$id,"添加成功");
        }else{
            return $this->jsonResponse(true,[],"添加失败");
        }
        
    }

    public function delete($id)
    {   
        $param = Request::all();
        $id = DB::table('news')
        ->where('id',$id)
        ->update(
            ['is_delete' => 1]
        );
        return $this->jsonResponse(false,$id,"删除成功");
    }
}