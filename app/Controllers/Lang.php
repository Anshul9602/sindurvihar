<?php

namespace App\Controllers;

class Lang extends BaseController
{
    public function switch()
    {
        $language = (string) ($this->request->getPost('language') ?? 'hi');

        $supported = config('App')->supportedLocales ?? ['hi'];
        if (! in_array($language, $supported, true)) {
            $language = config('App')->defaultLocale ?? 'hi';
        }

        // Store in session
        session()->set('language', $language);

        // Apply to current request
        service('request')->setLocale($language);

        // Redirect back to previous page
        $referer = $this->request->getServer('HTTP_REFERER') ?? site_url('/');
        return redirect()->to($referer);
    }
}


