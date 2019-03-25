const token			    =	$('input[name="token"]').val();
let userIDCheck     = 0;
let userPWCheck     = 0;
let userRPWCheck    = 0;

//아이디체크
function checkUserID(){
  userIDCheck       = 0;
  let userIDRule		=	/^[a-z0-9+]{6,15}$/; 
  let userID        = $('input[name="userID"]');
  
  
  if(!userID.val().trim()){
    alert('아이디를 입력해주세요.');
    userID.focus();
    return;
  }
  
  if (!userIDRule.test(userID.val())) { 
		alert('아이디를 조건이 맞지 않습니다. (영문소문자/숫자, 6~15자)');
		userID.focus();
		return;
	}
  
  let url							=	'/intro/event/join_checkID';
	let dataType				=	'json';
	let param						= {
		userID					:	userID.val(),
		token						:	token
	};
	postService(url, dataType, param, '');
}
