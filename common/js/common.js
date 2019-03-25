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
