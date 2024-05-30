<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingPageEnquiryModel extends Model
{
    
    protected $table = 'landing_page_enquiry';
    protected $fillable = ['product', 'options', 'name', 'phone_number', 'email', 'location', 'no_of_pages', 'no_of_copies', 'requirement_specification'];
    protected $guarded = ['id'];

}