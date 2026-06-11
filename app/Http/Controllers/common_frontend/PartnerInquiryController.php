<?php

namespace App\Http\Controllers\common_frontend;

use App\Http\Controllers\Controller;
use App\Mail\PartnerInquiryAcknowledgementMail;
use App\Mail\PartnerInquiryAdminMail;
use App\Models\PartnerInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PartnerInquiryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_name' => ['required', 'string', 'max:255'],
            'organization_type' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:2000'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'contact_name' => ['required', 'string', 'max:255'],
            'designation' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:100'],
            'partnership_interests' => ['required', 'array', 'min:1'],
            'partnership_interests.*' => ['string', 'max:255'],
            'partnership_idea' => ['required', 'string', 'max:5000'],
            'collaboration_types' => ['required', 'array', 'min:1'],
            'collaboration_types.*' => ['string', 'max:255'],
            'target_sector' => ['nullable', 'string', 'max:255'],
            'geographic_area' => ['nullable', 'string', 'max:255'],
            'expected_timeline' => ['nullable', 'string', 'max:255'],
            'document' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
            'accuracy_consent' => ['accepted'],
            'processing_consent' => ['accepted'],
        ]);

        $documentUploadId = null;
        if ($request->hasFile('document')) {
            $documentUploadId = uploads($request->file('document'));
        }
        unset($validated['document']);

        $inquiry = PartnerInquiry::create(array_merge($validated, [
            'document_upload_id' => $documentUploadId,
            'accuracy_consent' => true,
            'processing_consent' => true,
            'read_status' => false,
        ]));

        $this->sendEmails($inquiry);

        return response()->json([
            'title' => 'Thank you for your partnership interest. Your inquiry has been submitted successfully. OSHE Foundation will contact you soon.',
            'type' => 'success',
        ]);
    }

    protected function sendEmails(PartnerInquiry $inquiry): void
    {
        try {
            if (function_exists('setMailConfig')) {
                setMailConfig();
            }

            $adminEmail = settings('partner_inquiry_admin_email', 409);
            if ($adminEmail === 'Partner Inquiry Admin Email' || blank($adminEmail)) {
                $adminEmail = settings('app_email', 9);
            }

            if (filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
                Mail::mailer('dynamic')->to($adminEmail)->send(new PartnerInquiryAdminMail($inquiry));
            }

            if ((string) settings('partner_inquiry_acknowledgement_status', 409) === '1') {
                Mail::mailer('dynamic')->to($inquiry->email)->send(new PartnerInquiryAcknowledgementMail($inquiry));
            }
        } catch (\Throwable $e) {
            Log::warning('Partner inquiry email notification failed.', [
                'partner_inquiry_id' => $inquiry->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
