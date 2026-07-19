if (matchMedia.matches) {
	// 1000px以下で行う処理
	var favCount = parseInt(CookieRead("favCount"));
	favTask();
	function favTask() {
		if (favCount > 0) {
			document.getElementsByClassName("num")[0].innerHTML = favCount;
			document.getElementsByClassName("fav")[0].style.display = "block";
		}
	}
} else {
	// 1001px以上で行う処理
	var favCount = parseInt(CookieRead("favCount"));
	var o = document.getElementsByClassName("num");
	if (favCount > 0) {
		o[0].innerHTML = favCount;
		o[0].style.display = "inline-block";
		o[1].innerHTML = favCount;
		o[1].style.display = "inline-block";
		o = getObj("favInfo");
		o.innerHTML = o.innerHTML.replace("____fCount____", favCount);
		o.style.display = "block";

		// お気に入り情報を5秒後に自動的に消す
		setTimeout(function() {
			o.style.display = "none";
		}, 5000);
	} else {
		// お気に入り数が0の場合は何もしない（PHP側で既にdisplay:noneが設定されている）
	}
}
