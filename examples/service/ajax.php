<?php
require_once __DIR__ . '/../../kma_biz.php';
$html=<<<EOD
<div class="col-md-4 col-sm-6">
    <a class="link hvr-pulse-grow" data-toggle="modal" data-target="#myModal{ID}">
    <div class="circle-tile ">
        <div class="circle-tile-heading" style='background: url("{LOGO}")'></div>
                <div class="circle-tile-content {COLOR}">
                    <div class="circle-tile-number text-faded ">{NAME_OFFER}</div>
                </div>
    </div>
    </a>
    <div id="myModal{ID}"class="modal fade newspaper" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content {COLOR}">
                        <div class="modal-header text-center text-faded"><h2>{NAME_OFFER}</h2></div>
                        <div class="modal-body text-left text-faded desc">{DESCRIPTION}</div>
                        <div class="modal-footer"><button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button></div>
                </div>
            </div>
    </div>
</div>

EOD;
$kma = new \cpa\kma('AUTH_ID_HERE', 'AUTH_HASH_HERE');
$result=$kma->getOffers();

if ($kma->error) {

    file_put_contents('errors.log', date("Y-m-d H:i:s ") . $result . PHP_EOL, FILE_APPEND);

} else {

    foreach ($result->offers as $index => $offer) {
        $colors=array('red','green','dark-blue','orange','purple');
        $num=array_rand($colors);
        echo str_replace(
            array('{NAME_OFFER}','{LOGO}','{COLOR}','{ID}','{DESCRIPTION}'),
            array($offer->name,$offer->logo,$colors[$num],$offer->id,$offer->description),
            $html
        );
    }
}