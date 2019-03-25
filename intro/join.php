<?php include $_SERVER['DOCUMENT_ROOT'] . '/common/page/head.php'; ?>
<body>
	<form id="frm">
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

	  <label><input type="checkbox">필수사항</label>

	  <button id="joinBtn">회원가입</button>
	</form>

  <script src="/intro/js/join.js"></script>
</body>
</html>
