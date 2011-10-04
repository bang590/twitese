//this script is not created by me, who's the author?
img_processor = [
    {
        reg: /^http:\/\/img\.ly\/([\d\w]+)/,
        func:function (url_key, url_elem) {
            var src = "http://img.ly/show/thumb/" + url_key[1];
            append_image (src, url_elem);
        }
    },


    {
        reg: /^http:\/\/ow\.ly\/i\/([\d\w]+)/,
        func:function (url_key, url_elem) {
            var src = "http://static.ow.ly/photos/thumb/" + url_key[1] + ".jpg";
            append_image (src, url_elem);
        }
    },

    {
        reg:/^http:\/\/pic\.gd\/([\d\w]+)/,
        func:function (url_key, url_elem) {
            var src = "http://TweetPhotoAPI.com/api/TPAPI.svc/imagefromurl?size=thumbnail&url=" + url_key[0];
            append_image (src, url_elem);
        }
    },

    {
        reg:/^http:\/\/tweetphoto\.com\/([\d\w]+)/,
        func:function (url_key, url_elem) {
            var src = "http://TweetPhotoAPI.com/api/TPAPI.svc/imagefromurl?size=thumbnail&url=" + url_key[0];
            append_image (src, url_elem);
        }
    },

    {
        reg:/^http:\/\/twitxr.com\/[^ ]+\/updates\/([\d]+)/,
        func: function (url_key, url_elem) {
            var src = 'http://twitxr.com/thumbnails/' + url_key[1].substr(-2,2) + '/'+url_key[1] + '_th.jpg';
            append_image (src, url_elem);
        }
    },

    { 
        reg: /^http:\/\/twitgoo.com\/([\d\w]+)/,
        func: function (url_key, url_elem) {
            var src = "http://twitgoo.com/show/thumb/" + url_key[1];
            append_image (src, url_elem);
        }
    },


    { 
        reg: /(^http:\/\/yfrog.com\/.+)/,
        func: function (url_key, url_elem) {
            var src = url_key[0] + ".th.jpg";
            append_image (src, url_elem);
        }
    },

    {
        reg:/^(http:\/\/moby\.to\/[A-Za-z0-9]+)/,
        func:function (url_key, url_elem) {
            var src = "http://api.mobypicture.com?s=small&format=plain&k=OozRuDDauQlucrZ3&t=" + url_key[1];
            append_image (src, url_elem);
        }
    },

    {
        reg:/^http:\/\/twitpic\.com\/([A-Za-z0-9]{6})/,
        func:function (url_key, url_elem) {
            var src = "http://mirrornt.appspot.com/twitpic.com/show/thumb/" + url_key[1];
            append_image(src, url_elem);
        }
    },

    {
        reg: /^http:\/\/flic\.kr\/p\/([A-Za-z0-9]+)/,
        func: function (url_key, url_elem) {
            function base58_decode( snipcode ) {
                var alphabet = '123456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ' ;
                var num = snipcode.length ;
                var decoded = 0 ;
                var multi = 1 ;
                for ( var i = (num-1) ; i >= 0 ; i-- ) {
                    decoded = decoded + multi * alphabet.indexOf( snipcode[i] ) ;
                    multi = multi * alphabet.length ;
                }
                return decoded;
            }
            var id = base58_decode(url_key[1]);
            var apiKey = '4ef2fe2affcdd6e13218f5ddd0e2500d';
            var url = "http://api.flickr.com/services/rest/?method=flickr.photos.getInfo&api_key=" + apiKey + "&photo_id=" + id;
            
            $.getJSON(url + "&format=json&jsoncallback=?", function(data) {
                if (data.stat == "ok"){
                    var imgsrc = "http://farm" + data.photo.farm + ".static.flickr.com/"
                        + data.photo.server + "/" + data.photo.id + "_" + data.photo.secret + "_m.jpg";
                    append_image(imgsrc, url_elem);
                }
            });
        }
    }
];


function showpic_main(html) {
	if (getCookie("show_img") != 0) {
		var dom;
		var temp = $(html);
		if (html) dom = temp.find('.status_word > a');
		else dom = $('.status_word > a');
		dom.each (function () {
				
			if (typeof $(this).attr("atl") == 'undefined') {
				for (i in img_processor) {
					
					if ((img_url_key = img_processor[i].reg.exec(this.href)) != null) {
						$(this).attr("atl", "image");
						img_processor[i].func(img_url_key, this);
						break;
					}
				}
			}
		});
		return temp;
	}
	else{
		return html;
	}
}

function append_image(src, elem) {
    var img = $('<img style="padding:3px;border:1px solid #ccc;" />').attr("src", src);
    var link = $(elem).clone().empty().append(img);
    $(elem).parent().after($('<div class="thumb_pic" style="display:block;margin:6px 10px;" />').append(link));
}

$(function() {
    showpic_main();
});

