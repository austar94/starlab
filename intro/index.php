<?php include $_SERVER['DOCUMENT_ROOT'] . '/common/page/head.php'; ?>
<body>
	<form id="frm" onkeypress="if(event.keyCode==13) {loginProc(); return false;}">
		<input type="hidden" name="token" value="<?=$_SESSION['token'][$nowPage]?>">
		<input type="text" name="userID">
		<input type="text" name="userPWD">
		<button onclick="loginProc()">로그인</button>
	</form>

	<script src="/intro/js/index.js"></script>
</body>
</html>
