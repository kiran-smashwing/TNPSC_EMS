<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ExamTrunkBoxScan extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'exam_trunkbox_scans';

   // Specify the primary key if it's different from the default 'id'
   protected $primaryKey = 'id';

   // Enable timestamps (created_at and updated_at columns)
   public $timestamps = true;

   // Specify the fillable fields for mass assignment
   protected $fillable = [
       'exam_trunkbox_id', 
       'dept_off_scanned_at',
   ];

   // Define the relationships

   /**
    * Get the exam examTrunkBox that the scan belongs to.
    */
   public function examTrunkBox()
   {
       return $this->belongsTo(ExamTrunkBoxOTLData::class, 'exam_trunkbox_id');
   }

}
