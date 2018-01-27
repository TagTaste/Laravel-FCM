{{--<body>--}}
{{--<center>--}}
    {{--<table width="100%" border ="0" cellspacing ="0" cellpadding = "0"  style="font-family:Arial"  bgcolor="#F8F6F9">--}}
        {{--@include("emails.header.header")--}}
        {{--<tr>--}}
            {{--<td align="center" valign="top" >--}}
                {{--<table class="container" width= "620" align="center" border="0" cellspacing="0" cellpadding="0"  border-collapse="collapse" bgcolor="#F8F6F9" >--}}
                    {{--<tr>--}}
                        {{--<td align="center">--}}
                            {{--<table width= "550" align="center" border="0" cellspacing="0" cellpadding="0"  border-collapse="collapse" style="margin-top:10px">--}}
                                {{--<tr>--}}
                                    {{--<td align="center"  bgcolor="#FFFFFF" style="width:100%!important">--}}
                                        {{--<div style="font-size: 20px;font-weight: bold;padding:20px 70px;color: #181818;">--}}
                                            {{--Welcome aboard, {{$name}}--}}
                                        {{--</div>--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                                {{--<tr>--}}
                                    {{--<td style="background-color: #FAFAFA;height: 10px;">--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                                {{--<tr>--}}
                                    {{--<td bgcolor="#FFFFFF">--}}
                                        {{--<div style="padding: 20px 40px 0px 40px;">--}}
                                            {{--<p style="font-size: 16px;color: #181818;margin:0px;">We are delighted to share with you that TagTaste is live in Beta Phase!--}}
                                                {{--Please explore the platform using these credentials: </p>--}}
                                        {{--</div>--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                                {{--<tr>--}}
                                    {{--<td bgcolor="#FFFFFF">--}}
                                        {{--<div style="padding: 20px 40px 20px 40px;">--}}
                                            {{--<p style="font-size: 16px;color: #181818;margin:0px;padding:0px 0px 0px 0px;">Login ID: <b>{{$email}}</b></p>--}}
                                            {{--<p style="font-size: 16px;color: #181818;margin:0px;padding:0px 0px 0px 0px;">Password: <b>{{$password}}</b></p>--}}
                                        {{--</div>--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                                {{--<tr>--}}
                                    {{--<td bgcolor="#FFFFFF">--}}
                                        {{--<div style="padding: 10px 40px 10px 40px;">--}}
                                            {{--<p style="font-size: 16px;color: #181818;margin:0px;padding:0px 0px 0px 0px;">We are honoured to be working closely with you to make TagTaste, the de facto platform for food professionals across the world.</p>--}}
                                        {{--</div>--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                            {{--</table>--}}

                        {{--</td>--}}
                    {{--</tr>--}}
                    {{--<tr>--}}
                        {{--<td align="center" valign="top">--}}
                            {{--<table class="container" width= "620" align="center" border="0" cellspacing="0" cellpadding="0"  border-collapse="collapse" bgcolor="#F8F6F9" >--}}
                                {{--<tr>--}}
                                    {{--<td>--}}
                                        {{--<table width= "550" align="center" border="0" cellspacing="0" cellpadding="0"  border-collapse="collapse">--}}
                                            {{--<tr>--}}
                                                {{--<td valign="top" align="center"  bgcolor="#FFFFFF">--}}
                                                    {{--<a href="https://www.tagtaste.com" style="display:inline-block; text-decoration:none; padding: 15px 36px;border-radius: 4px;color: #FFFFFF;background-color: #D9222A;box-shadow: none;border: none;font-size: 18px;margin: 30px 0px 30px 0px;border-radius: 30px;font-weight:bold;">LOGIN TO YOUR ACCOUNT</a>--}}
                                                {{--</td>--}}
                                            {{--</tr>--}}
                                            {{--<tr>--}}
                                                {{--<td valign="top" align="center"  bgcolor="#FFFFFF">--}}
                                                    {{--<div style="font-size: 14px;color: #181818;font-weight:bold;padding:0px 0px 30px 0px;">--}}
                                                        {{--Read more about us on our blogs: <a href = "https://blog.tagtaste.com" style="color: #4397E7;text-decoration:none">https://blog.tagtaste.com </a>.--}}
                                                    {{--</div>--}}
                                                {{--</td>--}}
                                            {{--</tr>--}}
                                            {{--<tr>--}}
                                                {{--<td style="background-color: #FAFAFA;height: 10px;">--}}
                                                {{--</td>--}}
                                            {{--</tr>--}}

                                            {{--@include("emails.footer.download")--}}
                                            {{--@include("emails.footer.footer")--}}
                                        {{--</table>--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                            {{--</table>--}}
                        {{--</td>--}}
                    {{--</tr>--}}

                {{--</table>--}}
            {{--</td>--}}
        {{--</tr>--}}


    {{--</table>--}}
{{--</center>--}}
{{--</body>--}}

<body>
<center>
    <table width="100%" border ="0" cellspacing ="0" cellpadding = "0"  style="font-family:Arial"  bgcolor="#F8F6F9">
        @include("emails.header.header")
        <tr>
            <td align="center" valign="top" >
                <table class="container" width= "620" align="center" border="0" cellspacing="0" cellpadding="0"  border-collapse="collapse" bgcolor="#F8F6F9" >
                    <tr>
                        <td align="center">
                            <table width= "550" align="center" border="0" cellspacing="0" cellpadding="0"  border-collapse="collapse" style="margin-top:10px">
                                <tr>
                                    <td align="center"  bgcolor="#FFFFFF" style="width:100%!important">
                                        <div style="font-size: 20px;font-weight: bold;padding:20px 70px;color: #181818;">
                                            Welcome aboard, {{$name}}
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="background-color: #FAFAFA;height: 10px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td bgcolor="#FFFFFF">
                                        <div style="padding: 20px 40px 0px 40px;">
                                            <p style="font-size: 16px;color: #181818;margin:0px;">We are delighted to share with you that TagTaste is live in Beta Phase!
                                                Please explore the platform using these credentials: </p>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td bgcolor="#FFFFFF">
                                        <div style="padding: 16px 40px 10px 40px;">
                                            <p style="font-size: 16px;color: #181818;margin:0px;padding:0px 0px 0px 0px;">Login ID: <b>{{$email}}</b></p>
                                            <p style="font-size: 16px;color: #181818;margin:0px;padding:10px 0px 0px 0px;">Password: <b>{{$password}}</b></p>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td bgcolor="#FFFFFF">
                                        <div style="padding: 10px 40px 10px 40px;">
                                            <p style="font-size: 16px;color: #181818;margin:0px;padding:0px 0px 0px 0px;">We are honoured to be working closely with you to make TagTaste, the de facto platform for food professionals across the world.</p>
                                        </div>
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
                                                    <a href="https://www.tagtaste.com" style="display:inline-block; text-decoration:none; padding: 15px 36px;border-radius: 4px;color: #FFFFFF;background-color: #D9222A;box-shadow: none;border: none;font-size: 18px;margin: 30px 0px 30px 0px;border-radius: 30px;font-weight:bold;">LOGIN TO YOUR ACCOUNT</a>
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
            </td>
        </tr>


    </table>
</center>
</body>