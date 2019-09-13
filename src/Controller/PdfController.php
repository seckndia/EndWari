<?php

namespace App\Controller;
use Dompdf\Dompdf;
use Dompdf\Options;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PdfController extends AbstractController
{
    /**
     * @Route("/pdf", name="pdf")
     */
    public function index()
    {
  // Configure Dompdf according to your needs
  $pdfOptions = new Options();
  $pdfOptions->set('defaultFont', 'Arial');
  
  // Instantiate Dompdf with our options
  $dompdf = new Dompdf($pdfOptions);
  
  // Retrieve the HTML generated in our twig file
  $html = $this->renderView('pdf/index.html.twig', [
      'title' => "Welcome to our PDF Test"
  ]);
  
  // Load HTML to Dompdf
  $dompdf->loadHtml($html);
  
  // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
  $dompdf->setPaper('A4', 'portrait');

  // Render the HTML as PDF
  $dompdf->render();

  // Output the generated PDF to Browser (inline view)
  $dompdf->stream("contrat.pdf", [
      "Attachment" => false
  ]);

        // return $this->render('pdf/index.html.twig', [
        //     'controller_name' => 'PdfController',
        // ]);
    }
}
