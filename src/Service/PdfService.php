<?php
namespace App\Service;

use Symfony\Component\HttpKernel\KernelInterface;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
    private $kernel;
    private $templating;

    public function __construct(KernelInterface $kernel, \Twig_Environment $templating)
    {
        $this->kernel = $kernel;
        $this->templating = $templating;
    }

    public function savePdf(String $twigTemplate, String $filename, Array $content)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        // $html = $this->renderView('default/mypdf.html.twig', [
        //     'title' => "Test Me"
        // ]);
        $html = $this->templating->render($twigTemplate, [
            'title' => $content['title'],
            'name' => $content['name'],
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Store PDF Binary Data
        $output = $dompdf->output();
        
        // In this case, we want to write the file in the public directory
        $publicDirectory = $this->kernel->getProjectDir() . '/private/pdf/';
        // $publicDirectory = $this->get('kernel')->getProjectDir() . '/public/pdf';
        // e.g /var/www/project/public/mypdf.pdf
        $pdfFilepath =  $publicDirectory . $filename;

        // Write file to the desired path
        file_put_contents($pdfFilepath, $output);
    }
}