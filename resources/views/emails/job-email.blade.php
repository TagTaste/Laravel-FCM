
<head>
    <!-- If you delete this meta tag, Half Life 3 will never be released. -->
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Tagtasteemails</title>
    <!--<link rel="stylesheet" type="text/css" href="stylesheets/email.css" /> -->
</head>
<body>
    <table width="100%" border ="0" cellspacing ="0" cellpadding = "0" bgcolor="#FAFAFA" style="border-bottom: 2px solid #D9222A;font-family:helvetica neue">
        <tr   >
            <td>
                <table class="container" width= "620" align="center" border="0" cellspacing="0" cellpadding="0"  border-collapse="collapse">
                    <tr>
                        <td valign="top" class="logo" align="center" style="padding: 30px 0px 0px 15px;">
                            <img src = "https://www.tagtaste.com/images/icons/logo-transparent.png" alt="tagtaste" width="115px" height="40px">

                        </td>
                    </tr>
                    <tr>
                        <table width= "620" align="center" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border-collapse="collapse" style="padding:20px;margin-top: 15px;margin-bottom:50px;">
                            <tr>
                                <td style="font-size: 24px; font-weight: bold;">
                                    <h1 style="font-size: 24px; font-weight: bold;padding:0px;margin: 0px 0px 20px 0px;color:#000">Job <span style="color: #D9222A;">Application Recieved</span></h1>
                                </td>
                            </tr>
                            <tr>
                                <td padding="11px 0px 0px 0px" style="color: rgba(0,0,0,0.9)!important;font-size: 16px;">
                                    <p style="margin-top: 0px;color: rgba(0,0,0,0.9)!important;font-size: 16px;">{{$name}} wants to be a part of tagtaste team  as a {{$job}}</p>
                                    @if(!empty ($email )) 
                                        <p style="margin-top: 0px; color: rgba(0,0,0,0.9)!important;"> You can get in touch with {{$name}} on {{$email}}</p>
                                    @endif
                                    @if(!empty($description)) 
                                        <p style="margin-top: 0px;color: rgba(0,0,0,0.9)!important;">Candidate thinks he/she will be a good fit because:</p>
                                        <p style="margin-top: 0px;color: rgba(0,0,0,0.9)!important;"> {!! nl2br(e($description)) !!} </p>
                                    @endif
                                  
                                </td>
                            </tr>
                        </table>
                    </tr>
                </table>
            </td>
        </tr>       
    </table>
</body>