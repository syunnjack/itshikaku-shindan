<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\View\View;

class CertificationController extends Controller
{
    public function show(string $certification): View
    {
        $certifications = collect(config('certifications'))->sortBy('rank')->all();

        abort_unless(array_key_exists($certification, $certifications), 404);

        $currentCertification = $certifications[$certification];
        $questionCount = Question::where('certification_slug', $certification)->count();
        $sampleQuestions = Question::where('certification_slug', $certification)
            ->orderBy('sort_order')
            ->limit(3)
            ->get();

        return view('certifications.show', compact(
            'certifications',
            'certification',
            'currentCertification',
            'questionCount',
            'sampleQuestions'
        ));
    }
}
