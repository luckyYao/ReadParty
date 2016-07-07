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

        foreach ($result as $key => $value1) {
            $comments = json_decode($this->httpRequest('http://dev.timepicker.cn/api/comment?object=readparty&object_id='.$value1->id.'&app_id=25&page=1','5a350362534f8a12d148743d26faa21d'))->result;
            // var_dump($comments);exit();
            foreach ($comments as $key => $value2) {
                $value2->commentsRelate = json_decode($this->httpRequest('http://dev.timepicker.cn/api/comment/'.$value2->id.'/relate?page=1','5a350362534f8a12d148743d26faa21d'))->result;
            }
            $value1->comments = $comments;
            $value1->commentsCount = json_decode($this->httpRequest('http://dev.timepicker.cn/api/comment/count?object=readparty&object_ids='.$value1->id.'&app_id=25','5a350362534f8a12d148743d26faa21d'))->result[0];
            
        }
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

    public function remove($id)
    {
        $result = DB::table('news')
                ->where('id', $id)
                ->update(['is_show' => 0]);
        return $this->jsonResponse(false,$result,"删除成功");
    }
    public function comment()
    {   
        $data = Request::all();
        $data['app_id'] = '25';
        $data['object'] = 'readparty';
        // 获取用户信息
        $token = !empty($_SESSION['token'])?$_SESSION['token']:'';
        if (empty($token)) {
            $result['is_login'] = false;
            return $this->jsonResponse(false,$result,"请登录");
        }
        $userInfo = $this->tokenUserInfo($token);
        $data['user_id'] = $userInfo->id;
        if (empty($data['content'])) {
            return $this->jsonResponse(true,[],'评论内容不能为空');
        }else{
            $result = json_decode($this->httpRequest('http://dev.timepicker.cn/api/comment','5a350362534f8a12d148743d26faa21d',$data));
            return $this->jsonResponse(false,[],'添加成功');
        }
    }

    public function star()
    {
        $data = Request::all();
        $data['app_id'] = '25';
        $data['object'] = 'readparty';
        // 获取用户信息
        $token = !empty($_SESSION['token'])?$_SESSION['token']:'';
        if (empty($token)) {
            $result['is_login'] = false;
            return $this->jsonResponse(false,$result,"请登录");
        }
        $userInfo = $this->tokenUserInfo($token);
        $data['user_id'] = $userInfo->id;
        $result = json_decode($this->httpRequest('http://dev.timepicker.cn/api/star','5a350362534f8a12d148743d26faa21d',$data));
        if ($result->message=='star exist') {
            return $this->jsonResponse(false,[],'不可以重复点赞');
        }else{
            return $this->jsonResponse(false,[],'点赞成功');
        }    
    }
}