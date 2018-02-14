@include("emails.header.definition")
<body>
    <center>
        <table width="100%" border ="0" cellspacing ="0" cellpadding = "0"  style="font-family:Arial" bgcolor="#F8F6F9">
            @include("emails.header.header")
            <tr>
                <td align="center" valign="top">
                    <table class="container" width= "620" align="center" border="0" cellspacing="0" cellpadding="0"  border-collapse="collapse" >
                        <tr>
                            <td align="center" width="100%" >
                                <table width= "550" align="center" border="0" cellspacing="0" cellpadding="0"  border-collapse="collapse">
                                    <tr>
                                        <td align="center"  bgcolor="#FFFFFF" style="padding:20px 0px 20px 0px" width="100%" style="width=100%!important">
                                            <div style="font-size: 20px;font-weight: bold;color: #181818;">{{$model['title']}}</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #FAFAFA;height: 10px;">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:100%!important;background-color:#FFFFFF!important;padding: 0px 40px 0px 40px;">
                                            <table style="margin: 20px 0px 0px 0px;" width="100%" bgcolor="#FFFFFF" >
                                                <tr>
                                                    <td>
                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#FFFFFF">
                                                            <tr>
                                                                <td bgcolor="#FFFFFF">
                                                                    @if(strlen($content) > 140)
                                                                    <div style="font-size: 16px;color: #181818;">{{substr($content, 0, 140)}}...<a href="{{ $model['url'] }}" style="color: #4397E7; text-decoration: none;">(more)</a></div>
                                                                    @else
                                                                    <div style="font-size: 16px;color: #181818;">{{$content}}</div>
                                                                    @endif
                                                                </td>
                                                            </tr>   
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    @if(isset($model['image']))
                                    <tr>
                                        <td bgcolor="#FFFFFF" align= "center">
                                            <div style="padding: 0px 40px;">
                                                <div style="padding: 20px 0px;border-bottom: 1px solid rgba(0,0,0,0.2);">
                                                    <img src="{{ $model['image']}}" width="350px"	height=230px"/>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td style="width:100%!important;background-color:#FFFFFF!important;padding: 20px 40px 0px 40px;">
                                            <table width= "100%" align="center" border="0" cellspacing="0" cellpadding="0"  border-collapse="collapse" bgcolor="#FFFFFF">
                                                @if(isset($comment))
                                                    <tr>
                                                        <td align="center" width="50px" class="templateColumnContainer">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                <tr>
                                                                    <td>
                                                                        <img src="{{ !empty($data->who['imageUrl']) ? $data->who['imageUrl'] : env('APP_URL').'/images/emails/profile-circle.png'}}" height="50px" width="50px" style="border-radius: 50%;"/>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                        <td align="center" valign="top" width="373px" class="templateColumnContainer" style="padding:0px 0px 0px 10px;">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#FAFAFA" style="border-radius:12px;" >
                                                                <tr>
                                                                    <td style="padding: 13px 14px;">
                                                                        <div style="color: #181818;font-weight: bold;font-size: 16px;">
                                                                            {{ $data->who['name'] }}
                                                                        </div>
                                                                        <div style="padding-top:8px;color: #717171;">
                                                                            <!-- message -->
                                                                            {{ $comment }} <a href="{{ $model['url'] }}" style="text-decoration: none; color: #4397E7;">(more)</a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </table>
                                        </td>
                                    </tr>

                                    {{--<tr>--}}
                                        {{--<td style="width:100%!important;background-color:#FFFFFF!important;padding: 20px 40px 0px 40px;">--}}
                                            {{--<table width= "100%" align="center" border="0" cellspacing="0" cellpadding="0"  border-collapse="collapse" bgcolor="#FFFFFF">--}}
                                                {{--@if($comment)--}}
                                                    {{--<tr>--}}
                                                        {{--<td align="center" width="50px" class="templateColumnContainer">--}}
                                                            {{--<table border="0" cellpadding="0" cellspacing="0" width="100%">--}}
                                                                {{--<tr>--}}
                                                                    {{--<td>--}}
                                                                        {{--<img src="https://i1.wp.com/www.femmehub.com/wp-content/uploads/2015/04/foods-wallpaper.jpg" height="50px" width="50px" style="border-radius: 50%;"/>--}}
                                                                    {{--</td>--}}
                                                                {{--</tr>--}}
                                                            {{--</table>--}}
                                                        {{--</td>--}}
                                                        {{--<td align="center" valign="top" width="373px" class="templateColumnContainer" style="padding:0px 0px 0px 10px;">--}}
                                                            {{--<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#FAFAFA" style="border-radius:12px;" >--}}
                                                                {{--<tr>--}}
                                                                    {{--<td style="padding: 13px 14px;">--}}
                                                                        {{--<div style="color: #181818;font-weight: bold;font-size: 16px;">--}}
                                                                            {{--Arun Tangri--}}
                                                                        {{--</div>--}}
                                                                        {{--<div style="padding-top:8px;color: #717171;">--}}
                                                                            {{--<!-- message -->--}}
                                                                            {{--we do source and market...<span style="color: #4397E7;">(more)</span>                                             --}}
                                                                        {{--</div>--}}
                                                                    {{--</td>--}}
                                                                {{--</tr>--}}
                                                            {{--</table>--}}
                                                        {{--</td>--}}
                                                    {{--</tr>--}}
                                                {{--@else--}}
                                                    {{--<tr>--}}
                                                        {{--<td align="center" valign="top" width="20%" class="templateColumnContainer">--}}
                                                            {{--<table border="0" cellpadding="0" cellspacing="0" width="100%" >--}}
                                                                {{--<tr>--}}
                                                                    {{--<td align="center">--}}
                                                                        {{--<img src="https://i1.wp.com/www.femmehub.com/wp-content/uploads/2015/04/foods-wallpaper.jpg" height="50px" width="50px" style="border-radius: 50%;"/>--}}
                                                                    {{--</td>--}}
                                                                {{--</tr>--}}
                                                                {{--<tr>--}}
                                                                    {{--<td align="center">--}}
                                                                        {{--<div style="margin:20px 0px 0px 0px;">--}}
                                                                            {{--<span style="color: #181818;font-size: 16px;font-weight:bold;">--}}
                                                                                {{--Naman--}}
                                                                            {{--</span>--}}
                                                                        {{--</div>--}}
                                                                    {{--</td>--}}
                                                                {{--</tr>--}}
                                                            {{--</table>--}}
                                                        {{--</td>--}}
                                                    {{--</tr>--}}
                                                {{--@endif--}}
                                            {{--</table>--}}
                                        {{--</td>--}}
                                    {{--</tr>--}}
                                    {{--<tr>--}}
                                        {{--<td style="width:100%!important;background-color:#FFFFFF!important;padding: 20px 0px 0px 0px;">--}}
                                            {{--<table width= "100%" align="center" border="0" cellspacing="0" cellpadding="0"  border-collapse="collapse" bgcolor="#FFFFFF">--}}
                                                {{--<tr>--}}
                                                    {{--<td valign="top" align="center"  bgcolor="#FFFFFF">--}}
                                                        {{--<button style="padding: 18px 36px;border-radius: 4px;color: #FFFFFF;background-color: #D9222A;box-shadow: none;border: none;font-size: 18px;margin: 31px 0px 31px 0px;border-radius: 30px;">VIEW ON TAGTASTE</button>--}}
                                                    {{--</td>--}}
                                                {{--</tr>--}}
                                                {{----}}
                                            {{--</table>--}}
                                        {{--</td>--}}
                                    {{--</tr> --}}
                                    <tr>
                                        <td  style="width:100%!important;background-color:#FFFFFF!important;">
                                            <table width= "100%" align="center" border="0" cellspacing="0" cellpadding="0"  border-collapse="collapse" bgcolor="#FFFFFF">
                                                <tr>
                                                    <td style="height:31px;background-color:#FFFFFF">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width:100%!important;background-color:#FFFFFF!important;padding: 18px 36px;" align="center">
                                                        <table>
                                                        
                                                            <tr>
                                                                <td valign="top" align="center"  bgcolor="#FFFFFF" >
                                                                
                                                                        <a href="{{ $model['url'] }}" style="text-decoration:none;padding: 15px 36px;border-radius: 4px;color: #FFFFFF;background-color: #D9222A;box-shadow: none;border: none;font-size: 18px;border-radius: 30px; font-weight: normal">VIEW ON TAGTASTE</a>
                                                                
                                                                </td>
                                                            </tr>
                                                            
                                                            
                                                        </table>
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <td style="height:31px;background-color:#FFFFFF">
                                                       
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