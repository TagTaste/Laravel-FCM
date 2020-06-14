@include("emails.header.v1.definition")
<body style="margin: 0; padding: 0;">
<center>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial" bgcolor="#F8F6F9">
        @include("emails.header.v1.header")
        <tr>
            <td align="center" valign="top">
                <table class="container" width="620" align="center" border="0" cellspacing="0" cellpadding="0"
                       border-collapse="collapse">
                    <tr>
                        <td align="center" width="100%" bgcolor="#F8F6F9">
                            <table width="550" align="center" border="0" cellspacing="0" cellpadding="0"
                                   border-collapse="collapse">
                                <tr>
                                    <td align="center" bgcolor="#FFFFFF" style="padding:20px 0 0;border-radius: 4px 4px 0 0;width:100%!important;" width="100%">
                                        <div style="font-size: 24px;font-weight: normal;color: #171717;padding: 0 40px 0 40px">
                                            "Berry Talks"
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:100%!important;background-color:#FFFFFF!important;padding: 0px 40px 0px 40px;border-bottom: 1px solid rgba(0, 0, 0, 0.04);">
                                        <table style="margin: 20px 0px 20px 0px;" width="100%" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgba(0, 0, 0, 0.04);">
                                            <tr>
                                                <td align="center" valign="top" width="100%">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#FFFFFF">
                                                        <tr>
                                                            <td>
                                                                <div style="font-size: 16px;">
                                                                    <p>Hi {{$userName}},</p>
                                                                    <p>TagTaste home page is abuzz with vibrant pictures, lovely childhood memories, and loads of knowledge about our own Indian Berries. Attached below is a snapshot of the <b>“Berry Talks”</b> in case you missed it.</p>
                                                                    <p>If you want to know more about berries, we recommend that you check out the TagTaste feed.</p>
                                                                    <p>Regards, <br/>Team TagTaste</p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td valign="top" align="center" bgcolor="#FFFFFF">
                                                    <a href="http://bit.ly/tagtaste-berry-talk"
                                                       style="display: inline-block;text-decoration: none;padding: 14px 24px;color: #FFFFFF;background-color: #D9222A;box-shadow: none;border: none;font-size: 16px;margin: 31px 0px 31px 0px;border-radius: 24px;font-weight: normal;">
                                                        Go To Homepage
                                                    </a>
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