<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\User;
use app\admin\model\Home;
use app\admin\model\Order;
use app\admin\model\Version;
use app\admin\model\Product_type;
class Index extends Controller
{
 	//用户登录
	public function login(User $user)
	{
		return $this->fetch();
	}

	public function doLogin(User $user)
	{
			$user = $user->user_info($_POST);
			
			if($user['permissions']){
				if($user['username'] != $_POST['username']){
					echo json_encode(['status'=>0,'msg'=>'用户名不存在']);
					die();
				} elseif($user['password'] != $_POST['password']){
					echo json_encode(['status'=>0,'msg'=>'密码错误']);
					die();
				}elseif(!captcha_check($_POST['verify'])){
					echo json_encode(['status'=>0,'msg'=>'验证码错误']);
					die();
				} else {
					echo json_encode(['status'=>1,'msg'=>'登录成功']);
					die();
				}
				
				if(!captcha_check($_POST['verify'])){
					echo '验证失败';
				} else {
					echo '验证成功';
				}
			} else {
				echo json_encode(['status'=>0,'msg'=>'对不起,您不是管理员']);
				die();
			}

	}
	//删除用户
	public function user_delete(User $user)
	{
		$result = $user->userDel($_POST);
		echo $result;
	}
	//设置管理员
	public function set_user(User $user)
	{
		$result = $user->setuser($_POST);
		echo $result;
	}
	//后台系统首页
	public function home(Home $home,Order $order,Version $ver)
	{
		//用户数量
		$userNum = $home->info();
		//订单数量
		$orderNumber = $order->info();
		//代发货数量
		$backNumber = $order->back_order();
		//已发货数量
		$orderNum = $order->select_order();
		//商品总数
		$verNum = $ver->products_list();
		//上下架数量
		$updown = $ver->upDown();
		//时间
		$time = time();
		$this->assign('userNum',$userNum);
		$this->assign('time',$time);
		$this->assign('up',$updown[1]);
		$this->assign('down',$updown[0]);
		$this->assign('verNum',$verNum);
		$this->assign('orderNum',$orderNum[1]);
		$this->assign('backNumber',$backNumber);
		$this->assign('orderNumber',$orderNumber[1]);
		return $this->fetch();
	}
	//后台主页
	public function admin_index(User $user)
	{

		return $this->fetch();
	}
	//产品类表
	public function products_list(Version $pro)
	{
		$products = $pro->products_list();
		$this->assign('products',$products[0]);
		$this->assign('total_count',$products[1]);
		return $this->fetch();
	}
	//产品管理
	public function brand_Manage()
	{
		return $this->fetch();
	}
	//添加产品类型
	public function add_type(Version $pro)
	{
		echo $pro->addType($_POST);
	}
	//禁用商品类型
	public function soft_delete(Product_type $pro)
	{
		echo $pro->soft_del($_POST);
	} 

	//删除商品类型
	public function typeDel(Product_type $pro)
	{
		echo $pro->type_delete($_POST);
	}
	//添加商品
	public function picture_add(Version $pro)
	{
		$attr = $pro->attribute();
		$Ptype = $pro->phoneType();
		$this->assign('attr',$attr);
		$this->assign('Ptype',$Ptype);
		return $this->fetch();
	}
	public function goods_add(Version $pro)
	{
		foreach($_POST as $key => $value){
			if(empty($_POST[$key])){
				$attr = $pro->attribute();
				$Ptype = $pro->phoneType();
				$this->assign('attr',$attr);
				$this->assign('Ptype',$Ptype);
				return $this->fetch('picture_add');
				die();
			}
		}
	
		$files = request()->file('image');
		$arr = [];
		foreach($files as $file){
			// 移动到框架应用根目录/public/uploads/ 目录下
			$info = $file->move(ROOT_PATH . 'public' . DS . 'upload');
		
			$arr[] = $info->getSaveName();
			
		}
		 $pic=[];
		 for($i=0;$i<count($arr);$i++){
		 	if(!$arr[$i] == ''){
		 		$pic['goods_pic'.$i.''] = '/upload/'.str_replace("\\", "/",$arr[0]);
		 	}
		 }
		 $result = $pro->add($_POST,$pic);
		if($result){
			echo '<meta http-equiv="Refresh" content="1; url=picture_add" />';
		}
		
	}
	//删除商品（软）
	public function goods_delete(Version $pro)
	{
		$pro->goods_delete($_POST);
	}
	//商品回收站
	public function product_back(Version $ver)
	{
		$result = $ver->goodsback();
		$total_count = count($result);
		$this->assign('products',$result);
		$this->assign('total_count',$total_count);
		return $this->fetch();
	}
	//还原商品
	public function productBack(Version $ver)
	{
		$result = $ver->proback($_POST);
	}
	//删除商品
	public function goodsdelete(Version $ver)
	{
		$resutl = $ver->delprouct($_POST);
	}
	//编辑商品
	public function goods_update(Version $ver)
	{
		$version_id = input('param.versionId');
		$result = $ver->goods_select($version_id);
		$this->assign('result',$result);
		return $this->fetch();
	}
	//修改商品
	public function goodsupdate(Version $ver)
	{
		$files = request()->file('image');
		
		$arr = [];
		if(empty($files)){
			$result = $ver->goodsup($_POST);
			if($result){
				echo '<meta http-equiv="Refresh" content="0; url=products_list" />';
				die;
			} else {
				echo '<meta http-equiv="Refresh" content="0; url=goods_update" />';
				die;
			}
		}else{
			$info = $files->move(ROOT_PATH . 'public' . DS . 'upload');	
			$arr[] = $info->getSaveName();
			$pic['version_pic'] = '/upload/'.str_replace("\\", "/",$arr[0]);
			
			$result = $ver->goodsup1($pic,$_POST);
			if($result){
				echo '<meta http-equiv="Refresh" content="0; url=products_list" />';
				die;
			} else {
				echo '<meta http-equiv="Refresh" content="0; url=goods_update" />';
				die;
			}
		}
		
	}

	//商品状态
	public function product_status(Version $pro)
	{
		$result = $pro->goods_status($_POST);
		echo $result;
	}
	//分类管理
	public function category_manage()
	{
		return $this->fetch();
	}
	//添加分类
	public function product_category_add(Version $pro)
	{
		$productType_list = $pro->product_type();
		$this->assign('pro_list',$productType_list);
		return $this->fetch();
	}


	//交易信息
	public function transaction(Order $order)
	{
		//交易金额 查找已收货
		$money = $order->overOrder();
		//全部订单
		$orderNum = $order->info();
		$this->assign('money',$money[2]);
		$this->assign('orderNum',$orderNum[1]);
		$this->assign('vic',$money[3]);
		return $this->fetch();
	}
	//交易订单（图）
	public function order_chart()
	{
		return $this->fetch();
	}
	//待发货订单
	public function orderform(Order $order)
	{
		$back_order = $order->back_order();
		$this->assign('back_order',$back_order[0]);
		return $this->fetch();
	}
	//处理代发货
	public function doback(Order $order)
	{
		dump($_POST['express_number']);
		dump($_POST['express']);
		if($_POST['express'] == '' || $_POST['express_number'] == ''){
			echo 0;
			return false;
		} else {
			$result = $order->doback_order($_POST);
			echo 1;
			echo $result;
		}
		
	}
	//已发货订单
	public function orderform1(Order $order)
	{
		$result = $order->select_order();
		$this->assign('result',$result[0]);
		return $this->fetch();
	}
	//订单详情
	public function order_detailed()
	{
		return $this->fetch();
	}
	//删除待发货订单
	public function deleteOrder(Order $order)
	{
		$order->order_delete($_POST);
		echo $order;
	}
	//删除已发货 已收货
	public function delete_order(Order $order)
	{
		$result = $order->orderDelete($_POST);
		echo $result;
	}
	//删除回收站
	public function deleteback(Order $order)
	{
		$result = $order->delback($_POST);
		echo $result;
	}
	//交易金额
	public function amounts(Order $order)
	{	
		$time = time();
		$total = $order->overOrder();
		$this->assign('money',$total[2]);
		$this->assign('time',$time);
		$this->assign('vic',$total[3]);
		$this->assign('select',$total[0]);
		return $this->fetch();
	}
	//订单处理
	public function order_handling()
	{
		return $this->fetch();
	}
	//已收货
	public function refund(Order $order)
	{
		$result = $order->overorder();
		$this->assign('result',$result[0]);
		return $this->fetch();
	}
	//回收站
	public function back(Order $order)
	{
		$result = $order->back_goods();
		$this->assign('result',$result);
		return $this->fetch();
	}
	//还原已发货为待发货
	public function orderback(Order $order)
	{
		$result = $order->order_back($_POST);
		echo $result;
	}
	//还原已收货为已发货
	public function overback(Order $order)
	{
		$result = $order->over_back($_POST);
		echo $result;
	}
	//还原回收站
	public function goodsback(Order $order)
	{
		$result = $order->goods_back($_POST);
		echo $result;
	}
	//用户管理
	public function user_list(User $user)
	{
		$userList = $user->userInfo();
		$total_user = count($userList);
		$this->assign('userList',$userList);
		$this->assign('total_user',$total_user);
		return $this->fetch();
	}
	//订单评论
	public function guestbook(Order $order)
	{
		$result = $order->overorder();
		$this->assign('result',$result[0]);
		return $this->fetch();
	}
	//删除订单评论
	public function delete_notice(Order $order)
	{
		//echo $_POST['order_id'];
		$result = $order->del_notice($_POST);
		echo $result;
	}
	//用户记录管理
	public function integration()
	{
		return $this->fetch();
	}
	//系统栏目(导航)管理
	public function system_section()
	{
		return $this->fetch();
	}
	//系统日志
	public function system_logs()
	{
		return $this->fetch();
	}
	//权限管理
	public function admin_competence()
	{
		return $this->fetch();
	}
	//管理员列表
	public function administrator()
	{
		return $this->fetch();
	}
	//管理员信息
	public function admin_info()
	{
		return $this->fetch();
	}
}

