<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <body style="margin: 0; padding: 0">
    <center>
      <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family: Arial" bgcolor="#F8F6F9">
        @include("emails.header.v1.header")
        <tr>
          <td align="center" valign="top">
            <table
              class="container"
              width="620"
              align="center"
              border="0"
              cellspacing="0"
              cellpadding="0"
              border-collapse="collapse"
            >
              <tr>
                <td align="center" width="100%" bgcolor="#F8F6F9">
                  <table
                    width="550"
                    align="center"
                    border="0"
                    cellspacing="0"
                    cellpadding="0"
                    border-collapse="collapse"
                  >
                    <tr>
                      <td
                        align="center"
                        bgcolor="#FFFFFF"
                        style="padding: 20px 0 0; border-radius: 4px 4px 0 0; width: 100% !important"
                        width="100%"
                      >
                        <div style="font-size: 18px; font-weight: 900; padding: 0 40px 0 40px; color: #499DE2;">
                            Payment Initiated
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td
                        style="
                          width: 100% !important;
                          background-color: #ffffff !important;
                          padding: 0 20px 20px 20px;
                          border-radius: 4px;">
                        <table
                          style="
                            margin: 20px 0px 0px 0px;
                            padding: 16px;
                            border: solid 0.5px rgba(0, 0, 0, 0.1);
                            border-radius: 10px;
                          "
                          width="100%"
                          bgcolor="#FFFFFF"
                        >
                          <tr>
                            <td align="center" valign="top" width="100%" class="templateColumnContainer">
                              <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#FFFFFF">
                                <tr>
                                  <td bgcolor="#FFFFFF" align="left">
                                    <p style="font-size: 16px; color: #171717; margin: 0px; line-height: 24px; font-weight: 900;">
                                        Congratulations!
                                    </p>
                                  </td>
                                </tr>
                                <tr>
                                    <td bgcolor="#FFFFFF" align="left">
                                      <p style="font-size: 20px; color: #171717; margin-top: 16px; text-align: center; line-height: 28.2px; font-weight: 900;">
                                        Your payment has been initiated
                                      </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td bgcolor="#FFFFFF" align="left">
                                      <p style="font-size: 32px; color: #171717; margin-top: 20px; text-align: center; line-height: 0px; font-weight: 900;">
                                        <span style="font-weight: 400;">₹</span>{{$data["amount"]}}
                                      </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td bgcolor="#FFFFFF" align="left">
                                      <p style="font-size: 14px; color: #747474; margin-top: 4px; text-align: center; line-height: 20px; font-weight: 900;">
                                        Transaction ID: {{$data["order_id"]}}
                                      </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td bgcolor="#FFFFFF" align="left">
                                      <p style="font-size: 16px; color: #181818; margin-top: 4px; text-align: center; line-height: 0px; font-weight: 400;">
                                        Tax deducted at source (TDS) @ 10% : ₹{{$data["tds_amount"]}}
                                      </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td bgcolor="#FFFFFF" align="left">
                                      <p style="font-size: 16px; color: #181818; margin-top: 12px; text-align: center; line-height: 0px; font-weight: 400;">
                                        Actual Redeemable Amount: ₹{{$data["payout_amount"]}}
                                      </p>
                                    </td>
                                </tr>
                                <table>
                                <tr>
                                <hr  style="
                                     border: none;
                                     border-top: 1px dashed #747474;
                                     color: #fff;
                                     background-color: #fff;
                                     height: 1px;
                                     margin-top: 20px;" />
                                </tr>
                                <tr>
                                  <td bgcolor="#FFFFFF" align="left">
                                    <p
                                      style="
                                        color: #181818;
                                        margin: 8px 0px 0px 0px;
                                        font-size: 14px;
                                        line-height: 21px;
                                        font-weight: 400;">
                                       Status: 
                                        <span  style="
                                            color: #4990E2;
                                            margin: 16px 0px 0px 0px;
                                            font-size: 14px;
                                            line-height: 20px;
                                            font-weight: 900;">Initiated</span>
                                    </p>
                                    <p
                                    style="
                                      color: #181818;
                                      margin: 4px 0px 0px 0px;
                                      font-size: 14px;
                                      line-height: 21px;
                                      font-weight: 400;">
                                     {{$data["type"]}}: 
                                      <span  style="
                                          color: #499DE2;
                                          margin: 4px 0px 0px 0px;
                                          font-size: 14px;
                                          line-height: 20px;
                                          font-weight: 400;
                                          text-decoration: underline;">
                                           <a href="#" target="_blank" rel="noreferrer" style="color: #4990e2">{!!$data["pretext"] !!}</a></span>
                                  </p>
                                    <p
                                      style="
                                        color: #181818;
                                        margin: 24px 0px 0px 0px;
                                        font-size: 16px;
                                        line-height: 1.5;
                                        font-weight: 700;">
                                      TDS Certificate:
                                    </p>
                                    <p
                                      style="
                                        color: #181818;
                                        margin: 0px 0px 0px 0px;
                                        font-size: 14px;
                                        line-height: 21px;">
                                       Our finance team will provide you with the TDS Certificate for the tax deducted at source by the end of the next quarter, ensuring you have all the necessary documents for your records and any potential tax filings.
                                    </p>
                                    <p
                                      style="
                                        color: #181818;
                                        margin: 20px 0px 0px 0px;
                                        font-size: 14px;
                                        line-height: 21px;">
                                      If you have any questions or require further clarification regarding tax deductions or the payment process, please do not hesitate to contact us at 
                                      <span style="color: #4990E2;">
                                        <a href="finance@tagtaste.com." target="_blank" rel="noreferrer" style="color: #4990e2; text-decoration: none;">finance@tagtaste.com.</a></span> 
                                    </p>
                                    <div style="margin-top: 24px">
                                      <p style="color: #181818; font-size: 14px; margin: 0px; line-height: 1.5">
                                        Sincerely,<br />
                                        Team TagTaste
                                      </p>
                                    </div>
                                  </td>
                                </tr>
                                </table>
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
</html>
