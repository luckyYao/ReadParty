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
        $bookInfo = json_decode($this->httpRequest('https://api.douban.com/v2/book/isbn/'.$isbn));
        return $this->jsonResponse(false,$bookInfo,"书籍详情");
    }

    // 添加一条图书记录或求帮忙记录
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
                }
            }

            $borrow_id = DB::table($type)
                        ->insertGetId([
                            'user_id' => $user_id,
                            'isbn'    => $isbn,
                            'book_name' => $bookInfo->title, 
                            'book_img' => $bookInfo->image, 
                            'words' => $data['words'], 
                            'times' => 0, 
                            'deadline' => '2016-05-10 09:47:03',
                            'value' => $bookInfo->pages*0.3
                            ]);
            if ($borrow_id) {
                $result = ['url'=>'/'.$type.'/'.$borrow_id];
                return $this->jsonResponse(false,$result,"添加成功!");
            }
        }else{
            $result = ['url'=>'/'.$type.'/'.$book_exits->id];
            return $this->jsonResponse(true,$result,"书籍已存在！");
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