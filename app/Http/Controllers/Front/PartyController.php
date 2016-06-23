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
    // 读书
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

    public function help($id)
    {   
        $token = !empty($_SESSION['token'])?$_SESSION['token']:'';
        $userInfo = $this->tokenUserInfo($token);
        $user_exits =  DB::table('user')->where('school_id',$userInfo->school_id)->select('*')->first();
        $user_id = $user_exits->id;
        $helpInfo = DB::table('help')
            ->where('help.id',$id)
            ->select('*')
            ->first();
        if(in_array($user_id,explode('-',$helpInfo->helpers))){
            return $this->jsonResponse(true,[],"不可重复操作~");
        }else{
            $result = DB::table('help')
                ->where('id', $id)
                ->update(['times' => $helpInfo->times+1,'helpers' => $helpInfo->helpers.'-'.$user_id]);
            return $this->jsonResponse(false,$user_exits,"操作成功~");
        };
    }

    public function borrow($id)
    {   
        $token = !empty($_SESSION['token'])?$_SESSION['token']:'';
        if (!empty($token)) {
            $userInfo = $this->tokenUserInfo($token);
            $user_exits =  DB::table('user')->where('school_id',$userInfo->school_id)->select('*')->first();
            $user_id = $user_exits->id;
            $result = DB::table('help')
                ->where('id', $id)
                ->update(['is_done' => 1,'helper_id' => $user_id]);
            return $this->jsonResponse(false,[],"操作成功~");
        }else{
            $url = env('PTIME_URL').'/oauth2/auth?client_id='.env('CLIENT_ID').'&redirect_uri='.urlencode('http://'.$_SERVER['HTTP_HOST']).'&response_type=token'; 
            return $this->jsonResponse(true,$url,"请登录~");
        }
    }

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
    
    public function addBook()
    {
        $token = !empty($_SESSION['token'])?$_SESSION['token']:'';
        $userInfo = $this->tokenUserInfo($token);
        if (!empty($token)) {
            return view("front/book/add",['user_name'=>$userInfo->name]);
        }else{
            $url = env('PTIME_URL').'/oauth2/auth?client_id='.env('CLIENT_ID').'&redirect_uri='.urlencode('http://'.$_SERVER['HTTP_HOST'].'/book/add').'&response_type=token'; 
            return redirect($url);
        }
    }

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

        // 获取当前用户姓名
        $token = !empty($_SESSION['token'])?$_SESSION['token']:'';
        $userInfo = $this->tokenUserInfo($token);
        $result->user_current = empty($userInfo->name)?'':$userInfo->name;

        // 获取本书时间轴数据
        if ($type=='borrow') {
           $table = 'timeline_'.$type;
            $timeline = DB::table($table)
                ->where($table.'.'.$type.'_id',$id)
                ->join('user', $table.'.user_id', '=', 'user.id')
                ->where($table.'.is_delete',0)
                ->select($table.'.*','user.name as user_name')
                ->orderBy($table.'.create_at','DESC')
                ->get();
            $result->timeline = $timeline;
        }else{
            $users_id=explode('-',$result->helpers);
            $result->helpers = DB::table('user')
                   ->wherein('user.id',$users_id)
                   ->select('*')
                   ->get();
        }
        return view("front/book/show",['result'=>$result]);
    }

    public function timeLineAdd($book_id)
    {   
        $user = $this->getUser();
        $param = Request::all();
        $timeline_exits = DB::table('timeline_borrow')->where('user_id',$user['user_id'])->where('borrow_id',$book_id)->select('*')->first();
        if (empty($timeline_exits)) { 
            $states = DB::table('timeline_borrow')
                    ->where('borrow_id',$book_id)
                    ->wherein('state',[1,2,3])
                    ->select('state')
                    ->get();
            if (in_array('1', $states) || in_array('3', $states)) {
                $state = 0;
            }else{
                $state = 3;
            };
            $id = DB::table('timeline_borrow')->insertGetId([
                'borrow_id' => $book_id,
                'user_id'   => $user['user_id'],
                'user_pic'  => $user['user_pic'],
                'state'     => $state,
                'words'     => $param['words']   
                ]);
            if ($id) 
                return $this->jsonResponse(false,$state,"添加成功");
            else
                return $this->jsonResponse(true,[],"添加失败"); 
        }else
            return $this->jsonResponse(true,[],"您已经申请借过此书"); 
    }

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

    public function douban(){
        $data = Request::all();
        $isbn = $data['isbn'];
        $bookInfo = json_decode($this->httpRequest('https://api.douban.com/v2/book/isbn/'.$isbn));
        return $this->jsonResponse(false,$bookInfo,"书籍详情");
    }

    public function store($type)
    {   
        $data = Request::all();
        $isbn = $data['isbn'];
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
        }
        // 获取图书信息
        $bookInfo = json_decode($this->httpRequest('https://api.douban.com/v2/book/isbn/'.$isbn));

        //如果是第一次分享这本书则保存该书的标签
        $tags = $bookInfo->tags;
        $is_tags =  DB::table('tag')->where('isbn',$isbn)->select('*')->first();
        if (empty($is_tags)) {
            foreach ($tags as $key => $value) {
                DB::table('tag')
                ->insert(
                    ['isbn' => $isbn,
                     'name' => $value->name]
                );
            }
        }
        // 将图书基本信息放到对应的表里面
        $book_exits  = DB::table($type)->where('user_id',$user_id)->where('isbn',$isbn)->first();
        if (empty($book_exits)) {
            if ($type == 'help') {
                $borrow_exits  = DB::table('borrow')->where('user_id',$user_id)->where('isbn',$isbn)->first();
                if (!empty($borrow_exits)) {
                    $result = ['url'=>'/borrow/'.$borrow_exits->id];
                    return $this->jsonResponse(true,$result,"派对上已经有这本书了~");
                }else{
                    $help_id = DB::table($type)
                        ->insertGetId([
                            'user_id' => $user_id,
                            'isbn'    => $isbn,
                            'book_name' => $bookInfo->title, 
                            'book_img' => $bookInfo->image, 
                            'words' => $data['words'], 
                            'times' => 0
                            ]);
                    $result = ['id'=> $help_id];
                    return $this->jsonResponse(true,$result,"帮助请求发布成功~");
                }
            }

            $borrow_id = DB::table($type)
                        ->insertGetId([
                            'user_id' => $user_id,
                            'isbn'    => $isbn,
                            'book_name' => $bookInfo->title, 
                            'book_img' => $bookInfo->image, 
                            'words' => $data['words'], 
                            'times' => 0
                            ]);
            if ($borrow_id) {
                $result = ['url'=>'/'.$type.'/'.$borrow_id];
                DB::table('timeline_borrow')->insertGetId([
                'borrow_id' => $borrow_id,
                'user_id'   => $user_id,
                'user_pic'  => $userInfo->icon,
                'state'     => 2,
                'words'     => '图书开始漂流'   
                ]);
                return $this->jsonResponse(false,$result,"添加成功!");
            }
        }else{
            $result = ['url'=>'/'.$type.'/'.$book_exits->id];
            return $this->jsonResponse(true,$result,"书籍已存在！");
        }
    }

    public function tagIndex()
    {
        $result = DB::table('tag')
            ->where('tag.is_delete',0)
            ->where('tag.is_show',1)
            ->distinct()
            ->select('name')
            ->get();
        var_dump($result);exit();
    }

    public function bookTagIndex($isbn)
    {
        $result = DB::table('tag')
            ->where('tag.isbn',$isbn)
            ->where('tag.is_delete',0)
            ->where('tag.is_show',1)
            ->distinct()
            ->select('name')
            ->get();
        var_dump($result);exit();
    }
}