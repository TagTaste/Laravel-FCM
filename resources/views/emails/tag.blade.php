@include("emails.header.definition")
<body>
<center>
    <table width="100%" border ="0" cellspacing ="0" cellpadding = "0"  style="font-family:Arial">
        @include("emails.header.header")
        <tr>
            <td align="center" valign="top">
                <table class="container" width= "620" align="center" border="0" cellspacing="0" cellpadding="0"  border-collapse="collapse" bgcolor="#F8F6F9" >
                    <tr>
                        <td align="center">
                            <table width= "550" align="center" border="0" cellspacing="0" cellpadding="0"  border-collapse="collapse">
                                <tr>
                                    <td align="center"  bgcolor="#FFFFFF" style="padding:20px 0px 20px 0px">
                                        <div style="font-size: 20px;font-weight: bold;color: #181818;">{{ $model['title'] }}</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
        <tr>
            <td align="center" valign="top" >
                <table class="container" width= "620" align="center" border="0" cellspacing="0" cellpadding="0"  border-collapse="collapse" bgcolor="#F8F6F9" >
                    <tr>
                        <td align="center">
                            <table width= "550" align="center" border="0" cellspacing="0" cellpadding="0"  border-collapse="collapse" style="margin-top:10px">
                                <tr>
                                    <td bgcolor="#FFFFFF">
                                        @if(strlen($content) <= 140)
                                        <div style="font-size: 16px;padding: 20px 40px 0px 40px;color: #181818;">{{$content}}</div>
                                        @else
                                        <div style="font-size: 16px;padding: 20px 40px 0px 40px;color: #181818;">{{substr($content, 0, 140)}}...<a href="{{ $model['url'] }}" style="color: #4397E7; text-decoration: none;">(more)</a></div>
                                        @endif
                                    </td>
                                </tr>
                                @if(isset($model['image']))
                                <tr>
                                    <td bgcolor="#FFFFFF" align= "center">
                                        <div style="padding: 0px 40px;">
                                            <div style="padding: 20px 0px;border-bottom: 1px solid rgba(0,0,0,0.2);">
                                                <img src="{{$model['image']}}" width="350px" height="230px"/>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                @if(isset($comment) && !empty($comment))
                                <tr>
                                    <td style="width:100%!important;background-color:#FFFFFF!important;padding: 20px 40px 0px 40px;">
                                        <table width= "100%" align="center" border="0" cellspacing="0" cellpadding="0"  border-collapse="collapse" bgcolor="#FFFFFF">
                                                <tr>
                                                    <td align="center" width="50px" class="templateColumnContainer">
                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                            <tr>
                                                                <td>
                                                                    <img src="{{ !empty($data->who['imageUrl']) ? $data->who['imageUrl'] : env('APP_URL').'/images/emails/profile-circle.png'}}" width="50px" style="border-radius: 50%;"/>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td align="center" valign="top" width="373px" class="templateColumnContainer" style="padding:0 0 0 10px;">
                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#FAFAFA" style="border-radius:12px;" >
                                                            <tr>
                                                                <td style="padding: 13px 14px;">
                                                                    <div style="color: #181818;font-weight: bold;font-size: 16px;">
                                                                        {{ $data->who['name'] }}
                                                                    </div>
                                                                    <div style="padding-top:8px;color: #717171;">
                                                                        <!-- message -->
                                                                        @if(strlen($comment) > 140)
                                                                            {{ substr($comment, 0, 140) }}... <a href="{{ $model['url'] }}" style="text-decoration: none; color: #4397E7;">(more)</a>
                                                                        @else
                                                                            {{ $comment }}
                                                                        @endif
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
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center" valign="top">
                <table class="container" width= "620" align="center" border="0" cellspacing="0" cellpadding="0"  border-collapse="collapse" bgcolor="#F8F6F9" >
                    <tr>
                        <td>
                            <table width= "550" align="center" border="0" cellspacing="0" cellpadding="0"  border-collapse="collapse">
                                <tr>
                                    <td valign="top" align="center"  bgcolor="#FFFFFF">
                                        <a href="{{ $model['url'] }}" style="text-decoration:none; display:inline-block; padding: 15px 67px;border-radius: 4px;color: #FFFFFF;background-color: #D9222A;box-shadow: none;border: none;font-size: 18px;margin: 31px 0px 31px 0px;border-radius: 30px; font-weight: normal;">VIEW ON TAGTASTE</a>
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
</center>
</body>