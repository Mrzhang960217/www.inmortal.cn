<?php
namespace app\index\controller;
use app\index\model\Outcheck;
use app\index\model\checkUpload;
use app\index\model\checkInfo;
use app\index\model\Backpass;
use app\index\model\Product;
use app\index\model\Indent;
use app\index\model\Shopping;
use think\Controller;
use think\Request;
// use think\Session;


class Index extends Controller
{
    protected static $encryption;

    public function __construct(Request $request)
    {
        parent::__construct();

        $session['username'] = session('username');
        $session['userId'] = session('user_id');
        $session['userPhone'] = session('user_phone');
        $session['userSex'] = session('sex');
        $session['headPortrait'] = session('head_portrait');
        $session['userEmail'] = session('user_email');
        $session['userBirthday'] = session('birthday');
        $session['address'] = session('address');

        $this->assign('session', $session);
    }

    // 公共加载头文件的购物车

    public static function shopCart()
    {
        $shopping = new Shopping();
        $userId = session('user_id');
        return $shopping->shop($userId);    
    }

    public function index()
    {
        // 加载静态方法shopCart来实现实时更新头文件数据
        $shop = self::shopCart();

        $shopNum = count($shop);
        $totalPrice = 0;

        foreach ($shop as $value) {
            $totalPrice += $value['total_price'];
        }

        $this->assign([
                'shop' => $shop,
                'shopNum' => $shopNum,
                'shopPri' => $totalPrice
            ]);

        return $this->fetch();
    }

    public function loginout(Outcheck $outcheck)
    {
        $session = $outcheck->checkout();
        $this->assign('session', $session);
        echo '<meta http-equiv="Refresh" content="0; url=index" />';
    }

    public function portal(Indent $indent)
    {
        // 加载静态方法shopCart来实现实时更新头文件数据
        $shop = self::shopCart();

        $shopNum = count($shop);
        $totalPrice = 0;

        foreach ($shop as $value) {
            $totalPrice += $value['total_price'];
        }


        $userId = session('user_id');
        // 所有订单数量
        $all = $indent->deliver($userId);
        $allNum = count($all);

        // 未发货订单数量
        $not = $indent->notSed($userId);
        $notNum = count($not);

        // 已发货订单数量
        $has = $indent->hasSed($userId);
        $hasNum = count($has);

        $this->assign([
                'allNum' => $allNum,
                'notNum' => $notNum,
                'hasNum' => $hasNum,
                'shop' => $shop,
                'shopNum' => $shopNum,
                'shopPri' => $totalPrice
            ]);
        return $this->fetch('portal/portal');
    }

    public function userInfo()
    {
        return $this->fetch('userInfo/userInfo');
    }

    public function home()
    {
        return $this->fetch('home/home');
    }

    // 处理头像上传
    public function upload(checkUpload $checkupload)
    {
        if (!request()->file('image')) {
            // return $this->error('失败', 'index/index/home', 0, 0);
            echo '<meta http-equiv="Refresh" content="0; url=home" />';
        } else {
            // 获取表单上传文件 例如上传了001.jpg
            $file = request()->file('image');
            // 移动到框架应用根目录/public/upload/ 目录下
            $info = $file->move(ROOT_PATH . 'public' . DS . 'upload');

            if($info){
            // 拼接全路径
            $path = '/upload/' . $info->getSaveName();
            $headPath = str_replace('\\', '/', $path);

            // 上传数据库并接收新的SESSION数据
            $checkupload->checkUp($headPath);
            $session['headPortrait'] = session('head_portrait');
            // $this->assign($session['headPortrait']);

            // 上传成功后返回来源页
            // return $this->fetch('home/home');
            echo '<meta http-equiv="Refresh" content="0; url=home" />';

            }else{
            // 上传失败获取错误信息
            echo $file->getError();
            }
        }
    }

    // 修改个人信息
    public function changeInfo(checkInfo $checkinfo)
    {
        $session['username'] = session('username');
        $session['userId'] = session('user_id');
        $session['userPhone'] = session('user_phone');
        $session['userSex'] = session('sex');
        $session['headPortrait'] = session('head_portrait');
        $session['userEmail'] = session('user_email');
        $session['userBirthday'] = session('birthday');
        $session['address'] = session('address');

        $this->assign($session);
        return $checkinfo->editInfo($_REQUEST);
    }

    // 发送邮箱验证
    public function coned()
    {
        $subject="小米账户密码验证找回";

        $email = $_REQUEST['email'];
        // $email="1049535354@qq.com";

        $pass = md5(time());
        $passPath = 'http://www.inmortal.com/index/Index/Pass?pass=' . $pass;

        $con = "<style class='fox_global_style'>
div.fox_html_content {
    line-height: 1.5;
}


/* 一些默认样式 */

blockquote {
    margin-Top: 0px;
    margin-Bottom: 0px;
    margin-Left: 0.5em
}

ol,
ul {
    margin-Top: 0px;
    margin-Bottom: 0px;
    list-style-position: inside;
}

p {
    margin-Top: 0px;
    margin-Bottom: 0px
}
</style>
<table style='-webkit-font-smoothing: antialiased;font-family:' 微软雅黑 ', 'Helvetica Neue ', sans-serif, SimHei;padding:35px 50px;margin: 25px auto; background:rgb(247,246, 242); border-radius:5px' border='0' cellspacing='0' cellpadding='0' width='640' align='center'>
    <tbody>
        <tr>
            <td style='color:#000;'> </td>
        </tr>
        <tr>
            <td style='padding:0 20px'>
                <hr style='border:none;border-top:1px solid #ccc;'>
            </td>
        </tr>
        <tr>
            <td style='padding: 20px 20px 20px 20px;'> Hi 尊敬的小米用户你好 </td>
        </tr>
        <tr>
            <td valign='middle' style='line-height:24px;padding: 15px 20px;'> 感谢您注册小米账户
                <br> 请点击以下链接修改您的密码： </td>
        </tr>
        <tr>
            <td style='height: 50px;color: white;' valign='middle'>
                <div style='padding:10px 20px;border-radius:5px;background: rgb(64, 69, 77);margin-left:20px;margin-right:20px'> <a style='word-break:break-all;line-height:23px;color:white;font-size:15px;text-decoration:none;' href='$passPath'>$passPath</a> </div>
            </td>
        </tr>
        <tr>
            <td style='padding: 20px 20px 20px 20px'> 请勿回复此邮件，如果有疑问，请联系我们：<a style='color:#5083c0;text-decoration:none' href='1049535354@qq.com'>1049535354@qq.com
    // </a> </td>
        </tr>
    </tbody>
</table>";
        // $con = "<h1>$passPath</h1>";
        $status = send($email,$subject,$con);
        if($status){
            // 如果成功，将加密路径存到SESSION中，如果已存在，将其覆盖
            // session('passPath', $passPath);
            self::$encryption = $pass;
            echo json_encode(['status' => 1, 'msg' => '发送成功、3秒后跳到首页']);
        }else{
            echo json_encode(['status' => 0, 'msg' => '发送失败']);
        }
    }

    // 修改密码
    public function changePass()
    {
        $urlPass = ltrim(strstr($_SERVER['REDIRECT_URL'], '='), '=');

        if ($urlPass == '') {
            // 加载静态方法shopCart来实现实时更新头文件数据
            $shop = self::shopCart();

            $shopNum = count($shop);
            $totalPrice = 0;

            foreach ($shop as $value) {
                $totalPrice += $value['total_price'];
            }

            $this->assign([
                    'shop' => $shop,
                    'shopNum' => $shopNum,
                    'shopPri' => $totalPrice
                ]);
            return $this->fetch('index/index');
        }

        if (self::$encryption == $urlPass) {
            return $this->fetch('pass/pass');
        } else {
            // 加载静态方法shopCart来实现实时更新头文件数据
            $shop = self::shopCart();

            $shopNum = count($shop);
            $totalPrice = 0;

            foreach ($shop as $value) {
                $totalPrice += $value['total_price'];
            }

            $this->assign([
                    'shop' => $shop,
                    'shopNum' => $shopNum,
                    'shopPri' => $totalPrice
                ]);
            return $this->fetch('index/index');
        }
    }

    public function dopass()
    {
        return $this->fetch('pass/dopass');
    }

    public function pass()
    {
        $urlPass = ltrim(strstr($_SERVER['REDIRECT_URL'], '='), '=');
        if ($urlPass == '' || self::$encryption == $urlPass) {
            // 加载静态方法shopCart来实现实时更新头文件数据
            $shop = self::shopCart();

            $shopNum = count($shop);
            $totalPrice = 0;

            foreach ($shop as $value) {
                $totalPrice += $value['total_price'];
            }

            $this->assign([
                    'shop' => $shop,
                    'shopNum' => $shopNum,
                    'shopPri' => $totalPrice
                ]);
            return $this->fetch('index/index');
        }

        return $this->fetch('pass/pass');
    }

    public function checkuser(Backpass $backpass)
    {
        return $backpass->checkusername($_REQUEST);
    }

    public function doBack(Backpass $backpass)
    {
        return $backpass->back($_REQUEST);
    }

    public function service(Product $product)
    {
        // 加载静态方法shopCart来实现实时更新头文件数据
        $shop = self::shopCart();

        $shopNum = count($shop);
        $totalPrice = 0;

        foreach ($shop as $value) {
            $totalPrice += $value['total_price'];
        }

        $pro = $product->proName();
        $goods = $product->goodsName();
        
        $this->assign([
                'product' => $pro,
                'goods' => $goods,
                'shop' => $shop,
                'shopNum' => $shopNum,
                'shopPri' => $totalPrice
            ]);
        return $this->fetch('service/service');
    }

    // 查询所有的订单
    public function order(Indent $indent)
    {
         // 加载静态方法shopCart来实现实时更新头文件数据
        $shop = self::shopCart();

        $shopNum = count($shop);
        $totalPrice = 0;

        foreach ($shop as $value) {
            $totalPrice += $value['total_price'];
        }

        $userId = session('user_id');
        $orderInfo = array_reverse($indent->deliver($userId));

        $this->assign([
                'shop' => $shop,
                'shopNum' => $shopNum,
                'shopPri' => $totalPrice,
                'orderInfo' => $orderInfo
            ]);
        return $this->fetch('order/order');
    }

    // 查询未发货的订单
    public function notSend(Indent $indent)
    {
         // 加载静态方法shopCart来实现实时更新头文件数据
        $shop = self::shopCart();

        $shopNum = count($shop);
        $totalPrice = 0;

        foreach ($shop as $value) {
            $totalPrice += $value['total_price'];
        }

        $userId = session('user_id');
        $orderInfo = array_reverse($indent->notSed($userId));

        $this->assign([
                'shop' => $shop,
                'shopNum' => $shopNum,
                'shopPri' => $totalPrice,
                'orderInfo' => $orderInfo
            ]);
        return $this->fetch('order/order');
    }

    // 查询已发货的订单
    public function hasSend(Indent $indent)
    {
         // 加载静态方法shopCart来实现实时更新头文件数据
        $shop = self::shopCart();

        $shopNum = count($shop);
        $totalPrice = 0;

        foreach ($shop as $value) {
            $totalPrice += $value['total_price'];
        }

        $userId = session('user_id');
        $orderInfo = array_reverse($indent->hasSed($userId));

        $this->assign([
                'shop' => $shop,
                'shopNum' => $shopNum,
                'shopPri' => $totalPrice,
                'orderInfo' => $orderInfo
            ]);
        return $this->fetch('order/order');
    }

    // 查询已收货的订单
    public function yetSend(Indent $indent)
    {
         // 加载静态方法shopCart来实现实时更新头文件数据
        $shop = self::shopCart();

        $shopNum = count($shop);
        $totalPrice = 0;

        foreach ($shop as $value) {
            $totalPrice += $value['total_price'];
        }

        $userId = session('user_id');
        $orderInfo = array_reverse($indent->yetSed($userId));

        $this->assign([
                'shop' => $shop,
                'shopNum' => $shopNum,
                'shopPri' => $totalPrice,
                'orderInfo' => $orderInfo
            ]);
        return $this->fetch('order/order');
    }

    // 处理商品收货
    public function yet(Indent $indent)
    {
         // 加载静态方法shopCart来实现实时更新头文件数据
        $shop = self::shopCart();

        $shopNum = count($shop);
        $totalPrice = 0;

        foreach ($shop as $value) {
            $totalPrice += $value['total_price'];
        }

        $userId = session('user_id');
        $orderInfo = array_reverse($indent->yetSed($userId));
        $indent->doYet(input('param.oid'));

        $this->assign([
                'shop' => $shop,
                'shopNum' => $shopNum,
                'shopPri' => $totalPrice,
                'orderInfo' => $orderInfo
            ]);
        return $this->fetch('order/order');
    }

    // 处理订单评论
    public function massage(Indent $indent)
    {
        return $indent->msg($_REQUEST);
    }

    // 订单搜索
    public function search(Indent $indent)
    {
        // 加载静态方法shopCart来实现实时更新头文件数据
        $shop = self::shopCart();

        $shopNum = count($shop);
        $totalPrice = 0;

        foreach ($shop as $value) {
            $totalPrice += $value['total_price'];
        }

        $userId = session('user_id');
        $orderInfo = array_reverse($indent->seek($_REQUEST));
        $indent->doYet(input('param.oid'));

        $this->assign([
                'shop' => $shop,
                'shopNum' => $shopNum,
                'shopPri' => $totalPrice,
                'orderInfo' => $orderInfo
            ]);
        return $this->fetch('order/order');
    }

    public function mi5()
    {
        // 加载静态方法shopCart来实现实时更新头文件数据
        $shop = self::shopCart();

        $shopNum = count($shop);
        $totalPrice = 0;

        foreach ($shop as $value) {
            $totalPrice += $value['total_price'];
        }

        $this->assign([
                'shop' => $shop,
                'shopNum' => $shopNum,
                'shopPri' => $totalPrice
            ]);
        $goodsId = input('param.goodsId');

        $this->assign('goodsId', $goodsId);
        return $this->fetch('mi5/mi5');
    }

    public function mi5s()
    {
        // 加载静态方法shopCart来实现实时更新头文件数据
        $shop = self::shopCart();

        $shopNum = count($shop);
        $totalPrice = 0;

        foreach ($shop as $value) {
            $totalPrice += $value['total_price'];
        }

        $this->assign([
                'shop' => $shop,
                'shopNum' => $shopNum,
                'shopPri' => $totalPrice
            ]);
        $goodsId = input('param.goodsId');

        $this->assign('goodsId', $goodsId);
        return $this->fetch('mi5s/mi5s');
    }

    public function mi5splus()
    {
        // 加载静态方法shopCart来实现实时更新头文件数据
        $shop = self::shopCart();

        $shopNum = count($shop);
        $totalPrice = 0;

        foreach ($shop as $value) {
            $totalPrice += $value['total_price'];
        }

        $this->assign([
                'shop' => $shop,
                'shopNum' => $shopNum,
                'shopPri' => $totalPrice
            ]);
        $goodsId = input('param.goodsId');

        $this->assign('goodsId', $goodsId);
        return $this->fetch('mi5splus/mi5splus');
    }

    public function mimax()
    {
        // 加载静态方法shopCart来实现实时更新头文件数据
        $shop = self::shopCart();

        $shopNum = count($shop);
        $totalPrice = 0;

        foreach ($shop as $value) {
            $totalPrice += $value['total_price'];
        }

        $this->assign([
                'shop' => $shop,
                'shopNum' => $shopNum,
                'shopPri' => $totalPrice
            ]);
        $goodsId = input('param.goodsId');

        $this->assign('goodsId', $goodsId);
        return $this->fetch('mimax/mimax');
    }

    public function minote2()
    {
        // 加载静态方法shopCart来实现实时更新头文件数据
        $shop = self::shopCart();

        $shopNum = count($shop);
        $totalPrice = 0;

        foreach ($shop as $value) {
            $totalPrice += $value['total_price'];
        }

        $this->assign([
                'shop' => $shop,
                'shopNum' => $shopNum,
                'shopPri' => $totalPrice
            ]);
        $goodsId = input('param.goodsId');

        $this->assign('goodsId', $goodsId);
        return $this->fetch('minote2/minote2');
    }

    public function mimix()
    {
        // 加载静态方法shopCart来实现实时更新头文件数据
        $shop = self::shopCart();

        $shopNum = count($shop);
        $totalPrice = 0;

        foreach ($shop as $value) {
            $totalPrice += $value['total_price'];
        }

        $this->assign([
                'shop' => $shop,
                'shopNum' => $shopNum,
                'shopPri' => $totalPrice
            ]);
        $goodsId = input('param.goodsId');

        $this->assign('goodsId', $goodsId);
        return $this->fetch('mimix/mimix');
    }

    public function redmi3s()
    {
        // 加载静态方法shopCart来实现实时更新头文件数据
        $shop = self::shopCart();

        $shopNum = count($shop);
        $totalPrice = 0;

        foreach ($shop as $value) {
            $totalPrice += $value['total_price'];
        }

        $this->assign([
                'shop' => $shop,
                'shopNum' => $shopNum,
                'shopPri' => $totalPrice
            ]);
        $goodsId = input('param.goodsId');

        $this->assign('goodsId', $goodsId);
        return $this->fetch('redmi3s/redmi3s');
    }

    public function redmi4()
    {
        // 加载静态方法shopCart来实现实时更新头文件数据
        $shop = self::shopCart();

        $shopNum = count($shop);
        $totalPrice = 0;

        foreach ($shop as $value) {
            $totalPrice += $value['total_price'];
        }

        $this->assign([
                'shop' => $shop,
                'shopNum' => $shopNum,
                'shopPri' => $totalPrice
            ]);
        $goodsId = input('param.goodsId');

        $this->assign('goodsId', $goodsId);
        return $this->fetch('redmi4/redmi4');
    }

    public function redmi4a()
    {
        // 加载静态方法shopCart来实现实时更新头文件数据
        $shop = self::shopCart();

        $shopNum = count($shop);
        $totalPrice = 0;

        foreach ($shop as $value) {
            $totalPrice += $value['total_price'];
        }

        $this->assign([
                'shop' => $shop,
                'shopNum' => $shopNum,
                'shopPri' => $totalPrice
            ]);
        $goodsId = input('param.goodsId');

        $this->assign('goodsId', $goodsId);
        return $this->fetch('redmi4a/redmi4a');
    }

    public function redmi3x()
    {
        // 加载静态方法shopCart来实现实时更新头文件数据
        $shop = self::shopCart();

        $shopNum = count($shop);
        $totalPrice = 0;

        foreach ($shop as $value) {
            $totalPrice += $value['total_price'];
        }

        $this->assign([
                'shop' => $shop,
                'shopNum' => $shopNum,
                'shopPri' => $totalPrice
            ]);
        $goodsId = input('param.goodsId');

        $this->assign('goodsId', $goodsId);
        return $this->fetch('redmi3x/redmi3x');
    }

    public function redminote4()
    {
        // 加载静态方法shopCart来实现实时更新头文件数据
        $shop = self::shopCart();

        $shopNum = count($shop);
        $totalPrice = 0;

        foreach ($shop as $value) {
            $totalPrice += $value['total_price'];
        }

        $this->assign([
                'shop' => $shop,
                'shopNum' => $shopNum,
                'shopPri' => $totalPrice
            ]);
        $goodsId = input('param.goodsId');

        $this->assign('goodsId', $goodsId);
        return $this->fetch('redminote4/redminote4');
    }

    public function redmipro()
    {
        // 加载静态方法shopCart来实现实时更新头文件数据
        $shop = self::shopCart();

        $shopNum = count($shop);
        $totalPrice = 0;

        foreach ($shop as $value) {
            $totalPrice += $value['total_price'];
        }

        $this->assign([
                'shop' => $shop,
                'shopNum' => $shopNum,
                'shopPri' => $totalPrice
            ]);
        $goodsId = input('param.goodsId');

        $this->assign('goodsId', $goodsId);
        return $this->fetch('redmipro/redmipro');
    }
}
