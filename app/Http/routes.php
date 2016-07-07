<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
$app->get('/admin', function()
{	
	$_SESSION = Session::all();
	if(!isset($_SESSION["id"]) || !isset($_SESSION["name"]))
		return view('admin.login');
	return view('admin.admin');
});

session_start();
if(isset($_GET['token'])){
	$_SESSION['token'] = $_GET['token'];
	$url = $_SERVER['REQUEST_URI'];
	$urlArr = [];
	$urlArr = parse_url($url);
	parse_str($urlArr['query'],$queryArr);
	unset($queryArr['token']);
	$urlNew = http_build_query($queryArr);
	$redirectUrl = 'http://'.$_SERVER['HTTP_HOST'].$urlArr['path'].'?'.$urlNew;
	header("location:".$redirectUrl);exit();
}

$app->group(array('prefix'=>'/'),function() use ($app){

	// 读书
	$app->get('/',                						    	    	'App\Http\Controllers\Front\PartyController@index');
	$app->post('/search',                						    	'App\Http\Controllers\Front\PartyController@search');
	$app->get('/help',                						    	    'App\Http\Controllers\Front\PartyController@indexHelp');
	$app->get('/book/add',                 					    		'App\Http\Controllers\Front\PartyController@addBook');
	$app->get('/{type}/{id}',                   						'App\Http\Controllers\Front\PartyController@show');
	$app->post('/book-tag',                 					    	'App\Http\Controllers\Front\PartyController@tagBookIndex');
	$app->post('/help/{id}',                 					    	'App\Http\Controllers\Front\PartyController@help');
	$app->post('/borrow/{id}',                 					    	'App\Http\Controllers\Front\PartyController@borrow');
	
	$app->post('borrow/{book_id}/timeline',                 			'App\Http\Controllers\Front\PartyController@timeLineAdd');
	$app->post('{type}/{book_id}/timeline/{id}',                		'App\Http\Controllers\Front\PartyController@timeLineUpdate');

	$app->post('/douban',                 					        	'App\Http\Controllers\Front\PartyController@douban');
	$app->post('/book/{type}',                 					        'App\Http\Controllers\Front\PartyController@store');
	
	$app->get('/tag',                 					        		'App\Http\Controllers\Front\PartyController@tagIndex');
	$app->get('/book/{isbn}/tag',                 					    'App\Http\Controllers\Front\PartyController@bookTagIndex');
	

	// 分享
	$app->post('news',                 					         		'App\Http\Controllers\Front\NewsController@store');
	$app->post('news/{id}',                 					        'App\Http\Controllers\Front\NewsController@remove');
	$app->get('news',                						         	'App\Http\Controllers\Front\NewsController@index');
	$app->post('comment',                 								'App\Http\Controllers\Front\NewsController@comment');
	$app->post('star',                 									'App\Http\Controllers\Front\NewsController@star');
	

	// 个人
	$app->get('/myBook',                 					    		'App\Http\Controllers\Front\MineController@myBook');
	$app->post('/recallBook/{id}',                 					    'App\Http\Controllers\Front\MineController@recallBook');
	$app->get('/myNews',                 					    		'App\Http\Controllers\Front\MineController@myNews');
	$app->post('/state',                 					    		'App\Http\Controllers\Front\MineController@updateState');
});

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {
	$api->get 	('/menu',									'App\Http\Controllers\Admin\MenuController@index');
	$api->get 	('/menu/{id:[0-9]+}',						'App\Http\Controllers\Admin\MenuController@show');
	$api->post 	('/menu',     								'App\Http\Controllers\Admin\MenuController@store');
	$api->put 	('/menu/{id:[0-9]+}',						'App\Http\Controllers\Admin\MenuController@update');

	$api->get 	('/category',     							'App\Http\Controllers\Admin\CategoryController@index');
	$api->get 	('/category/{id:[0-9]+}',					'App\Http\Controllers\Admin\CategoryController@show');
	$api->post 	('/category',     							'App\Http\Controllers\Admin\CategoryController@store');
	$api->put 	('/category/{id:[0-9]+}',					'App\Http\Controllers\Admin\CategoryController@update');

	//通用
	$api->get 	('/{table:[A-Z_a-z]+}/config', 				'App\Http\Controllers\Admin\ApiController@config'		);

	$api->post 	('/file', 					                'App\Http\Controllers\Admin\ApiController@upload'		);
	$api->get 	('/{table:[A-Z_a-z]+}', 					'App\Http\Controllers\Admin\ApiController@index'		);
	$api->get 	('/{table:[A-Z_a-z]+}/{id:[0-9]+}', 		'App\Http\Controllers\Admin\ApiController@show'		);
	$api->post 	('/{table:[A-Z_a-z]+}', 					'App\Http\Controllers\Admin\ApiController@store'		);
	$api->put 	('/{table:[A-Z_a-z]+}/{id:[0-9]+}', 		'App\Http\Controllers\Admin\ApiController@update'		);
	$api->delete('/{table:[A-Z_a-z]+}/{id:[0-9]+}', 		'App\Http\Controllers\Admin\ApiController@destroy'	);
	$api->get 	('/config/init', 							'App\Http\Controllers\Admin\ConfigController@init'	);

	$api->get  ('/user/session',               				 'App\Http\Controllers\Admin\UserController@check');
	$api->post ('/user/login',                				 'App\Http\Controllers\Admin\UserController@login');
	$api->get  ('/user/logout',                				 'App\Http\Controllers\Admin\UserController@logout');
	$api->post ('/user/{id}/password',        				 'App\Http\Controllers\Admin\UserController@password');	
});

