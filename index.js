
function getUsernameAndPassword(){
	var name = $('#username').val();
	var psd = $('#password').val();
	if( !name ){
		alert('用户名不能为空！');
		return -1;
	}
	if( !psd ){
		alert('密码不能为空！');
		return -1;
	}
	
	return {username:name,password:psd};
}

$(function(){
	$('#regist').click(function(){
		var data = getUsernameAndPassword();
		if(data ===  -1)
			return;
		$.post( 'api.php',$.extend(data,{ action:'regist'}),function(rs){
			alert(rs.result);
		},'json' );
	});
	
	$('#login').click(function(){
		var data = getUsernameAndPassword();
		if(data ===  -1)
			return;
		$.post( 'api.php',$.extend(data,{ action:'login'}),function(rs){
			alert(rs.result);
			if(rs.status)
				history.go(0);
		},'json' );
	});
});