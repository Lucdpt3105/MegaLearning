<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Firebase Service Account
    |--------------------------------------------------------------------------
    |
    | Path to Firebase service account JSON file
    |
    */
    'credentials' => [
        'file' => base_path('megalearning-firebase-adminsdk-fbsvc-a6a6cad3f7.json'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Firebase Realtime Database URL
    |--------------------------------------------------------------------------
    |
    | URL của Firebase Realtime Database
    | Format: https://YOUR-PROJECT-ID-default-rtdb.firebaseio.com
    |
    */
    'database' => [
        'url' => env('FIREBASE_DATABASE_URL', 'https://megalearning-default-rtdb.firebaseio.com'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Firebase Storage Bucket
    |--------------------------------------------------------------------------
    |
    | Storage bucket cho upload files, images
    |
    */
    'storage' => [
        'bucket' => env('FIREBASE_STORAGE_BUCKET', 'megalearning.appspot.com'),
    ],
];
