<?php

namespace App\Http\Controllers\Front;

use DB;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Response;
use Request;
use Session;
use Validator;

class MineController extends BaseController
{   
    // 个人
    public function myBook()
    {
        $result['user'] = $this->getUser();
        if (!empty($result['user'])) {
            // 我借出的书
            $result['book_mine'] = DB::table('borrow')
                ->where('borrow.user_id',$result['user']['user_id'])
                ->where('borrow.is_delete',0)
                ->where('borrow.is_show',1)
                ->select('borrow.*')
                ->orderBy('borrow.create_at','DESC')
                ->get();
            foreach ($result['book_mine'] as $key => $value1) {
                $reader_current = DB::table('timeline_borrow')
                ->where('timeline_borrow.borrow_id',$value1->id)
                ->join('user', 'timeline_borrow.user_id', '=', 'user.id')
                ->wherein('timeline_borrow.state',[1,2,3])
                ->where('timeline_borrow.is_delete',0)
                ->select('timeline_borrow.state','user.name as user_name','user.phone as user_phone')
                ->orderBy('timeline_borrow.create_at','ASC')
                ->get();
                $reader_next = DB::table('timeline_borrow')
                ->where('timeline_borrow.borrow_id',$value1->id)
                ->join('user', 'timeline_borrow.user_id', '=', 'user.id')
                ->where('timeline_borrow.state',0)
                ->where('timeline_borrow.is_delete',0)
                ->select('timeline_borrow.state','user.name as user_name','user.phone as user_phone')
                ->orderBy('timeline_borrow.create_at','ASC')
                ->get();
                $value1->reader_current = $reader_current;
                $value1->reader_next = $reader_next;
            }
            // var_dump($result['book_mine'][0]);exit();
            // 我借入的书籍
            $result['book_borrow'] = DB::table('timeline_borrow')
                ->join('borrow', 'timeline_borrow.borrow_id', '=', 'borrow.id')
                ->where('timeline_borrow.user_id',$result['user']['user_id'])
                ->where('timeline_borrow.is_delete',0)
                ->where('timeline_borrow.is_show',1)
                ->select('timeline_borrow.words as my_words','timeline_borrow.state','borrow.*')
                ->orderBy('timeline_borrow.create_at','DESC')
                ->get();
            foreach ($result['book_borrow'] as $key => $value1) {
                $reader_current = DB::table('timeline_borrow')
                    ->where('timeline_borrow.borrow_id',$value1->id)
                    ->join('user', 'timeline_borrow.user_id', '=', 'user.id')
                    ->wherein('timeline_borrow.state',[1,2,3])
                    ->where('timeline_borrow.is_delete',0)
                    ->select('timeline_borrow.state','user.user_id as user_id','user.name as user_name','user.phone as user_phone')
                    ->orderBy('timeline_borrow.create_at','ASC')
                    ->get();
                $reader_next = DB::table('timeline_borrow')
                    ->where('timeline_borrow.borrow_id',$value1->id)
                    ->join('user', 'timeline_borrow.user_id', '=', 'user.id')
                    ->where('timeline_borrow.state',0)
                    ->where('timeline_borrow.is_delete',0)
                    ->select('timeline_borrow.state','user.user_id as user_id','user.name as user_name','user.phone as user_phone')
                    ->orderBy('timeline_borrow.create_at','ASC')
                    ->get(); 
                $value1->reader_current = $reader_current;
                $value1->reader_next = $reader_next;
            }
            // 求帮忙的书籍
            $result['book_help'] = DB::table('help')
                    ->join('user', 'help.user_id', '=', 'user.id')
                    ->where('help.user_id',$result['user']['user_id'])
                    ->orwhere('help.helper_id',$result['user']['user_id'])
                    ->where('help.is_delete',0)
                    ->where('help.is_show',1)
                    ->select('help.*','user.name as helper_name','user.phone as helper_phone')
                    ->orderBy('help.create_at','DESC')
                    ->get();
        }
        return view("front/my/index",['result'=>$result]);
    }
    public function updateState()
    {
        $param = Request::all();
        $user = $this->getUser();
        // 开始漂流
        if ($param['type']==1) {
            // 当前用户状态换成“漂流（from）”
            $result['current'] = DB::table('timeline_borrow')
                    ->where('timeline_borrow.user_id', $user['user_id'])
                    ->where('timeline_borrow.borrow_id', $param['borrow_id'])
                    ->where('timeline_borrow.state', '1')
                    ->update(['state'=>2]);
            if ($result['current']) {
                // 寻找下一个漂流对象状态换成“漂流（to）”
                $result['next'] = DB::table('timeline_borrow')
                    ->where('timeline_borrow.user_id', $param['next_id'])
                    ->where('timeline_borrow.borrow_id', $param['borrow_id'])
                    ->update(['state'=>3]);
                if ($result['next']) {
                    $informData = [
                        "title"         => '阅读派对图书“'.$param['book_name'].'”漂流到你这里了,登陆阅读派对去看看？',
                        "app_id"        => 25,
                        "set_id"        => 88,
                        "sender_id"     => 1,
                        "users"         => [$param['next_id']],
                        "reply_opts"    => [
                            ["content"=>"去看看","is_expected"=>true,"url_page"=>"http://192.168.2.83:85/myBook"],
                            ["content"=>"稍后再说"]
                        ],
                    ];
                    $informResult = $this->sendInform($informData,$_ENV["PTIME_TOKEN"]);
                    if($informResult["error"]){
                        return $informResult;
                    }
                    return $this->jsonResponse(false,[],"漂流成功");
                }
                else return $this->jsonResponse(false,[],"漂流中");   
            }else{
                return $this->jsonResponse(true,[],"漂流失败");
            }
        }elseif($param['type']==2){
            // 飘过
            // 当前用户状态换成“想读”
            $result['current'] = DB::table('timeline_borrow')
                    ->where('timeline_borrow.user_id', $user['user_id'])
                    ->where('timeline_borrow.borrow_id', $param['borrow_id'])
                    ->update(['state'=>0,'create_at'=>date("Y-m-d H:i:s")]);
            if ($result['current']) {
                // 寻找下一个漂流对象状态换成“漂流（to）”
                $result_next = DB::table('timeline_borrow')
                        ->join('user','user.id','=','timeline_borrow.user_id')
                        ->where('timeline_borrow.borrow_id', $param['borrow_id'])
                        ->where('timeline_borrow.user_id','!=',$user['user_id'])
                        ->where('timeline_borrow.state', '0')
                        ->orderBy('timeline_borrow.create_at','DESC')
                        ->select('timeline_borrow.*','user.user_id')
                        ->first();
                if (!empty($result_next)&&$result_next->user_id != $user['user_id']) {
                    $result['next'] = DB::table('timeline_borrow')
                        ->where('timeline_borrow.user_id', $result_next->user_id)
                        ->where('timeline_borrow.borrow_id', $param['borrow_id'])
                        ->update(['state'=>3]);
                    $informData = [
                        "title"         => '阅读派对图书“'.$param['book_name'].'”漂流到你这里了,登陆阅读派对去看看？',
                        "app_id"        => 25,
                        "set_id"        => 88,
                        "sender_id"     => 1,
                        "users"         => [$result_next->user_id],
                        "reply_opts"    => [
                            ["content"=>"去看看","is_expected"=>true,"url_page"=>"http://192.168.2.83:85/myBook"],
                            ["content"=>"稍后再说"]
                        ],
                    ];
                    $informResult = $this->sendInform($informData,$_ENV["PTIME_TOKEN"]);
                    if($informResult["error"]){
                        return $informResult;
                    }
                    return $this->jsonResponse(false,[],"漂流成功~");
                }else{
                    return $this->jsonResponse(false,[],"漂流中~");
                }
            }else{
                return $this->jsonResponse(true,[],"漂流失败");
            }
        }else{
            // 结束漂流
            // 当前用户状态换成“在读”
            $result['current'] = DB::table('timeline_borrow')
                    ->where('timeline_borrow.user_id', $user['user_id'])
                    ->where('timeline_borrow.borrow_id', $param['borrow_id'])
                    ->update(['state'=>1]);
            if ($result['current']) {
                // 寻找上一个漂流对象状态换成“读过”
                $user_prev= DB::table('timeline_borrow')
                    ->where('timeline_borrow.state', 2)
                    ->where('timeline_borrow.borrow_id', $param['borrow_id'])
                    ->join('user', 'timeline_borrow.user_id', '=', 'user.id')
                    ->select('user.user_id','user.name')
                    ->get();
                $user_id = [$user_prev[0]->user_id];
                $user_name = $user_prev[0]->name;
                $result['prev'] = DB::table('timeline_borrow')
                    ->where('timeline_borrow.state', 2)
                    ->where('timeline_borrow.borrow_id', $param['borrow_id'])
                    ->update(['state'=>4]);
                if ($result['prev']) {
                    //给图书的主人发消息
                    $owner_id = DB::table('borrow')
                    ->where('borrow.id', $param['borrow_id'])
                    ->select('borrow.user_id')
                    ->get();
                    $informData2 = [
                        "title"         => '您的图书“'.$param['book_name'].'”已从'.$user_name.'漂流到'.$user['user_name'],
                        "app_id"        => 25,
                        "set_id"        => 88,
                        "sender_id"     => 1,
                        "users"         => $owner_id,
                        "reply_opts"    => [
                            ["content"=>"去看看","is_expected"=>true,"url_page"=>"http://192.168.2.83:85/myBook"],
                            ["content"=>"稍后再说"]
                        ],    
                    ];
                    $informResult2 = $this->sendInform($informData2,$_ENV["PTIME_TOKEN"]);
                    if($informResult2["error"]){
                        return $informResult2;
                    }
                    //给漂流（from）的用户发消息 
                    $informData1 = [
                        "title"         => '阅读派对图书“'.$param['book_name'].'”已从您这里成功漂出',
                        "app_id"        => 25,
                        "set_id"        => 88,
                        "sender_id"     => 1,
                        "users"         => $user_id,
                        "reply_opts"    => [
                            ["content"=>"去看看","is_expected"=>true,"url_page"=>"http://192.168.2.83:85/myBook"],
                            ["content"=>"稍后再说"]
                        ],    
                    ];
                    $informResult1 = $this->sendInform($informData1,$_ENV["PTIME_TOKEN"]);
                    if($informResult1["error"]){
                        return $informResult1;
                    }
                    
                    return $this->jsonResponse(false,[],"漂流成功");
                }
                else return $this->jsonResponse(false,[],"漂流中"); 
            }else{
                return $this->jsonResponse(true,[],"漂流失败");
            }
        }
    }
    // 个人消息
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
            foreach ($result['news_mine'] as $key => $value1) {
                $comments = json_decode($this->httpRequest('http://dev.timepicker.cn/api/comment?object=readparty&object_id='.$value1->id.'&app_id=25&page=1','5a350362534f8a12d148743d26faa21d'))->result;
                foreach ($comments as $key => $value2) {
                    $value2->commentsRelate = json_decode($this->httpRequest('http://dev.timepicker.cn/api/comment/'.$value2->id.'/relate?page=1','5a350362534f8a12d148743d26faa21d'))->result;
                }
                $value1->comments = $comments;
                $value1->commentsCount = json_decode($this->httpRequest('http://dev.timepicker.cn/api/comment/count?object=readparty&object_ids='.$value1->id.'&app_id=25','5a350362534f8a12d148743d26faa21d'))->result[0];
            }
        }
        return view("front/my/news",['result'=>$result]);
    }

    // 撤回书籍
    public function recallBook($id)
    {
        $time_now = time();
        $book_info = DB::table('borrow')
            ->where('borrow.id', $id)
            ->select('create_at')
            ->get();
        $deadline = strtotime($book_info[0]->create_at)+2629743*3;
        if ($time_now>$deadline) {
            $result = DB::table('borrow')
                ->where('borrow.id', $id)
                ->update(['is_delete'=>1]);
            return $this->jsonResponse(false,[],"撤回成功");
        }else{
            return $this->jsonResponse(true,[],"发布书三个月后才可撤回哦");
        }
        
        
    }
}