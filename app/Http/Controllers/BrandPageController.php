<?php

namespace App\Http\Controllers;

use App\Models\BrandStorySection;
use App\Models\ProcessStep;
use App\Models\TeamMember;
use App\Models\EthosSection;
use App\Models\EthosValue;

class BrandPageController extends Controller
{
    public function story()
    {
        $sections = BrandStorySection::getActive();
        $team = TeamMember::getActive();
        
        return view('pages.our-story', compact('sections', 'team'));
    }

    public function process()
    {
        $steps = ProcessStep::getActive();
        
        return view('pages.our-process', compact('steps'));
    }

    public function ethos()
    {
        $sections = EthosSection::active()->ordered()->get();
        $values = EthosValue::active()->ordered()->get();
        
        return view('pages.our-ethos', compact('sections', 'values'));
    }
}
