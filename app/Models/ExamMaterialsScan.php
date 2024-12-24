<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ExamMaterialsScan extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'exam_materials_scans';

   // Specify the primary key if it's different from the default 'id'
   protected $primaryKey = 'id';

   // Enable timestamps (created_at and updated_at columns)
   public $timestamps = true;

   // Specify the fillable fields for mass assignment
   protected $fillable = [
       'exam_material_id', 
       'qr_code', 
       'district_scanned_at',
       'center_scanned_at', 
       'mobile_team_scanned_at', 
       'ci_scanned_at'
   ];

   // Define the relationships

   /**
    * Get the exam material that the scan belongs to.
    */
   public function examMaterial()
   {
       return $this->belongsTo(ExamMaterialsData::class, 'exam_material_id');
   }

}
