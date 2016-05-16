var globalData = {
	"site_title":"readParty"
};

var menuData = [
		{	
			'key'			: 'BOOK',
			'name'			: '派对藏书',
			'description'	: '对书籍进行管理',
			'ico'			: 'equal-box',
			'url'			: '#',
			'need_admin'    : true,
			'son'			:
			[
				{
					'key'			: 'borrow',
					'name'			: '书·可借',
					'description'	: '对用户可借书进行管理',
					'ico'			: 'library-books',
					'need_admin'    : false,
					'state'			: ['object',{'objectName':'borrow'}]
				},
				{
					'key'			: 'help',
					'name'			: '书·想借',
					'description'	: '对用户想借的书进行管理',
					'ico'			: 'library-books',
					'need_admin'    : false,
					'state'			: ['object',{'objectName':'help'}]
				},
				{
					'key'			: 'book',
					'name'			: '书',
					'description'	: '对藏书进行管理',
					'ico'			: 'settings',
					'need_admin'    : false,
					'state'			: ['object',{'objectName':'book'}]
				},
				{
					'key'			: 'tab',
					'name'			: '标签',
					'description'	: '对藏书分类进行管理',
					'ico'			: 'settings',
					'need_admin'    : false,
					'state'			: ['object',{'objectName':'tab'}]
				}
			]
		},
		{	
			'key'			: 'USER',
			'name'			: '用户',
			'description'	: '对用户进行管理',
			'ico'			: 'account',
			'url'			: '#',
			'need_admin'    : false,
			'son'			:
			[
				{
					'key'			: 'user',
					'name'			: '用户',
					'description'	: '对用户进行管理',
					'ico'			: 'account',
					'need_admin'    : false,
					'state'			: ['object',{'objectName':'user'}]
				},
				{
					'key'			: 'news',
					'name'			: '动弹',
					'description'	: '对用户分享的进行管理',
					'ico'			: 'comment-account-outline',
					'need_admin'    : false,
					'state'			: ['object',{'objectName':'news'}]
				}
			]
		}
	];

var objectMenuData = {
	'book'           :
	[
		{
			'key'			: 'detail',
			'name'			: '详情',
			'name_field'    : 'title',
			'description'	: '您可在此页面查看书的详情',
			'ico'			: 'information',
			'state'			: ['objectDetail',{}]
		},
		{
			'key'			: 'tab',
			'name'			: '所属标签',
			'name_field'    : 'title',
			'description'	: '您可在此页面查看书的分类',
			'ico'			: 'eye',
			'state'			: ['objectRelate',{'objectRelate':'book_tab'}]
		}
	],
	'borrow'           :
	[
		{
			'key'			: 'detail',
			'name'			: '详情',
			'name_field'    : 'title',
			'description'	: '您可在此页面查看用户分享图书的详情',
			'ico'			: 'eye',
			'state'			: ['objectDetail',{'objectName':'borrow'}]
		},
		{
			'key'			: 'book',
			'name'			: '书籍详情',
			'name_field'    : 'title',
			'description'	: '您可在此页面查看书详情',
			'ico'			: 'eye',
			'state'			: ['objectRelate',{'objectRelate':'book'}]
		},
		{
			'key'			: 'timeline',
			'name'			: '时间轴',
			'name_field'    : 'title',
			'description'	: '您可在此页面查看本书的时间轴',
			'ico'			: 'eye',
			'state'			: ['objectRelate',{'objectRelate':'timeline'}]
		}
	],
	'help'           :
	[
		{
			'key'			: 'detail',
			'name'			: '详情',
			'name_field'    : 'title',
			'description'	: '您可在此页面查看用户申请借书的详情',
			'ico'			: 'eye',
			'state'			: ['objectDetail',{'objectName':'help'}]
		},
		{
			'key'			: 'book',
			'name'			: '空间会员',
			'name_field'    : 'title',
			'description'	: '您可在此页面查看书详情',
			'ico'			: 'eye',
			'state'			: ['objectRelate',{'objectRelate':'book'}]
		},
		{
			'key'			: 'timeline',
			'name'			: '时间轴',
			'name_field'    : 'title',
			'description'	: '您可在此页面查看本书的时间轴',
			'ico'			: 'eye',
			'state'			: ['objectRelate',{'objectRelate':'timeline'}]
		}
	],
	'news'           :
	[
		{
			'key'			: 'detail',
			'name'			: '动弹详情',
			'name_field'    : 'title',
			'description'	: '您可在此页面查看动弹详情',
			'ico'			: 'eye',
			'state'			: ['objectDetail',{'objectName':'news'}]
		},
		{
			'key'			: 'comments',
			'name'			: '动弹评论',
			'name_field'    : 'title',
			'description'	: '您可在此页面查看动弹评论',
			'ico'			: 'eye',
			'state'			: ['objectRelate',{'objectRelate':'comments'}]
		},
	]
}
var relateData = {
	'book_caregory':
	{
		'table'    :'book',
		'field'    :'id',
		'r_table'  :'category',
		'r_field'  :'id',
		'r_filter' :{}
	},
	'borrow_book':
	{
		'table'    :'borrow',
		'field'    :'isbn',
		'r_table'  :'book',
		'r_field'  :'isbn',
		'r_filter' :{}
	},
	'borrow_timeline':
	{
		'table'    :'borrow',
		'field'    :'id',
		'r_table'  :'timeline',
		'r_field'  :'book_id',
		'r_filter' :{}
	},
	'help_book':
	{
		'table'    :'help',
		'field'    :'isbn',
		'r_table'  :'book',
		'r_field'  :'isbn',
		'r_filter' :{}
	},
	'help_timeline':
	{
		'table'    :'help',
		'field'    :'id',
		'r_table'  :'timeline',
		'r_field'  :'book_id',
		'r_filter' :{}
	}

}

//自定义操作
var operaData = {
};

//列表背景色
var bgColorData = {};
