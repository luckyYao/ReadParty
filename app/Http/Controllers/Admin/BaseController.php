<?php

namespace App\Http\Controllers\Admin;

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

    //获取地区父级
    public function parent($districtId,&$district=array())
    {   
        $districtNow = DB::table('zrq_district')->select('id','name','parent','pinyin','level')->where('id',$districtId)->orderBy('level','desc')->first();
        $district[] = $districtNow;
        if($districtNow->parent != 0){
            $this->parent($districtNow->parent,$district); 

        }
        return $district;
    }

    //获取地区所有子集
    public function children($districtId)
    {   
        $ptime = require __DIR__.'/../../../../config/district.php';
        $childrenIds = $ptime[$districtId];
        return $childrenIds;
    }

    //父级数组转换成字符串
    public function parentStr($districtArr)
    {   
        $district = [];
        foreach (array_reverse($districtArr) as $key1 => $value1) {
            $district[] = $value1->name;
        }
        $districtStr = implode('-', $district);
        return $districtStr;
    }

    public function getImageInfo($img) {
        
        $imageInfo  = getimagesize($img);
        if($imageInfo !== false){
        
            $imageType = strtolower(substr(image_type_to_extension($imageInfo[2]),1));
            $imageSize = filesize($img);
            $info = array(
                "width"  => $imageInfo[0],
                "height" => $imageInfo[1],
                "type"   => $imageType,
                "size"   => $imageSize,
                "mime"   => $imageInfo["mime"]
            );
            return $info;
        }
        return false;
    }
    
    public function thumb($image,$thumbname,$maxwidth = 0,$maxheight =0,$serverPath='',$interlace=true) {
        $info = $this->getImageInfo($image);
        if($info){
            
            $srcwidth  =  $info["width"];
            $srcheight =  $info["height"];

            $type=strtolower($info["type"]);
            if($type == "jpg") $type = "jpeg";
            $interlace = $interlace?1:0;
            unset($info);

            if( $maxwidth && $maxheight ) {
                $scale = min($maxwidth / $srcwidth,$maxheight / $srcheight);
            } else if( $maxwidth ) {
                $scale = $maxwidth / $srcwidth;
            } else if( $maxheight ) {
                $scale = $maxheight / $srcheight;
            }

            if($scale >= 1){
                $width  = $srcwidth;
                $height = $srcheight;
            } else {
                $width  = (int)($srcwidth * $scale);
                $height = (int)($srcheight * $scale);
            }
            
            $createFun = 'ImageCreateFrom'.$type;
            $srcImg    = $createFun($image);
            if($type != 'gif' && function_exists('imagecreatetruecolor')){
                
                $thumbImg = imagecreatetruecolor($width,$height);
            }else{
                $thumbImg = imagecreate($width,$height);
            }

            if(function_exists('imagecopyresampled')){
                imagecopyresampled($thumbImg,$srcImg,0,0,0,0,$width,$height,$srcwidth,$srcheight);
            }else{
                imagecopyresized($thumbImg,$srcImg,0,0,0,0,$width,$height,$srcwidth,$srcheight);
            }

            if ('gif' == $type || 'png' == $type) {
                $background_color = imagecolorallocate($thumbImg, 0, 255, 0);  //  指派一个绿色
                imagecolortransparent($thumbImg, $background_color);  //  设置为透明色，若注释掉该行则输出绿色的图
            }
            if ('jpg' == $type || 'jpeg' == $type)
                imageinterlace($thumbImg, $interlace);
            $imageFun = 'image' .$type;
            $imageFun($thumbImg, $thumbname);
            imagedestroy($thumbImg);
            imagedestroy($srcImg);
            return $thumbname;
        }
        return false;
    }

     //获取随机字符串
    public function getRandString($length) 
    {
        $str = "abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXY3456789";
        $len = strlen($str)-1;
        $ret = "";
        for($i=1;$i<=$length;$i++){
            $ret .= substr($str,mt_rand(0,$len),1);
        }
        return $ret;
    } 
}