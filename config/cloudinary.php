<?php

return [
    'url'           => env('CLOUDINARY_URL'),
    'cloud_name'    => env('CLOUDINARY_CLOUD_NAME'),
    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET', 'chat_preset'),
];
