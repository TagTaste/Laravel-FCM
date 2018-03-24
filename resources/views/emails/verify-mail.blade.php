@include("emails.header.definition")
<body>
    <center>
        <table width="100%" border ="0" cellspacing ="0" cellpadding = "0"  style="font-family:Arial" bgcolor="#F8F6F9">
            @include("emails.header.header")
            <tr>
                <td align="center" valign="top">
                    <table class="container" width= "620" align="center" border="0" cellspacing="0" cellpadding="0"  border-collapse="collapse" >
                        <tr>
                            <td align="center" width="100%" bgcolor="#F8F6F9" >
                                <table width= "550" align="center" border="0" cellspacing="0" cellpadding="0"  border-collapse="collapse" >
                                    <tr>
                                        <td align="center"  bgcolor="#FFFFFF" style="padding:20px 0px 20px 0px;" width="100%" style="width=100%!important">
                                            <div style="font-size: 20px;font-weight: bold;color: #181818;margin: 0px 70px 0px 70px;"> Verify your email address</div>
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
                                                    <td align="center" valign="top" width="100%" class="templateColumnContainer" >
                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#FFFFFF" >
                                                            <tr>
                                                                <td bgcolor="#FFFFFF" align="center">
                                                                    <p style="font-size: 16px;color: #181818;margin:0px;">You are just a step away from verifying your email address. A verified email address makes your profile more authentic and improves the chances of getting good response from the 
                                                                        community.
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
                                                                
                                                                        <a href="{{ env('APP_URL') }}/user/verify/email/{{$email_token}}" style="text-decoration:none;padding: 15px 36px;border-radius: 4px;color: #FFFFFF;background-color: #D9222A;box-shadow: none;border: none;font-size: 18px;border-radius: 30px; font-weight: normal;">VERIFY EMAIL</a>
                                                                
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
                                        <td align="center" bgcolor="#FFFFFF">
                                            <div style="margin:0px 0px 20px 0px;font-size: 14px;color: #181818;" >or you can also paste this link into your browser</div>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td align="center" bgcolor="#FFFFFF" style="padding:0px 0px 30px 0px;">
                                            <a href="{{ env('APP_URL') }}/user/verify/email/{{$email_token}}" style="text-decoration:none;font-size: 14px;color:#0D86E3">{{ env('APP_URL') }}/user/verify/email/{{$email_token}}</a>
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