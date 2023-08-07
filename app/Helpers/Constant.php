<?php

namespace App\Helpers;

class Constant{
    // NEWS CONSTANTS
    const POST_TYPE=[
        'feature_post'=>0,
        'pinned_post'=>1,
        'general'=>2,
    ];
    const POST_STATUS=[
        'review'=>1,
        'correction_review'=>2,
        'published'=>3,
        'draft'=>4,
        'rejected'=>5,
    ];
    
}