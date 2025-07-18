<?php

namespace App\Http\Controllers\Frontend\Provider;

use Inertia\Inertia;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SocialLinkController extends Controller
{
    public function index(Request $request)
    {
        $socialLinks = $request->user()->providerSocialLinks;
        
        return Inertia::render('Frontend/provider/MySocialLinks', [
            'social_links' => $socialLinks,
        ]);
    }

    public function store(Request $request)
    {
        $serviceDomains = [
            'Facebook' => 'facebook.com',
            'Instagram' => 'instagram.com',
            'LinkedIn' => 'linkedin.com',
            'X' => ['twitter.com', 'x.com'],
            'Youtube' => ['youtube.com', 'youtu.be'],
        ];

        $validated = $request->validate([
            'links' => ['required', 'array', 'min:1'],
            'links.*' => ['required', 'array'],
            'links.*.name' => ['required', 'string', Rule::in(array_keys($serviceDomains))],
            'links.*.url' => [
                'required',
                'url:http,https',
                'distinct',
                function ($attribute, $value, $fail) use ($serviceDomains, $request) {
                    // $attribute is 'links.0.url', so we extract the index.
                    $index = explode('.', $attribute)[1];
                    $name = $request->input("links.{$index}.name");

                    if (!isset($serviceDomains[$name])) {
                        // This is a fallback; Rule::in should prevent this.
                        $fail("The social network {$name} is not supported.");
                        return;
                    }

                    $expectedDomains = (array) $serviceDomains[$name];
                    $urlHost = strtolower(parse_url($value, PHP_URL_HOST) ?? '');

                    if (!$urlHost) {
                        $fail("The URL for {$name} is invalid.");
                        return;
                    }

                    // Remove 'www.' for simpler comparison (e.g., www.facebook.com -> facebook.com)
                    $urlHost = preg_replace('/^www\./', '', $urlHost);

                    $matchFound = false;
                    foreach ($expectedDomains as $expectedDomain) {
                        // The URL host must be the exact domain or a subdomain of it.
                        if ($urlHost === $expectedDomain || str_ends_with($urlHost, '.' . $expectedDomain)) {
                            $matchFound = true;
                            break;
                        }
                    }

                    if (!$matchFound) {
                        $fail("The URL for {$name} does not appear to be a valid {$name} link.");
                    }
                },
            ],
        ], [
            'links.min' => 'Please provide at least one social link.',
            'links.*.url.distinct' => 'Each social link URL must be unique.',
            'links.*.url.url' => 'The social link must be a valid URL starting with http or https.',
        ]);

        // Clear existing links and add the new validated ones
        $request->user()->providerSocialLinks()->delete();
        foreach ($validated['links'] as $link) {
            $request->user()->providerSocialLinks()->create($link);
        }

        return back()->with('success', 'Social links updated successfully!');
    }
}
