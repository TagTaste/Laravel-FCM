@include("emails.header.definition")
<body>
    <center>
        <table width="100%" border ="0" cellspacing ="0" cellpadding = "0"  style="font-family:Arial" bgcolor="#F8F6F9">
            @include("emails.header.header")
            <tr>
                <td align="center" valign="top">
                    <table class="container" width= "620" align="center" border="0" cellspacing="0" cellpadding="0"  border-collapse="collapse" >
                        <tr>
                            <td align="center" width="100%" bgcolor="#F8F6F9" >
                                <table width= "550" align="center" border="0" cellspacing="0" cellpadding="0"  border-collapse="collapse">
                                    <tr>
                                        <td align="center"  bgcolor="#FFFFFF" style="padding:20px 0px 20px 0px;" width="100%" style="width=100%!important">
                                           <img src="https://www.tagtaste.com/images/emails/lock.png" width="67px" height="67px"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center" bgcolor="#FFFFFF">
                                            <div style="color: #181818;font-family: Arial;	font-size: 20px;font-weight: bold;">Recover your account</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top" class="logo" align="center" style="padding: 20px 40px 0px 40px;border-radius: 0px 0px 5px 5px;" bgcolor="#FFFFFF">
                                            <div style="font-size: 16px;color: #181818;">You recently made a request to recover your account on TagTaste. Click the button below to complete the process.</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  style="width:100%!important;background-color:#FFFFFF !important;">
                                            <table width= "100%" align="center" border="0" cellspacing="0" cellpadding="0"  border-collapse="collapse" bgcolor="#FFFFFF ">
                                                <tr>
                                                    <td style="height:31px;background-color:#FFFFFF ">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width:100%!important;background-color:#FFFFFF !important;padding: 18px 36px;" align="center">
                                                        <table>

                                                            <tr>
                                                                <td valign="top" align="center"  bgcolor="#FFFFFF " >

                                                                    <a href="{{ env('APP_URL') }}/password/reset/{{$token}}?email={{$email}}" style="text-decoration:none;padding: 15px 36px;border-radius: 4px;color: #FFFFFF;background-color: #D9222A;box-shadow: none;border: none;font-size: 18px;border-radius: 30px; font-weight: normal">RESET PASSWORD</a>

                                                                </td>
                                                            </tr>


                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="height:31px;background-color:#FFFFFF ">
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center" bgcolor="#FFFFFF">
                                             <div style="color: #181818;font-family: Arial;	font-size: 14px;font-weight: bold;">Didn’t request this change?</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center" bgcolor="#FFFFFF" >
                                            <div style="color: #181818;font-family: Arial;	font-size: 14px;padding: 20px 40px 30px 40px;">Please ignore this email if you did not request the account recovery. Ensure that your email is entered correctly to keep your account safe. Reach out to us at support@tagtaste.com for help.</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #FAFAFA;height: 10px;">
                                        </td>
                                    </tr>
                                    @include("emails.footer.download")
                                    @include("emails.footer.footer")
                                   
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </center>
</body>