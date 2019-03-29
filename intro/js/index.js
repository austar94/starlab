

function loginProc(){
	if(!$('input[name="userID"]').val().trim()){
		alert('아이디를 입력해주세요.');
		$('input[name="userID"]').focus();
		return;
	}

	if(!$('input[name="userPWD"]').val().trim()){
		alert('비밀번호를 입력해주세요.');
		$('input[name="userPWD"]').focus();
		return;
	}

	let form				=   document.querySelector("#frm");
	let postData			=   new FormData(form);

	let url					=	'/intro/event/loginProc';
	let dataType			=	'json';
	let param				= 	postData;
	let formType			=	1;

	postService(url, dataType, param, '', formType);
	

}