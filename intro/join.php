<?php include $_SERVER['DOCUMENT_ROOT'] . '/common/page/head.php'; ?>
<body>
<form id="frm">
	<input type="hidden" name="token" value="<?=$_SESSION['token'][$nowPage]?>">
	<input type="text" name="userID" maxlenth="16" onkeypress="if(event.keyCode==13) {checkUserID(); return false;}">
	<span id="idText"></span>
	<button onclick="checkUserID()">중복확인</button>

	<input type="text" name="userPWD">
	<span id="pwText"></span>

	<input type="text" name="re_userPWD">
	<span id="re_pwText"></span>

	 <input type="text" name="userMobile" onkeydown="return setNumberOnKeyDown(event)" maxlength="11">
	 <span id="mobileText"></span>

	 <input type="text" name="userMail">
	 <span id="mailText"></span>

	<input type="text" name="userZip" readonly>
	<a href="javascript:daumPost()">우편번호 찾기</a>
	<div id="wrap" style="display:none;border:1px solid;width:500px;height:300px;margin:5px 0;position:relative"></div>
	<input type="text" name="userAddr1" readonly>
	<input type="text" name="userAddr2">

	<label><input type="checkbox" name="check1">필수사항</label>

	<button id="joinBtn">회원가입</button>
</form>

<script src="/intro/js/join.js"></script>
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
  
<script>
    // 우편번호 찾기 찾기 화면을 넣을 element
    var element_wrap = document.getElementById('wrap');

    function foldDaumPostcode() {
        // iframe을 넣은 element를 안보이게 한다.
        element_wrap.style.display = 'none';
    }

    function daumPost() {
        // 현재 scroll 위치를 저장해놓는다.
        var currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
        new daum.Postcode({
            oncomplete: function(data) {
                // 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                // 각 주소의 노출 규칙에 따라 주소를 조합한다.
                // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                var addr = ''; // 주소 변수
                var extraAddr = ''; // 참고항목 변수

                //사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
                if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
                    addr = data.roadAddress;
                } else { // 사용자가 지번 주소를 선택했을 경우(J)
                    addr = data.jibunAddress;
                }

				// 우편번호와 주소 정보를 해당 필드에 넣는다.
				$('input[name="userZip"]').val(data.zonecode);
				$('input[name="userAddr1"]').val(addr);
				$('input[name="userAddr2"]').focus();

                // iframe을 넣은 element를 안보이게 한다.
                // (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
                element_wrap.style.display = 'none';

                // 우편번호 찾기 화면이 보이기 이전으로 scroll 위치를 되돌린다.
                document.body.scrollTop = currentScroll;
            },
            // 우편번호 찾기 화면 크기가 조정되었을때 실행할 코드를 작성하는 부분. iframe을 넣은 element의 높이값을 조정한다.
            onresize : function(size) {
                element_wrap.style.height = size.height+'px';
            },
            width : '100%',
            height : '100%'
        }).embed(element_wrap);

        // iframe을 넣은 element를 보이게 한다.
        element_wrap.style.display = 'block';
    }
</script>
</body>
</html>
