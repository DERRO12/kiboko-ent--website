<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ContactEnquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $r)
    {
        // Spam protection: hidden honeypot field + minimum fill time + rate limiting (route).
        if (filled($r->input('website')) || (time() - (int) $r->session()->get('contact_form_at', 0)) < 3) {
            return back()->with('contact_ok', 'Thank you. Your message has been received.');
        }

        $data = $r->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:190',
            'subject' => 'required|string|max:190',
            'message' => 'required|string|min:10|max:5000',
        ], [], ['name' => 'full name']);

        $enquiry = ContactEnquiry::create($data + ['ip_address' => $r->ip()]);

        try {
            $to = setting('notify_email') ?: setting('contact_email');
            if ($to) {
                Mail::raw(
                    "New website enquiry\n\nName: {$enquiry->name}\nEmail: {$enquiry->email}\nSubject: {$enquiry->subject}\n\n{$enquiry->message}\n\nManage: ".route('admin.enquiries.show', $enquiry),
                    fn ($m) => $m->to($to)->replyTo($enquiry->email, $enquiry->name)->subject('Website enquiry: '.$enquiry->subject)
                );
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect(url()->previous().'#contact-form')->with('contact_ok', 'Thank you. Your message has been sent and our team will get back to you shortly.');
    }
}
