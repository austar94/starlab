let userIDCheck     = 	0;
let userPWCheck     = 	0;
let userMailCheck	=	0;
let userRPWCheck    = 	0;

//회원가입
$('#loginBtn').on('click', function(){
	let moblieExp				=	/^\d{3}\d{3,4}\d{4}$/;
	let PWDCheck					=	PWDRuleCheck(PWD.val());
	let PWDResult					=	PWDCheck.result;
	let PWDMsg						=	PWDCheck.msg;

	if(!$('input[name="userID"]').val().trim()){
		alert('아이디를 입력해주세요.');
		$('input[name="userID"]').focus();
		return;
	}

	if(!userIDCheck){
		alert('아이디의 중복확인을 실시해주세요.');
		return;
	}

	if(!$('input[name="userPWD"]').val().trim()){
		alert('비밀번호를 입력해주세요.');
		$('input[name="userPWD"]').focus();
		return;
	}

	if(!PWDResult){
		alert(PWDMsg);
		$('input[name="userPWD"]').focus();
		return;
	}

	if(!$('input[name="re_userPWD"]').val().trim()){
		alert('비밀번호 확인을 입력해주세요.');
		$('input[name="re_userPWD"]').focus();
		return;
	}

	if($('input[name="userPWD"]').val() !== $('input[name="re_userPWD"]').val()){
		alert('비밀번호가 일치하지 않습니다.');
		$('input[name="re_userPWD"]').focus();
		return;
	}

	if(!$('input[name="userMobile"]').val().trim()){
		alert('휴대폰번호를 입력해주세요.');
		$('input[name="userMobile"]').focus();
		return;
	}

	if (!moblieExp.test($('input[name="userMobile"]').val())){
		alert("잘못된 휴대폰 번호입니다.");
		$('input[name="userMobile"]').focus();
		return;
	}

	if(!$('input[name="userMail"]').val().trim()){
		alert('이메일 주소를 입력해주세요.');
		$('input[name="userMail"]').focus();
		return;
	}

	if(!userMailCheck){
		alert('이메일 양식에 맞지않습니다.');
		$('input[name="userMail"]').focus();
		return;
	}

	if(!$('input[name="check"]').prop('checked')){
		alert('필수사항 체크 후 가입가능합니다.');
		return;
	}

	if(!$('input[name="userZip"]').val().trim()){
		alert('우편번호를 입력해주세요.');
		return;
	}

	if(!$('input[name="userAddr2"]').val().trim()){
		alert('상세주소를 입력해주세요.');
		return;
	}
	

	let form				=   document.querySelector("#frm");
	let postData			=   new FormData(form);

	let url					=	'/intro/event/join_joinProc';
	let dataType			=	'json';
	let param				= 	postData;
	let formType			=	1;

	postService(url, dataType, param, '', formType);


})

//이메일 확인
$('input[name="userMail"]').on('keyup', function(){
	let mail				=	$(this).val();

	if(emailCheck(mail)){
		userMailCheck		=	1;
	} else {
		userMailCheck		=	0;
		$('#mailText').text('메일 형식이 올바르지않습니다.');
		return;
	}
	
})

//비밀번호 확인
$('input[name="re_userPWD"]').on('keyup', function(){
	let userPWD				=	$('#userPWD').val();
	let reUserPWD			=	$(this).val();
	userRPWCheck			=	0;

	if(userPWD){
		if(userPWD != reUserPWD){
			$('#re_pwText').text('비밀번호가 일치하지 않습니다.');
			return;
		} else{
			userRPWCheck	=	1;
			$('#re_pwText').text('');
			return;
		}
	}
})


//비밀번호 체크
$('input[name="userPWD"]').on('keyup', function(){
	let PWD				=	$(this);
	let check			=	PWDRuleCheck(PWD.val());
	let result			=	check.result;
	let msg				=	check.msg;

	$('#pwText').text(msg);
	if(result)	userPWCheck	=	1;
})

/**
 * 비밀번호 체크(규칙, 자릿수)
 * @param {string} PWD 비밀번호
 * @returns {Array} 실패시 메시지, 성공여부
 */
function PWDRuleCheck(PWD){
	let mixCount			=	0;
	let result				=	array();
	let num					=	PWD.search(/[0-9]/g);
	let eng					=	PWD.search(/[a-z]/ig);
	let spe					=	PWD.search(/[`~!@@#$%^&*|₩₩₩'₩";:₩/?]/gi);
	
	if(PWD.length < 8 || PWD.length > 15){
		result				=	array(
			'msg'				=	'영문, 숫자, 특수문자 8~15자로 입력해주세요.',
			'result'			=	false
		);
		return result;
	}
	
	if(PWD.search(/₩s/) != -1){
		result				=	array(
			'msg'				=	'비밀번호는 공백없이 입력해주세요.',
			'result'			=	false
		);
		return result;
	}

	if(num != -1) mixCount++;
	if(eng != -1) mixCount++;
	if(spe != -1) mixCount++;

	//2가지 이상 조합이 아닐경우
	if(mixCount < 2){
		result				=	array(
			'msg'				=	'비밀번호는 8~15자 이내로 영문(대,소문자), 숫자, 특수문자 3가지 조합 중 2가지 이상',
			'result'			=	false
		);
		return result;
	}

	result					=	array(
		'msg'					=	'',
		'result'				=	true
	);
	return result;
}

//아이디체크
function checkUserID(){
	let token			=	$('input[name="token"]').val();
	userIDCheck       	= 0;
	let userIDRule		=	/^[a-zA-Z0-9+]{6,15}$/; 
	let userID        	= $('input[name="userID"]');
  
	if(!userID.val().trim()){
		$('#idText').text('아이디를 입력해주세요.');
		userID.focus();
		return;
	}
  
	if (!userIDRule.test(userID.val())) { 
		$('#idText').text('아이디를 조건이 맞지 않습니다. (영문소문자/숫자, 6~15자)');
		userID.focus();
		return;
	}
  
  	let url					=	'/intro/event/join_checkID';
	let dataType			=	'json';
	let param				= {
		userID					:	userID.val(),
		token					:	token
	};
	postService(url, dataType, param, resertIDCheck);
}

function resertIDCheck(data){
	if(data){
		userIDCheck				=	1;
	}
}

