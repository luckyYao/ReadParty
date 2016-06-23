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
                    ->select('timeline_borrow.state','user.id as user_id','user.name as user_name','user.phone as user_phone')
                    ->orderBy('timeline_borrow.create_at','ASC')
                    ->get();
                $reader_next = DB::table('timeline_borrow')
                    ->where('timeline_borrow.borrow_id',$value1->id)
                    ->join('user', 'timeline_borrow.user_id', '=', 'user.id')
                    ->where('timeline_borrow.state',0)
                    ->where('timeline_borrow.is_delete',0)
                    ->select('timeline_borrow.state','user.id as user_id','user.name as user_name','user.phone as user_phone')
                    ->orderBy('timeline_borrow.create_at','ASC')
                    ->get(); 
                $value1->reader_current = $reader_current;
                $value1->reader_next = $reader_next;
            }
            // 求帮忙的书籍
            $result['book_help'] = DB::table('help')
                    ->join('user', 'help.helper_id', '=', 'user.id')
                    ->where('help.user_id',$result['user']['user_id'])
                    ->where('help.is_delete',0)
                    ->where('help.is_show',1)
                    ->select('help.*','user.name as helper_name','user.phone as helper_phone')
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
    public function updateState()
    {
        $param = Request::all();
        $user = $this->getUser();

        // 开始漂流
        if ($param['type']==1) {
            // 当前用户状态换成“漂流（起）”
            $result['current'] = DB::table('timeline_borrow')
                    ->where('timeline_borrow.user_id', $user['user_id'])
                    ->where('timeline_borrow.borrow_id', $param['borrow_id'])
                    ->where('timeline_borrow.state', '1')
                    ->update(['state'=>2]);
            if ($result['current']) {
                // 寻找下一个漂流对象状态换成“漂流（终）”
                $result['next'] = DB::table('timeline_borrow')
                    ->where('timeline_borrow.user_id', $param['next_id'])
                    ->where('timeline_borrow.borrow_id', $param['borrow_id'])
                    ->update(['state'=>3]);
                if ($result['next']) return $this->jsonResponse(false,[],"漂流成功");
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
                // 寻找下一个漂流对象状态换成“漂流（终）”
                $result_next = DB::table('timeline_borrow')
                        ->where('timeline_borrow.borrow_id', $param['borrow_id'])
                        ->where('timeline_borrow.state', '0')
                        ->orderBy('timeline_borrow.create_at','DESC')
                        ->first();
                if (!empty($result_next)&&$result_next->user_id != $user['user_id']) {
                    $result['next'] = DB::table('timeline_borrow')
                        ->where('timeline_borrow.user_id', $result_next->user_id)
                        ->where('timeline_borrow.borrow_id', $param['borrow_id'])
                        ->update(['state'=>3]);
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
                $result['next'] = DB::table('timeline_borrow')
                    ->where('timeline_borrow.state', 2)
                    ->where('timeline_borrow.borrow_id', $param['borrow_id'])
                    ->update(['state'=>4]);
                if ($result['next']) return $this->jsonResponse(false,[],"漂流成功");
                else return $this->jsonResponse(false,[],"漂流中"); 
            }else{
                return $this->jsonResponse(true,[],"漂流失败");
            }
        }
    }
}