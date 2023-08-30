<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {


	public function index()
	{
		$action = 'view';

		$pdf_path = 'assets/pdf/';
		include_once APPPATH . '/third_party/mpdf/vendor/autoload.php';
		$pdf = new \Mpdf\Mpdf([
			'mode' => 'utf-8',
			'format' => 'A4-L',
			'orientation' => 'L',
			'format' => [300, 220] //Size in MM
		]);

		$data = [];
		$data['banner'] = FCPATH . 'assets/images/banner.jpg';

		$pdfHtml = $this->load->view('pdf', $data, TRUE);
		$pdfStylesheet = file_get_contents(FCPATH . 'assets/css/pdf.css');

		$pdf->SetTitle("PDF Title");
		$pdf->SetSubject('PDF');
		// $pdf->AddPage('', '', '', '', 0, 0, 0, 0, 0, 0);
		$pdf->WriteHTML($pdfStylesheet, 1);
		$pdf->WriteHTML($pdfHtml, 2);

		$pdfName = 'my_pdf.pdf';
		$fileName = FCPATH . $pdf_path . $pdfName;

		if ($action == 'download') {
			$pdf->Output($pdfName, 'D');
		} else if ($action == 'view') {
			$pdf->Output($pdf_path . $pdfName, 'I');
		} else if ($action == 'url') {
			$pdf->Output($fileName, 'F');
			return base_url($pdf_path . $pdfName);
		} else {
			$pdf->Output($fileName, 'F');
			return FCPATH . $pdf_path . $pdfName;;
		}
	}
}
