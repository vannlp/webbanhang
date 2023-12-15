<?php

return [
    'admin' => [
        [
            'id' => 1,
            'code' => 'USER',
            'title' => 'Người dùng',
            'name' => null,
            'permisstion_group' => ['LISTUSER','LISTROLE','LISTPERMISSION'],
            'icon' => '<i class="bi bi-people"></i>',
            'children' => [
                ['id' => 1, 'title' => 'Quản lý người dùng', 'name' => 'admin.user.index', 'permission_code' => 'LISTUSER'],
                ['id' => 2, 'title' => "Role" , 'name' => 'admin.role', 'permission_code' => 'LISTROLE'],
                ['id' => 3, 'title' => "Permission", 'name' => 'admin.permission', 'permission_code' => 'LISTPERMISSION']
            ],
        ],
        [
            'id' => 2,
            'code' => 'MEDIA',
            'name' => 'admin.media.index',
            'permisstion_group' => ['VIEW_MEDIA'],
            'icon' => '<i class="bi bi-collection-play-fill"></i>',
            'children' => [],
            'title' => "Media"
        ],
        [
            'id' => 3,
            'code' => 'POST',
            'title' => 'Bài viết',
            'name' => null,
            'permisstion_group' => ['VIEW_POST', 'VIEW_CATEGORY_POST'],
            'icon' => '<i class="bi bi-stickies-fill"></i>',
            'children' => [
                ['id' => 1, 'title' => 'Danh mục bài viết', 'name' => 'admin.post.category', 'permission_code' => 'VIEW_CATEGORY_POST'],
                ['id' => 2, 'title' => 'Bài viết', 'name' => 'admin.post.index', 'permission_code' => 'VIEW_POST'],
                ['id' => 3, 'title' => 'Thêm Bài viết', 'name' => 'admin.post.add', 'permission_code' => 'CREATE_POST'],
            ],
        ] ,
        [
            'id' => 4,
            'code' => 'PRODUCT',
            'title' => 'Sản phẩm',
            'name' => null,
            'permisstion_group' => ['VIEW_PRODUCT_CATEGORY', 'VIEW_PRODUCT', 'CREATE_PRODUCT'],
            'icon' => '<i class="bi bi-basket3-fill"></i>',
            'children' => [
                ['id' => 1, 'title' => 'Danh mục sản phẩm', 'name' => 'admin.product.category', 'permission_code' => 'VIEW_PRODUCT_CATEGORY'],
                ['id' => 2, 'title' => 'Danh sách sản phẩm', 'name' => 'admin.product.index', 'permission_code' => 'VIEW_PRODUCT'],
                ['id' => 3, 'title' => 'Thêm mới', 'name' => 'admin.product.create', 'permission_code' => 'CREATE_PRODUCT'],
            ],  
        ],
    ]
];