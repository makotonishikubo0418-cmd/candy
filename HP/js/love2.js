var getBrowser = function(){
    var ua = window.navigator.userAgent.toLowerCase();
    var ver = window.navigator.appVersion.toLowerCase();
    var name = 'unknown';

    if (ua.indexOf("msie") != -1){
        if (ver.indexOf("msie 6.") != -1){
            name = 'ie6';
        }else if (ver.indexOf("msie 7.") != -1){
            name = 'ie7';
        }else if (ver.indexOf("msie 8.") != -1){
            name = 'ie8';
        }else if (ver.indexOf("msie 9.") != -1){
            name = 'ie9';
        }else if (ver.indexOf("msie 10.") != -1){
            name = 'ie10';
        }else{
            name = 'ie';
        }
    } else if (ua.indexOf('edge') != -1) {
        name = 'edge';
    }
    else if(ua.indexOf('trident/7') != -1){
        name = 'ie11';
    }else if (ua.indexOf('chrome') != -1){
        name = 'chrome';
    }else if (ua.indexOf('safari') != -1){
        name = 'safari';
    }else if (ua.indexOf('opera') != -1){
        name = 'opera';
    }else if (ua.indexOf('firefox') != -1){
        name = 'firefox';
    } else if (userAgent.indexOf('edge') != -1) {
        /* Edge. */
        name = 'edge';
    } else if (userAgent.indexOf('gecko') != -1) {
        /* Gecko. */
        name = 'gecko';
    }
    return name;
};


// お気に入り登録・解除
function SetLovePt(gid, did){
    $.ajax({
        type: 'get',
        url: 'https://can-diary.com/api/get_diary_data.php',
        data: {
            'fno': 300,
            'gId': gid,
            'dId': did
        }
    });

    var key = "g" + gid + "/__/d" + did;
    var ar = {
        "gid": gid,
        "did": did
    };
    var value = JSON.stringify(ar);
    // iframeのwindowオブジェクトを取得
    var ifrm = document.getElementById('iframe').contentWindow;
    // 外部サイトにメッセージを投げる
    ifrm.postMessage(value, 'https://can-diary.com/love.html');
}

// クリック切り替え
function WAtoggleee(id_list){
    var p=id_list.split(",");
    var obj;
    for(var i=0,l=p.length;i<l;i++){
        obj=document.getElementById(p[i].substr(1,p[i].length-1));
        if(obj==null){
            alert(p[i].substr(1,p[i].length-1));
        }else{
            switch(p[i].substr(0,1)){
                case "+":
                    obj.style.display="block";
                    break;
                case "-":
                    obj.style.display="none";
                    break;
            }
        }
    }
}

//カウントダウン
function CountDown(countid){
    var count = document.getElementById(countid);
    count.innerHTML = new Number( count.innerHTML ) - 1;
}
//カウントアップ
function CountUp(countid){
    if(getBrowser() == "ie6" || getBrowser() == "ie7" || getBrowser() == "ie8" || getBrowser() == "ie9" || getBrowser() == "ie10" || getBrowser() == "ie11" || getBrowser() == "ie" || getBrowser() == "edge" || getBrowser() == "gecko") {
        var count = document.getElementById(countid);
        count.innerHTML = new Number(count.innerHTML) + 1;
    }
}
