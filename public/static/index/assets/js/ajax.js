<?php

//封装ajax的函数
function ajax (obj)
{
	//初始化对象
	var xhr = (function () {
		//让这个函数来实现对象的初始化
		if (typeof(XMLHttpRequest) != 'undefined') {
			//高级浏览器、支持XMLHttpRequest
			return new XMLHttpRequest();
		} else if (typeof(ActiveObject) != 'undefined') {
			//垃圾浏览器
			var version = ['Microsoft.XMLHTTP', 'MSXML2.XMLHttp.6.0', 'MSXML2.XMLHttp.3.0', 'MSXML2.XMLHttp'];
			
			for (var i=0; i<varsion.length; i++) {
				try {
					return new ActiveObject(version[i]);
				} catch (e) {
					//跳过
				}
			}
		} else {
			throw new Error('浏览器不支持AJAX');
		}
	}) ();
	
	//避免有些浏览器会缓存url信息。第一次请求一个地址、第二次请求同样一个地址是不请求。所以人为为它加上一个随机数、这样就不会出现第二次请求url地址也相同的情况了
	obj.url = obj.url + '?rand' + Math.random();
	
	//处理请求数据、避免数据污染、让每一块相互不影响
	//{username : 'zhanghaifeng', password : '123456'}
	//username=zhanghaifeng&password=123456
	obj.data = (function (data) {
		//创建一个空数组、用来存保存数据
		var arr = [];
		for (var i in data) {
			arr.push(encodeURIComponent(i) + '=' + encodeURIComponent(data[i]));
		}
		return arr.join('&');
	}) (obj.data);
	
	//方法判断是get还是post
	if (obj.method === 'get') {
		if (obj.url.indexOf('?') == -1) {
			obj.url += '?' + obj.data;
		} else {
			obj.url += '&' + obj.data;
		}
	}
	
	//异步
	if (obj.async === true) {
		xhr.onreadystatechange = function ()
		{
			if (xhr.readyState == 4) {
				callback();
			}
		}
	}
	
	//同步
	if (obj.async == false) {
		callback();
	}
	
	//打开xhr.open
	xhr.open(obj.method, obj.url, obj.async);
	
	if (obj.method === 'post') {
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencode');
		xhr.send(data);
	} else {
		xhr.send(null);
	}
	
	//会掉方法来处理回调
	function callback ()
	{
		if (xhr.status == 200) {
			obj.success(xhr.responseText);
		} else {
			alert('获取数据失败、状态码为' + xhr.status);
		}
	}
}


























