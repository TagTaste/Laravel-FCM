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
        "gen-z",
        "millenials",
        "gen-x",
        "yold"
    ],
    "SURVEY_APPLICANT_ANSWER_STATUS" => [
        "INVITED" => 0,
        "INCOMPLETE" => 1,
        "COMPLETED" => 2,
        "REJECTED" => 3
    ],

    "SURVEY_APPLICANT_STATUS" => [
        "TO BE NOTIFIED" => 0,
        "NOTIFIED" => 1,
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

    "COLLABORATE_HEADER_SELECTION_TYPE" => [
        "instruction" => 0,
        "NORMAL" => 1,
        "PRODUCT_EXPERIENCE" => 2,
        "bill_shot" => 3
    ],
    'SELECT_TYPES' => [
        'SELFIE_TYPE' => 6
    ],
    "DEFAULT_SIZE" => 8,

    "TAGTASTE_POLL_COMPANY_ID" => '9,10',
    "TAGTASTE_INSIGHT_COMPANY_ID" => '9,10',

    "LANDING_MODEL" => [
        "COLLABORATE" => "collaborate",
        "PRODUCT-REVIEW" => "product-review",
        "SURVEYS" => "surveys",
        "POLLING" => "polling",
        "PRODUCT" => "product",
        "HASHTAG" => "hashtag",
        "LEARN_AND_EARN" => "paid_taster"
    ],

    "LANDING_UI_TYPE" => [
        "QUICK_LINKS" => "quick_links",
        "BIG_BANNNER" => "big_banner",
        "BANNER" => "banner",
        "PASSBOOK" => "passbook",
        "PRODUCT_AVAILABLE" => "product_available",
        "PAID_TASTER" => "paid_taster",
        "SUGGESTION" => "suggestion",
        "CAROUSEL" => "carousel",
        "IMAGE_CAROUSEL" => "image_carousel",
        "HASHTAG" => "hashtag",
        "FEED" => "feed"
    ],

    "POLL_PLACEHOLDER_IMAGE" => [
        '{"meta":{"height":192,"width":500,"tiny_photo":"https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/placeholder-poll-images/tiny/1650040773892_Poll%201.png"},"original_photo":"https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/placeholder-poll-images/1650040773892_Poll%201.png"}',
        '{"meta":{"height":192,"width":500,"tiny_photo":"https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/placeholder-poll-images/tiny/1650040872771_Poll%202.png"},"original_photo":"https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/placeholder-poll-images/1650040872771_Poll%202.png"}',
        '{"meta":{"height":192,"width":500,"tiny_photo":"https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/placeholder-poll-images/tiny/1650040951080_Poll%203.png"},"original_photo":"https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/placeholder-poll-images/1650040951080_Poll%203.png"}',
        '{"meta":{"height":192,"width":500,"tiny_photo":"https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/placeholder-poll-images/tiny/1650040980028_Poll%204.png"},"original_photo":"https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/placeholder-poll-images/1650040980028_Poll%204.png"}',
        '{"meta":{"height":192,"width":500,"tiny_photo":"https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/placeholder-poll-images/tiny/1650041006499_Poll%205.png"},"original_photo":"https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/placeholder-poll-images/1650041006499_Poll%205.png"}'
    ],

    "LANDING_PLACEHOLDER_IMAGE" => [
        '{"meta":{"height":281,"width":500,"tiny_photo":"https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/placeholder-images/tiny/1650041076526_3.png"},"original_photo":"https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/placeholder-images/1650041076526_3.png"}',
        '{"meta":{"height":281,"width":500,"tiny_photo":"https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/placeholder-images/tiny/1650041117841_7.png"},"original_photo":"https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/placeholder-images/1650041117841_7.png"}',
        '{"meta":{"height":281,"width":500,"tiny_photo":"https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/placeholder-images/tiny/1650041145634_8.png"},"original_photo":"https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/placeholder-images/1650041145634_8.png"}',
        '{"meta":{"height":281,"width":500,"tiny_photo":"https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/placeholder-images/tiny/1650041192663_13.png"},"original_photo":"https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/placeholder-images/1650041192663_13.png"}',
        '{"meta":{"height":281,"width":500,"tiny_photo":"https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/placeholder-images/tiny/1650041244422_18.png"},"original_photo":"https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/placeholder-images/1650041244422_18.png"}',
        '{"meta":{"height":281,"width":500,"tiny_photo":"https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/placeholder-images/tiny/1650041288090_20.png"},"original_photo":"https://s3.ap-south-1.amazonaws.com/fortest.tagtaste.com/placeholder-images/1650041288090_20.png"}'
    ],

    "LEARN_AND_EARN_IMAGE" => '{"meta": {"width": 343, "height": 190, "tiny_photo": "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/banner-images/tiny/1649688161520_learn_earn.png"}, "original_photo": "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/banner-images1649688161520_learn_earn.png"}'
];
