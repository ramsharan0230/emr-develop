<style type="text/css">
    .th-style {
        padding: 10px;
        border: 1px solid;
    }
    .td-style {
        padding: 10px;
        border: 1px solid;
    }
    .th-bak {
        background-color: #d8d7d7;
    }
    .div-table {
        margin-top: 20px;
        margin: 0 auto;
        width: 95%;
    }
    .table-first {
        width: 95%;
    }
    .table-second {
        width: 95%;
    }
    .th-head {
        width: 13%;
    }
     h3 h2 {
        margin: 15px 0px 2px 0px;
    }
    .table-break2{width: 100%;}
    @media print {
       .table-break{page-break-before: always;}
       .table-break2{page-break-before: always;}
       .th-bak {-webkit-print-color-adjust: exact;}
       .table-bak{-webkit-print-color-adjust: exact;}
    }
</style>
<div class="pdf-container">
    <div class="heading">
        <table style="width: 95%; margin: 0 auto;">
           <tbody>
        <tr>
            <td style="width: 20%;">
                @if( Options::get('brand_image') && Options::get('brand_image') != "" )
                    <img src="{{ asset('uploads/config/'.Options::get('brand_image')) }}" class="img-fluid" alt="logo"/>
                @endif
            </td>
            <td style="width:60%;">
                <h3 style="text-align: center;">SHREE BIRENDRA HOSPITAL</h3>
                <h4 style="text-align: center;">NEPAL ARMY INSTITUE OF HEALTH AND SCIENCE</h4>
                <h4 style="text-align: center;">COLLEGE OF MEDICINE</h4>
                <h4 style="text-align: center;">CHHAUNI, KATHMANDU</h5>
            </td>
            <td></td>
        </tr>
        </tbody>
        </table>
         <table style="width: 95%; margin: 0 auto;">
            <tbody>
                <tr>
                    <td style="width: 57%;">
                     <h3 style="text-align: center;">Department Of Surgery</h3></td>
                </tr>
                <tr>
                    <td style="width: 57%;">
                        <h3 style="text-align: center;">Discharge Summary</h3>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <table width="95%" border="0" cellspacing="0" cellpadding="0" class="table-bak" style="margin-top: 20px; margin: 0 auto; background-color: #d8d7d7;">
        <thead>
            <tr>
                <th style="padding: 6px; border: none; text-align: left; font-size: 14px; width: 53%;">Name:</th>
                <th style="padding: 6px; border: none; text-align: left; font-size: 14px;">ID no.</th>
            </tr>
            <tr>
                <th style="padding: 6px; border: none; text-align: left; font-size: 14px;">Age/Sex</th>
                <th style="padding: 6px; border: none; text-align: left; font-size: 14px;">Address</th>
            </tr>
            <tr>
                <th style="padding: 6px; border: none; text-align: left; font-size: 14px;">Admission Date:</th>
                <th colspan="2" style="padding: 6px; border: none; text-align: left; font-size: 14px;">Discharge Date</th>
            </tr>
            <tr>
                <th style="padding: 6px; border: none; text-align: left; font-size: 14px;">Relation:</th>
                <th style="padding: 6px; border: none; text-align: left; font-size: 14px;">Bed No:</th>
                <th style="padding: 6px; border: none; text-align: left; font-size: 14px;">Speciality:</th>
            </tr>
        </thead>
    </table>
    <div class="table" style="margin-bottom: 20px;">
        <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
            <thead>
                <tr>
                    <th style="padding: 10px; border: 1px solid;" class="th-bak">DIGONOSIS AND COMORBITIS ACUTE APENDENDICITIES</th>
                </tr>
            </thead>
        </table>
        <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
            <thead>
                <tr>
                    <th style="padding: 10px; border: 1px solid;" class="th-bak">MEDICAL HISTORY AND PERSISTING COMPLAINTS</th>
                </tr>
                <tr>
                    <td style="border: none; font-size: 14px; padding: 10px;">
                        according to the patient he was apparantly well 1 day back when he developed pain in the perimbilical area which gradually shifted to right iliac fossa. the pain aggravated on taking food. he gives h/o two episodes
                        of vomiting. there is no h/o fever, abdominal trauma, diarrhoea and constipation. his bowel and bladder habits are normal
                    </td>
                </tr>
            </thead>
        </table>
        <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
            <thead>
                <tr>
                    <th style="padding: 10px; border: 1px solid;" class="th-bak">PAST HISTORY</th>
                </tr>
                <tr>
                    <td style="border: none; font-size: 14px; padding: 10px;">no h/o htn, dm, copd or other chronic illness, no h/o surgical intervention in the past</td>
                </tr>
            </thead>
        </table>
        <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
            <thead>
                <tr>
                    <th style="padding: 10px; border: 1px solid;" class="th-bak">PHYSICAL AND SYSTEMIC EXAMINATION</th>
                </tr>
                <tr>
                    <td style="border: none; font-size: 14px; padding: 10px;">
                        gc - fair, plccod: nil p/a: normal in shape, umbilicus central and inverted, soft tenderness and rebound tenderness present in right iliac fossa, rebound tenderness present, pointing sign present, rovsing's sign
                        present, no organomegaly. bowel sounds present. hernial sites intact. rest of systemic examination - normal
                    </td>
                </tr>
            </thead>
        </table>
        <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
            <thead>
                <tr>
                    <th style="padding: 10px; border: 1px solid;" class="th-bak">APPENDICITIES</th>
                </tr>
                <tr>
                    <td style="border: none; font-size: 14px; padding: 10px;">
                        sero: negative, hb: 11.9, bg: a+ve, tlc: 16100, plt: 200000, pt/inr: 17/1.28. ur/cr:27/0.8. tb/cb 1.0/0.4, and tp/alb: 6.2/3.9, alt/ast/alp: 14/21/40, na/k:137/4.0, rbs. 109 usg abdomen and pelvis (2020/12/10): a
                        blind ending, non-compressible, aperistaltic tubular structure of diameter 16 mm seems to be arising from the base of caecum s/o acute appendicitis.
                    </td>
                </tr>
            </thead>
        </table>
        <table border="0" cellspacing="0" cellpadding="0" class="table-break" style="margin: 0 auto; margin-top: 20px;">
            <thead>
                <tr>
                    <th style="padding: 10px; border: 1px solid;" class="th-bak">OPERATION PERFORMED: EMERGENCY OPEN APPENDECTOMY UNDER SAB ON 2077/09/21</th>
                </tr>
                <tr>
                    <td style="border: none; font-size: 14px; padding: 10px;"><strong>OPERATIVE DETAILS:</strong>Acutely inflammed appendix with fecolith with healthy base with no peri appendiceal collection</td>
                </tr>
            </thead>
        </table>
        <table border="0" width="95%" cellspacing="0" cellpadding="0"  style="margin: 0 auto; margin-top: 20px;">
            <thead>
                <tr>
                    <th style="padding: 10px; border: 1px solid;" class="th-bak">COURSE IN HOSPITAL:</th>
                </tr>
                <tr>
                    <td style="border: none; font-size: 14px; padding: 10px;">intraoperative and postoperative period was uneventful at time of discharge patient is tollerating orally, passing stool and flatus with healthy wound.</td>
                </tr>
            </thead>
        </table>
        <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
            <thead>
                <tr>
                    <th style="padding: 10px; border: 1px solid;" class="th-bak">ADVICE AND DISCHARGE</th>
                </tr>
            </thead>
        </table>
        <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
            <thead>
                <tr>
                    <th style="padding: 10px; border: none; text-align: left;" colspan="2">Medication</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 10px; border: none;" width="2%">1.</td>
                    <td style="padding: 10px; border: none; text-align: left;">tab ritocef 200 mg po bd x 7 days.</td>
                </tr>
                <tr>
                    <td style="padding: 10px; border: none;">2.</td>
                    <td style="padding: 10px; border: none; text-align: left;">tab protogyl 400 mg po tds x 5 days</td>
                </tr>
                <tr>
                    <td style="padding: 10px; border: none;">3.</td>
                    <td style="padding: 10px; border: none; text-align: left;">tab ondem 4 mg po tds x 5 days</td>
                </tr>
            </tbody>
        </table>

        <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
            <thead>
                <tr>
                    <th style="padding: 10px; border: none; text-align: left;" colspan="2">Diet</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 10px; border: none;" width="2%">1.</td>
                    <td style="padding: 10px; border: none; text-align: left;">soft blended diet</td>
                </tr>
                <tr>
                    <td style="padding: 10px; border: none;">2.</td>
                    <td style="padding: 10px; border: none; text-align: left;">avoid oily and spicy foods</td>
                </tr>
            </tbody>
        </table>
        <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
            <thead>
                <tr>
                    <th style="padding: 10px; border: none; text-align: left;" colspan="2">Special Instruction</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 10px; border: none;" width="2%">1.</td>
                    <td style="padding: 10px; border: none; text-align: left;">DAILY DRESSING</td>
                </tr>
                <tr>
                    <td style="padding: 10px; border: none;">2.</td>
                    <td style="padding: 10px; border: none; text-align: left;">suture removal on 2077/10/03</td>
                </tr>
                <tr>
                    <td style="padding: 10px; border: none;">3.</td>
                    <td style="padding: 10px; border: none; text-align: left;">in case of fever, abdominal pain and multiple vomiting, please visit hospital</td>
                </tr>
            </tbody>
        </table>
        <div class="div-table">
            <h4>DISCHARGE SUMMARY PREPARED BY:</h4>
            <h4>DISCHARGE SUMMARY CHECKED BY:</h4>
        </div>
        <table border="0" cellspacing="0" cellpadding="0" class="table-break2" style="margin: 0 auto; margin-top: 20px; margin-bottom: 20px;">
            <thead>
                <tr>
                    <th class="th-style th-bak">Consult Unit</th>
                    <th class="th-style th-bak">Head Of Department</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="td-style">brig gen dr bharat b bhandari</td>
                    <td class="td-style" rowspan="8" style="text-align: center;">lt col dr narayan thapa</td>
                </tr>
                <tr>
                    <td class="td-style">lt col dr narayan thapa</td>
                </tr>
                <tr>
                    <td class="td-style">maj dr bikash b thapa</td>
                </tr>
                <tr>
                    <td class="td-style">maj dr manoj kuha</td>
                </tr>
                <tr>
                    <td class="td-style">maj dr dhirendra ayer</td>
                </tr>
                <tr>
                    <td class="td-style">maj dr suresh thapa</td>
                </tr>
                <tr>
                    <td class="td-style">maj dr sunil basukala</td>
                </tr>
                <tr>
                    <td class="td-style">dr sanjeeb b bista</td>
                </tr>
            </tbody>
        </table>
        <table border="0" cellspacing="0" cellpadding="0" class="table-first" style="margin: 0 auto; margin-top: 20px;">
            <thead>
                <tr>
                    <th style="padding: 10px; border: 1px solid;" class="th-bak">f/u in sopd after 1 week on mon /wed/fri/or sos with hpe reports (surgery unit ii)</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
