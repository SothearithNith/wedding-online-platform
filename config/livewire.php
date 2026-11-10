<?php

/*
 | Minimal Livewire config: it only overrides the temporary upload settings.
 | Livewire merges it with its own defaults, so every other option keeps working.
 | (If you published the full file with `php artisan livewire:publish --config`,
 |  do not use this file; just change the 'rules' line there instead.)
 */

return [

    'temporary_file_upload' => [
        'disk'            => env('LIVEWIRE_TEMPORARY_FILE_UPLOAD_DISK'),
        'rules'           => ['required', 'file', 'max:20480'],   // 20 MB (Livewire default is 12 MB)
        'directory'       => null,
        'middleware'      => null,
        'preview_mimes'   => [
            'png',
            'gif',
            'bmp',
            'svg',
            'wav',
            'mp4',
            'mov',
            'avi',
            'wmv',
            'mp3',
            'm4a',
            'jpg',
            'jpeg',
            'mpga',
            'webp',
            'wma',
        ],
        'max_upload_time' => 5,   // minutes
        'cleanup'         => true,
    ],

];
