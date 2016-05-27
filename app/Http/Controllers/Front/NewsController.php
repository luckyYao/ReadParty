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
            ->select('news.*','user.name as user_name','user.head_pic as user_pic')
            ->get();
        return view("front/news/index",['result'=>$result]);
	}

    public function store()
    {   
        $param = Request::all();
        $id = DB::table('news')->insertGetId(
            ['content' => $param['content'],
             'pic'     => $param['pic'],
             'user_id' => $param['user_id']
            ]
        );
        return $this->jsonResponse(false,$id,"添加成功");
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