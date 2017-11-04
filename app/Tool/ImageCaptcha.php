<?php
/**
 * User: salamander
 * Date: 2016/11/28
 * Time: 18:06
 * 图形验证码
 */

namespace App\Tool;


class ImageCaptcha
{
    /**
     * [yzm 验证码]
     * @param  int $width  验证码的宽度,默认100px
     * @param  int $height 验证码高度,默认40px
     * @param  int $type   验证码的类型,默认纯数字 =1
     * @param  int $length 验证码的长度 默认为4个
     * @return 验证码图片
     */
    public function create($width = 100, $height = 40, $type = 1, $length = 4) {
        //创建画布
        $img = imagecreatetruecolor($width, $height);
        //准备颜色  在外部写两个随机颜色的函数 深色darkColor() 浅色lightColor()
        //填充背景
        imagefill($img,0, 0, $this->lightColor($img));
        //干扰元素
        for ($i=0; $i < floor($width); $i++) {
            $x = mt_rand(0,$width);
            $y = mt_rand(0,$height);
            imagesetpixel($img, $x, $y, $this->darkColor($img));
        }
        for ($i=0; $i < floor($height/10); $i++) {
            $x1 = mt_rand(0,$width);
            $x2 = mt_rand(0,$width);
            $y1 = mt_rand(0,$height);
            $y2 = mt_rand(0,$height);
            imageline($img, $x1, $y1, $x2, $y2, $this->darkColor($img));
        }
        //产生源字符
        $str= '1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
        // echo $str[55];
        switch ($type) { //输出什么类型的验证码
            case 1: //纯数字
                $start = 0;
                $end = 9;
                break;
            case 2: //纯字母
                $start = 10;
                $end = 61;
                break;
            case 3: //混合型
                $start = 0;
                $end = 61;
                break;
        }

        $sjstr = ''; //初始值
        for ($i=0; $i < $length; $i++) {
            $sj = mt_rand($start, $end);
            $sjstr .= substr($str,$sj,1);
        }
        // echo $sjstr;
        $w = floor($width / $length);
        for ($i=0; $i < $length; $i++) {
            $x = $w * $i + 5;
            $y = mt_rand($height/2, $height);
            imagettftext(
                $img,
                ceil($width / 4),//字体大小随着宽度而变大,越宽越大
                mt_rand(-20, 20), //随机的角度
                $x,$y, //写入位置
                $this->darkColor($img), //随机颜色
                ROOT . '/asset/font/'.mt_rand(1,5).'.ttf', //随机的字体
                $sjstr[$i] //每回写一个
            );
        }

        $_SESSION['captcha_code'] = strtolower($sjstr);
        imagepng($img);
        //释放资源
        imagedestroy($img);
    }

    /**
     * 核对提交的验证码是否正确
     * @param string $value
     * @return boolean
     */
    public function check($value) {
        if(isset($_SESSION['captcha_code']) &&  strcmp(strtolower($value), $_SESSION['captcha_code']) === 0 )
            return true;
        else
            // 刷新
            if($_SESSION['captcha_code']) {
                unset($_SESSION['captcha_code']);
            }
            return false;
    }

    private function darkColor($img){
        return imagecolorallocate($img,mt_rand(0,100),mt_rand(0,100),mt_rand(0,100));
    }

    private function lightColor($img){
        return imagecolorallocate($img,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
    }
}