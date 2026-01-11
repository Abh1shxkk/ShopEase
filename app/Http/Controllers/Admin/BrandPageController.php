<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BrandStorySection;
use App\Models\ProcessStep;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandPageController extends Controller
{
    // Story Sections
    public function storySections()
    {
        $sections = BrandStorySection::orderBy('sort_order')->get();
        return view('admin.brand.story-sections.index', compact('sections'));
    }

    public function createStorySection()
    {
        return view('admin.brand.story-sections.form');
    }

    public function storeStorySection(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'image_position' => 'required|in:left,right,full,background',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('brand', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');
        BrandStorySection::create($validated);

        return redirect()->route('admin.brand.story-sections')->with('success', 'Story section created successfully.');
    }

    public function editStorySection(BrandStorySection $section)
    {
        return view('admin.brand.story-sections.form', compact('section'));
    }

    public function updateStorySection(Request $request, BrandStorySection $section)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'image_position' => 'required|in:left,right,full,background',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($section->image) {
                Storage::disk('public')->delete($section->image);
            }
            $validated['image'] = $request->file('image')->store('brand', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');
        $section->update($validated);

        return redirect()->route('admin.brand.story-sections')->with('success', 'Story section updated successfully.');
    }

    public function destroyStorySection(BrandStorySection $section)
    {
        if ($section->image) {
            Storage::disk('public')->delete($section->image);
        }
        $section->delete();

        return redirect()->route('admin.brand.story-sections')->with('success', 'Story section deleted successfully.');
    }


    // Process Steps
    public function processSteps()
    {
        $steps = ProcessStep::orderBy('step_number')->get();
        return view('admin.brand.process-steps.index', compact('steps'));
    }

    public function createProcessStep()
    {
        return view('admin.brand.process-steps.form');
    }

    public function storeProcessStep(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:2048',
            'step_number' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('brand', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');
        ProcessStep::create($validated);

        return redirect()->route('admin.brand.process-steps')->with('success', 'Process step created successfully.');
    }

    public function editProcessStep(ProcessStep $step)
    {
        return view('admin.brand.process-steps.form', compact('step'));
    }

    public function updateProcessStep(Request $request, ProcessStep $step)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:2048',
            'step_number' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($step->image) {
                Storage::disk('public')->delete($step->image);
            }
            $validated['image'] = $request->file('image')->store('brand', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');
        $step->update($validated);

        return redirect()->route('admin.brand.process-steps')->with('success', 'Process step updated successfully.');
    }

    public function destroyProcessStep(ProcessStep $step)
    {
        if ($step->image) {
            Storage::disk('public')->delete($step->image);
        }
        $step->delete();

        return redirect()->route('admin.brand.process-steps')->with('success', 'Process step deleted successfully.');
    }

    // Team Members
    public function teamMembers()
    {
        $members = TeamMember::orderBy('sort_order')->get();
        return view('admin.brand.team.index', compact('members'));
    }

    public function createTeamMember()
    {
        return view('admin.brand.team.form');
    }

    public function storeTeamMember(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'linkedin' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('team', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');
        TeamMember::create($validated);

        return redirect()->route('admin.brand.team')->with('success', 'Team member added successfully.');
    }

    public function editTeamMember(TeamMember $member)
    {
        return view('admin.brand.team.form', compact('member'));
    }

    public function updateTeamMember(Request $request, TeamMember $member)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'linkedin' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($member->image) {
                Storage::disk('public')->delete($member->image);
            }
            $validated['image'] = $request->file('image')->store('team', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');
        $member->update($validated);

        return redirect()->route('admin.brand.team')->with('success', 'Team member updated successfully.');
    }

    public function destroyTeamMember(TeamMember $member)
    {
        if ($member->image) {
            Storage::disk('public')->delete($member->image);
        }
        $member->delete();

        return redirect()->route('admin.brand.team')->with('success', 'Team member deleted successfully.');
    }
}
