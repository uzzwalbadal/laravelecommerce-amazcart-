<?php

namespace App\Traits;

use PDF;

trait GeneratePdf
{
    function generate_pdf($view, $order) {
    	$pdf = PDF::loadView($view, compact('order'));
    	return $pdf->stream($order->order_number.'.pdf');
    }

    public function getPDF($view, $data, $title)
    {
        $pdf = PDF::loadView($view, $data);
        return $pdf->stream($title.'.pdf');
    }

}
