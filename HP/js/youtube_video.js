// customs
// ---------------------------------------

$(function(){

  $.fn.customvideo = function(options){


    // variable
    //-------------------------------------

    var isDown = false,
        isPlay = false,
        jsdir = 'js/draggable/';

    // options
    //-------------------------------------

    options = $.extend({
      youtubeId : '',
      wrapperId: 'video_wrapper',
      width : 640,
      height : 360,
      fullsize : true,
      loop : false,
      customcontrols : true,
      seek: true,
      draggable: true,
      endevent : true,
      autoplay : 1
    }, options);

    target = this.attr('id');
    playerid = 'myvideo'+target;
    wrapperId = options.wrapperId; youtubeId = options.youtubeId; width = options.width; height = options.height; fullsize = options.fullsize; loop = options.loop; autoplay = options.autoplay; controls = options.controls; showinfo = options.showinfo; customcontrols = options.customcontrols; seek = options.seek; draggable = options.draggable; endevent = options.endevent;

    this.wrap('<div id="'+wrapperId+'"></div>');
    $wrapper = $('#'+wrapperId);


    // fullsize setting
    //-------------------------------------

    if(fullsize){
      $(window).load(function(){  fixSize(width,height,'#'+playerid); });
      $(window).resize(function(){ fixSize(width,height,'#'+playerid); });
    }else{
      $wrapper.css({'width':width,'height':height});
    }

    function fixSize(w,h,video){

      var winW  = $(window).width(),
          winH  = $(window).height(),
          scale = Math.max(winW/w,winH/h),
          fixW  = (w*scale)+10,
          fixH  = (h*scale)+10;

      $wrapper.css({ overflow:'hidden', position:'relative', width:winW, height:winH });

      $(video).css({
        position: 'absolute',
        top: '50%',
        left: '50%',
        'margin-top': -(fixH/2),
        'margin-left': -(fixW/2),
        width: fixW,
        height: fixH
      });
    }


    // targets
    // ---------------------------------------

    var t_play =    '.player.play',
        t_pause =   '.player.pause',
        t_first =   '.pos.first',
        t_last =    '.pos.last',
        t_sound =   '.sound.on',
        t_mute =    '.sound.mute',
        t_cur =   '#seek .current',
        t_drag =  '#seek .draggable';


    // setting
    //-------------------------------------

    if(customcontrols){
      $wrapper
        .append(
          '<ul id="controls" class="overlay"><li class="player play"></li><li class="player pause"></li><li class="sound on"></li><li class="sound mute"></li></ul>' +
          '<ul id="poscontrols" class="overlay"><li class="pos first"></li><li class="pos last"></li></ul>');
    }

    if(seek){
      $wrapper
        .append('<div id="seek" class="overlay"><div class="bar"><span class="current"></span><span class="loaded"></span></div></div>');

      if(draggable){

        $('#seek .bar').append('<span class="draggable"></span>').css('cursor','pointer');

        $('body')
          .append($(
            '<script src="'+jsdir+'jquery.ui.core.min.js"></script>' +
            '<script src="'+jsdir+'jquery.ui.widget.min.js"></script>' +
            '<script src="'+jsdir+'jquery.ui.mouse.min.js"></script>' +
            '<script src="'+jsdir+'jquery.ui.draggable.min.js"></script>'));

        $("#seek .draggable").draggable({ axis: "x" , containment: 'parent'});
      }

    }
    if(!loop){
      if(endevent){
        $wrapper
          .append('<div id="endevent"><a href="javascript:void(0);" class="repeat">REPEAT</a></div>');
      }
    }

    if(autoplay!=1){
      isPlay = false;
      showib(t_play);
    }else{
      isPlay = true;
      showib(t_pause);
    }


    // youtube javascript API
    // ---------------------------------------

    // param set
    var params = { wmode: "opaque", allowScriptAccess: "always"},
        atts = { id: playerid },
        customs;

        if(loop){
          customs = "&autoplay="+autoplay+"&rel=0&loop=1&playlist="+youtubeId;
        }else{
          customs = "&autoplay="+autoplay+"&rel=0";
        }

    // youtube swf set
    swfobject.embedSWF(
      "//www.youtube.com/apiplayer?video_id="+youtubeId+"&enablejsapi=1&playerapiid=ytplayer"+customs, 
      target, width, height, "8", null, null, params, atts
    );

    // controls
    // ---------------------------------------

    onYouTubePlayerReady = function (playerId) {
        ytplayer = document.getElementById(playerid);
        ytplayer.addEventListener("onStateChange", "onPlayerStateChange");

        if(ytplayer.isMuted()){
          showib(t_mute);
        }else{
          showib(t_sound);
        }

        if(autoplay){
          seektime();
        }
    };

    // Event
    // ---------------------------------------

    onPlayerStateChange = function (state) {

        if (state === 1) { //1（再生中）
          $(t_play).hide(); showib(t_pause);
          //seektime();
        }else if(state === 2){ // 2（停止）
          $(t_pause).hide(); showib(t_play);
          clearInterval(timer);

        }else if(state === 0){ // 0（終了）
          clearInterval(timer);
          if(loop){
            first();
          }else{
            if(endevent){
              $('#endevent').fadeIn();
            }
          }
        }
    };


    // seek
    // --------------

    $(t_drag).mousedown(function(){
        clearInterval(timer);
        dragstart();
    }).mouseup(function(){
        dragend();
    });

    function seektime(){
      timer = setInterval(function(){
        seekset();
      },10);
    }

    function seekset(){
      var currentTime,
          duration = ytplayer.getDuration();

      currentTime = ytplayer.getCurrentTime();
      percentage = (currentTime / duration)*100;

      $(t_cur).css('width',percentage+'%');

      if(draggable){
        $(t_drag).css('left',percentage+'%');
      }
    }

    function dragstart(){
      ytplayer.pauseVideo();
    }
    function dragend(){

      var dragPos = $(t_drag).position().left,
          videoPos = (dragPos/$('#seek').width()),
          videoTime = Math.round(ytplayer.getDuration()*videoPos),
          seekbarPos = videoPos*100;

      ytplayer.seekTo(videoTime);
      $(t_cur).css('width',seekbarPos+'%');
      $(t_drag).css('left',seekbarPos+'%');

      if(isPlay){
        ytplayer.playVideo();
      }

      seektime();
    }

    // controls
    // --------------

    function play(){
      if(!isPlay){
        isPlay = true;
      }
      ytplayer.playVideo();
      seektime();
    }
    function pause(){
      if(isPlay){
        isPlay = false;
      }
      ytplayer.pauseVideo();
    }

    function first(){
      $(t_cur).css('width',0);
      $(t_drag).css('left',0);
      ytplayer.seekTo(0);

      if(isPlay){
        ytplayer.playVideo();
      }else{
        ytplayer.pauseVideo();
      }
    }
    function last(){
      $(t_cur).css('width','100%');
      $(t_drag).css('left','100%');
      clearInterval(timer);
      ytplayer.seekTo(ytplayer.getDuration());
    }
    function sound(){
      if(ytplayer.isMuted()){
        ytplayer.unMute();
        $(t_mute).hide(); showib(t_sound);
      }
    }
    function mute(){
      if(!ytplayer.isMuted()){
        ytplayer.mute();
        $(t_sound).hide(); showib(t_mute);
      }
    }

    $(t_pause).click(function() { pause(); });
    $(t_play).click(function() { play(); });

    $(t_sound).click(function() { mute(); });
    $(t_mute).click(function() { sound(); });

    $(t_first).click(function() { first(); });
    $(t_last).click(function() { last(); });

    $('.repeat').click(function() {
      $('#endevent').fadeOut();

      setTimeout(function(){
        first();
        if(!isPlay){
          ytplayer.pauseVideo();
          clearInterval(timer);
        }else{
          seektime();
        }
      },300);
    });


    // display
    // ---------------------------------------

    function showib(target){
      $(target).css('display','inline-block');
    }

  };
});
