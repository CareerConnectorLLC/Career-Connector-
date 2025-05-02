<?php

namespace App\Jobs\Emails;

use App\Mail\InquiryReplyMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendInquiryReplyEmail implements ShouldQueue
{
    use Queueable;

    protected $to;
    protected $name;
    protected $reply;

    /**
     * Create a new job instance.
     */
    public function __construct(string $to, string $name, string $reply)
    {
        $this->to = $to;
        $this->name = $name;
        $this->reply = $reply;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->to)->send(new InquiryReplyMail($this->name, $this->reply));
    }
}
