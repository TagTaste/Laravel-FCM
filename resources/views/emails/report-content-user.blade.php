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
                                            {{ $data['type'] }} Reported on TagTaste
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
                                                                    <p style="font-size: 14px;color: #181818;margin:0px">
                                                                        Hi,
                                                                        <br>
                                                                        <br>
                                                                        <a href="{{ $data['profileUrl'] }}" style="display: inline-block;text-decoration: none;font-weight: normal;">
                                                                            {{ $data['reporterName'] }}</a> has reported a {{ $data['type'] }} on TagTaste.
                                                                        <br>
                                                                        <br>
                                                                        <b>
                                                                            {{ $data['type'] }} - <a href="{{ $data['reportedUrl'] }}" style="display: inline-block;text-decoration: none;font-weight: normal;"> Reported {{ $data['type'] }} </a></b>
                                                                        <br>
                                                                        <b>Specified Issue - {{ $data['issue'] }}</b>
                                                                        <br>
                                                                        <b>Reported on - {{ $data['reportedOn'] }}</b>
                                                                        <br>
                                                                        <b>Name of the reporter - {{ $data['reporterName'] }}</b>
                                                                        <br>
                                                                        <b>Email ID - {{ $data['emailId'] }}</b>
                                                                        <br>
                                                                        <b>Phone Number - {{ $data['phoneNumber'] }}</b>
                                                                        <br>
                                                                    </p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="top" align="center" bgcolor="#FFFFFF">
                                                                <a href="{{ $data['profileUrl'] }}"
                                                                   style="display: inline-block;text-decoration: none;padding: 14px 24px;color: #FFFFFF;background-color: #D9222A;box-shadow: none;border: none;font-size: 16px;margin: 31px 0px 31px 0px;border-radius: 24px;font-weight: normal;">
                                                                    Visit Profile
                                                                </a>
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
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</center>
</body>