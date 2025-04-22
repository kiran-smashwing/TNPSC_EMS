<?php

namespace App\Jobs;


use App\Mail\CIConfirmationMail;
use App\Models\CIMeetingQrcode;
use App\Models\ExamConfirmedHalls;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Models\Currentexam;

class SendCIConfirmationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $examId;
    protected $districtCode;

    /**
     * Create a new job instance.
     */
    public function __construct($examId, $districtCode)
    {
        $this->examId = $examId;
        $this->districtCode = $districtCode;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $meetingDetails = CIMeetingQrcode::where('exam_id', $this->examId)->where('district_code', $this->districtCode)->first();


        $CIs = ExamConfirmedHalls::selectRaw('
        ci_id, 
        MIN(id) as id, 
        array_to_string(ARRAY_AGG(DISTINCT hall_code), \',\') as hall_codes,
        array_to_string(ARRAY_AGG(DISTINCT exam_date), \',\') as exam_dates,
        array_to_string(ARRAY_AGG(DISTINCT exam_session), \',\') as exam_sessions
    ')
            ->where('exam_id', $this->examId)
            ->where('district_code', $this->districtCode)
            ->with('chiefInvigilator')
            ->groupBy('ci_id')
            ->get();


        $exam = Currentexam::where('exam_main_no', $this->examId)->first();
        foreach ($CIs as $CI) {
            try {
                Mail::to($CI->chiefInvigilator->ci_email)->send(new CIConfirmationMail($exam, $CI, $meetingDetails));
            } catch (\Throwable $e) {
                \Log::error('Failed to send CI confirmation email', [
                    'email' => $CI->chiefInvigilator->ci_email,
                    'ci_id' => $CI->ci_id,
                    'error' => $e->getMessage()
                ]);
            }

            // Sleep for 1.85 seconds to prevent email rate limits
            usleep(1850000); // 1.85 seconds

        }
    }
}
