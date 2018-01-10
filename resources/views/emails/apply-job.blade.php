@include("emails.header.definition")
<body>
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
                                        <div style="font-size: 20px;font-weight: bold;color: #181818;padding: 20px 78px 20px 78px;">{{$data->who['name']}} has applied for {{$model['content']}}</div>
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
                                                                        <p style="font-size: 16px;color: #181818;margin:0px;padding:0px 0px 20px 0px;font-weight:bold;">Hi {{$notifiable->name}},</p>
                                                                        <p style="font-size: 16px;color: #181818;margin:0px">{{$data->who['name']}} has applied for {{$model['content']}}.
                                                                            <a href="{{env('APP_URL')}}/jobs/{{$model['id']}}" style="color: #4397E7; text-decoration: none;">Click here</a> to  see the list of all applicants.</p>
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
                                    <td style="width:100%!important;background-color:#ffffff!important;padding:20px 40px 20px 40px;border-bottom:1px solid rgba(0,0,0,0.2)">
                                        <table border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
                                            <tbody><tr>
                                                <td valign="top" width="71px" class="templateColumnContainer">
                                                    <table border="0" cellpadding="0" cellspacing="0">
                                                        <tbody><tr>
                                                            <td>
                                                                <img src="{{ isset($data->who['imageUrl']) ? $data->who['imageUrl'] : env('APP_URL').'/images/emails/profile-circle.png'}}" height="71px" width="71px" class="CToWUd">
                                                            </td>
                                                        </tr>
                                                        </tbody></table>
                                                </td>
                                                <td valign="top" width="291px" class="emplateColumnContainer" style="padding-left:21px">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#FFFFFF">
                                                        <tbody><tr>
                                                            <td>
                                                                <div style="color:#181818;font-weight:bold;font-size:18px;word-wrap:break-word">
                                                                    {{$data->who['name']}}
                                                                </div>
                                                                <div style="padding-top:8px;font-size:18px;color:#717171;word-wrap:break-word">

                                                                </div>
                                                            </td>
                                                        </tr>
                                                        </tbody></table>
                                                </td>
                                                <td valign="top" width="88px" class="m_6147689296000473060templateColumnContainer">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="88px" style="padding-left:20px">
                                                        <tbody><tr>
                                                            <td>
                                                                <a href="{{env('APP_URL')}}/profile/{{$data->who['id']}}" style="display:inline-block; text-decoration:none; text-align: center; background-color:#d81f2e;padding:10px 7px;color:#ffffff;font-size:13px;border:none;width:70px!important;border-radius: 4px;">View Profile</a>
                                                            </td>
                                                        </tr>
                                                        </tbody></table>
                                                </td>
                                            </tr>
                                            </tbody></table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:100%!important;background-color:#FFFFFF!important;">
                                        <table width= "100%" align="center" border="0" cellspacing="0" cellpadding="0"  border-collapse="collapse" bgcolor="#FFFFFF">
                                            <tr>
                                                <td valign="top" align="center"  bgcolor="#FFFFFF">
                                                    <a href="{{env('APP_URL')}}/jobs/{{$model['id']}}" style="display:inline-block; text-decoration:none; padding: 15px 36px;border-radius: 4px;color: #FFFFFF;background-color: #D9222A;box-shadow: none;border: none;font-size: 14px;margin: 30px 0px 30px 0px;border-radius: 30px;font-weight:normal;">DOWNLOAD RESUME</a>
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