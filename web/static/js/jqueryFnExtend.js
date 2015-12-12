jQuery.fn.extend({
    getVal: function () {
        ///<summary>得到全部数据组成的json 例如$(*).Getval()或者$("table").find("*").GetVal()</summary>
        var val = {};
        $(this).each(function () {
        	var _ob = $(this);
            var id = _ob.attr("id");
            if (!id || id.length <= 4) { return ;}
            var v = "";
            var _chob = $("#" + id);
            if(_ob.is("label") || (_ob.is("span"))){v = _chob.html();}
            else if(_ob.hasClass("easyui-combobox")){ v = _chob.combobox('getValue');}
            else if(_ob.hasClass("easyui-combotree")){ v = _chob.combotree('getValue');}
            else if(_ob.hasClass("easyui-datebox")){ v = _chob.datebox('getValue');}
            else if(_ob.hasClass("easyui-combogrid")){ v = _chob.combogrid('getValue');}
            else if(_ob.hasClass("easyui-datetimebox")){ v = _chob.datetimebox('getValue');}
            else if(_ob.hasClass("easyui-textbox")){ v = _chob.textbox('getValue');}
            else if(_ob.hasClass("easyui-numberbox")){ v = _chob.numberbox('getValue');}
            else if(_ob.is("[type='checkbox']")){
            	 var _name =_ob.attr('name');
            	$("[name='" + _name + "']:checked").each(function () { v += "," + _ob.val();});
                if (v != "") { v = v.substring(1);}
            }
            else if(_ob.is("[type='radio']")){
            	var _name =_ob.attr('name'); v = $("[name='" + _name + "']:checked").val();
            	}
            else {v = _chob.val();}
            if (v){ v = v.replace(/\n/g, "\\n");}
            val[id.substring(5)] = v;
        });
        return val;
    },
    setVal: function (json) {
        ///<summary>把json赋值给指定dom 例如：$("*").Setval()或$("table").find("*").Setval()
        ///<param name="json">数据源，通常从ajax得到的json 比如后台用的k=dtTojson(dt,"ceshi"),那么数据源就是k.ceshi[0]</param>
        var jqjson = $(json);
        $(this).each(function () {
        	var _ob = $(this);
            var id = _ob.attr("id");
            if (!id || id.indexOf("data") < 0) { return;}
            var val = jqjson.attr(id.substring(4));
            if (!val || val === null || val == "null") {return;}
            val = $.trim(val);
            var _chob = $("#" + id);
            if (val) val = val.replace(/\\n/g, "\n");
            if(_ob.is("label") || (_ob.is("span"))){_chob.html(val);}
            else if(_ob.hasClass("easyui-combobox")){ _chob.combobox('setValue', val);}
            else if(_ob.hasClass("easyui-combotree")){ _chob.combotree('setValue', val);}
            else if(_ob.hasClass("easyui-datebox")){
                    if (val != "") {val = val.split(' ')[0].replace(new RegExp('/', 'g'), "-"); }
                    _chob.datebox('setValue', val);
            }
            else if(_ob.hasClass("easyui-combogrid")){ _chob.combogrid('setValue', val);}
            else if(_ob.hasClass("easyui-datetimebox")){ _chob.datetimebox('setValue', val);}
            else if(_ob.hasClass("easyui-textbox")){ _chob.textbox('setValue', val);}
            else if(_ob.hasClass("easyui-numberbox")){ _chob.numberbox('setValue', val);}
            else if(_ob.is("[type='checkbox']")){
            	var _name =_ob.attr('name');
            	$("[name='" + _name + "']:checked").val([val]);
            }else if(_ob.is("[type='radio']")){
            	var _name =_ob.attr('name');
            	$("input[name='"+_name+"'][value="+val+"]").attr("checked",true);
            }
            else {_chob.val(val);}
        });
    },
    getEasyuiChecked: function () {
        ///<summary>得到Easyui的选中的复选框主键必须为id或者ID</summary>
        var record = $(this).datagrid('getChecked');
        var _return = "";
        if (record) {
            for (var i = 0; i < record.length; i++) {
                var _temp = "";
                if (record[i].ID) _temp = record[i].ID;
                else if (record[i].Id) _temp = record[i].Id;
                else if (record[i].id) _temp = record[i].id;
                _return += "," + _temp;
            }
        }
        if (_return.length > 0) _return = _return.substring(1);
        return _return;
    },
    getTable:function(url,columns,params,title,rownumber){
        $(this).datagrid({
            url:url,
            queryParams:params,
            columns:columns,
            singleSelect: true,
            checkOnSelect:false,
            selectOnCheck:false,
            toolbar:'#toolbar',
            title:title,
            rownumbers: rownumber,
            loadMsg: '数据装载中......',
            pagination: true,
            pageList: [10],
            pageSize: 20,
            method:'get'
        });
        var pager = $(this).datagrid().datagrid('getPager');
        pager.pagination({
            buttons: []
        });

    }
});

/*{"total":28,"rows":[
	{"productid":"FI-SW-01","productname":"Koi","unitcost":10.00,"status":"P","listprice":36.50,"attr1":"Large","itemid":"EST-1"},
	{"productid":"K9-DL-01","productname":"Dalmation","unitcost":12.00,"status":"P","listprice":18.50,"attr1":"Spotted Adult Female","itemid":"EST-10"},
	{"productid":"RP-SN-01","productname":"Rattlesnake","unitcost":12.00,"status":"P","listprice":38.50,"attr1":"Venomless","itemid":"EST-11"},
	{"productid":"RP-SN-01","productname":"Rattlesnake","unitcost":12.00,"status":"P","listprice":26.50,"attr1":"Rattleless","itemid":"EST-12"},
	{"productid":"RP-LI-02","productname":"Iguana","unitcost":12.00,"status":"P","listprice":35.50,"attr1":"Green Adult","itemid":"EST-13"},
	{"productid":"FL-DSH-01","productname":"Manx","unitcost":12.00,"status":"P","listprice":158.50,"attr1":"Tailless","itemid":"EST-14"},
	{"productid":"FL-DSH-01","productname":"Manx","unitcost":12.00,"status":"P","listprice":83.50,"attr1":"With tail","itemid":"EST-15"},
	{"productid":"FL-DLH-02","productname":"Persian","unitcost":12.00,"status":"P","listprice":23.50,"attr1":"Adult Female","itemid":"EST-16"},
	{"productid":"FL-DLH-02","productname":"Persian","unitcost":12.00,"status":"P","listprice":89.50,"attr1":"Adult Male","itemid":"EST-17"},
	{"productid":"AV-CB-01","productname":"Amazon Parrot","unitcost":92.00,"status":"P","listprice":63.50,"attr1":"Adult Male","itemid":"EST-18"}
]}*/
