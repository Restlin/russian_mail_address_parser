<?php

return [
    'definitions' => [

    ],
    'singletons' => [
        'app\services\FileService' => [
            'class' => 'app\services\FileService',
            'path' => '@app/files/',
        ],
    ],
];
