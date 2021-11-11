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
                                  color: #000000;
                                  padding: 0 40px 0 40px;
                                ">
                                                Reminder for Payment Redemption
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
                                                                    <p style="
                                              color: #181818;
                                              margin: 8px 0px 0px 0px;
                                              font-size: 16px;
                                              text-align: center;
                                              line-height: 1.5;
                                            ">
                                                                        Looks like you have a payment pending to be
                                                                        redeemed
                                                                    </p>
                                                                    <div style="
                                              text-align: center;
                                              margin: 24px 0px 0px 0px;
                                              color: #171717;
                                              font-size: 32px;
                                            ">
                                                                        <span style="font-weight: normal">₹</span><span
                                                                            style="font-weight: bold">{{$data["content"]["amount"]}}</span>
                                                                    </div>
                                                                    <p style="
                                              color: #181818;
                                              margin: 10px 0px 0px 0px;
                                              font-size: 14px;
                                              font-weight: bold;
                                              text-align: center;
                                              opacity: 0.6;
                                            ">
                                                                        Transaction ID: {{$data["content"]['Order']}}
                                                                    </p>
                                                                    <hr style="
                                              margin-top: 24px;
                                              border-top: 1px dashed #171717;
                                              margin-bottom: 0px;
                                              opacity: 0.4;
                                            " />

                                                                    <div style="margin-top: 24px">
                                                                        <div>
                                                                            <span style="
                                                  font-size: 14px;
                                                  color: #181818;
                                                  margin: 0px;
                                                  line-height: 1.5;
                                                ">Status:</span>
                                                                            <span style="
                                                  font-size: 14px;
                                                  font-weight: bold;
                                                  color: #f47816;
                                                  margin: 0px;
                                                  line-height: 1.5;
                                                ">Pending</span>
                                                                        </div>
                                                                        <div style="margin-top: 4px">
                                                                            <span style="
                                                  font-size: 14px;
                                                  color: #181818;
                                                  margin: 0px;
                                                  line-height: 1.5;
                                                ">Payment Link:
                                                                            </span>
                                                                            <a style="
                                                  font-size: 14px;
                                                  font-weight: normal;
                                                  color: #4990e2;
                                                  margin: 0px;
                                                  line-height: 1.5;
                                                " href="#" target="_blank" rel="noreferrer">{{$data["content"]["Link"]}}</a>
                                                                        </div>
                                                                        <div style="margin-top: 4px">
                                                                            <span style="
                                                  font-size: 14px;
                                                  color: #181818;
                                                  margin: 0px;
                                                  line-height: 1.5;
                                                ">Expires on:</span>
                                                                            <span style="
                                                  font-size: 14px;
                                                  font-weight: bold;
                                                  color: #dd2e1f;
                                                  margin: 0px;
                                                  line-height: 1.5;
                                                ">{{date("d-M-Y",strtotime($data["content"]["expiry_time"]))}}</span>
                                                                        </div>
                                                                        <div style="margin-top: 4px">
                                                                            <span style="
                                                  font-size: 14px;
                                                  color: #181818;
                                                  margin: 0px;
                                                  line-height: 1.5;
                                                ">{{$data["content"]["type"]}}:</span>
                                                                            <a style="
                                                  font-size: 14px;
                                                  font-weight: normal;
                                                  color: #4990e2;
                                                  margin: 0px;
                                                  line-height: 1.5;
                                                " href="#" target="_blank" rel="noreferrer">{!! $data["content"]["hyperlink"] !!}</a>
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
