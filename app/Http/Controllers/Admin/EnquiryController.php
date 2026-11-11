<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactEnquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class EnquiryController extends Controller
{
    public function index(Request $r)
    {
        Gate::authorize('enquiries');
        $items = ContactEnquiry::query()
            ->when($r->status === 'unread', fn ($q) => $q->where('is_read', false))
            ->when($r->status === 'read', fn ($q) => $q->where('is_read', true))
            ->when($r->filled('q'), fn ($q) => $q->where(fn ($w) => $w->where('name', 'like', '%'.$r->q.'%')->orWhere('email', 'like', '%'.$r->q.'%')->orWhere('subject', 'like', '%'.$r->q.'%')))
            ->latest()->paginate(25)->withQueryString();

        return view('admin.enquiries.index', compact('items'));
    }

    public function show(ContactEnquiry $enquiry)
    {
        Gate::authorize('enquiries');
        $enquiry->update(['is_read' => true]);

        return view('admin.enquiries.show', ['e' => $enquiry]);
    }

    public function reply(Request $r, ContactEnquiry $enquiry)
    {
        Gate::authorize('enquiries');
        $data = $r->validate(['reply' => 'required|string|max:10000']);
        try {
            Mail::raw($data['reply'], fn ($m) => $m->to($enquiry->email, $enquiry->name)
                ->subject('Re: '.$enquiry->subject)
                ->replyTo(setting('contact_email', config('mail.from.address'))));
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'The reply could not be emailed. Check the mail settings in .env.');
        }
        $enquiry->update(['reply' => $data['reply'], 'replied_at' => now(), 'is_read' => true]);

        return back()->with('ok', 'Reply sent to '.$enquiry->email.'.');
    }

    public function toggle(ContactEnquiry $enquiry)
    {
        Gate::authorize('enquiries');
        $enquiry->update(['is_read' => ! $enquiry->is_read]);

        return back()->with('ok', 'Marked as '.($enquiry->is_read ? 'read' : 'unread').'.');
    }

    public function destroy(ContactEnquiry $enquiry)
    {
        Gate::authorize('enquiries');
        $enquiry->delete();

        return redirect()->route('admin.enquiries.index')->with('ok', 'Enquiry deleted.');
    }
}
