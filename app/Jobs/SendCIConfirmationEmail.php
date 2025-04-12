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
        ->limit(1)
        ->groupBy('ci_id')  
        ->get();
    

        $exam = Currentexam::where('exam_main_no', $this->examId)->first();
        foreach ($CIs as $CI) {
            Mail::to($CI->chiefInvigilator->ci_email)->send(new CIConfirmationMail($exam, $CI, $meetingDetails));
        }
    }
}
