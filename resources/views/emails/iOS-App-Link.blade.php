@include("emails.header.definition")
<body style="margin: 0; padding: 0;">
<center>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial" bgcolor="#F8F6F9">
        @include("emails.header.header")
        <tr>
            <td align="center" valign="top">
                <table class="container" width="620" align="center" border="0" cellspacing="0" cellpadding="0"
                       border-collapse="collapse">
                    <tr>
                        <td align="center" width="100%" bgcolor="#F8F6F9">
                            <table width="550" align="center" border="0" cellspacing="0" cellpadding="0"
                                   border-collapse="collapse">
                                <tr>
                                    <td align="center" bgcolor="#FFFFFF" style="padding:20px 0px 20px 0px;border-radius: 10px 10px 0 0;width:100%!important;" width="100%">
                                        <div style="font-size: 20px;font-weight: bold;color: rgb(217,34,42);padding: 0 40px 0 40px">
                                            TagTaste - iOS & Android apps!
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="background-color: #FAFAFA;height: 10px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:100%!important;background-color:#FFFFFF!important;padding: 0px 40px 0px 40px;border-bottom: 1px solid rgba(0, 0, 0, 0.04);">
                                        <table style="margin: 20px 0px 20px 0px;" width="100%" bgcolor="#FFFFFF"
                                               style="border-bottom: 1px solid rgba(0, 0, 0, 0.04);">
                                            <tr>
                                                <td align="center" valign="top" width="100%"
                                                    class="templateColumnContainer">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%"
                                                           bgcolor="#FFFFFF">
                                                        <tr>
                                                            <td>
                                                                <div>
                                                                    <p style="font-size: 16px;color: #181818;margin:0px">
                                                                        Dear <strong>{{$userName}}</strong>
                                                                        <br>
                                                                        <br>
                                                                        TagTaste <strong>iOS applications</strong> and <strong>Android applications</strong> can make it super easy for you to discover,
                                                                        network and collaborate with F&B professionals around you. Click the links given below to download
                                                                        our apps now!
                                                                        <br>
                                                                        <br>
                                                                        Use your TagTaste login email and password to access your account.

                                                                    </p>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td style="width:100%!important;background-color:#FFFFFF!important;padding: 18px 36px;" align="center">
                                                                <table>
                                                                    <tr>
                                                                        <td valign="top" align="center" bgcolor="#FFFFFF ">
                                                                            <div style="color:rgb(24,24,24);font-size:14px;text-align:center;padding:0px 20px 20px;display: block; width: 100%;">
                                                                                Reach out to us at <a href="mailto:support@tagtaste.com">support@tagtaste.com</a> for any assistance.
                                                                                <br>
                                                                                Lost account access? <a href="https://www.tagtaste.com/password/reset">Click here</a> to reset your password.
                                                                            </div>
                                                                        </td>
                                                                    </tr>

                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
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