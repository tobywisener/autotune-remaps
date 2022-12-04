<!--<![endif]--><!-- [if IE]><div class="ie-browser"><![endif]-->
<table bgcolor="#FFFFFF" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="table-layout: fixed; vertical-align: top; min-width: 320px; margin: 0 auto; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; width: 100%;" valign="top" width="100%">
<tbody>
<tr style="vertical-align: top;" valign="top">
<td style="word-break: break-word; vertical-align: top;" valign="top"><!-- [if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="background-color:#FFFFFF"><![endif]-->
<div style="background-color: transparent;">
<div class="block-grid" style="margin: 0 auto; min-width: 320px; max-width: 500px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
<div style="border-collapse: collapse; display: table; width: 100%; background-color: transparent;"><!-- [if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:500px"><tr class="layout-full-width" style="background-color:transparent"><![endif]--> <!-- [if (mso)|(IE)]><td align="center" width="500" style="background-color:transparent;width:500px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;"><![endif]-->
<div class="col num12" style="min-width: 320px; max-width: 500px; display: table-cell; vertical-align: top; width: 500px;">
<div style="width: 100% !important;"><!-- [if (!mso)&(!IE)]><!-->
<div style="border: 0px solid transparent; padding: 5px 0px 5px 0px;"><!--<![endif]--> <!-- [if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px; font-family: Arial, sans-serif"><![endif]-->
<div style="color: #555555; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; line-height: 1.2; padding: 10px;">
<div style="font-size: 14px; line-height: 1.2; color: #555555; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 17px;">
<p style="font-size: 14px; line-height: 1.2; word-break: break-word; mso-line-height-alt: 17px; margin: 0;"><strong><span style="font-size: 20px;">Your remap has now been completed!</span></strong></p>
</div>
</div>
<!-- [if mso]></td></tr></table><![endif]-->
<table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
<tbody>
<tr style="vertical-align: top;" valign="top">
<td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding: 10px;" valign="top">
<table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 1px solid #BBBBBB; width: 100%;" valign="top" width="100%">
<tbody>
<tr style="vertical-align: top;" valign="top">
<td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<div style="font-size: 16px; text-align: center; font-family: Arial, Helvetica Neue, Helvetica, sans-serif;">
<table style="width: 100%;">
<tbody>
<tr>
<td style="width: 50%; padding-right: 0.5em; text-align: right;"><b>Manufacturer:</b></td>
<td style="text-align: left;"><? echo $data['remap']->manufacturer; ?></td>
</tr>
<tr>
<td style="width: 50%; padding-right: 0.5em; text-align: right;"><b>Model:</b></td>
<td style="text-align: left;"><? echo $data['remap']->model; ?></td>
</tr>
<tr>
<td style="width: 50%; padding-right: 0.5em; text-align: right;"><b>Year:</b></td>
<td style="text-align: left;"><? echo $data['remap']->year; ?></td>
</tr>
<tr>
<td style="width: 50%; padding-right: 0.5em; text-align: right;"><b>Engine Type:</b></td>
<td style="text-align: left;"><? echo $data['remap']->engine_size; ?></td>
</tr>
<tr>
<td style="width: 50%; padding-right: 0.5em; text-align: right;"><b>Requested at:</b></td>
<td style="text-align: left;"><?php echo date("F j, Y, g:i a", strtotime($data["remap"]->created_at)); ?></td>
</tr>
<tr>
<td style="width: 50%; padding-right: 0.5em; text-align: right;"><b>Completed at:</b></td>
<td style="text-align: left;"><?php echo date("F j, Y, g:i a", strtotime($data["remap"]->updated_at)); ?></td>
</tr>
<tr>
    <td style="width: 50%; padding-right: 0.5em; text-align: right;"><b>Autotune Note:</b></td>
    <td style="text-align: left;"><? echo $data['remap']->autotune_note; ?></td>
</tr>
</tbody>
</table>
</div>
<table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
<tbody>
<tr style="vertical-align: top;" valign="top">
<td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding: 10px;" valign="top">
<table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 1px solid #BBBBBB; width: 100%;" valign="top" width="100%">
<tbody>
<tr style="vertical-align: top;" valign="top">
<td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<!-- [if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px; font-family: Arial, sans-serif"><![endif]-->
<div style="color: #555555; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; line-height: 1.2; padding: 10px;">
<div style="font-size: 14px; line-height: 1.2; color: #555555; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 17px;">
<p style="    font-size: 17px; line-height: 1.2; word-break: break-word; mso-line-height-alt: 24px; margin: 0;"><span style="font-size: 17px;">Your remap has been completed and can be downloaded from either of the links below.</span></p>
</div>
</div>
<!-- [if mso]></td></tr></table><![endif]-->
<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px; font-family: Arial, sans-serif"><![endif]-->
<div style="color:#555555;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.2;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
<div style="font-size: 14px; line-height: 1.2; color: #555555; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 17px;">
	<p style="font-size:20px;line-height:1.2;word-break:break-word;margin:0;text-align: center; margin-top:1em;"><a style="font-size: 17px;padding: 15px;background: #e2c21f;color: white;border-radius: 3px;cursor: pointer;"
	href="<?php echo $data["download_link"]; ?>">Download Finished File</a></p>
  <p style="font-size:20px;line-height:1.2;word-break:break-word;margin:0;text-align: center;margin-top:2.5em;margin-bottom:1em;"><a style="font-size: 14px;padding: 15px;background: grey;color: white;border-radius: 3px;cursor: pointer;"
	href="<?php echo $data["remaps_link"]; ?>">View All Remap(s)</a></p>
</div>
</div>
<!--[if mso]></td></tr></table><![endif]-->
<table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
<tbody>
<tr style="vertical-align: top;" valign="top">
<td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding: 10px;" valign="top">
<table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 1px solid #BBBBBB; width: 100%;" valign="top" width="100%">
<tbody>
<tr style="vertical-align: top;" valign="top">
<td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<!-- [if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px; font-family: Arial, sans-serif"><![endif]-->
<div style="color: #555555; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; line-height: 1.2; padding: 10px;">
<div style="font-size: 14px; line-height: 1.2; color: #555555; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 17px;">
<p style="font-size: 14px; line-height: 1.2; word-break: break-word; mso-line-height-alt: 17px; margin: 0;"><span style="color: #999999;">2019 Autotune</span></p>
<p style="font-size: 14px; line-height: 1.2; word-break: break-word; mso-line-height-alt: 17px; margin: 0;"></p>
<p style="font-size: 14px; line-height: 1.2; word-break: break-word; mso-line-height-alt: 17px; margin: 0;"><em><span style="color: #999999; font-size: 13px; mso-ansi-font-size: 14px;">The content of this email is confidential and intended for the recipient specified in message only. It is strictly forbidden to share any part of this message with any third party, without a written consent of the sender. If you received this message by mistake, please reply to this message and follow with its deletion, so that we can ensure such a mistake does not occur in the future.</span></em></p>
</div>
</div>
<!-- [if mso]></td></tr></table><![endif]--> <!-- [if (!mso)&(!IE)]><!--></div>
<!--<![endif]--></div>
</div>
<!-- [if (mso)|(IE)]></td></tr></table><![endif]--> <!-- [if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]--></div>
</div>
</div>
<!-- [if (mso)|(IE)]></td></tr></table><![endif]--></td>
</tr>
</tbody>
</table>
<!-- [if (IE)]></div><![endif]-->