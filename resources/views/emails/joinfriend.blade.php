@include("emails.header.definition")
<body style="margin: 0">
<center>
    <table width="100%" border ="0" cellspacing ="0" cellpadding = "0"  style="font-family:Arial;background-color:#F8F6F9!important;">
        @include("emails.header.header")
        <tr>
            <td align="center" valign="top">
                <table class="container" width= "620" align="center" border="0" cellspacing="0" cellpadding="0"  border-collapse="collapse" bgcolor="#F8F6F9">
                    <tr>
                        <td align="center" width="100%" bgcolor="#F8F6F9" >
                            <table width= "550" align="center" border="0" cellspacing="0" cellpadding="0"  border-collapse="collapse" bgcolor="#F8F6F9" style="background-color:#F8F6F9">
                                <tr>
                                    <td align="center"  bgcolor="#FFFFFF" style="border-radius:5px 5px 0px 0px;" width="100%">
                                        <div style="font-size: 20px;font-weight: bold;color: #181818;padding: 20px 78px 20px 78px;">Invitation accepted!.</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="background-color: #FAFAFA;height: 10px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:100%!important;background-color:#FFFFFF!important;padding: 0px 40px 0px 40px;border-bottom: 1px solid rgba(0,0,0,0.2);">
                                        <table style="margin: 20px 0px 20px 0px;" width="100%" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgba(0,0,0,0.2);">
                                            <tr>
                                                <td align="center" valign="top" width="100%" class="templateColumnContainer" >
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#FFFFFF" >
                                                        <tr>
                                                            <td>
                                                                <div style="padding:0px 10px 0px 10px;">
                                                                    <div>
                                                                        <p style="font-size: 16px;color: #181818;margin:0px;padding:0px 0px 20px 0px;font-weight:bold;">Hi {{ $data->model->name }},</p>
                                                                        <p style="font-size: 16px;color: #181818;margin:0px">Your friend {{$data->who['name']}} joined TagTaste</p>
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
                                    <td style="width:100%!important;background-color:#ffffff!important;padding:20px 40px 20px 40px;">
                                        <table border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
                                            <tbody><tr>
                                                <td valign="top" width="71px" class="templateColumnContainer">
                                                    <table border="0" cellpadding="0" cellspacing="0">
                                                        <tbody><tr>
                                                            <td>
                                                                <img src="{{ isset($data->who['imageUrl']) ? $data->who['imageUrl'] : env('APP_URL').'/images/emails/profile-circle.png'}}" width="71px" class="CToWUd" style="border-radius: 4px;">
                                                            </td>
                                                        </tr>
                                                        </tbody></table>
                                                </td>
                                                <td valign="top" width="291px" class="emplateColumnContainer" style="padding-left:21px">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#FFFFFF">
                                                        <tbody><tr>
                                                            <td>
                                                                <div style="color:#181818;font-weight:bold;font-size:18px;word-wrap:break-word">
                                                                    {{ $data->who['name'] }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        </tbody></table>
                                                </td>
                                                <td valign="top" width="88px" style="vertical-align: middle;">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="88px">
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <a href="{{ \App\Deeplink::getShortLink('profile', $data->who['id']) }}" style="display:inline-block;text-decoration:none;text-align: center;background-color:#d81f2e;padding: 8px 7px;color:#ffffff;font-size:13px;border:none;border-radius: 4px;font-weight: bold;text-align: center;">View Profile</a>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            </tbody>
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