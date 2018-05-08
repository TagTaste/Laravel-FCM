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
                                            TagTaste has an iOS application!
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
                                                                        iPhone (Apple) users can now use TagTaste on their phones via our <strong>new iOS app!</strong> Click the link to download
                                                                        it from the App Store. Please use your TagTaste login email and password to activate the same.
                                                                    </p>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td style="width:100%!important;background-color:#FFFFFF!important;padding: 18px 36px;" align="center">
                                                                <table>

                                                                    <tr>
                                                                        <td valign="top" align="center"  bgcolor="#FFFFFF" >

                                                                            <a href="https://itunes.apple.com/us/app/tagtaste/id1347112212?mt=8" style="text-decoration:none;padding: 15px 36px;border-radius: 4px;color: #FFFFFF;background-color: #D9222A;box-shadow: none;border: none;font-size: 18px;border-radius: 30px; font-weight: normal; display: inline-block;margin-bottom: 10px;">Download Application</a>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td valign="top" align="center" bgcolor="#FFFFFF ">
                                                                            <div style="color:rgb(24,24,24);font-size:14px;text-align:center;padding:0px 20px 20px;display: block; width: 100%;">
                                                                                Reach out to us at <a href="mailto:support@tagtaste.com">support@tagtaste.com</a> for any assistance.
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