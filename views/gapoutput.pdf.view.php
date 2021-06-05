<style>
    p, h1, h2, h3, h4 {
        margin: 5px 0;
        padding: 0;
        color: #333;
    }
    h1 {
        font-size: 24px;
    }

    tr th {
        background-color: #777;
        color: #fff;
        border-top: none;
        border-bottom: none;
    }
    tr th,
    tr td {
        border-color: #BABABA;
    }

    .requirements td,
    .requirements th {
        padding: 6px 8px;
    }
    .requirements td {
        background-color: #fff;
    }
    .p-win {
        width: 16.6%;
        padding: 5px;
    }
    .p-win div {
        text-align: center;
        width: 90%;
        height: 50px;
        vertical-align: middle;
        margin: 0 5%;
        color: #878480;
        border: 1px dashed #333;
    }
    .active div {
        border: 2px solid #333;
        color: #111;
    }

    .p-win-1 div {
        background-color: #77d122;
    }
    .p-win-2 div {
        background-color: #98e239;
    }
    .p-win-3 div {
        background-color: #98e239;
    }
    .p-win-4 div {
        background-color: #fffe31;
    }
    .p-win-5 div {
        background-color: #fd7b48;
    }
    .p-win-6 div {
        background-color: #fd2118;
    }

    .p-win-this {
        width: 16.6%;
        position: relative;
    }
    .p-win-this div.image {
        width: 40%;
        margin-left: 30%;
        margin-top: -15px;
    }
    .p-win-this div.image img {
        left: 0px;
        top: 0px;
        width: 100%;
        height: auto;
    }

    .top-table h3 span {
        font-size: 16px;
        color: #878480;
    }
</style>
<page backcolor="#f3f3f3">

    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="width: 70%;border-bottom: 1px solid #777;padding-bottom:10px; padding-right: 40px"><p>Generated by <?=$sol['user_fullname']?> for: <?=$sol['user_email']?></p></td>
            <td style="width: 30%;border-bottom: 1px solid #777;padding-bottom:10px"><img style="width: 100%; height: auto;" src="<?=URL?>/assets/images/logo.png"></td>
        </tr>
    </table>

    <table style="width: 100%; border-collapse: collapse; margin-top: 15px;" class="top-table">
        <tr>
            <td style="width: 50%;">
                <h1><?=$sol['solicitation_title']?></h1>
                <h2><?=$sol['solicitation_number']?></h2>
                <h3><?=$sol['solicitation_agency']?></h3>
            </td>
            <td style="width: 50%">
                <h3><span>Due Date</span> <?=normal_date($sol['solicitation_due_date'], 'M d, Y')?></h3>
                <h3><span>Generated</span> <?=date('M d, Y h:i A')?></h3>
                <h4 <?=$days_due < 7 ? 'style="color: red"':''?>>Due in <?=$days_due?> Days</h4>
                <h3><?=$sol['solicitation_url']?></h3>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border-bottom: 1px solid #777;padding-bottom: 15px; padding-top: 15px;">
                <p>
                    <?=$sol['solicitation_description']?>
                </p>
            </td>
        </tr>
    </table>

    <table style="width: 100%; border-collapse: collapse;margin-top: 15px;">
        <tr>
            <td><h2>Gap Analysis</h2></td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #777;padding-bottom: 15px;padding-top: 15px;">
                <table class="requirements" style="width: 100%; border-collapse: collapse" border="1">
                    <tr>
                        <th style="width: 40%;border-left: none">Requirements</th>
                        <th style="width: 10%;">Gap?</th>
                        <th style="width: 15%;">Risk Rating</th>
                        <th style="width: 35%;border-right: none">Action Items</th>
                    </tr>
                    <?php foreach ($reqs as $req): ?>
                    <tr>
                        <td style="width: 40%;"><?=$req['requirement_title']?></td>
                        <td style="width: 10%; background-color: <?=$req['calculated_gap'][2]?>"><?=$req['calculated_gap'][1]?> (<?=$req['calculated_gap'][0]?>)</td>
                        <td style="width: 15%; background-color: <?=$req['calculated_risk_rating'][2]?>"><?=$req['calculated_risk_rating'][1]?> (<?=$req['calculated_risk_rating'][0]?>)</td>
                        <td style="width: 35%;"><?=$req['requirement_action_items']?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </td>
        </tr>
    </table>

    <table style="width: 100%; border-collapse: collapse;">
        <tr><td style="width: 100%; padding-top: 10px; padding-bottom: 5px;"><h1>Notes</h1></td></tr>
        <tr><td style="border-bottom: 1px solid #777;padding-bottom: 15px"><p><?=$sol['solicitation_notes']?></p></td></tr>
        
        <tr><td style="width: 100%; padding-top: 15px; padding-bottom: 5px;"><h1>Recommendation</h1></td></tr>
        <tr><td style="border-bottom: 1px dashed #777;padding-bottom: 15px"><p><?=$sol['solicitation_recommendation']?></p></td></tr>
        
        <tr><td style="width: 100%; padding-top: 15px; padding-bottom: 15px;"><h1>P-Win</h1></td></tr>
        <tr>
            <td style="border-bottom: 1px solid #777;padding-bottom: 15px">
                <table style="width: 100%;">
                    <tr>
                        <td class="p-win p-win-1 <?=$pwin_table_index == 0?'active':''?>"><div>Bid</div></td>
                        <td class="p-win p-win-2 <?=$pwin_table_index == 1?'active':''?>"><div>Agree / Likely</div></td>
                        <td class="p-win p-win-3 <?=$pwin_table_index == 2?'active':''?>"><div>Agree / Maybe</div></td>
                        <td class="p-win p-win-4 <?=$pwin_table_index == 3?'active':''?>"><div>Neutral</div></td>
                        <td class="p-win p-win-5 <?=$pwin_table_index == 4?'active':''?>"><div>Low P-win</div></td>
                        <td class="p-win p-win-6 <?=$pwin_table_index == 5?'active':''?>"><div>No BID</div></td>
                    </tr>
                    <tr>
                    <?php for ($i = 0; $i < 6; $i++): ?>
                        <?php if ($pwin_table_index === $i):?>
                        <td class="p-win-this">
                            <div class="image">
                                <img src="<?=URL?>/assets/images/icons/this-bg.png">
                            </div>
                        </td>
                        <?php else: ?>
                        <td></td>
                        <?php endif; ?>
                    <?php endfor; ?>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</page>
