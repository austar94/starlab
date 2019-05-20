//카테고리 선택시
function seleteCategory(e){
	let token				=	$('input[name="token"]').val();
	let cateLevel			=	$(e).attr('name').substr($(e).attr('name').length - 1);		//이름에서 레벨값 가져옴
	let cate				=	$(e).val();														//선택 카테고리 값

	//해당 카테고리보다 하위값이 존재할경우 모두 삭제
	$('.category:eq('+cateLevel+')').remove();

	//다음 카테고리값이 존재하는지 확인
	let url					=	'/goods/event/goodsAdd_getCategory';
	let dataType			=	'json';
	let param				= 	{
		cate					:	cate,
		token					:	token
	};
	postService(url, dataType, param, function(data){
		let cateList			=	data.list;

		//하위 카테고리가 존재할경우 하위 카테고리 출력
		if(cateList.length){
			var str				=	'';
			str					+=	'<select class="category" name="category'+(cateLevel+1)+'" onchange="seleteCategory(this)">';
			str					+=	'	<option value="">'+(cateLevel+1)+'차카테고리</option>';
			$.each(cateList, function(index, cate) {
				str				+=	'	<option value="'+cate.cateIdx+'">'+cate.cateName+'</option>'
			});
			str					+=	'</select>';
			$(e).append(str);
		}
	});
}

//할인 입력시
function setDiscount(){
	let goodsPrice			=	$('input[name="goodsPrice"]');
	let goodsPriceVal		=	onlyNumber(goodsPrice.val());
	let discount			=	$('input[name="discount"]');
	let discountVal			=	onlyNumber(discount.val());
	let isDiscountVal		=	$('input[name="isDiscount"]:checked').val();
	let showDiscountPrice	=	$('input[name="showDiscountPrice"]');				//계산가격

	//할인을 입력하는데 상품가격이 입력되어있지 않을경우
	if(goodsPriceVal < 1){
		alert('할인 입력에 앞서 상품 금액을 입력해주세요.');
		goodsPrice.focus();
		return;
	}
	showDiscountPrice.val(goodsPrice);

	//원 할인일 경우
	if(isDiscountVal == 1){
		//입력된 할인 금액이 상품금액보다 많을 경우
		if(goodsPriceVal < discountVal){
			alert('실제 금액보다 할인된 금액이 많습니다.');
			discount.val(numberWithCommas(goodsPrice));
			return;
		}
		showDiscountPrice.val(numberWithCommas(goodsPrice - discountVal));
		return;
	} else if(isDiscountVal == 2){
		//입력된 할인 퍼센트가 100이상일 경우 되돌려보냄
		if(discountVal > 100){
			alert('할인율은 100퍼센트를 넘길 수 없습니다.');
			discount.val('');
			return;
		}
		//몪이 0이 아닌경우
		else if(discountVal%100 != 0){
			alert('할인 판매 금액은 100단위가 되도록 입력해주세요.');
			return;
		} 
		//금액 계산
		else {
			showDiscountPrice.val(numberWithCommas(goodsPrice * (1 - discountVal % 100)));
			return;
		}
	} else {
		alert('할인 방식을 선택해주세요.');
		return;
	}
}

//할인 여부 선택시
$('input[name="isDiscount"]').on('change', function(){
	let isDiscount			=	$(this).val();

	//원할인, 퍼센트 할인 선택시
	if(isDiscount == 1 || isDiscount == 2){
		$('input[name="discount"]').val('');
		$('.discount').show();
	} else {
		$('.discount').hide();
	}
})