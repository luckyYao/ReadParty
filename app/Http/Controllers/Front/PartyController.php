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
	public function indexBorrow()
	{	
        $result = DB::table('borrow')
            ->join('user', 'borrow.user_id', '=', 'user.id')
            ->where('borrow.is_delete',0)
            ->where('borrow.is_show',1)
            ->select('borrow.*', 'user.name')
            ->get();
        var_dump($result);exit();
        return view("front/borrow/index",['result'=>$result]);
	}
    public function indexHelp()
    {   
        $result = DB::table('help')
            ->join('user', 'help.user_id', '=', 'user.id')
            ->where('help.is_delete',0)
            ->where('help.is_show',1)
            ->select('help.*','user.name')
            ->get();
        var_dump($result);exit();
        return view("front/help/index",['result'=>$result]);
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
            ->select($type.'.*', 'user.name','user.id as user_id')
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
        return view("front/".$type."/show",['result'=>$result]);
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