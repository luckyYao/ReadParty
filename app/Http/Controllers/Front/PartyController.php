<?php

namespace App\Http\Controllers\Front;

use DB;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Response;
use Request;
use Session;
use Validator;

class PartyController extends BaseController
{	
    public function index()
    {
        $result['borrow'] = $this->getBooks('borrow');
        $result['type'] = 'borrow';
        $result['tags'] = $this->getTags('borrow');
        $result['tags_current'] = 'all';
        return view("front/book/index",['result'=>$result]);
    }

    public function indexHelp()
    {   
        $result['help'] = $this->getBooks('help');
        $result['type'] = 'help';
        $result['tags'] = $this->getTags('help');
        $result['tags_current'] = 'all';
        return view("front/book/index",['result'=>$result]);
    }

    // 获取标签下的所有书
    public function tagBookIndex()
    {
        $param = Request::all();
        $tag = $param['tag'];
        $type = $param['type'];
        //根据标签获取图书 
        $result[$type] = DB::table('tag')
            ->join($type,'tag.isbn','=',$type.'.isbn')
            ->join('user',$type.'.user_id','=','user.id')
            ->where('tag.name',$tag)
            ->where('tag.is_show',1)
            ->where('tag.is_delete',0)
            ->select($type.'.*','user.name as user_name')
            ->get();
        foreach ($result[$type]  as $key => $value) {
            $value->tags = DB::table('tag')
                        ->select('*')
                        ->where('tag.isbn',$value->isbn)
                        ->where('tag.is_delete',0)
                        ->where('tag.is_show',1)
                        ->distinct()
                        ->get();
        }
        $result['tags'] = $this->getTags($type);
        $result['type'] = $type;
        $result['tags_current'] = $tag;
        return view("front/book/index",['result'=>$result]);
    }
    
    // 添加一本书
    public function addBook()
    {
        // var_dump(empty($_SESSION['token']));exit();
        $token = !empty($_SESSION['token'])?$_SESSION['token']:'';
        $userInfo = $this->tokenUserInfo($token);
        if (!empty($token)) {
            return view("front/book/add",['result'=>$userInfo]);
        }else{
            $url = env('PTIME_URL').'/oauth2/auth?client_id='.env('CLIENT_ID').'&redirect_uri='.urlencode('http://'.$_SERVER['HTTP_HOST'].'/book/add').'&response_type=token'; 
            return redirect($url);
        }
    }

    // 获取借书/帮忙的详情
    public function show($type,$id)
    {
        // 获取本书基本信息
        $result = DB::table($type)
            ->where($type.'.id',$id)
            ->where($type.'.is_show', 1)
            ->where($type.'.is_delete', 0)
            ->join('user', $type.'.user_id', '=', 'user.id')
            ->select($type.'.*', 'user.name as user_name','user.id as user_id')
            ->first();
        if (empty($result)) {
            return $this->jsonResponse(false,[],"书籍不存在");
        }
        // 获取本书时间轴数据
        $table = 'timeline_'.$type;
        $timeline = DB::table($table)
            ->where($table.'.'.$type.'_id',$id)
            ->join('user', $table.'.user_id', '=', 'user.id')
            ->where($table.'.is_delete',0)
            ->where($table.'.is_show',1)
            ->select($table.'.*','user.name as user_name')
            ->get();
        $result->timeline = $timeline;
        return view("front/book/show",['result'=>$result]);
    }

    // wode 
    public function myIndex()
    {
        return view("front/my/index");
    }
    // 添加时间点 借书/帮忙
    public function timeLineAdd($type,$book_id)
    {   
        $param = Request::all();
        $table = 'timeline_'.$type;
        $id = DB::table($table)->insertGetId([
                $type.'_id' => $book_id,
                 'user_id'   => 1,
                 'state'     => $param['state'],
                 'words'     => $param['words'],
                 'pic'       => $param['pic']     
                ]);
        if ($id) 
            return $this->jsonResponse(false,$id,"添加成功");
        else
            return $this->jsonResponse(true,[],"添加失败");
    }

    // 更新借书/帮忙的时间点
    public function timeLineUpdate($type,$book_id,$id)
    {
        $param  = Request::all();
        $table  = 'timeline_'.$type;
        $result = DB::table($table)
                ->where('id', $id)
                ->update($param);
        if ($result) return $this->jsonResponse(false,[],"修改成功");
        else  return $this->jsonResponse(true,[],"修改失败");
    }
}