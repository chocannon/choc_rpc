<?php

return [
    'index' => [
        'rules' => [
            'title' => 'required|string|min:2|max:13', 
        ],
        'attributes' => [
            'title' => '权限名称',
        ]
    ],
    // 'test' => [
    //     'rules' => [
    //         'title' => 'required|string|min:2|max:13', 
    //     ],
    //     'attributes' => [
    //         'title' => 'TEST权限名称',
    //     ]
    // ],
];