<?php include $_SERVER['DOCUMENT_ROOT'] . '/common/page/head.php'; ?>
<body>
 <input type="text" name="userID">
  <span id="idText"></span>
  <button onclick="checkUserID()">중복확인</button>

  <input type="text" name="userPW">
  <span id="pwText"></span>

  <input type="text" name="re_userPW">
  <span id="re_pwText"></span>

  <input type="text" name="userMail">
  <span id="mailText"></span>

  <label><input type="checkbox">필수사항</label>

  <script src="/intro/js/join.js"></script>
</body>
</html>
