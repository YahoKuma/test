<?php
include_once 'db.php';
include_once 'online.php';

class Account{
	const NAMENOTEXIST = -1;
	const PASSWORDINVALID = -2;
	const KEYUSERID = 'user_id';
	const KEYNAME = 'user_name';
	private $db ;
	private $online;
	
	
	public function __construct(){
		Session_start();
		$this->db = DB::init();
		$this->online = new Online();
		$this->online->update();
		$this->online->persistent();
	}
	
	
	public function logout(){
		session_destroy();
		
	
	}
	
	
	public function getOnline(){
		return $this->online;
	}
	
	public function regist($name, $password){
		$user = $this->db->user();
		$rs = $user->insert(
				array(
					'last_login' =>	time(),
					'count' =>	0,
					'password'	=>	md5($password),
					'username'	=>	$name
				)	
			);
		
		return $rs;
	}
	
	public function username_exist($username){

		$rs = $this->db->
			user()->select('count(1)')
			->where('username = '.$username)->fetch();
		
		return
			$rs['count(1)'] == 1;
	}
	
	public function login($username, $password){
		
		if(!$this->username_exist($username))
			return Account::NAMENOTEXIST;
		$rs = $this->db->user()
			->select('*')
			->where('username = ? AND password = ?',$username, md5($password))
			->fetch();
		
		if( $rs ){
			$_SESSION[Account::KEYUSERID] = $rs['id'];
			$user = $this->db->user[$rs['id'] ] ;

			$user->update(array(
					'count'	=>	$rs['count'] + 1,
					'last_login'	=>	time()
				));
			
			$_SESSION[Account::KEYNAME] = $rs['username'];
			return 0;
		}else{
			return Account::PASSWORDINVALID;
		}
		
	}
	
	public function getLoginMinite(){
		$uid = $_COOKIE[online::COOKIENAME]; 
		if($this->is_login())
			$time = $this->db->user[$this->getUid()]['last_login'];
		else{
			$time = $this->db->user[$uid]['first'];
		}
		return intval((time() - $time)/60);
		
	}
	
	private function getUid(){
		return $_SESSION[Account::KEYUSERID];
	}
	
	public function get_login_count(){
		$rs = $this->db->user()
			->select('id, username, last_login,count' )
			->where('id = '.$this->getUid())->fetch();
		
		return $rs['count'];
		
	}
	
	public function is_login(){ 
		return 
			isset($_SESSION[Account::KEYUSERID]);
	}
	
}