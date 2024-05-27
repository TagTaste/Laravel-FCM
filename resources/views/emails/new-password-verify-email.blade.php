@include("emails.header.v1.definition")
<body style="margin: 0; padding: 0">
<center>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family: Arial" bgcolor="#F8F6F9">
        @include("emails.header.v1.header")
        <tr>
            <td align="center" valign="top">
                <table class="container" width="620" align="center" border="0" cellspacing="0" cellpadding="0" border-collapse="collapse">
                    <tr>
                        <td align="center" width="100%" bgcolor="#F8F6F9">
                            <table width="550" align="center" border="0" cellspacing="0" cellpadding="0" border-collapse="collapse">
                                <tr>
                                    <td align="center" bgcolor="#FFFFFF" style="padding: 20px 0 0; border-radius: 4px 4px 0 0; width: 100% !important" width="100%">   
                                        <div style="font-size: 18px; font-weight: 900; padding: 0 40px 0 40px">
                                            {{$heading}}
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 100% !important; background-color: #ffffff !important; padding: 0 20px 20px 20px; border-radius: 4px;">
                                        <table style="margin: 20px 0px 0px 0px; padding: 16px; border: solid 0.5px rgba(0, 0, 0, 0.1); border-radius: 10px;" width="100%" bgcolor="#FFFFFF">
                                            <tr>
                                                <td align="center" valign="top" width="100%" class="templateColumnContainer">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#FFFFFF">
                                                        <tr>
                                                            <td bgcolor="#FFFFFF" align="left">
                                                            <p style="font-size: 16px; color: #181818; margin: 0px; line-height: 1.5">
                                                                Hi {{$name}},
                                                            </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td bgcolor="#FFFFFF" align="left">
                                                            <p style="color:#181818; margin: 16px 0px 0px 0px;font-size: 16px; line-height: 1.5;">
                                                                {{$firstParagraph}}
                                                            </p>
                                                            <p style="color: #181818; margin: 16px 0px 0px 0px; font-size: 16px; line-height: 1.5;">
                                                                Verification Code (OTP): 
                                                                <span  style="color: #181818; margin: 16px 0px 0px 0px; font-size: 16px; line-height: 1.5; font-weight: 700;">
                                                                {{$otp}}</span>
                                                            </p>
                                                            <p style="color: #181818; margin: 16px 0px 0px 0px; font-size: 16px; line-height: 1.5;">
                                                                For security purposes, please ensure that you do not share this OTP with anyone. If you did not request this OTP, please disregard this email and contact our team immediately at support@tagtaste.com.
                                                            </p>
                                                            <div style="margin-top: 24px">
                                                                <p style="color: #181818; font-size: 14px; margin: 0px; line-height: 1.5">
                                                                    Sincerely,<br />
                                                                    Team TagTaste
                                                                </p>
                                                            </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                @include("emails.footer.v1.footer")
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</center>
</body>
