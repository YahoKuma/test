<?php
include_once 'db.php';

class Online{
	
	const FILENAME = 'online.txt';  //数据文件，如果没有新建
	const COOKIENAME = 'VGOTCN_OnLineCount';  //cookie名称
	const ONLINETIME = 600;  //在线有效时间，单位：秒 (即600等于10分钟)
	
	private $db;
	private $nowonline;
	private $nowtime;
	
	public function  __construct(){
		@$this->db = DB::init();
		$this->nowtime = time();
		$this->nowonline = array();
		
		$this->init_online_array();
	}
	
	//初始化在线数组
	private function init_online_array(){
		if(!empty($this->online)){
			$cur = $this->db->current();
			
			foreach($cur as $line) {
				
				if(($this->nowtime -  $line['time']) <= Online::ONLINETIME) { 
					$nowonline[ $line['sessionId'] ] = $line['time'];  
				}
			}
		}
	}
	
	private  function isLogin(){
		return 
			isset($_SESSION[Account::KEYUSERID]);
	}
	
	//更新在线人数
	public function update(){
		$first = false;
		if(isset($_COOKIE[online::COOKIENAME])) {  //如果有COOKIE即并非初次访问则不添加人数并更新通信时间
			$uid = $_COOKIE[online::COOKIENAME];
			$first = $_COOKIE['FIRSTTIME'];
		} else {  //如果没有COOKIE即是初次访问
			$vid = 0;  //初始化访问者ID
			do {  //给用户一个新ID
				$vid++;
				$uid = 'U'.$vid;
			} while (array_key_exists($uid,$this->nowonline));
			$first = time();
			setcookie('FIRSTTIME',$first);
			setcookie(online::COOKIENAME,$uid);
		}
		$this->nowonline[$uid] = array( 'time'	=> $this->nowtime, 'l'	=> $this->isLogin() , 'first' => $first);  //更新现在的时间状态
	}
	
	public function persistent(){
		$this->db->current()->delete();
		
		$rs = array();
		foreach ($this->nowonline as $uid => $v){
			array_push($rs, array(
				'sessionId'	=>	$uid,
				'time'		=>	$v['time'],
				'isLogin'	=>	$v['l'] ? 1:0,
				'first'		=>	$v['first']
			));
		}
		
		$this->db->current()->insert_multi(
				$rs
		);
		
	}
	
	public function getOnlinePeople(){
		$rs = 
			$this->db->current()->select('count(*)') ->where('isLogin = ?', 1 )->fetch();
			
		return 
			array(
				'total'	=>
					count($this->nowonline),
				'online'	=>	$rs['count(*)'],
				'offline'	=>	count($this->nowonline) -$rs['count(*)'] 
			);
	}
	
	
	
}