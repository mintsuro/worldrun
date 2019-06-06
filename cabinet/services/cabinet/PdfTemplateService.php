<?php

namespace cabinet\services\cabinet;

use yii\web\NotFoundHttpException;
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use Yii;

class PdfTemplateService
{
    public function generatePDF($html, $orientation, $format): void
    {
        try{
            $html2pdf = new Html2Pdf($orientation, $format,'en',true,'UTF-8',[0,0,0,0]);
            $html2pdf->AddFont('verdana', '', Yii::getAlias('@common') . '/pdf_template/fonts/verdana.php');
            $html2pdf->setDefaultFont(Yii::getAlias('@common') . '/pdf_template/fonts/verdana');
            $html2pdf->writeHTML($html);
            $html2pdf->output();
        }catch (Html2PdfException $e) {
            $html2pdf->clean();
            $formatter = new ExceptionFormatter($e);
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $formatter->getHtmlMessage());
        }
    }
}