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
                                        <td align="center" bgcolor="#FFFFFF" style="
                            padding: 20px 0 0;
                            border-radius: 4px 4px 0 0;
                            width: 100% !important;
                          " width="100%">
                                            <div style="
                              font-size: 18px;
                              font-weight: bold;
                              color: #171717;
                              padding: 0 40px 0 40px;
                            "><?php

                                        
?>
                                                {{$info["profile"]->name}} has expressed interest in your survey
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="
                            width: 100% !important;
                            background-color: #ffffff !important;
                            padding: 0 20px 20px 20px;
                            border-radius: 4px;
                          ">
                                            <table style="
                              margin: 20px 0px 0px 0px;
                              padding: 16px;
                              border: solid 0.5px rgba(0, 0, 0, 0.1);
                              border-radius: 10px;
                            " width="100%" bgcolor="#FFFFFF">
                                                <tr>
                                                    <td align="center" valign="top" width="100%"
                                                        class="templateColumnContainer">
                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%"
                                                            bgcolor="#FFFFFF">
                                                            <tr>
                                                                <td bgcolor="#FFFFFF" align="left">
                                                                    <div>
                                                                        <img src="{{$info["profile"]->image}}"
                                                                            style="
                                            width: 50px;
                                            border-radius: 30px;
                                            margin: 0 16px 0 0;
                                            vertical-align: top;
                                          " alt="user image" />
                                                                        <div style="display: inline-block">
                                                                            <p style="
                                              font-size: 16px;
                                              color: #171717;
                                              margin: 0px;
                                              line-height: 1.5;
                                              font-weight: bold;
                                            ">
                                                                            {{$info["profile"]->name}}
                                                                            </p>
                                                                            <p style="
                                              font-size: 14px;
                                              color: #171717;
                                              margin: 0px;
                                              line-height: 1.5;
                                              font-weight: normal;
                                              width: 260px;
                                            ">
                                                                                {{$info["comment"] ?? ""}}
                                                                            </p>
                                                                        </div>
                                                                        <div style="
                                            display: inline-block;
                                            vertical-align: top;
                                            margin: 10px 0 0 0;
                                          ">
                                                                            <a style="
                                              width: 63px;
                                              height: 29px;
                                              margin: 10px 0 11px 68px;
                                              padding: 6px 12px;
                                              border-radius: 4px;
                                              background-color: #00a146;
                                              color: white;
                                              border: 0px;
                                              font-size: 14px;
                                              font-weight: bold;
                                              cursor: pointer;
                                            " href="{{$info["survey_url"]}}">
                                                                                Reply
                                                                            </a>
                                                                            <br />
                                                                            <br />
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td bgcolor="#FFFFFF" align="left">
                                                                    <div
                                                                        style="text-align: center; margin: 26px 0px 0px 0px">
                                                                        <a style="
                                            font-size: 16px;
                                            font-weight: normal;
                                            color: #4990e2;
                                            margin: 0px;
                                            line-height: 1.25;
                                          " href="{{$info["survey_url"]}}" target="_blank" rel="noreferrer">See All Applicants</a>
                                                                    </div>
                                                                    <hr style="
                                          margin-top: 24px;
                                          border-top: 1px dashed #171717;
                                          margin-bottom: 0px;
                                          opacity: 0.4;
                                        " />

                                                                    <div style="margin-top: 24px">
                                                                        <div style="margin-top: 4px">
                                                                            <span style="
                                              font-size: 14px;
                                              color: #171717;
                                              margin: 0px;
                                              line-height: 1.5;
                                            ">Survey:</span>
                                                                            <a style="
                                              font-size: 14px;
                                              font-weight: normal;
                                              color: #4990e2;
                                              margin: 0px;
                                              line-height: 1.5;
                                            " href="{{$info["survey_url"]}}" target="_blank" rel="noreferrer">{{$info["survey_name"]}}</a>
                                                                        </div>
                                                                    </div>

                                                                    <div style="margin-top: 24px">
                                                                        <p style="
                                            color: #181818;
                                            font-size: 14px;
                                            margin: 0px;
                                            line-height: 1.5;
                                          ">
                                                                            Sincerely,<br />
                                                                            Team TagTaste
                                                                        </p>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
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
