<?php
namespace App\Controller;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use TCPDF;

final class VoucherController
{
    private $view;
    private $logger;
    private $pdf, $x, $y, $align, $img, $w, $h;

    public function __construct($container)
    {
        $this->view = $container->get('view');
        $this->logger = $container->get('logger');
        $this->pdf = self::initPDF();
    }

    public function getVoucher(Request $request, Response $response, $args)
    {
        $pdf = $this->pdf;

        switch ($args['id']) {
            case 1:
                $pdf = self::setFields(65, 52, 'center');
                $pdf = self::setImg('voucher2.jpg', 160, 80);
                $pdf = self::printCards();
            break;
            case 2:
                $pdf = self::setFields(37, 50, 'left');
                $pdf = self::setImg('voucher1.jpg', 160, 80);
                $pdf = self::printCards();
            break;
            case 3:
                $pdf = self::setFields(65, 52, 'center');
                $pdf = self::setImg('voucher3.jpg', 160, 80);
                $pdf = self::printCards();
            break;
            default:
                $this->view->render($response, '404.twig');
                return $response->withStatus(404);
                break;
        }
    
        $response = $response->withHeader('Content-type', 'application/pdf');
        $response->write($pdf->output('doc.pdf', 'S'));
    
        return $response;
    }

    private function setFields($x,$y,$align)
    {
        $this->x = $x;
        $this->y = $y;
        $this->align = $align;
        return true;
    }

    private function setImg($img,$w,$h)
    {
        $this->img = $img;
        $this->w = $w;
        $this->h = $h;
        return true;
    }

    private function printCards()
    {
        $pdf = $this->pdf;
        $fx = $this->x;
        $fy = $this->y;
        $align = $this->align;
        $img = $this->img;
        $w = $this->w;
        $h = $this->h;
     
        $docX = (210 - $w) / 2;
        $docY = 10;
        
        for ($i=0;$i<3;$i++) {
            $pdf->Image( __DIR__ . '/../../../public/img/' . $img, $docX, $docY, $w, $h, 'JPG', '', '', true, 300, '', false, false, 0, false, false, false); 
            
            $pdf->SetTextColor(37, 91, 49, 0);
            $pdf->setFormDefaultProp();
            // linea
            $pdf->SetFont('helvetica', '', 10, '', false);
            $pdf->TextField('nombre', 80, 5,[
                'alignment' => $align,
                'value'=>'Tu negocio'
            ], [], $fx, $docY + $fy);
            // linea
            $pdf->SetFont('helvetica', '', 10, '', false);
            $pdf->TextField('direccion', 80, 5,[
                'alignment' => $align,
                'value'=>'c/calle 1'
            ], [], $fx, $docY + $fy + 5);
            // linea
            $pdf->SetFont('helvetica', '', 10, '', false);
            $pdf->TextField('direccion2', 80, 5,[
                'alignment' => $align,
                'value'=>'Madrid'
            ], [], $fx, $docY + $fy + 10);
            // linea
            $pdf->SetFont('helvetica', '', 10, '', false);
            $pdf->TextField('tel', 80, 5,[
                'alignment' => $align,
                'value'=>'600 123 123 - direccion@email.com'
            ], [], $fx, $docY + $fy + 15);
            $docY += $h + 10;
        }
            
        return $pdf;
    }

    private function initPDF()
    {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(0, 0, 0, 0);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(false);
        $pdf->addPage();
        $pdf->setJPEGQuality(85);
        return $pdf;
    }
}
