<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class OnBoarding extends Model
{
	use Translatable;

	public $translatedAttributes = ['header_text','description','business_man_image_path','job_seeker_image_path'];


	protected $guarded = [];
}
