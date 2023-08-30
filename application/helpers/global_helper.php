<?php

	if (!function_exists('getMoneyReceiptPdf')) {
		function getMoneyReceiptPdf($id, $action = false)
		{
			$CI = &get_instance();
			$money_receipt_path = 'assets/media/exhibitor_money_receipt/';

			$data = [];
			// get the specific information based on ID
			$data['paymentInfo'] = $paymentInfo = $CI->db->select('tran_id, amount')->get_where('exhibitor_payment_details', ['exhibitor_id' => $id])->row();
			$data['exhibutor'] = $exhibutor = $CI->db->get_where('exhibitors', ['exhibitor_id' => $id])->row();
			$data['serial_no'] = getMoneyReceiptNumber($exhibutor->payment_date);
			$data['paid_amount'] = !empty($paymentInfo->amount) ? $paymentInfo->amount : $exhibutor->cal_amount;
			$data['basis_pdf_logo'] = FCPATH . 'assets/images/basis_pdf_logo_new.jpg';

			include_once APPPATH . '/third_party/mpdf/vendor/autoload.php';
			$pdf = new \Mpdf\Mpdf([
				'mode' => 'utf-8',
				'format' => 'A4-L',
				'orientation' => 'L',
				'format' => [135, 270]
			]);

			$pdfHtml = $CI->load->view('admin/exhibitor/pdf/money_receipt_pdf', $data, TRUE);
			$pdfStylesheet = file_get_contents(FCPATH . 'assets/admin/css/money-receipt-pdf.css');

			$pdf->SetTitle($exhibutor->invoice_no);
			$pdf->SetSubject($exhibutor->invoice_no);
			$pdf->AddPage('', '', '', '', 0, 0, 0, 0, 0, 0);
			$pdf->WriteHTML($pdfStylesheet, 1);
			$pdf->WriteHTML($pdfHtml, 2);

			$pdfName = 'Money-Receipt-' . $exhibutor->invoice_no . '.pdf';
			$fileName = FCPATH . $money_receipt_path . $pdfName;

			if ($action == 'download') {
				$pdf->Output($pdfName, 'D');
			} else if ($action == 'view') {
				$pdf->Output($money_receipt_path . $pdfName, 'I');
			} else if ($action == 'url') {
				$pdf->Output($fileName, 'F');
				return base_url($money_receipt_path . $pdfName);
			} else {
				$pdf->Output($fileName, 'F');
				return FCPATH . $money_receipt_path . $pdfName;;
			}
		}

	}





