jQuery.extend({
    urlVal : function(name) {
        // /<summary>得到url传递的参数,说明:不支持中文传参</summary>
        // /<param name="name">例如?id=5中的id</param>
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        var r = window.location.search.substr(1).match(reg);
        if (r != null)
            return unescape(r[2]);
        return "";
    }
});
