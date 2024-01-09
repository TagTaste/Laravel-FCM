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
                            Questionnaire Preview for Approval
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
                                        Dear Client,
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
                                       We are pleased to share with you a preview of the questionnaire that we have designed for your project. This questionnaire will help us gather valuable insights and feedback from your target audience.
                                       <br>
                                       To access the preview of the questionnaire, please click on the button below and enter the verification code (OTP) that is provided in this email. The veriiffication code is valid for 7 days from the date of this email.
                                      
                                    </p>
                                    <p style="font-weight: 400;font-size: 16px;color: #181818;" >
                                        Verification Code (OTP): <span
                                        style="font-weight: 700;font-size: 16px;color: #181818;">{{$data["otp"]}}</span>
                                       </p>
                                    <div style="text-align: center;">
                                     <a href={{$data["link"]}} target="_blank"  style="background-color:
                                     #EFB920;border-radius: 8px;padding: 8px 12px 8px 12px;border: none;font-weight: 500;font-size: 16px;color: #171717;" >View Questionnaire</a>
                                    </div>
                                
                                    <p
                                      style="
                                        color: #181818;
                                        margin: 16px 0px 0px 0px;
                                        font-size: 16px;
                                        line-height: 1.5;">
                                        We request you to review the questionnaire and share your approval or suggestions with us as soon as possible. Your feedback is very important for us to proceed with the next steps of the project.
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