<?php
namespace App\Http\Controllers\Front;

use DB;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Response;

class BaseController extends Controller
{   

    public function jsonResponse($error,$result="",$message=""){
        if($message == "paramError") $message = $result->first();
        return ["error"=>$error,"result"=>$result,"message"=>$message];
    }

    public function tableConfig($table){
        return require "../storage/app/config/$table.php";
    }

    //http请求函数
    public function httpRequest($url,$token=null,$data=null)
    {   
        $header = [
            'ptimeauth:'.$token,
            'clientdate:123',
        ];
        $curl = curl_init($url);
        curl_setopt ($curl, CURLOPT_HTTPHEADER, $header );
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,false);  //1：回复内容 0：输出内容
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,false); 
        curl_setopt ($curl, CURLOPT_USERAGENT,'PTime.Activity/1.0');
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1); //模拟post方式
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data)); //对数组进行处理
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //执行事务
        $output = curl_exec($curl);
        //关闭
        curl_close($curl);
        //输出内容
        return $output; 
    }

    //获取用户信息
    public function tokenUserInfo($token)
    {
        $userApi = $_ENV["PTIME_URL"]."/api/user?token=$token";
        $userResult = $this->httpRequest($userApi,$token);
        $userInfo = json_decode($userResult);
        return $userInfo->result;
    }

    //发送通知
    public function sendInform($send_data,$token)
    {   
        $sendInformApi = $_ENV["PTIME_URL"]."/api/inform/send";
        $result = $this->httpRequest($sendInformApi,$token,$send_data);
        $sendResult = json_decode($result);
        return $sendResult->result;
    }

    //团队信息
    public function teamInfo($team_id,$token)
    {
        $teamApi = $_ENV["PTIME_URL"]."/api/team/$team_id";
        $result = $this->httpRequest($teamApi,$token);
        $teamResult = json_decode($result);
        return $teamResult->result;
    }

    //团队信息
    public function createTeamUser($team_id,$user_id,$token)
    {
        $createTeamUserApi = $_ENV["PTIME_URL"]."/api/team/$team_id/user/$user_id";
        $result = $this->httpRequest($createTeamUserApi,$token,['user_id'=>1]);
        $createTeamUserResult = json_decode($result);
        return $createTeamUserResult->result;
    }

    //oauth授权
    public function oauth2($redirect_uri)
    {   
        $clientId = $_ENV['CLIENT_ID'];
        $oauthUrl = $_ENV["PTIME_URL"]."/oauth2/auth?client_id=$clientId&redirect_uri=$redirect_uri&response_type=token";
        Header("Location: $oauthUrl");exit;
    }

    //教务计算当前周
    public function schoolWeekNow()
    {
        //计算当前周
        $weekStartArr = Config('ptime.week_start_arr');
        $unixTimeNow = time();
        $tempWeekCounter = 1;
        $tempWeekArr = array();
        $weekStartArrLength = count($weekStartArr);
        $weekStartArrLength--;
        //计算每周开始和结束时间戳
        foreach( $weekStartArr as $key => $value ){
            $tempNextKey = $key+1;
            if( $key < $weekStartArrLength ){
                $tempWeekArr[$tempWeekCounter] = array(
                    '0' => strtotime($value),
                    '1' => strtotime($weekStartArr[$tempNextKey]),
                    );
            }
            $tempWeekCounter++;
        }
        //计算当前周
        foreach( $tempWeekArr as $key => $value){
            if( $unixTimeNow >= $value[0] && $unixTimeNow <= $value[1] ){
                $targetSchoolWeek = $key;
            }else{
                continue;
            }
        }
        return $targetSchoolWeek;
    }


    public function get_student_type($schoolNum){
        $schoolNumLen  = strlen($schoolNum);

        if($schoolNumLen < 12){
            $userType = "TEA";
        }else if($schoolNumLen == 12 && substr($schoolNum, 0 ,1) == "S"){
            $userType = "YAN";
        }else if($schoolNumLen == 12 && substr($schoolNum, 2 ,1) == "0"){
            $userType = "DAX";
        }else if($schoolNumLen == 12 && substr($schoolNum, 2 ,1) == "1"){
            $userType = "LIR";
        }else{
            $userType = "";
        }
        return $userType;
    }

    // 获取借书|帮忙列表
    public function getBooks($type)
    {
        $result = DB::table($type)
            ->join('user', $type.'.user_id', '=', 'user.id')
            ->where($type.'.is_delete',0)
            ->where($type.'.is_show',1)
            ->select($type.'.*', 'user.name as user_name')
            ->orderBy($type.'.times','DESC')
            ->get();
        foreach ($result as $key => $value) {
            $value->tags = DB::table('tag')
                        ->select('*')
                        ->where('tag.isbn',$value->isbn)
                        ->where('tag.is_delete',0)
                        ->where('tag.is_show',1)
                        ->distinct()
                        ->get();
        }
        return $result;
    }
    // 获取所有书籍标签
    public function getTags($type)
    {
        $isbns = DB::table($type)
        ->select($type.'.isbn')
        ->where($type.'.is_delete',0)
        ->where($type.'.is_show',1)
        ->get();
        $isbn_new = [];
        foreach ($isbns as $key => $value) {
            array_push($isbn_new,$value->isbn);
        }
        $result = DB::table('tag')
            ->where('tag.is_delete',0)
            ->where('tag.is_show',1)
            ->whereIn('tag.isbn',$isbn_new)
            ->distinct()
            ->select('name')
            ->get();
        return $result;
    }
    
}