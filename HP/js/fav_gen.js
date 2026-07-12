var favCount=parseInt(CookieRead("favCount"));
	if(favCount>0){
		favCount-=1;
		CookieWrite("favCount", favCount, 1);
	}