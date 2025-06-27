<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\SeoSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SeoSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $seoSettings = SeoSetting::orderBy('page_identifier')->paginate(10);

        return Inertia::render('Admin/seo-setting/List', [
            'seoSettings' => $seoSettings,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageIdentifiers = collect([
            'welcome_page',
            'provider_listing_page',
            'blog_page'
        ])->mapWithKeys(fn ($item) => [
            $item => \Str::title(\Str::replace('_', ' ', $item))
        ]);
        
        return Inertia::render('Admin/seo-setting/Create', [
            'pageIdentifiers' => $pageIdentifiers->all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'page_identifier' => 'required|string|max:255|unique:seo_settings,page_identifier',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'keywords' => 'required|string|max:255',
            'canonical_url' => 'required|url|max:255',
        ]);

        SeoSetting::create($validatedData);

        return to_route('admin.seo-settings.index')->with('success', 'Settings added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageIdentifiers = collect([
            'welcome_page',
            'provider_listing_page',
            'blog_page'
        ])->mapWithKeys(fn ($item) => [
            $item => \Str::title(\Str::replace('_', ' ', $item))
        ]);

        $seoSetting = SeoSetting::find($id);

        return Inertia::render('Admin/seo-setting/Edit', [
            'pageIdentifiers' => $pageIdentifiers->all(),
            'seoSetting' => $seoSetting,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'page_identifier' => 'required|string|max:255|unique:seo_settings,page_identifier,' . $id,
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'keywords' => 'nullable|string|max:255',
            'canonical_url' => 'nullable|url|max:255',
        ]);

        $seoSetting = SeoSetting::find($id);

        $seoSetting->update($validatedData);

        return to_route('admin.seo-settings.index')->with('success', 'Settings added successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $seoSetting = SeoSetting::find($id);

        $seoSetting->delete();

        return to_route('admin.seo-settings.index')->with('success', 'Settings removed successfully');
    }
}
