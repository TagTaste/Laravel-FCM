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
                                            {{$userName}} has uploaded documents for your collaboration
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
                                                                <a style="text-decoration: none;" href="">
                                                                    <img src="{{ isset($data->who['imageUrl']) ? $data->who['imageUrl'] : env('APP_URL').'/images/emails/profile-circle.png'}}" height="50px" width="50px" style="border-radius: 50%;" height="50px" width="50px" style="border-radius:50%; display: inline-block;vertical-align:middle;">
                                                                    <div style="color:#181818;font-weight:medium;display: inline-block; margin-left: 10px; word-break: break-word; width: calc(100% - 60px); width: 85%;vertical-align: middle;">
                                                                        {{$userName}}
                                                                    </div>
                                                                </a>
                                                                <div>
                                                                    <p style="margin: 16px 0 0; line-height: 1.5; font-size: 14px;">{{$data->who['name']}} has uploaded the following documents:</p>
                                                                    <ol style="margin: 0; padding: 0 14px;">
                                                                    @foreach ($files as $file)
                                                                        <li style="margin-top: 12px;color: #4990e2; font-size: 14px;"><a style="color: #4990e2; font-size: 14px; text-decoration: none;" href="{{ $file['url']}}">{{ $file['original_name'] }}</a></li>
                                                                    @endforeach
                                                                    </ol>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" align="center" bgcolor="#FFFFFF">
                                                                <a href="{{env('APP_URL')}}/collaborations/{{$model['id']}}/manage"
                                                                   style="display: inline-block;text-decoration: none;padding: 14px 24px;color: #FFFFFF;background-color: #D9222A;box-shadow: none;border: none;font-size: 16px;margin: 31px 0px 31px 0px;border-radius: 24px;font-weight: normal;">
                                                                    View Documents
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