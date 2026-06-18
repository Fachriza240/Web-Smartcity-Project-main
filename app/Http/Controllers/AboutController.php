<?php

namespace App\Http\Controllers;

use App\Models\Partner;

class AboutController extends Controller
{
    public function userIndex()
    {
        $partners = Partner::published()->orderBy('urutan')->get();

        return view('halaman-user.about-user', compact('partners'));
    }

    public function dosenIndex()
    {
        $partners = Partner::published()->orderBy('urutan')->get();

        return view('halaman-dosen.about-dosen', compact('partners'));
    }

    public function partnersIndex()
    {
        $partners = Partner::published()->orderBy('urutan')->get();

        return view('halaman-user.mitra-user', compact('partners'));
    }

    public function partnersDosenIndex()
    {
        $partners = Partner::published()->orderBy('urutan')->get();

        return view('halaman-dosen.mitra-dosen', compact('partners'));
    }
}
