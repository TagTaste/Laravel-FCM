<body>
<center>
    <table width="100%" border ="0" cellspacing ="0" cellpadding = "0" style="font-family:Arial" bgcolor="#F8F6F9 ">
        @include("emails.header.header")
        <tr>
            <td align="center" valign="top" >
                <table class="container" width= "620" align="center" border="0" cellspacing="0" cellpadding="0" border-collapse="collapse" bgcolor="#F8F6F9 " >
                    <tr>
                        <td align="center">
                            <table width= "550" align="center" border="0" cellspacing="0" cellpadding="0" border-collapse="collapse" style="">
                                <tr>
                                    <td align="center" bgcolor="#FFFFFF " style="width:100%!important">
                                        <div style="font-size: 20px;font-weight: bold;padding:20px 70px;color: #181818 ;">
                                            Invitation to join TagTaste
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="background-color: #FAFAFA ;height: 10px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td bgcolor="#FFFFFF ">
                                        <div style="padding: 20px 40px 0px 40px;">
                                            <p style="font-size: 16px;color: #181818 ;margin:0px;padding:0px 0px 0px 0px;">Hi,</p>

                                        </div>
                                    </td>
                                </tr>
                                {{--<tr>--}}
                                {{--<td bgcolor="#FFFFFF " align="center">--}}
                                {{--<div style="padding: 30px 40px 30px 40px;">--}}
                                {{--<img src="{{$senderImage}}" height="80px" width="80px" style="border-radius:50%"/>--}}
                                {{--</div>--}}
                                {{--<p style="font-size: 16px;color: #181818 ;margin:0px;font-weight:bold;">Invitation to join TagTaste </p>--}}
                                {{--</td>--}}
                                {{--</tr>--}}
                                <tr>
                                    <td bgcolor="#FFFFFF ">
                                        <div style="padding: 20px 40px 0px 40px;">
                                            <p style="font-size: 16px;color: #181818 ;margin:0px;padding:0px 0px 0px 0px;">Thank you for showing interest in TagTaste.</p>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td bgcolor="#FFFFFF ">
                                        <div style="padding: 20px 40px 20px 40px;">
                                            <p style="font-size: 16px;color: #181818 ;margin:0px;padding:0px 0px 0px 0px;">TagTaste is the world’s first online community for food
                                                professionals to discover, network and collaborate with each other. Food industry professionals from all over the world including, chefs, farmers, food companies, hotels, restaurants, and experts use this platform to share their ideas and opportunities.</p>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td bgcolor="#FFFFFF ">
                                        <div style="padding: 0px 60px 0px 60px;">
                                            <p style="font-size: 14px;color: #181818 ;margin:0px;font-weight:bold;text-transform:uppercase;">Join now and leverage the power of this community.</p>
                                        </div>
                                    </td>
                                </tr>

                                {{--<tr>--}}
                                    {{--<td bgcolor="#FFFFFF ">--}}
                                        {{--<div style="padding: 5px 60px 0px 60px;">--}}
                                            {{--<p style="font-size: 14px;color: #181818 ;margin:0px;font-weight:bold;text-transform:uppercase;">YOUR UNIQUE INVITE CODE IS: <span style="color: #b42128">542090</span> </p>--}}
                                        {{--</div>--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                            </table>

                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top">
                            <table class="container" width= "620" align="center" border="0" cellspacing="0" cellpadding="0" border-collapse="collapse" bgcolor="#F8F6F9 " >
                                <tr>
                                    <td>
                                        <table width= "550" align="center" border="0" cellspacing="0" cellpadding="0" border-collapse="collapse">
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

                                                                            <a href="{{ env('APP_URL') }}" style="text-decoration:none;padding: 15px 36px;border-radius: 4px;color: #FFFFFF;background-color: #D9222A;box-shadow: none;border: none;font-size: 18px;border-radius: 30px; font-weight: normal">Sign Up</a>

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
                                                <td valign="top" align="center" bgcolor="#FFFFFF ">
                                                    <div style="font-size: 14px;color: #181818 ;font-weight:bold;padding:0px 0px 30px 0px;">
                                                        Read more about us on our blog: <a href = "https://blog.tagtaste.com" style="color: #4397E7 ;text-decoration:none">https://blog.tagtaste.com </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="background-color: #FAFAFA ;height: 10px;">
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
