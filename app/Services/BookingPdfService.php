<?php

namespace App\Services;

use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class BookingPdfService
{
    /**
     * Generate or regenerate booking agreement PDF
     *
     * @param Booking $booking
     * @param bool $includeFullContacts Whether to include full contact information (after payment)
     * @return string Path to the generated PDF
     */
    public function generateAgreementPdf(Booking $booking, bool $includeFullContacts = false): string
    {
        // Load all necessary relationships
        $booking->load(['tourist.user', 'guide.user', 'guidePlan', 'addons']);

        // Generate PDF from view
        $pdf = Pdf::loadView('pdfs.booking-agreement', [
            'booking' => $booking,
            'includeFullContacts' => $includeFullContacts,
        ]);

        // Create filename
        $filename = 'booking-' . $booking->booking_number . '.pdf';
        $filePath = 'agreements/' . $filename;

        // Save PDF to storage
        Storage::disk('public')->put($filePath, $pdf->output());

        return $filePath;
    }

    /**
     * Regenerate PDF with full contact details after payment
     *
     * @param Booking $booking
     * @return string Path to the regenerated PDF
     */
    public function regeneratePdfAfterPayment(Booking $booking): string
    {
        // Delete old PDF if it exists
        if ($booking->agreement_pdf_path && Storage::disk('public')->exists($booking->agreement_pdf_path)) {
            Storage::disk('public')->delete($booking->agreement_pdf_path);
        }

        // Generate new PDF with full contact details
        $filePath = $this->generateAgreementPdf($booking, true);

        // Update booking with new PDF path
        $booking->update(['agreement_pdf_path' => $filePath]);

        return $filePath;
    }
}
