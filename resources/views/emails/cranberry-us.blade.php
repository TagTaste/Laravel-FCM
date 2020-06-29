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
                                            US Cranberry Recipe Rally!
                                        </div>
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
                                                                    <p style="font-size: 14px;color: #181818;margin:0px; line-height: 1.5;">
                                                                        Hi {{$userName}},
                                                                        <br />
                                                                        <br />
                                                                        We are pleased to launch a very engaging <b>‘Product Experience’</b> service for our community. Thanks to <b>US Cranberries</b> and <b>SS Associates</b> for giving us the opportunity to make a debut in this service line.
                                                                        <br />
                                                                        <br />
                                                                        In this project, the overarching objective is to introduce our community to the wonderful world of cranberries- history, culture, farming practices, science and most importantly culinary delights. The whole project aims to unleash the creativity of our members who love to cook and experiment with new ingredients.
                                                                        <br />
                                                                        <br />
                                                                        The COVID-19 crisis has had a jarring impact on our society, but one positive outcome we have seen is a shift in how people think about food and nutrition. It is important for children to try, or at least be exposed to, a variety of flavors and textures in their youth. Please feel free to involve all your family members, the whole program is age agnostic and there is a lot of interesting stuff for our students.
                                                                        <br />
                                                                        <br />
                                                                        To join this rally, please express your interest in the collaboration post and we will take care of the rest.
                                                                        <br />
                                                                        <br />
                                                                        Best Wishes!
                                                                        <br />
                                                                        TagTaste
                                                                    </p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" align="center" bgcolor="#FFFFFF">
                                                                <a href="https://bit.ly/30kpDlS"
                                                                   style="display: inline-block;text-decoration: none;padding: 14px 24px;color: #FFFFFF;background-color: #D9222A;box-shadow: none;border: none;font-size: 16px;margin: 31px 0px 31px 0px;border-radius: 24px;font-weight: normal;">
                                                                    View Collaboration
                                                                </a>
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