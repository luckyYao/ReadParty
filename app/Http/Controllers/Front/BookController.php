<?php

namespace App\Http\Controllers\Front;

use DB;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Response;
use Request;
use Session;
use Validator; 

class BookController extends BaseController
{	
    // douban
    public function douban(){
        $data = Request::all();
        $isbn = $data['isbn'];
        // $user_id = $data['user_id'];
        //验证用户

        $bookInfo = json_decode($this->httpRequest('https://api.douban.com/v2/book/isbn/'.$isbn));
        return $this->jsonResponse(false,$bookInfo,"书籍详情");
    }

    // 添加一条图书记录或求帮忙记录
	public function store($type)
	{	
        $data = Request::all();
        $isbn = $data['isbn'];
        // $user_id = $data['user_id'];
        //验证用户

        $bookInfo = json_decode($this->httpRequest('https://api.douban.com/v2/book/isbn/'.$isbn));
        $data['book_img']  =  $bookInfo->image;
        $data['book_name'] =  $bookInfo->title;
        $data['value']     =  $bookInfo->pages*0.3;

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
            // 检验申请的书是否已经存在
            if ($type == 'help') {
                $borrow_exits  = DB::table('borrow')->where('user_id',$user_id)->where('isbn',$isbn)->first();
                if (!empty($borrow_exits)) {
                    return $this->jsonResponse(true,$borrow_exits,"派对上已经有这本书了~");
                }
            }
            $borrow_id = DB::table($type)->insertGetId($data);
            if ($borrow_id) {
                return $this->jsonResponse(false,$borrow_id,"添加成功");
            }
        }else{
            return $this->jsonResponse(true,$book_exits,"书籍已存在！");
        }
	}

    // 获取书籍的所有标签
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

    // 获取某本书的标签
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