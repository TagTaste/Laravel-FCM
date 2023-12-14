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
                        <div style="font-size: 18px; font-weight: 900; padding: 0 40px 0 40px">
                            Tax deducted for Transaction ID {{$data["order_id"]}}
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
                                    <p style="font-size: 16px; color: #181818; margin: 0px; line-height: 1.5">
                                      Hi Team,
                                    </p>
                                  </td>
                                </tr>
                                <tr>
                                  <td bgcolor="#FFFFFF" align="left">
                                    <p
                                      style="
                                        color: #181818;
                                        margin: 28px 0px 0px 0px;
                                        font-size: 16px;
                                        line-height: 1.5;">
                                        We wish to bring to your attention a recent transaction where Tax Deducted at Source (TDS) has been applied. Detailed below are the particulars of this transaction:
                                    </p>
                                    <p
                                    style="
                                      color: #747474;
                                      margin: 28px 0px 0px 0px;
                                      font-size: 16px;
                                      line-height: 1.5;">
                                      Transaction Details:
                                  </p>
                                    <p
                                      style="
                                        color: #181818;
                                        margin: 4px 0px 0px 0px;
                                        font-size: 14px;
                                        line-height: 1.5;
                                        font-weight: 700;">
                                         User Name: 
                                        <span  style="
                                            color: #499DE2;
                                            margin: 4px 0px 0px 0px;
                                            font-size: 14px;
                                            line-height: 1.5;
                                            font-weight: 700;
                                            text-decoration: underline;">
                                   <a href={{$data["profile_link"]}} target="_blank" rel="noreferrer" style="color: #4990e2">{{$data["name"]}}</a>
                                </span>
                                    </p>
                                    <p
                                    style="
                                      color: #181818;
                                      margin: 4px 0px 0px 0px;
                                      font-size: 14px;
                                      line-height: 1.5;
                                      font-weight: 700;">
                                      User Email:
                                      <span  style="
                                          color: #181818;
                                          margin: 16px 0px 0px 0px;
                                          font-size: 14px;
                                          line-height: 1.5;
                                          font-weight: 700;">
                                      {{$data["email"]}}</span>
                                  </p>
                                  <p
                                  style="
                                    color: #181818;
                                    margin: 4px 0px 0px 0px;
                                    font-size: 14px;
                                    line-height: 1.5;
                                    font-weight: 700;">
                                    Transaction ID: 
                                    <span  style="
                                        color: #499DE2;
                                        margin: 16px 0px 0px 0px;
                                        font-size: 14px;
                                        line-height: 1.5;
                                        font-weight: 700;
                                        text-decoration: underline;">
                                         <a href={{$data["txn_link"]}} target="_blank" rel="noreferrer" style="color: #4990e2">{{$data["order_id"]}}</a>
                                   </span>
                                </p>
                                <p
                                  style="
                                    color: #181818;
                                    margin: 4px 0px 0px 0px;
                                    font-size: 14px;
                                    line-height: 1.5;
                                    font-weight: 700;">
                                    {{$data["type"]}}:  
                                    <span  style="
                                        color: #499DE2;
                                        margin: 16px 0px 0px 0px;
                                        font-size: 14px;
                                        line-height: 1.5;
                                        font-weight: 700;
                                        text-decoration: underline;">
                                         <a href="#" target="_blank" rel="noreferrer" style="color: #4990e2">{!!$data["pretext"] !!}</a>
                                   </span>
                                </p>
                                <p
                                  style="
                                    color: #181818;
                                    margin: 4px 0px 0px 0px;
                                    font-size: 14px;
                                    line-height: 1.5;
                                    font-weight: 700;">
                                    Transaction Date:
                                    <span  style="
                                        color: #181818;
                                        margin: 16px 0px 0px 0px;
                                        font-size: 14px;
                                        line-height: 1.5;
                                        font-weight: 700;">
                                     {{$data["created_at"]}}</span>
                                </p>
                                <p
                                  style="
                                    color: #181818;
                                    margin: 4px 0px 0px 0px;
                                    font-size: 14px;
                                    line-height: 1.5;
                                    font-weight: 700;">
                                    Initiated Payment Amount:
                                    <span  style="
                                        color: #181818;
                                        margin: 16px 0px 0px 0px;
                                        font-size: 14px;
                                        line-height: 1.5;
                                        font-weight: 700;">
                                    ₹{{$data["amount"]}}</span>
                                </p>
                                <p
                                  style="
                                    color: #181818;
                                    margin: 4px 0px 0px 0px;
                                    font-size: 14px;
                                    line-height: 1.5;
                                    font-weight: 700;">
                                  Tax Deducted at Source (TDS) @ 10%:
                                    <span  style="
                                        color: #181818;
                                        margin: 16px 0px 0px 0px;
                                        font-size: 14px;
                                        line-height: 1.5;
                                        font-weight: 700;">
                                     ₹{{$data["tds_amount"]}}</span>
                                </p>
                                <p
                                  style="
                                    color: #181818;
                                    margin: 4px 0px 0px 0px;
                                    font-size: 14px;
                                    line-height: 1.5;
                                    font-weight: 700;">
                                 Actual Redeemable Amount :
                                    <span  style="
                                        color: #181818;
                                        margin: 16px 0px 0px 0px;
                                        font-size: 14px;
                                        line-height: 1.5;
                                        font-weight: 700;">
                                     ₹{{$data["payout_amount"]}}</span>
                                </p>
                                <p
                                  style="
                                    color: #181818;
                                    margin: 4px 0px 0px 0px;
                                    font-size: 14px;
                                    line-height: 1.5;
                                    font-weight: 700;">
                                    PAN Available:
                                    <span  style="
                                        color: #181818;
                                        margin: 16px 0px 0px 0px;
                                        font-size: 14px;
                                        line-height: 1.5;
                                        font-weight: 700;">
                                     No</span>
                                </p>
                                    <p
                                      style="
                                        color: #181818;
                                        margin: 28px 0px 0px 0px;
                                        font-size: 16px;
                                        line-height: 1.5;">
                                         We kindly ask the finance team to check these details and take the needed steps following our rules and laws. Also, please make sure to create and send out the necessary paperwork, including the TDS certificate, quickly and on time.
                                    </p>
                                    <p
                                      style="
                                      color: #181818;
                                      margin: 28px 0px 0px 0px;
                                      font-size: 16px;
                                      line-height: 1.5;">
                                      Thank you for your cooperation and diligence.
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
