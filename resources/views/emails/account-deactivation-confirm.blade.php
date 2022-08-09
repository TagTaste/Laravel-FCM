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
                        <div style="font-size: 18px; font-weight: bold; padding: 0 40px 0 40px">
                          Account Deactivated
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td
                        style="
                          width: 100% !important;
                          background-color: #ffffff !important;
                          padding: 0 20px 20px 20px;
                          border-radius: 4px;
                        "
                      >
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
                                    <p style="font-size: 16px; color: #171717; margin: 0px; line-height: 1.5">
                                      Hi {{$data['name']}},
                                    </p>
                                  </td>
                                </tr>
                                <tr>
                                  <td bgcolor="#FFFFFF" align="left">
                                    <p
                                      style="
                                        color: #171717;
                                        margin: 16px 0px 0px 0px;
                                        font-size: 16px;
                                        line-height: 1.5;
                                      "
                                    >
                                      Your TagTaste account has been deactivated. Deactivating your account hides your
                                      account and does not delete it.
                                    </p>
                                    <p
                                      style="
                                        color: #171717;
                                        margin: 16px 0px 0px 0px;
                                        font-size: 16px;
                                        line-height: 1.5;
                                      "
                                    >
                                      Whenever you wish to be back on TagTaste, just login with your email and you'll be
                                      good to go.
                                    </p>
                                    <p
                                      style="
                                        color: #171717;
                                        margin: 16px 0px 0px 0px;
                                        font-size: 16px;
                                        line-height: 1.5;
                                      "
                                    >
                                      We'll miss your time away from us.
                                    </p>
                                    <p
                                      style="
                                        color: #171717;
                                        margin: 16px 0px 0px 0px;
                                        font-size: 16px;
                                        line-height: 1.5;
                                      "
                                    >
                                      If you need any help, you can reach out to us at
                                      <a href="/profile/id" target="_blank" rel="noreferrer" style="color: #4990e2"
                                        >contact@tagtaste.com</a
                                      >.
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
