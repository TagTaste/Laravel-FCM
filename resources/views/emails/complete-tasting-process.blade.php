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
                                        <div style="font-size: 20px;font-weight: bold;color: #181818;padding: 0 40px 0 40px">
                                            Introduction to TagTaste Taster's Program
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
                                                                    <p style="font-size: 14px;color: #181818;margin:0px">
                                                                        Hi {{$userName}},

                                                                        <br>
                                                                        <br>
                                                                        Welcome to TagTaste! We hope you enjoyed your first tasting session with us. Although our local partner would have briefed you about TagTaste, <a href="https://www.youtube.com/watch?v=2SXP3dHj_-Y" target="_blank">here is a quick two-minute video</a> introduction to the platform for your quick reference.                                                                         <br>
                                                                        <a href="https://www.youtube.com/watch?v=2SXP3dHj_-Y" style="text-decoration:none;display:block"
                                                                           class="nonplayable"
                                                                           target="_blank"><img style="display:none" src="https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/p/7/SvoUA9qoV4UfwJNpFalqI0tgJrb59B4I.jpg" height="274" width="498">
                                                                            </a>
                                                                        <embed width="640" height="385" base="https://www.youtube.com/v/" wmode="opaque" id="swfContainer0" type="application/x-shockwave-flash" src="https://www.youtube.com/v/Bk_6r-b3kqU?border=0&autoplay=1&client=ytapi-google-gmail&version=3&start=0">

                                                                        <br>
                                                                        Food review is a science as well as an art; it requires training and continued practice. We take this opportunity to formally invite you to join our structured ‘Taster’s Program’. This program would not only educate and train you about various aspects of food & beverages but also enable you to earn incentives. Most importantly, your feedback triggers progress and innovation among start-ups, farmers, and large food companies.
                                                                        <br>
                                                                        <br>
                                                                        The details of this program are attached for your kind reference
                                                                        <br>
                                                                        <br>
                                                                        Regards,
                                                                        <br>
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