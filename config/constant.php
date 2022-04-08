<?php

return  [

    "SURVEY_STATES" => [
        'DRAFT' => 1,
        'PUBLISHED' => 2,
        'CLOSED' => 3,
        'EXPIRED' => 4
    ],

    "SURVEY_STATUS" => [
        "COMPLETED" => 2,
        "PENDING" => 1
    ],

    "AGE_RANGE_LIST" => [
        "gen-z" ,
        "millenials",
        "gen-x",
        "yold"
    ],
    "SURVEY_APPLICANT_ANSWER_STATUS"=>[
        "INVITED" => 0,
        "INCOMPLETE" => 1,
        "COMPLETED" => 2
    ],

    "MEDIA_SURVEY_QUESTION_TYPE" => 5,
    'PAYMENT_INITIATED_STATUS_ID' => 1,
    'PAYMENT_PENDING_STATUS_ID' => 2,
    'PAYMENT_SUCCESS_STATUS_ID' => 3,
    'PAYMENT_FAILURE_STATUS_ID' => 4,
    'PAYMENT_CANCELLED_STATUS_ID' => 5,
    'PAYMENT_EXPIRED_STATUS_ID' => 6,
    'MINIMUM_PAID_TASTER_REVIEWS' => 4,

    'PAYMENT_STATUS' => [
        'initiated' => 1,
        'pending' => 2,
        'success' => 3,
        'failure' => 4,
        'cancelled' => 5,
        'expired' => 6
    ],

    'SURVEY_QUESTION_TYPES' => [
        'LONG_ANSWER' => 1,
        'SHORT_ANSWER' => 2,
        'MULTIPLE_CHOICE' => 3,
        'SINGLE_CHOICE' => 4,
        'UPLOAD_FILE' => 5,
        'RANGE' => 6,
        'RANK' => 7,
        'MULTI_SELECT_RADIO' => 8,
        'MULTI_SELECT_CHECK' => 9

    ],

    'NOTIFICATION_DELETE_PERIOD' => 60,
    'MINIMUM_PAID_TASTER_PRIVATE_REVIEWS' => 2,
    'MINIMUM_PAID_TASTER_TOTAL_REVIEWS' => 3,
    'PAYMENT_REMINDER_BEFORE_DAYS' => 2,

    'OTP_LOGIN_TIMEOUT_MINUTES' => 1,
    'OTP_LOGIN_VERIFY_MAX_ATTEMPT' => 3,
    'LOGIN_OTP_SOURCE' => "login_otp",
    "LINKEDIN_CLIENTID" => env("LINKEDIN_ID"),
    "LINKEDIN_SECRET" => env("LINKEDIN_LOGIN_SECRET"),

    "SURVEY_PRIVATE" => 1,
    "SURVEY_PUBLIC" => 0,

    "COLLABORATE_HEADER_SELECTION_TYPE" =>[
        "instruction"=>0,
        "NORMAL" =>1,
        "PRODUCT_EXPERIENCE" =>2,
        "bill_shot" =>3
    ]
    ,
    'SELECT_TYPES' => [
        'SELFIE_TYPE' => 6
    ],
    "DEFAULT_SIZE" => 8,

    "COMPANY_ID" => 9
    
];


?>
