<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Sentiment;

class PDFController extends Controller
{
    public function download($id)
    {
        $sentiment = Sentiment::findOrFail($id);

        // Pass sentiment data to a view
        $pdf = Pdf::loadView('pdf.sentiment', compact('sentiment'));

        // Return the generated PDF for download
        return $pdf->download('sentiment-analysis.pdf');
    }
}
