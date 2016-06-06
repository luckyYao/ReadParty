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
        $token = !empty($_SESSION['token'])?$_SESSION['token']:'';
        $userInfo = $this->tokenUserInfo($token);
        if (!empty($token)) {
            return view("front/book/add",['user_name'=>$userInfo->name]);
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

        // 获取当前用户姓名
        $token = !empty($_SESSION['token'])?$_SESSION['token']:'';
        $userInfo = $this->tokenUserInfo($token);
        $result->user_current = empty($userInfo->name)?'':$userInfo->name;

        // 获取本书时间轴数据
        $table = 'timeline_'.$type;
        $timeline = DB::table($table)
            ->where($table.'.'.$type.'_id',$id)
            ->join('user', $table.'.user_id', '=', 'user.id')
            ->where($table.'.is_delete',0)
            ->where($table.'.is_show',1)
            ->select($table.'.*','user.name as user_name')
            ->orderBy($table.'.create_at','DESC')
            ->get();
        $result->timeline = $timeline;
        return view("front/book/show",['result'=>$result]);
    }

    // my
    public function myBook()
    {
        $result['user'] = $this->getUser();
        if (!empty($result['user'])) {
            $result['book_mine'] = DB::table('borrow')
            ->where('borrow.user_id',$result['user']['user_id'])
            ->where('borrow.is_delete',0)
            ->where('borrow.is_show',1)
            ->select('borrow.*')
            ->orderBy('borrow.create_at','DESC')
            ->get();
            foreach ($result['book_mine'] as $key => $value1) {
                $timeline = DB::table('timeline_borrow')
                ->where('timeline_borrow.borrow_id',$value1->id)
                ->join('user', 'timeline_borrow.user_id', '=', 'user.id')
                ->where('timeline_borrow.is_delete',0)
                ->where('timeline_borrow.is_show',1)
                ->select('timeline_borrow.state','user.name as user_name','user.phone as user_phone')
                ->orderBy('timeline_borrow.create_at','DESC')
                ->get();
                $read_done = 0;
                $read_todo = 0;
                $reading_name = '';
                $reading_phone = '';
                foreach ($timeline as $key => $value2) {
                    switch ($value2->state)
                    {
                    case '0':
                      $read_todo++;
                      break;  
                    case '1':
                      $reading_name = $value2->user_name;
                      $reading_phone = $value2->user_phone;
                      break;
                    default:
                      $read_done++;
                    }
                }
                $value1->read_done = $read_done;
                $value1->reading_name = $reading_name;
                $value1->reading_phone = $reading_phone;
                $value1->read_todo = $read_todo;
            }
            // var_dump($result['book_mine'][1]);exit();
            $result['book_borrow'] = DB::table('timeline_borrow')
                    ->join('borrow', 'timeline_borrow.borrow_id', '=', 'borrow.id')
                    ->where('timeline_borrow.user_id',$result['user']['user_id'])
                    ->where('timeline_borrow.is_delete',0)
                    ->where('timeline_borrow.is_show',1)
                    ->select('timeline_borrow.words as my_words','timeline_borrow.state','borrow.*')
                    ->orderBy('timeline_borrow.create_at','DESC')
                    ->get();
            foreach ($result['book_borrow'] as $key => $value1) {
                $timeline = DB::table('timeline_borrow')
                ->where('timeline_borrow.borrow_id',$value1->id)
                ->join('user', 'timeline_borrow.user_id', '=', 'user.id')
                ->where('timeline_borrow.is_delete',0)
                ->where('timeline_borrow.is_show',1)
                ->select('timeline_borrow.state','user.name as user_name','user.phone as user_phone')
                ->orderBy('timeline_borrow.create_at','DESC')
                ->get();
                $read_done = 0;
                $read_todo = 0;
                $reading_name = '';
                $reading_phone = '';
                $readnext_name = '';
                $readnext_phone = '';
                $reading_key = 0;
                foreach ($timeline as $key2 => $value2) {
                    switch ($value2->state)
                    {
                    case '0':
                      $read_todo++;
                      break;  
                    case '1':
                      $reading_name = $value2->user_name;
                      $reading_phone = $value2->user_phone;
                      $reading_key = $key2;
                      break;
                    default:
                      $read_done++;
                    }
                }
                $value1->read_done = $read_done;
                $value1->reading_name = $reading_name;
                $value1->reading_phone = $reading_phone;
                $value1->read_todo = $read_todo;
                $value1->reader_num = count($timeline);
                $value1->a = $key;
                if ($read_todo>0&&!empty($timeline[$reading_key+1])) {
                    $value1->readnext_name = $timeline[$reading_key+1]->user_name;
                    $value1->readnext_phone = $timeline[$reading_key+1]->user_phone;
                }
            }
            // var_dump($result['book_borrow']);exit();

            $result['book_help'] = DB::table('help')
                    ->where('help.user_id',$result['user']['user_id'])
                    ->where('help.is_delete',0)
                    ->where('help.is_show',1)
                    ->select('help.*')
                    ->orderBy('help.create_at','DESC')
                    ->get();
        }
        return view("front/my/index",['result'=>$result]);
    }
    public function myNews()
    {
        $result['user'] = $this->getUser();
        if (!empty($result['user'])) {
            $result['news_mine'] = DB::table('news')
                ->join('user', 'news.user_id', '=', 'user.id')
                ->where('news.user_id',$result['user']['user_id'])
                ->where('news.is_delete',0)
                ->where('news.is_show',1)
                ->select('news.*','user.name as user_name','user.icon as user_pic')
                ->orderBy('news.create_at','DESC')
                ->get();
        }
        return view("front/my/news",['result'=>$result]);
    }


    // 添加时间点 借书/帮忙
    public function timeLineAdd($book_id)
    {   
        $user = $this->getUser();
        $param = Request::all();
        $timeline_exits = DB::table('timeline_borrow')->where('user_id',$user['user_id'])->where('borrow_id',$book_id)->select('*')->first();
        if (empty($timeline_exits)) {
            $id = DB::table('timeline_borrow')->insertGetId([
                'borrow_id' => $book_id,
                'user_id'   => $user['user_id'],
                'user_pic'  => $user['user_pic'],
                'state'     => $param['state'],
                'words'     => $param['words']   
                ]);
            if ($id) 
                return $this->jsonResponse(false,$id,"添加成功");
            else
                return $this->jsonResponse(true,[],"添加失败"); 
        }else
            return $this->jsonResponse(true,[],"您已经申请借过此书"); 
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