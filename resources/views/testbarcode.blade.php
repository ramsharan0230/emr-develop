<@php
    echo DNS1D::getBarcodeSVG('4445645656', 'PHARMA2T',3,33);
echo DNS1D::getBarcodeHTML('4445645656', 'PHARMA2T',3,33);
echo '<img src="' . DNS1D::getBarcodePNG('4', 'C39+',3,33) . '" alt="barcode"   />';
echo DNS1D::getBarcodePNGPath('4445645656', 'PHARMA2T',3,33);
echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG('4', 'C39+',3,33) . '" alt="barcode"   />';
echo'<img style="width: 261.216px; height: 262px; display: block;" alt="Eosinophil" class="" src="https://www.w3schools.com/html/pic_trulli.jpg">'
@endphp