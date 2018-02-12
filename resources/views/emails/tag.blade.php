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
                                        <div style="font-size: 20px;font-weight: bold;color: #181818;">{{$data->who['name']}} tagged you in a post</div>
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
                                        <div style="font-size: 16px;padding: 20px 40px 0px 40px;color: #181818;">{{substr($content, 0, 140)}}...<a href="{{ \App\Deeplink::getShortLink($model['name'], $model['id']) }}" style="color: #4397E7; text-decoration: none;">(more)</a></div>
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
                                        <a href="{{ \App\Deeplink::getShortLink($model['name'], $model['id']) }}" style="text-decoration:none; display:inline-block; padding: 15px 67px;border-radius: 4px;color: #FFFFFF;background-color: #D9222A;box-shadow: none;border: none;font-size: 18px;margin: 31px 0px 31px 0px;border-radius: 30px; font-weight: normal;">VIEW ON TAGTASTE</a>
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