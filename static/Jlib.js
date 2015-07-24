/**
+----------------------------------------------------------
* 
* @authors: Jea Yang (JJonline@JJonline.Cn)
* @CopyRights: Any source code changes without permission prohibited
* @Time: 2014-02-12 13:45:16
* @Time: 2014-02-19 Add J.cookie() &&  J.localData()
* @Time: 2014-02-22 fix <=ie7 object of JSON , Merge JSON2.js to Jlib.js && Add  J.toString() &&  J.toObject() && J.isCardNumber()[同名函数J.isID()]
* @Time: 2014-02-28 fix J.isNumber() too eazy too small  ,  Add  J.escapeHTML()
* @Time:2014-05-22 add J.trimEnter()
* @Time:2014-06-21 fixed get cookie J.Cookie(cookieNmae) use decodeURIComponent instead of unescape
* @Time:2015-5-16 fixed isUrl support more url string
* @Time:2015-5-25 fixed isMail support domainName has _ && userName has .
* @Time:2015-7-24 add html_encode()  &&  html_decode()
* @Description: Jlib.js === jjonline javascript library
* @version 1.1.5 dev
*
*@ Api
* 产生随机整数方法  =>  J.rand([min],[max]); 
* 产生随机字符串[去除易混淆的0、1、I、l、L、O、o] => J.randString([length])
* 去除字符串中所有空白 => J.trimAll(str);
* 检测传入的字符串是否是邮箱格式 => J.isMail(str)
* 检测传入的字符、数字字符串是否是手机格式 =>J.isPhone(num)
* 检测传入的字符串是否是中文 => J.isChinese(str)
* 检测传入的字符串是否是正整数 => J.isNumber(num)
* 检测传入的字符串是否是QQ号码 => J.isQQ(qq)
* 检测传入的字符串是否是合法网址 => J.isUrl(url)
* 检测传入的字符串是否是合法密码格式 => J.isPassWord(pwd)
* 检测传入的字符串是否是全部由字母构成 => J.isAlphabet(Alphabets)
* 检测传入的字符串是否是合法的天朝身份证号码[严格效验] => J.isID(CardNumber) 或 J.isCardNumber(CardNumber)
* 传入当前页面的url中的get变量名称获取当前url中的get变量 => J.GetUrlQueryString(getName)
* 获取当前窗口的宽高,返回对象 => J.windowSize()
*
*
* cookie操纵函数方法，类似jquery.cookie => J.cookie(key,[value],[{expires: 365, path: '/', domain: 'jjonline.cn',secure: false}]) 
* ====> 读取cookie  => J.cookie(CookieName) 或 J.Cookie(CookieName);
* ====> 删除cookie  => J.cookie(CookieName,null) 或 J.cookie(CookieName,'null') 或 J.Cookie(CookieName,null) 或 J.Cookie(CookieName,'null')
* ====> 设置cookie  => J.cookie(CookieName,CookieValue,options)  或 J.Cookie(CookieName,CookieValue,options)
* ========> 设置cookie时options参数为json对象格式 格式范例 => {expires: 365, path: '/', domain: 'jjonline.cn',secure: false}
* ========> J.cookie方法中options参数说明 => expires为过期时间，单位：天，可以设置该参数为负数达到删除cookie的效果；
* ========> path为cookie作用目录，默认'/'，整站可用；
* ========> domain为cookie的作用域，默认当前域名下；
* ========> secure为是否该cookie仅作用于安全链接，默认所有链接可用
*
*
* 本地相对永久存储文本数据[<=ie7使用userData，建议文本大小不要大于64K、其他使用localStorage] =>J.localData(key,[value])
* ====> 读取本地数据  => J.localData(key)
* ====> 删除本地数据  => J.localData(key,null) 或 J.localData(key,'null')
* ====> 设置本地数据  => J.localData(key,value)
* ====> 清空本地数据  => J.localData('null') 或 J.localData(null)
* PS：该方法目前默认仅接受value参数为字符串类型的文本，若要存储其他类型数据请使用J.toString()方法转换成字符串后存储，取出后使用J.toObject()还原即可
*
*
* 将对象[object of json 、object of array]转换成字符串方法 => J.toString(object)
* 将对象字符串[需符合对象字符串的格式要求]转换成对象方法 => J.toObject(objectString)
* PS：目前Jlib.js已经将开源的JSON2.js合并，所有浏览器中可直接调用JSON.parse()、JSON.stringify()等JSON原生方法
*
*
* 对HTMl字符串进行转义处理 => J.escapeHTML(htmlString)
* 去除字符串中的回车符 \n\r之类的看不到的 J.trimEnter(string)
+----------------------------------------------------------
*/
(function(window, undefined) {
    var	_version = '1.1.5 beta',//Jlib版本号
    J = {};//初始化J命名空间

    /*===未内置原生JSON对象的浏览器扩展JSON对象方法===*/
    typeof JSON != "object" && (JSON = {}),
    function() {
        "use strict";
        function f(e) {
            return e < 10 ? "0" + e: e
        }
        function quote(e) {
            return escapable.lastIndex = 0,
            escapable.test(e) ? '"' + e.replace(escapable,
            function(e) {
                var t = meta[e];
                return typeof t == "string" ? t: "\\u" + ("0000" + e.charCodeAt(0).toString(16)).slice( - 4)
            }) + '"': '"' + e + '"'
        }
        function str(e, t) {
            var n, r, i, s, o = gap,
            u, a = t[e];
            a && typeof a == "object" && typeof a.toJSON == "function" && (a = a.toJSON(e)),
            typeof rep == "function" && (a = rep.call(t, e, a));
            switch (typeof a) {
            case "string":
                return quote(a);
            case "number":
                return isFinite(a) ? String(a) : "null";
            case "boolean":
            case "null":
                return String(a);
            case "object":
                if (!a) return "null";
                gap += indent,
                u = [];
                if (Object.prototype.toString.apply(a) === "[object Array]") {
                    s = a.length;
                    for (n = 0; n < s; n += 1) u[n] = str(n, a) || "null";
                    return i = u.length === 0 ? "[]": gap ? "[\n" + gap + u.join(",\n" + gap) + "\n" + o + "]": "[" + u.join(",") + "]",
                    gap = o,
                    i
                }
                if (rep && typeof rep == "object") {
                    s = rep.length;
                    for (n = 0; n < s; n += 1) typeof rep[n] == "string" && (r = rep[n], i = str(r, a), i && u.push(quote(r) + (gap ? ": ": ":") + i))
                } else for (r in a) Object.prototype.hasOwnProperty.call(a, r) && (i = str(r, a), i && u.push(quote(r) + (gap ? ": ": ":") + i));
                return i = u.length === 0 ? "{}": gap ? "{\n" + gap + u.join(",\n" + gap) + "\n" + o + "}": "{" + u.join(",") + "}",
                gap = o,
                i
            }
        }
        typeof Date.prototype.toJSON != "function" && (Date.prototype.toJSON = function() {
            return isFinite(this.valueOf()) ? this.getUTCFullYear() + "-" + f(this.getUTCMonth() + 1) + "-" + f(this.getUTCDate()) + "T" + f(this.getUTCHours()) + ":" + f(this.getUTCMinutes()) + ":" + f(this.getUTCSeconds()) + "Z": null
        },
        String.prototype.toJSON = Number.prototype.toJSON = Boolean.prototype.toJSON = function() {
            return this.valueOf()
        });
        var cx, escapable, gap, indent, meta, rep;
        typeof JSON.stringify != "function" && (escapable = /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g, meta = {
            "\b": "\\b",
            "	": "\\t",
            "\n": "\\n",
            "\f": "\\f",
            "\r": "\\r",
            '"': '\\"',
            "\\": "\\\\"
        },
        JSON.stringify = function(e, t, n) {
            var r;
            gap = "",
            indent = "";
            if (typeof n == "number") for (r = 0; r < n; r += 1) indent += " ";
            else typeof n == "string" && (indent = n);
            rep = t;
            if (!t || typeof t == "function" || typeof t == "object" && typeof t.length == "number") return str("", {
                "": e
            });
            throw new Error("JSON.stringify");
        }),
        typeof JSON.parse != "function" && (cx = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g, JSON.parse = function(text, reviver) {
            function walk(e, t) {
                var n, r, i = e[t];
                if (i && typeof i == "object") for (n in i) Object.prototype.hasOwnProperty.call(i, n) && (r = walk(i, n), r !== undefined ? i[n] = r: delete i[n]);
                return reviver.call(e, t, i)
            }
            var j;
            text = String(text),
            cx.lastIndex = 0,
            cx.test(text) && (text = text.replace(cx,
            function(e) {
                return "\\u" + ("0000" + e.charCodeAt(0).toString(16)).slice( - 4)
            }));
            if (/^[\],:{}\s]*$/.test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, "@").replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, "]").replace(/(?:^|:|,)(?:\s*\[)+/g, ""))) return j = eval("(" + text + ")"),
            typeof reviver == "function" ? walk({
                "": j
            },
            "") : j;
            throw new SyntaxError("JSON.parse");
        })
        window.JSON = JSON;
    }();
    /*===获取Jlib.js的版本号===*/
    J.version = _version;
    J.getVersion=function() {
        return _version;
    };

    /*===产生随机整数方法===*/
    J.rand = function(min,max) {
        var params = {
            min: min || 0,
            max: max || 9999999
        };
        var Range = params.max - params.min;
        var Rand  = Math.random();
        return (params.min + Math.round(Rand * Range));
    };

    /*===产生随机字符串[去除易混淆的0、1、I、l、L、O、o]===*/
    J.randString=function(length) {
        var lens = length || 16;
        var chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678';
        var maxPos = chars.length;
        var strings = '';
        for (i = 0; i < lens; i++) {
            strings += chars.charAt(Math.floor(Math.random() * maxPos));
        }
        return strings;
    };

    /*===去除字符串中所有空白===*/
    J.trimAll=function(strings) {
        if (!strings) {
            return false;
        }
        strings.replace(/(^\s+)|(\s+$)/g,"");
        return strings.replace(/\s/g,"");
    };

    /*===检测传入的字符串是否是邮箱格式===*/
    J.isMail=function(match) {
        if (!match) {
            return false;
        }
        var mail = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
        if (mail.test(match)) {
            var maiarr = match.split(/@/);
            var mailobj = { //返回用户名与域名部分组成的数组 boolean判断时为真
                'name': maiarr[0],
                'domain': maiarr[1]
            };
            return mailobj;
        }
        return false;
    };

    /*===检测传入的字符、数字字符串是否是手机格式===*/
    J.isPhone=function(match) {
        if (!match) {
            return false;
        }
        var phone = /^(13[0-9]|14[0-9]|15[0-9]|18[0-9]|170)\d{8}$/;
        if (phone.test(match)) {
            return true;
        }
        return false;
    };

    /*===检测传入的字符串是否是中文--utf8编码===*/
    J.isChinese=function(match) {
        var zhcn =/^[\u4E00-\u9FA5]+$/gi;//fixed |[\uFE30-\uFFA0]
        if (!zhcn.exec(match)) {
            return false;
        }
        return true;
    };

    /*===检测传入的字符串是否是纯数字===*/
    J.isNumber=function(match) {
        /*
        var num = /^[1-9]+[0-9]*]*$/ ;//合法的数学数字不能以0开头
        if (num.test(match)) {
        return true;
        }
        return false;
        */
        //2014年2月27日优化该方法
        return !isNaN(parseFloat(match)) && isFinite(match);
    };

    /*===检测传入的字符串是否是QQ号码===*/
    J.isQQ=function(match) {
        var QQ = /^[1-9]\d{3,9}\d$/;
        if (QQ.test(match)) {
            return true;
        }
        return false;
    };

    /*===检测传入的字符串是否是合法网址===*/
    J.isUrl=function(match) {
        var Url = /^http[s]?:\/\/(([0-9]{1,3}\.){3}[0-9]{1,3}|([0-9a-z_!~*\'()-]+\.)*([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\.[a-z]{2,6})(:[0-9]{1,4})?((\/\?)|(\/[0-9a-zA-Z_!~\*\'\(\)\.;\?:@&=\+\$,%#-\/]*)?)$/i;
            if (Url.test(match)) {
            return true;
        }
        return false;
    };

    /*===检测传入的字符串是否是合法密码格式===*/
    //该检测方式仅检测传入的字符串即包含字母也包含数字的形式
    J.isPassWord=function(match) { 
        var pcre = /[A-Za-z]+/,num = /\d+/;
        if (pcre.test(match)) {
            if (num.test(match)) {
                return true;
            }
            return false;
        }
        return false;
    };

    /*===检测传入的字符串是否是全部由字母构成===*/
    J.isAlphabet=function(match) {
        var pcre = /^[A-Za-z]+$/;
        if (pcre.test(match)) {
            return true;
        }
        return false;
    };

    /*===检测传入的字符串是否是天朝身份证号码===*/
    J.isID=function(match) {
        var id = match.toUpperCase();//18位身份证中的x为大写
        id = this.trimAll(match);//去除字符中的所有空格
        var ID18 = /^\d{17}(\d|X)$/,ID15 = /^\d{15}$/,
        oCity = {11:"\u5317\u4eac",12:"\u5929\u6d25",13:"\u6cb3\u5317",14:"\u5c71\u897f",15:"\u5185\u8499\u53e4",21:"\u8fbd\u5b81",22:"\u5409\u6797",23:"\u9ed1\u9f99\u6c5f",31:"\u4e0a\u6d77",32:"\u6c5f\u82cf",33:"\u6d59\u6c5f",34:"\u5b89\u5fbd",35:"\u798f\u5efa",36:"\u6c5f\u897f",37:"\u5c71\u4e1c",41:"\u6cb3\u5357",42:"\u6e56\u5317",43:"\u6e56\u5357",44:"\u5e7f\u4e1c",45:"\u5e7f\u897f",46:"\u6d77\u5357",50:"\u91cd\u5e86",51:"\u56db\u5ddd",52:"\u8d35\u5dde",53:"\u4e91\u5357",54:"\u897f\u85cf",61:"\u9655\u897f",62:"\u7518\u8083",63:"\u9752\u6d77",64:"\u5b81\u590f",65:"\u65b0\u7586",71:"\u53f0\u6e7e",81:"\u9999\u6e2f",82:"\u6fb3\u95e8",91:"\u56fd\u5916"};
        //{11:"北京",12:"天津",13:"河北",14:"山西",15:"内蒙古",21:"辽宁",22:"吉林",23:"黑龙江 ",31:"上海",32:"江苏",33:"浙江",34:"安徽",35:"福建",36:"江西",37:"山东",41:"河南",42:"湖北 ",43:"湖南",44:"广东",45:"广西",46:"海南",50:"重庆",51:"四川",52:"贵州",53:"云南",54:"西藏 ",61:"陕西",62:"甘肃",63:"青海",64:"宁夏",65:"新疆",71:"台湾",81:"香港",82:"澳门",91:"国外"};
        if(!(ID18.test(id) || ID15.test(id))) {return false;}//不符合基本的身份证号码规则
        function _15to18(cardNumber) {
            //15位转换为18位 数据库统一保存18位数字身份证
            var CardNo17 = cardNumber.substr(0, 6) + '19' + cardNumber.substr(6, 9) ;
            var Wi = new Array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2, 1); 
            var Ai = new Array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'); 
            var cardNoSum = 0; 

        for (var i = 0; i < CardNo17.length; i++) {
            cardNoSum += CardNo17.charAt(i) * Wi[i];
        }  
        var seq = cardNoSum % 11; 
        return CardNo17+''+Ai[seq];
        }
        function CheckValidCode(carNumber) {
            //效验第18位字符的合法性
            var CardNo17 = carNumber.substr(0,17);//去除18位id中的最后一位进行运算后对比
            var Wi = new Array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2, 1); 
            var Ai = new Array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'); 
            var cardNoSum = 0; 
        for (var i = 0; i < CardNo17.length; i++) {
            cardNoSum += CardNo17.charAt(i) * Wi[i];
        }  
        var seq = cardNoSum % 11; 
        if(Ai[seq]!=carNumber.substr(17,1)) {return false;}
            return true;
        }
        //输入的18位效验码合法性检测
        if(ID18.test(id)) {if(!CheckValidCode(id)) {return false;}}//输入的18位身份证号  先效验其标准编码合法性
        if(ID15.test(id)) {id = _15to18(id);}//将15位转换为18位 == 唯一对应
        //使用处理并转换完毕的18位身份证数字进行统一效验
        var City       = id.substr(0, 2),
            BirthYear  = id.substr(6, 4),
            BirthMonth = id.substr(10, 2),
            BirthDay   = id.substr(12, 2),
            StrData    = id.substr(6, 8),//形如19881101类型的出生日期表示法
            Sex        = id.charAt(16) % 2 ,//男1 女0
            Sexcn      = Sex?'男':'女';
        //地域验证
        if(oCity[parseInt(City)] == null) {return false;}
        //出生日期验证
        var BirthObj = StrData.match(/^(\d{1,4})(\d{1,2})(\d{1,2})$/);
        if(BirthObj == null) {return false;}//出生日期基本的组合规则不符合要求
        var d = new Date(BirthObj[1], BirthObj[2] - 1, BirthObj[3]); //效验出生日期的数字年份是否符合要求
        if(d.getFullYear() == BirthObj[1] && (d.getMonth() + 1) == BirthObj[2] && d.getDate() == BirthObj[3]) {
            return {'ID':id,'Y':BirthYear,'m':BirthMonth,'d':BirthDay,'YmdNumber':Number(StrData),'YmdString':BirthYear+'-'+BirthMonth+'-'+BirthDay,'sexInt':Sex,'sexCn':Sexcn,'local':oCity[parseInt(City)]};
        }
        return false;
    }
    /*===检测传入的字符串是否是天朝身份证号码 同名函数传递===*/
    J.isCardNumber = J.isID;

    /*===获取当前url中的get变量，传入get变量名称===*/
    J.GetUrlQueryString=function(key) {
        var reg = new RegExp("(^|&)" + key + "=([^&]*)(&|$)", "i");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return unescape(r[2]);
        return null;
    };

    /*===获取当前窗口的宽高 返回对象===*/
    J.windowSize=function() {
        var winWidth,winHeight;
        if (window.innerWidth)
            winWidth = window.innerWidth;
        else if ((document.body) && (document.body.clientWidth))
            winWidth = document.body.clientWidth;
        if (window.innerHeight)
            winHeight = window.innerHeight;
        else if ((document.body) && (document.body.clientHeight))
            winHeight = document.body.clientHeight;
        if (document.documentElement && document.documentElement.clientHeight && document.documentElement.clientWidth) {
            winHeight = document.documentElement.clientHeight;
            winWidth = document.documentElement.clientWidth;
        }
        return {
            'width': winWidth,
            'w': winWidth,
            'height': winHeight,
            'h': winHeight
        };
    };

    /*===操纵cookie的方法===*/
    J.Cookie=function(key,value,options) {
        if(options == undefined) { //无options选项，options选项中 过期时间以天为单位
            options = {};//默认cookie设置{expires: 365, path: '/', domain: 'example.com',secure: true}
        }
        /*内部方法--cookie是否存在*/
        function hasCookie(key) {
            var cookieArray=document.cookie.split("; "); 
            var cookie=new Object();
            for (var i=0;i<cookieArray.length;i++){
                var arr=cookieArray[i].split("="); 
                if(arr[0]==key) return true;
            }
            return false;
        }
        /*===删除cookie 将value参数设定为null或字符串格式的'null'===*/
        if((!value && value !== undefined && value != 0) || value==='null') {
            if(hasCookie(key)) { //存在该cookie则删除
                var days = options.expires, t = options.expires = new Date();
                t.setDate(t.getDate() - 1);
                document.cookie = [
                    key, '=', '',
                    options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
                    options.path    ? '; path=' + options.path : '',
                    options.domain  ? '; domain=' + options.domain : '',
                    options.secure  ? '; secure' : ''
                ].join('');
            }
            return true;//删除成功
        }
        /*===读取cookie 仅key参数===*/
        if(value == undefined) {
            var cookieArray=document.cookie.split('; ');//得到分割的cookie名值对
                var cookie=new Object();
                for (var i=0;i<cookieArray.length;i++){
                    var arr=cookieArray[i].split('=');       //将名和值分开
                    if(arr[0]==key) return decodeURIComponent(arr[1]); //如果是指定的cookie，则返回它的值
                }
                return '';//不存在的key 则返回空串
        }
        /*===写入cookie===*/
        //写入cookie ；有key有value，且value!==null
        if (value !== undefined && key!==undefined) {
            if (typeof options.expires === 'number') {
                var days = options.expires, t = options.expires = new Date();
                t.setDate(t.getDate() + days);
            }

            return (document.cookie = [
                key, '=', String(value),
                options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
                options.path    ? '; path=' + options.path : '',
                options.domain  ? '; domain=' + options.domain : '',
                options.secure  ? '; secure' : ''
            ].join(''));//返回设置cookie的字符串原型
        }
    };
    /*===操纵cookie的方法 同名函数不区分大小写名称传递===*/
    J.cookie=J.Cookie;

    /*===本地数据存储封包方法===*/
    J.localData = function(key,value) {
        /*使用userData在本地存储文本数据的解决方案  -- 参考至 http://sofish.de/1872 */
        if(!window.localStorage) {
            //return 'use userData for localStorage';
            var localStorage  = window.localStorage =  {},
            prefix = 'data-userdata',
                doc = document,
                attrSrc = doc.body,
                //save attributeNames to <body>'s `data-userdata` attribute
                mark = function (keys, isRemove, temp, reg) {
                    attrSrc.load(prefix);
                    temp = attrSrc.getAttribute(prefix) || '';
                    reg = RegExp('\\b' + keys + '\\b,?', 'i');
                    var hasKey = reg.test(temp) ? 1 : 0;
                    temp = isRemove ? temp.replace(reg, '') : hasKey ? temp : temp === '' ? keys : temp.split(',').concat(keys).join(',');
                    //alert(temp);
                    attrSrc.setAttribute(prefix, temp);
                    attrSrc.save(prefix);
                };
                // add IE behavior support
            attrSrc.addBehavior('#default#userData');
         
            localStorage.getItem = function (keys) {
                    attrSrc.load(keys);
                    return attrSrc.getAttribute(keys);
            };
            localStorage.setItem = function (keys, values) {
                    attrSrc.setAttribute(keys, values);
                    attrSrc.save(keys);
                    mark(keys);
            };
            localStorage.removeItem = function (keys) {
                    attrSrc.removeAttribute(keys);
                    attrSrc.save(keys);
                    mark(keys, 1);
            };
         
            // clear all attributes on <body> tag that using for textStorage 
            // and clearing them from the 
            // 'data-userdata' attribute's value of <body> tag
            localStorage.clear = function () {
                    attrSrc.load(prefix);
                    var attrs = attrSrc.getAttribute(prefix).split(','),
                    len = attrs.length;
                    if (attrs[0] === '') return;
                    for (var i = 0; i < len; i++) {
                        attrSrc.removeAttribute(attrs[i]);
                        attrSrc.save(attrs[i]);
                    };
                    attrSrc.setAttribute(prefix, '');
                    attrSrc.save(prefix);
            };
            //window.localStorage = J.localStorage = localStorage;
        }//利用userData绑定到html的body标签的localStorage方法完毕

        /*===清除所有本地数据 -- key参数为null或'null'字符串===*/
        if((!key && key !== undefined && key != 0) || key==='null') {
            window.localStorage.clear();
            return true;
        }
        /*===删除指定key的本地数据===*/
        if((!value && value !== undefined && value != 0) || value==='null') {
            window.localStorage.removeItem(key);
            return true;
        }
        /*===写入本地数据===*/
        if (value !== undefined && key!==undefined) {
            window.localStorage.setItem(key,value);
            return true;
        }
        /*===读取本地数据===*/
        if(key !== undefined && value == undefined) {
            return window.localStorage.getItem(key);
        }
    };

    /*===对象转字符串方法===*/
    J.toString = function(obj) {
        if(typeof obj != "object") {return false;}
        return JSON.stringify(obj);
    };

    /*===对象转字符串方法===*/
    J.toObject = function(string) {
        if(typeof string != "string") {return false;}
        return JSON.parse(string);
    };
    /*===HTMl字符串转义===*/
    J.escapeHTML=function(text) {
        var replacements = {"<": "<", ">": ">","&": "&", "\"": '"'};
        return text.replace(/[<>&"]/g, function(character) {
            return replacements[character];
        });
    };
    /*===去除字符串中看不到的\r\n回车符号===*/
    J.trimEnter=function(text) {
        return text.replace(/[\r\n]/g,"");
    };
    /*===模仿PHP中的date函数方法 待完善===*/
    J.date=function (format,timestamp) {
        var timeObj=new Date();
        var timeStr = [
                timeObj.getFullYear(),
                timeObj.getMonth()+1,
                timeObj.getDate()
            ].join('-');
        return timeStr +=' '+[
            timeObj.getHours(),
            timeObj.getMinutes(),
            timeObj.getSeconds()
        ].join(':');
    };
	J.html_encode=function (str) {
		var s = "";   
		if (str.length == 0) {return "";}   
		s = str.replace(/&/g, "&gt;");   
		s = s.replace(/</g, "&lt;");   
		s = s.replace(/>/g, "&gt;");   
		s = s.replace(/ /g, "&nbsp;");   
		s = s.replace(/\'/g, "&#39;");   
		s = s.replace(/\"/g, "&quot;");   
		s = s.replace(/\n/g, "<br>");   
		return s;
	};
	J.html_decode=function (str) {
		var s = "";   
		if (str.length == 0) {return ""};   
		s = str.replace(/&gt;/g, "&");   
		s = s.replace(/&lt;/g, "<");   
		s = s.replace(/&gt;/g, ">");   
		s = s.replace(/&nbsp;/g, " ");   
		s = s.replace(/&#39;/g, "\'");   
		s = s.replace(/&quot;/g, "\"");   
		s = s.replace(/<br>/g, "\n");   
		return s;
	};
    //将J命名空间传递到全局对象
    window.J = window.JJ = J;
})(window);