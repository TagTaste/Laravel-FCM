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

                                Product Name - {{$product_name}}
                                <br>
                                Product Link - <a href="{{$product_link}}">{{$product_link}}</a>
                                <br>
                                Product Brand - {{$brand_name}}
                                <br>
                                Profile Id - <a href="https://www.tagtaste.com/profile/{{$profile_id}}">{{$profile_id}}</a>
                                <br>
                                Image Link - <a href="{{$image}}"><img src="{{$image}}" style="height: 300px; width: 300px; object-fit: cover;" /></a>

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