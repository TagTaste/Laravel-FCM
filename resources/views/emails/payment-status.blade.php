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
                                        <td align="center" bgcolor="#FFFFFF"
                                            style="padding:20px 0 0;border-radius: 4px 4px 0 0;width:100%!important;"
                                            width="100%">
                                            <div
                                                style="font-size: 24px;font-weight: normal;color: #171717;padding: 0 40px 0 40px">

                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td
                                            style="width:100%!important;background-color:#FFFFFF!important;padding: 0px 40px 0px 40px;">
                                            <table style="margin: 20px 0px 0px 0px;" width="100%" bgcolor="#FFFFFF">
                                                <tr>
                                                    <td align="center" valign="top" width="100%"
                                                        class="templateColumnContainer">
                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%"
                                                            bgcolor="#FFFFFF">
                                                            <tr>
                                                                <td bgcolor="#FFFFFF" align="left">
                                                                    <p
                                                                        style="font-size: 14px;color: #181818;margin:0px; line-height: 1.5;">
                                                                        Hi {{ $data['name'] ?? '' }},
                                                                    </p>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td bgcolor="#FFFFFF" align="left">
                                                                    <p
                                                                        style="font-size: 14px;color: #181818;margin:0px; line-height: 1.5;">
                                                                        {{ $data['descp'] }}
                                                                        <br />
                                                                        <b>TagTaste Product Review Payment</b>
                                                                    </p>
                                                                    <br />
                                                                    <p
                                                                        style="font-size: 14px;color: #181818;margin:0px; line-height: 1.5;">
                                                                        Payment Status: {{ $data['status'] }} <br />
                                                                        Transaction ID: {{ $data['order_id'] }}<br />
                                                                        Pretext : {!! $data['pretext'] !!}
                                                                    </p>

                                                                </td>
                                                            </tr>

                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:100%!important;background-color:#FFFFFF!important;">
                                            <table width="100%" align="center" border="0" cellspacing="0"
                                                cellpadding="0" border-collapse="collapse" bgcolor="#FFFFFF">
                                                <tr>
                                                    <td style="height:31px;background-color:#FFFFFF">
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
                                        {{-- <td align="center" bgcolor="#FFFFFF">
                                            <div style="margin:0px 0px 20px 0px;font-size: 14px;color: #181818;" >or you can also paste this link into your browser</div>
                                        </td> --}}
                                    </tr>

                                    <tr>
                                        <td align="center" bgcolor="#FFFFFF" style="padding:0px 0px 30px 0px;">
                                            {{-- <a href="{{ env('APP_URL') }}/user/verify/email/{{$email_token}}" style="text-decoration:none;font-size: 14px;color:#0D86E3">{{ env('APP_URL') }}/user/verify/email/{{$email_token}}</a> --}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #FAFAFA;height: 10px;">
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
