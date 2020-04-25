<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

// use Symfony\Component\HttpKernel\KernelInterface;


// use Symfony\Component\Mailer\MailerInterface;
// use Symfony\Component\Mime\Email;

// use Dompdf\Dompdf;
// use Dompdf\Options;

use App\Service\PdfService;

class TestFeatureController extends AbstractController
{
    /**
     * @Route("/test/feature/contoller", name="test_feature_contoller")
     */
    public function index()
    {
        return $this->render('test_feature/index.html.twig', [
            'controller_name' => 'TestFeatureController',
        ]);
    }

    // /**
    //  * @Route("/test/feature/email", name="test")
    //  */
    // public function test(\Swift_Mailer $mailer, KernelInterface $kernel){
    //     $bitcoinWallet = $_ENV['BITCOIN_WALLET'];
    //     $app_email = $_ENV['APP_EMAIL'];

    //     $publicDirectory = $kernel->getProjectDir() . '\private\pdf';

    //     $message = (new \Swift_Message('Bitcoin Payment Instruction'))
    //         ->setFrom($app_email)
    //         ->setTo('h.farokhmehr@gmail.com')
    //         ->setBody(
    //             $this->renderView(
    //                 'test_feature_contoller/email.html.twig',
    //                 []
    //             ),
    //             'text/html'
    //         )
    //         ->attach(\Swift_Attachment::fromPath($publicDirectory . '\mypdfsaved.pdf'));

    //     echo $mailer->send($message);

    //     $this->addFlash(
    //         'success',
    //         'This is a test!'
    //     );          
        
    //     return $this->render('dashboard/test.html.twig', [
    //         'controller_name' => 'DashboardController',
    //     ]);
    // }    

    // /**
    //  * @Route("/test/feature/pdf", name="test_feature_pdf")
    //  */
    // public function pdf()
    // {
    //     // Configure Dompdf according to your needs
    //     $pdfOptions = new Options();
    //     $pdfOptions->set('defaultFont', 'Arial');
        
    //     // Instantiate Dompdf with our options
    //     $dompdf = new Dompdf($pdfOptions);
        
    //     // Retrieve the HTML generated in our twig file
    //     $html = $this->renderView('test_feature_contoller/pdf.html.twig', [
    //         'title' => "Welcome to our PDF Test"
    //     ]);
        
    //     // Load HTML to Dompdf
    //     $dompdf->loadHtml($html);
        
    //     // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
    //     $dompdf->setPaper('A4', 'portrait');

    //     // Render the HTML as PDF
    //     $dompdf->render();

    //     // Output the generated PDF to Browser (force download)
    //     $dompdf->stream("mypdf.pdf", [
    //         "Attachment" => true
    //     ]);        

    //     return $this->render('test_feature/index.html.twig', [
    //         'controller_name' => 'TestFeatureController',
    //     ]);
    // }
    
    // /**
    //  * @Route("/test/feature/pdfsaved", name="test_feature_pdf")
    //  */    
    // public function savePdf(KernelInterface $kernel)
    // {
    //     // Configure Dompdf according to your needs
    //     $pdfOptions = new Options();
    //     $pdfOptions->set('defaultFont', 'Arial');
        
    //     // Instantiate Dompdf with our options
    //     $dompdf = new Dompdf($pdfOptions);
        
    //     // Retrieve the HTML generated in our twig file
    //     $html = $this->renderView('test_feature/pdf.html.twig', [
    //         'title' => "Welcome to our PDF Test saved.",
    //     ]);
        
    //     // Load HTML to Dompdf
    //     $dompdf->loadHtml($html);
        
    //     // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
    //     $dompdf->setPaper('A4', 'portrait');

    //     // Render the HTML as PDF
    //     $dompdf->render();

    //     // Store PDF Binary Data
    //     $output = $dompdf->output();
        
    //     // In this case, we want to write the file in the public directory
    //     $publicDirectory = $kernel->getProjectDir() . '/private/pdf';
    //     // $publicDirectory = $this->get('kernel')->getProjectDir() . '/public/pdf';
    //     // e.g /var/www/project/public/mypdf.pdf
    //     $pdfFilepath =  $publicDirectory . '/mypdfsaved.pdf';
        
    //     // Write file to the desired path
    //     file_put_contents($pdfFilepath, $output);

    //     return $this->render('test_feature/index.html.twig', [
    //         'controller_name' => 'TestFeatureController',
    //     ]);        
    // }

    /**
     * @Route("/test/feature/pdfusingservice", name="test_feature_pdf_using_service")
     */
    public function pdfUsingService(PdfService $pdfService)
    {
        $pdfService->savePdf('test_feature\pdf.html.twig', 'hello2.pdf', array('title' => 'Bitcoin Test', 'name' => 'Morteza'));
        return $this->render('test_feature/index.html.twig', [
            'controller_name' => 'TestFeatureController',
        ]);
    }
}
