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
                                            <div style="font-size: 20px;font-weight: bold;color: #181818;margin: 0px 70px 0px 70px;">Arun Tangri has expressed interest in your 
                                                colloboration</div>
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
                                                                            <p style="font-size: 16px;color: #181818;margin:0px;padding:0px 0px 20px 0px;font-weight:bold;">Hi Tanvi,</p>
                                                                            <p style="font-size: 16px;color: #181818;margin:0px">Arun Tangri expressed interest in your collaboration  Fresh fruits for gelato manufacturing</p>
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
                                        <td style="width:100%!important;background-color:#FFFFFF!important;padding: 20px 40px 20px 40px;border-bottom: 1px solid rgba(0,0,0,0.2);">
                                            <table width= "100%" align="center" border="0" cellspacing="0" cellpadding="0"  border-collapse="collapse" bgcolor="#FFFFFF">
                                                @if(!empty($replyMessage))
                                                    <tr>
                                                        <td align="center" valign="top" width="15%" class="templateColumnContainer">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                <tr>
                                                                    <td>
                                                                        <img src="https://i1.wp.com/www.femmehub.com/wp-content/uploads/2015/04/foods-wallpaper.jpg" height="50px" width="50px" style="border-radius: 50%;"/>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                        <td align="center" valign="top" width="70%" class="templateColumnContainer">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#FAFAFA" style="border-radius:12px;" >
                                                                <tr>
                                                                    <td style="padding: 13px 14px;">
                                                                        <div style="color: #181818;font-weight: bold;font-size: 16px;">
                                                                            Naman
                                                                        </div>
                                                                        <div style="padding-top:8px;color: #717171;">
                                                                            <!-- message -->
                                                                            we do source and market...<span style="color: #4397E7;">(more)</span>                                             
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                        <td align="center" valign="top" width="15%" class="templateColumnContainer">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%"  style="border-radius:12px;" >
                                                                <tr>
                                                                    <td style="padding: 13px 14px;">
                                                                        <button style="background-color: #D81F2E;padding:8px 20px;color:#FFFFFF;font-size: 13px;font-weight: bold;border: none;border-radius:5px;">Reply</button>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td align="center"  width="50px" class="templateColumnContainer">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                <tr>
                                                                    <td>
                                                                        <img src="https://i1.wp.com/www.femmehub.com/wp-content/uploads/2015/04/foods-wallpaper.jpg" height="50px" width="50px" style="border-radius: 50%;"/>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                        <td align="center"  width="317px" class="templateColumnContainer">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-radius:12px;padding-left:20px;" >
                                                                <tr>
                                                                    <td >
                                                                        <div style="color: #181818;font-weight: bold;">
                                                                            Arun Tangri
                                                                        </div>
                                                                        <div style="padding-top:8px;color: #181818;">
                                                                            <!-- message -->
                                                                            Co-founder &amp; COO @tagTaste                                             
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                        <td align="center"  width="72px" class="templateColumnContainer">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%"  style="border-radius:12px;padding-left:20px;" >
                                                                <tr>
                                                                    <td >
                                                                        <button style="background-color: #D81F2E;padding:8px 20px;color:#FFFFFF;font-size: 13px;font-weight: bold;border: none;border-radius: 4px;">Reply</button>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:100%!important;background-color:#FFFFFF!important;">
                                            <table width= "100%" align="center" border="0" cellspacing="0" cellpadding="0"  border-collapse="collapse" bgcolor="#FFFFFF">
                                                <tr>
                                                    <td valign="top" align="center"  bgcolor="#FFFFFF">
                                                        <button style="padding: 18px 36px;border-radius: 4px;color: #FFFFFF;background-color: #D9222A;box-shadow: none;border: none;font-size: 18px;margin: 31px 0px 31px 0px;border-radius: 30px;">VIEW ON TAGTASTE</button>
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