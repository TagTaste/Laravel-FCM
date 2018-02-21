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
                                            {{ $data['title'] }}
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
                                                                    <p style="font-size: 16px;color: #181818;margin:0px;padding:0px 0px 20px 0px;font-weight:bold;">
                                                                        Hi {{ $data['owner'] }},
                                                                    </p>
                                                                    <p style="font-size: 16px;color: #181818;margin:0px">
                                                                        {{ $data['msg'] }}
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
                                <tr>
                                    <td style="width:100%!important;background-color:#FFFFFF!important;padding: 20px 40px 20px 40px;border-bottom: 1px solid rgba(0, 0, 0, 0.04);">
                                        <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0"
                                               border-collapse="collapse" bgcolor="#FFFFFF">
                                            <tr>
                                                <td align="center" width="50px" class="templateColumnContainer">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                        <tr>
                                                            <td>
                                                                <img src="{{ $data['job']['imageUrl'] }}"
                                                                     width="75px"
                                                                     style="border-radius: 8px;">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td align="center" width="317px" class="templateColumnContainer" style="padding: 0 20px 0 20px;">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                        <tr>
                                                            <td>
                                                                <div style="color: #181818;font-weight: normal; font-size: 16px">
                                                                    {{ $data['job']['owner_name'] }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%"
                                                           style="padding-top: 8px;">
                                                        <tr>
                                                            <td>
                                                                <div style="color: #181818;font-weight: bold; font-size: 16px">
                                                                    {{ $data['job']['title'] }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%"
                                                           style="padding-top: 9px;">
                                                        <tr>
                                                            <td>
                                                                <div style="color:#999999; font-weight: normal; font-size: 14px;">
                                                                    {{ $data['job']['location'] }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>

                                                <td align="center" width="72px" class="templateColumnContainer">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%"
                                                           style="text-align: center;">
                                                        <tr>
                                                            <td>
                                                                <a href="{{ $data['job']['btn_url'] }}"
                                                                   style="text-decoration:none; display:inline-block;background-color: #D81F2E;padding:8px 20px;color:#FFFFFF;font-size: 13px;font-weight: normal;border: none;border-radius: 4px;">
                                                                    {{ $data['job']['btn_text'] }}
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                @if(isset($data['profile_count']))
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
                                                                    <div style="text-align: center; font-size: 18px; color: #181818;">
                                                                        <p style="margin:0;">Total Applicants</p>
                                                                        <p style="margin:10px 0 0 0; font-weight: bold;">{{ $data['profile_count'] }}</p>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td style="width:100%!important;background-color:#FFFFFF!important;">
                                        <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0"
                                               border-collapse="collapse" bgcolor="#FFFFFF">
                                            <tr>
                                                <td valign="top" align="center" bgcolor="#FFFFFF">
                                                    <a href="{{ $data['master_btn_url'] }}"
                                                       style="display: inline-block; text-decoration: none; padding: 15px 30px;border-radius: 4px;color: #FFFFFF;background-color: #D9222A;box-shadow: none;border: none;font-size: 18px;margin: 31px 0px 31px 0px;border-radius: 30px; font-weight: normal;">{{ $data['master_btn_text'] }}</a>
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