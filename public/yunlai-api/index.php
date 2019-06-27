<!DOCTYPE html>
<html>
<head>
	<title>兼职校缘平台接口列表</title>
	<meta charset="utf-8">
	<link rel="icon" type="image/png" href="http://www.yunlike.cn/uploads/46.png" sizes="32x32" />
	<link href='css/typography.css' media='screen' rel='stylesheet' type='text/css'/>
	<link href='css/reset.css' media='screen' rel='stylesheet' type='text/css'/>
	<link href='css/screen.css' media='screen' rel='stylesheet' type='text/css'/>
	<link href='css/reset.css' media='print' rel='stylesheet' type='text/css'/>
	<link href='css/print.css' media='print' rel='stylesheet' type='text/css'/>
	<style type="text/css">
		.swagger-section #header {
		    background-color: #1d2939;
		}
		.swagger-section #header a#logo {
			font-size: 1.2em;
		    background: transparent url(http://www.yunlike.cn/uploads/46.png) no-repeat left center;
		    padding: 20px 0px 20px 56px;
		}
		.swagger-section #header form#api_selector .input a#explore {
			background-color: #3c8dbc;
		}
		.swagger-section #header form#api_selector .input a#explore:hover {
		    background: #4db3ff;
		    border-color: #4db3ff;
		    color: #fff;
		}
	</style>
	<script src='lib/jquery-1.8.0.min.js' type='text/javascript'></script>
	<script src='lib/jquery.slideto.min.js' type='text/javascript'></script>
	<script src='lib/jquery.wiggle.min.js' type='text/javascript'></script>
	<script src='lib/jquery.ba-bbq.min.js' type='text/javascript'></script>
	<script src='lib/handlebars-2.0.0.js' type='text/javascript'></script>
	<script src='lib/underscore-min.js' type='text/javascript'></script>
	<script src='lib/backbone-min.js' type='text/javascript'></script>
	<script src='swagger-ui.js' type='text/javascript'></script>
	<script src='lib/highlight.7.3.pack.js' type='text/javascript'></script>
	<script src='lib/marked.js' type='text/javascript'></script>
	<script src='lib/swagger-oauth.js' type='text/javascript'></script>
	<!-- 语言包 -->
	<script src='lang/translator.js' type='text/javascript'></script>
	<script src='lang/zh-CN.js' type='text/javascript'></script>

	<script type="text/javascript">
	var url = '';
    $(function () {
		// var url = window.location.search.match(/url=([^&]+)/);
		// if(url && url.length > 1){
		// 	url = decodeURIComponent(url[1]);
		// }else{
		// 	url = "{{ http().domain() }}/swagger/json";
		// }
		url = url + '/swagger/json';
		window.swaggerUi = new SwaggerUi({
			url: url,
			dom_id: "swagger-ui-container",
			supportedSubmitMethods: ['get', 'post', 'put', 'delete', 'patch'],
			onComplete: function(swaggerApi, swaggerUi){
				window.SwaggerTranslator.translate();
				if(typeof initOAuth == "function"){
					initOAuth({
						clientId: "your-client-id",
						realm: "your-realms",
						appName: "your-app-name"
					});
				}

				$('pre code').each(function(i, e){
					hljs.highlightBlock(e)
				});

				addApiKeyAuthorization();
			},
			onFailure: function(data){
				log("Unable to Load SwaggerUI");
			},
			docExpansion: "none",
			apisSorter: "alpha",
			showRequestHeaders: false,
			validatorUrl: null, //ERROR在线调试开关
		});

		function addApiKeyAuthorization(){
			var key = encodeURIComponent($('#input_apiKey')[0].value);
			if(key && key.trim() != ""){
				var apiKeyAuth = new SwaggerClient.ApiKeyAuthorization("api_key", key, "query");
				window.swaggerUi.api.clientAuthorizations.add("api_key", apiKeyAuth);
				log("added key " + key);
			}
		}

		$('#input_apiKey').change(addApiKeyAuthorization);

		// if you have an apiKey you would like to pre-populate on the page for demonstration purposes...
		/*
        var apiKey = "myApiKeyXXXX123456789";
        $('#input_apiKey').val(apiKey);
		*/

		window.swaggerUi.load();

		function log(){
			if ('console' in window){
				console.log.apply(console, arguments);
			}
		}
	});
	</script>
</head>

<body class="swagger-section">
	<div id='header'>
		<div class="swagger-ui-wrap">
			<a id="logo" href="http://www.yunlike.cn" target="_blank">兼职校缘平台API</a>
			<form id='api_selector'>
				<div class='input'><input placeholder="http://example.com/api" id="input_baseUrl" name="baseUrl" type="text"/></div>
				<div class='input'><input placeholder="接口关键字" id="input_apiKey" name="apiKey" type="text"/></div>
				<div class='input'><a id="explore" href="#">查找</a></div>
			</form>
		</div>
	</div>

	<div id="message-bar" class="swagger-ui-wrap">&nbsp;</div>
	<div id="swagger-ui-container" class="swagger-ui-wrap"></div>
</body>

</html>
