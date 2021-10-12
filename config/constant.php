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
    'MINIMUM_PAID_TASTER_REVIEWS' => 3,

    'PAYMENT_STATUS' => [
        'initiated' => 1,
        'pending' => 2,
        'success' => 3,
        'failure' => 4,
        'cancelled' => 5,
        'expired' => 6
    ],

    'NOTIFICATION_DELETE_PERIOD' => 60
    'MINIMUM_PAID_TASTER_PRIVATE_REVIEWS' => 2,
    'MINIMUM_PAID_TASTER_TOTAL_REVIEWS' => 3
];


?>