function emailCheck(email) {
	var patten = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;
	
	if (!patten.test(email)) {
		return false;
	}
	return true;
}

/**
 * 
 * @param x
 * @returns 
 */
function onlyNumber(x){
	return x.toString().replace(/[^0-9]/g,'');
}

/**
 * 숫자 세자리 단위 마다 콤마
 *
 * @param x
 * @returns
 */
function numberWithCommas(x) {
	
	if( undefined == x || null == x || '' == x) return x;
	
	return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}


/**
 * 숫자 세자리 단위 마타 콤마 (KeyPress)
 * onkeyup="setNumberWithCommasKeyUp(this)"/
 */
function setNumberWithCommasKeyUp(obj) {
	obj.value = numberWithCommas(removeCommas(obj.value)); //콤마 찍기
}

/**
 * 금액 포멧에서 ','제거
 *
 * @param str
 * @returns
 */
function removeCommas(str) {
	return str.replace(/,/gi, "");
}

/**
 * 숫자 및 [.]
 * onkeydown="return setPeriodNumberOnKeyDown(event)"/
 * class="ime-mode" 필수
 * @param evt
 * @returns {Boolean}
 */
function setPeriodNumberOnKeyDown(evt){
	var code = evt.which ? evt.which : event.keyCode;

	//Backspace || Delete || Tab || ESC || Enter || F5
	if (code == 46 || code == 8 || code == 9 || code == 27 || code == 13 || code == 116
			// Ctrl + A , C , X , V
			|| (evt.ctrlKey === true && (code == 65 || code == 67 || code == 86 || code == 88))
			// PageUp ~ ArrowKey 
			|| (code >= 33 && code <= 39) || code == 110 || code == 190
			// 0 ~ 9 || KeyPad 0 ~ 9
			|| (code >= 48 && code <= 57) || (code >= 96 && code <= 105)) {
		return;
	}

	return false;
}


/**
 * 숫자만 입력
 * onkeydown="return setNumberOnKeyDown(event)"/
 * class="ime-mode" 필수
 */
function setNumberOnKeyDown(evt) {
	var code = evt.which ? evt.which : event.keyCode;

	//Backspace || Delete || Tab || ESC || Enter || F5
	if (code == 46 || code == 8 || code == 9 || code == 27 || code == 13 || code == 116
			// Ctrl + A , C , X , V
			|| (evt.ctrlKey === true && (code == 65 || code == 67 || code == 86 || code == 88))
			// PageUp ~ ArrowKey 
			|| (code >= 33 && code <= 39)
			// 0 ~ 9 || KeyPad 0 ~ 9
			|| (code >= 48 && code <= 57) || (code >= 96 && code <= 105)) {
		return;
	}

	return false;
}


/**
 * Call ajax service with POST
 * @param url
 * @param data
 * @param callback
 */
function postService(url, dataType, data, callback, formType, async){
	ajax(url, dataType, data, "POST", formType, "", callback, async);
}

/**
 * Call ajax service With GET
 *
 * @param url
 * @param callback
 */
function getService(url , callback){
	var data = new Object();

	url = encodeURI(url);
	ajax(url , data , "GET" , "" , callback);
}



/**
 * Call ajax
 */
function ajax(url, dataType, param, method, formType, gbn, callback, async){
	//showLoading();

	if (undefined === async) {
		async = true;
	}

	var ajaxData		= {
		type				:	method,
		url					:	url,
		dataType			:	dataType,
		data				:	param,
		async				:	async,
		success				:	function(data){
			if(dataType == 'json'){
				if(method == "GET"){
					data		=	JSON.parse(data);
				}
				var errCd		=	data.errCd;
				var errMsg		=	data.errMsg;
	
				if(errCd == "-9999"){
					alert("세션이 종료되었습니다. 다시 로그인 하여 주시기 바랍니다.");
					location.href = '/';
					return;
				}
				
				if(errCd == "-9"){
					alert("에러가 발생하였습니다.\n관리자에게 문의하세요.");
					return;
				}
	
				if(errCd != 0) {
					var url		=	data.url;
					if(errMsg){
						alert(errMsg);
					}

					if(url){
						if(url == 1){
							location.reload();
						} else {
							location.href	=	url;
						}
					}
					return;
				}

				callback(data , errCd , errMsg);
			} else {
				callback(data);
			}
		},
		error   : doAjaxError
	};

	if(dataType == "json"){
		if(formType == 1){
			ajaxData.contentType			=	false;
			ajaxData.processData			=	false;
		} else {
			
		}
	} else if(dataType == 'html'){
		if(formType == 1){
			ajaxData.contentType			=	false;
			ajaxData.processData			=	false;
		} else {
			ajaxData.contentType			=	"application/x-www-form-urlencoded; charset=UTF-8";
		}
	}

	ajaxData.responseText;
	$.ajax(ajaxData);
}
