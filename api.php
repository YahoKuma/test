<?php
include_once 'config.php';
include_once 'NotORM.php';
include 'Account/account.php';

$account = new Account();

$action = $_REQUEST['action'];

$rs =  array(
		'status'	=>	false,
		'result'	=> '未定义操作！'
	);
	
try{
	if($action == 'regist'){
		$username = $_REQUEST['username'];
		$password = $_REQUEST['password'];
		
		if($account->username_exist($username)){
			$rs['result'] =  '用户名已存在！';
		}else{
			if( !$account->regist($username,$password) ){
				$rs['result'] = '注册失败!';
			}else{
				$rs['result'] = '注册成功！';
				$rs['status'] = true;
			}
		}
	}
	
	if( 'login' == $action ){
		$username = $_REQUEST['username'];
		$password = $_REQUEST['password'];
		$resultVal = array( 
			0	=>	'登录成功！',
			-1	=>	'用户名不存在！',
			-2	=>	'密码错误！'
		);
		$r = $account->login($username,$password);
		if( $r === 0){
			$rs['status'] = true;
		}
		$rs['result'] = $resultVal[$r];
		
	}
	
	
	if('statistic' == $action ){
		$rs = $account->getOnline()->getOnlinePeople();
	}
	
	if('logout' == $action ){
		 $account->logout();
		$rs['status'] = true;
		$rs['result'] = '成功！';
	}
}catch (PDOException $e) { 
	$rs['msg'] =  $e->getMessage(); 
} 
echo json_encode($rs );