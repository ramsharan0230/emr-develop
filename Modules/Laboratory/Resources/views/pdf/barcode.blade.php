<style>
    .barcode-wrapper {
        width: 50mm;
        height: 25mm;
        /* border:1px solid red; */
        padding: 2px 5px;
    }

    .barcode-image {
        width: 100%;
        margin: 0;
        height: auto;
    }

    @media print {
        @page {
            width: 50mm;
            height: 25mm;
            padding: 2px 5px;
        }
    }
</style>
<script>
    window.onload = function() {
        window.print();
    }
</script>
<div class="barcode-wrapper">
    @if(count($testsData))
    @foreach($testsData as $test)
    @if($loop->first)
    @for ($i = 0; $i < request()->get('noOfPage', 1); $i++)



        <table class='title_table' style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="0" id="titletable">
            <tr>
                <td style="vertical-align: top; text-align: left;font-size:12px; white-space:nowrap">
                    <font face="Ubuntu"><b>{{ $encounter_data->patientInfo->fullname }}</b></font>
                </td>


            </tr>
            <tr>
                <td style="vertical-align: top; text-align: left;font-size:12px; white-space:nowrap">
                    <font face="Ubuntu"><b> {{ $encounter_data->patientInfo->fldagestyle }}/{{ $encounter_data->patientInfo->fldptsex }}</b></font>
                </td>
                <td style="vertical-align: top; text-align: right;font-size:12px;white-space:nowrap ">
                    <font face="Ubuntu"><b>{{ $encounter_data->fldencounterval }}<b></font><br>
                </td>
            </tr>
        </table>
        <table class='title_table' style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="0" id="titletable">
            <tr>
                <td>
                    <div>
                    @php
                    echo DNS1D::getBarcodeSVG($test->fldsampleid, \App\Utils\Options::get('barcode_format'))
                    @endphp
                    </div>
                    <br>
                </td>
            </tr>
        </table>
        <table class='title_table' style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="0" id="titletable">
            <tr>
                <td style="vertical-align: top; text-align: left;font-size:10px"><b>{{ $test->fldsampleid }}</b></td>
                <td style="vertical-align: top; text-align: right;font-size:10px">{{ \App\Utils\Helpers::dateEngToNepdash(date('Y-m-d'))->full_date . ' ' .  date('H:i:s') }}</td>
            </tr>
        </table>
        @endfor
        @endif

        @endforeach
        @endif
</div>
