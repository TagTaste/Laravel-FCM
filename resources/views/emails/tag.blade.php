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
                                            <div style="font-size: 20px;font-weight: bold;color: #181818;">{{$data->who['name']}} tagged you in a post.</div>
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
                                            <div style="font-size: 16px;padding: 20px 40px 0px 40px;color: #181818;">{{substr($model->content, 0, 50)}} ...</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td bgcolor="#FFFFFF" align= "center">
                                            <div style="padding: 0px 40px;">
                                                <div style="padding: 20px 0px;border-bottom: 1px solid rgba(0,0,0,0.2);">
                                                    <img src="{{$model->image}}" width="350px"	height="230px"/>
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
                <td align="center" valign="top">
                    <table class="container" width= "620" align="center" border="0" cellspacing="0" cellpadding="0"  border-collapse="collapse" bgcolor="#F8F6F9" >
                        <tr>
                            <td>
                                <table width= "550" align="center" border="0" cellspacing="0" cellpadding="0"  border-collapse="collapse">
                                    <tr>
                                        <td style="width:100%!important;background-color:#FFFFFF!important;padding: 18px 36px;" align="center">
                                            <table>

                                                <tr>
                                                    <td valign="top" align="center"  bgcolor="#FFFFFF" >

                                                        <a href="{{env('APP_URL')}}/feed" style="text-decoration:none;padding: 18px 36px;border-radius: 4px;color: #FFFFFF;background-color: #D9222A;box-shadow: none;border: none;font-size: 18px;border-radius: 30px;">VIEW ON TAGTASTE</a>

                                                    </td>
                                                </tr>


                                            </table>
                                        </td>
                                    </tr>
                                    
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
                                 <table width= "550" align="center" border="0" cellspacing="0" cellpadding="0"  border-collapse="collapse" style="margin-top:10px">
                                    <tr>
                                        <td valign="top" class="logo" align="center" style="padding:20px 0px 0px 0px;border-radius: 0px 0px 5px 5px;" bgcolor="#FFFFFF">
                                          <div style="font-size: 18px;color:#181818">Download our App</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top" class="logo" align="center" style="padding:20px 0px 20px 0px;border-radius: 0px 0px 5px 5px;" bgcolor="#FFFFFF">
                                          <img src="http://139.59.59.78:8081/images/emails/google%20play%20store.png" alt="download-app" width="141px" height="42px">
                                        </td>
                                    </tr>
                                 </table>
                                
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            @include("emails.footer.footer")
        </table>
    </center>
</body>

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
                                        <div style="font-size: 20px;font-weight: bold;color: #181818;">{{$data->who['name']}} tagged you and 2 others in a post</div>
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
                                        <div style="font-size: 16px;padding: 20px 40px 0px 40px;color: #181818;">{{$model->content}}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td bgcolor="#FFFFFF" align= "center">
                                        <div style="padding: 0px 40px;">
                                            <div style="padding: 20px 0px;border-bottom: 1px solid rgba(0,0,0,0.2);">
                                                <img src="{{$model->image}}" width="350px"	height="230px"/>
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
            <td align="center" valign="top">
                <table class="container" width= "620" align="center" border="0" cellspacing="0" cellpadding="0"  border-collapse="collapse" bgcolor="#F8F6F9" >
                    <tr>
                        <td>
                            <table width= "550" align="center" border="0" cellspacing="0" cellpadding="0"  border-collapse="collapse">
                                <tr>
                                    <td valign="top" align="center"  bgcolor="#FFFFFF">
                                        <a href="{{env('APP_URL')}}/feed" style="text-decoration:none; display:inline-block; padding: 15px 67px;border-radius: 4px;color: #FFFFFF;background-color: #D9222A;box-shadow: none;border: none;font-size: 18px;margin: 31px 0px 31px 0px;border-radius: 30px;">VIEW ON TAGTASTE</a>
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