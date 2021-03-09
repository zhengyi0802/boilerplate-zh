<?php

return [
    'categories' => [
        'default' => '後台工作',
        'users'   => '使用者',
    ],
    'backend_access' => [
        'display_name' => '允許進入後台工作',
        'description'  => '使用者可使用管理後台界面',
    ],
    'users_crud' => [
        'display_name' => '使用者管理',
        'description'  => '使用者可被新增, 修改或刪除其他使用者',
    ],
    'roles_crud' => [
        'display_name' => '權限與規則管理',
        'description'  => '使用者可編輯與自定義權限規則',
    ],
    'logs' => [
        'display_name' => '瀏覽記錄',
        'description'  => '使用者可瀏覽記錄',
    ],
];
