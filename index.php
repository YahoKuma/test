<script type="text/javascript" async="" src="jquery-1.11.1.min.js"></script>
<script type="text/javascript" async="" src="index.js"></script>
<?php
include_once 'config.php';
include_once 'NotORM.php';
include 'Account/account.php';

$account = new Account();
$statistic = $account->getOnline()->getOnlinePeople();

if( $account->is_login() ){
	echo $_SESSION[Account::KEYNAME].' 谢谢你登录了我们网站！你已经登录了 '.$account->get_login_count().' 次了，总共登录时间是'.$account->getLoginMinite().' 分钟了。';
}else{
?>
	你好，陌生人！你没有登录，或者你还没有注册，但是你已经浏览这个页面 <?php echo $account->getLoginMinite(); ?> 分钟了。
	<table>
		<tr>
			<td>用户名：<td>
			<td><input type="text" id="username" /></td>
		</tr>
		<tr>
			<td>密码：<td>
			<td><input type="password" id="password" /></td>
		</tr>
		<tr>
			<td><button id="regist" >注册</button><td>
			<td><button id="login" >登录</button></td>
		</tr>
	</table>
	<?php
}?>

<div>
<?php
	echo '现在总共有 '.$statistic['online'].' 个注册用户在查看这个网站，有 '.$statistic['offline'].' 个陌生人在查看这个网站';
?>
</div>
<?php
/*
echo '{用户名字} 谢谢你登录了我们网站！你已经登录了 {N} 次了，总共登录时间是 {M} 分钟了。';

echo '你好，陌生人！你没有登录，或者你还没有注册，但是你已经浏览这个页面 {M} 分钟了。';



echo '现在总共有 {n} 个注册用户在查看这个网站，有 {m} 个陌生人在查看这个网站';
*/

?>