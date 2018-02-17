@include("emails.header.definition")
<body>
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
                                    <td align="center" bgcolor="#FFFFFF" style="padding:20px 0px 20px 0px;" width="100%"
                                        style="width:100%!important">
                                        <div style="font-size: 20px;font-weight: bold;color: #181818;margin: 0px 70px 0px 70px;">
                                            Burger king’s collaboration is expiring today.
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="background-color: #FAFAFA;height: 10px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:100%!important;background-color:#FFFFFF!important;padding: 0px 40px 0px 40px;border-bottom: 1px solid rgba(0,0,0,0.2);">
                                        <table style="margin: 20px 0px 20px 0px;" width="100%" bgcolor="#FFFFFF"
                                               style="border-bottom: 1px solid rgba(0,0,0,0.2);">
                                            <tr>
                                                <td align="center" valign="top" width="100%"
                                                    class="templateColumnContainer">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%"
                                                           bgcolor="#FFFFFF">
                                                        <tr>
                                                            <td>
                                                                <div style="padding:0px 10px 0px 10px;">
                                                                    <div>
                                                                        <p style="font-size: 16px;color: #181818;margin:0px;padding:0px 0px 20px 0px;font-weight:bold;">
                                                                            Hi Sonika,
                                                                        </p>
                                                                        <p style="font-size: 16px;color: #181818;margin:0px">
                                                                            Burger King India Pvt. Ltd’s collaboration expires today.
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:100%!important;background-color:#FFFFFF!important;padding: 20px 40px 20px 40px;border-bottom: 1px solid rgba(0,0,0,0.2);">
                                        <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0"
                                               border-collapse="collapse" bgcolor="#FFFFFF">
                                            <tr>
                                                <td align="center" width="50px" class="templateColumnContainer">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                        <tr>
                                                            <td>
                                                                <img src="https://www.tagtaste.com/images/emails/profile-circle.png"
                                                                     height="50px" width="50px"
                                                                     style="border-radius: 50%;">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td align="center" width="317px" class="templateColumnContainer">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%"
                                                           style="border-radius:12px;padding-left:20px;">
                                                        <tr>
                                                            <td>
                                                                <div style="color: #181818;font-weight: bold;">
                                                                    Aman Kumar
                                                                </div>
                                                                <div style="padding-top:8px;color: #181818;">
                                                                    <!-- message -->
                                                                    {{--Co-founder &amp; COO @TagTaste--}}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>

                                                <td align="center" width="72px" class="templateColumnContainer">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%"
                                                           style="border-radius:12px;padding-left:20px;">
                                                        <tr>
                                                            <td>
                                                                <a href="{{env('APP_URL')}}/collaborate/100/applications"
                                                                   style="text-decoration:none; display:inline-block;background-color: #D81F2E;padding:8px 20px;color:#FFFFFF;font-size: 13px;font-weight: bold;border: none;border-radius: 4px;">Reply</a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:100%!important;background-color:#FFFFFF!important;">
                                        <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0"
                                               border-collapse="collapse" bgcolor="#FFFFFF">
                                            <tr>
                                                <td valign="top" align="center" bgcolor="#FFFFFF">
                                                    <a href="{{env('APP_URL')}}/collaborate/100/applications"
                                                       style="display: inline-block; text-decoration: none; padding: 15px 30px;border-radius: 4px;color: #FFFFFF;background-color: #D9222A;box-shadow: none;border: none;font-size: 18px;margin: 31px 0px 31px 0px;border-radius: 30px; font-weight: normal;">EXTEND NOW</a>
                                                </td>
                                            </tr>

                                        </table>
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