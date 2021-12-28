<?php

return  [

    "PAYTM_MID" => env("PAYTM_MID","Tagtas16252385153683"),
    "PAYTM_MERCHANT_KEY"=>env("PAYTM_MERCHANT_KEY","SlmBHI_OWtMgVO#Q"),
    "PAYTM_GUID_SURVEY"=>env("PAYTM_GUID_SURVEY","c96d6228-4f7c-11eb-b4be-fa163e429e83"),
    "PAYTM_GUID_TASTING"=>env("PAYTM_GUID_TASTING","c96d6228-4f7c-11eb-b4be-fa163e429e83"),
    "PAYTM_ENDPOINT" => env("PAYTM_ENDPOINT","https://staging-dashboard.paytm.com"),
    "PAYTM_CALLBACK_URL"=>env("PAYTM_CALLBACK_URL","https://apidev.tagtaste.com/api/link/status/callback"),


    "CASHFREE_CLIENT_ID" => env("CASHFREE_CLIENT_ID","CF114964C710418SN97UHOLPCFDG"),
    "CASHFREE_SECRET_ID"=>env("CASHFREE_SECRET_ID","9c357596c821b197f674b0b90658df66dc545751"),
    "CASHFREE_ENDPOINT" => env("CASHFREE_ENDPOINT","https://payout-gamma.cashfree.com"),
    "CASHFREE_CALLBACK_URL"=>env("CASHFREE_CALLBACK_URL","https://apidev.tagtaste.com/api/link/status/callback"),

];
