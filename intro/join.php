<?php include $_SERVER['DOCUMENT_ROOT'] . '/common/page/head.php'; ?>
<body>
<form id="frm">
	<input type="hidden" name="token" value="<?=$_SESSION['token'][$nowPage]?>">
	<input type="hidden" name="postCode" id="postcode">
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

	<input type="text" name="userZip" id="zip" readonly>
	<a href="javascript:daumPost()">우편번호 찾기</a>
	<div id="wrap" style="display:none;border:1px solid;width:500px;height:300px;margin:5px 0;position:relative"></div>
	<input type="text" name="userAddr1" id="addr1" readonly>
	<input type="text" name="userAddr2" id="addr2">

	<label><input type="checkbox" name="check1">필수사항</label>

	<button id="joinBtn">회원가입</button>
</form>

<script src="/intro/js/join.js"></script>
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script src="/common/js/duamPost.js"></script>
</body>
</html>
