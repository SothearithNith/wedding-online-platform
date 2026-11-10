<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Support\TemplateRegistry;

class PublicWeddingController extends Controller
{
    public function show(string $slug)
    {
        $invitation = Invitation::with(Invitation::RELATIONS)->where('slug', $slug)->first();

        if (! $invitation) {
            $this->notFound("No wedding has the link [{$slug}].");
        }

        // Guests only see published invitations. The owner can also preview their draft.
        $isOwner = auth()->check() && auth()->id() === $invitation->user_id;

        if (! $invitation->isPublished() && ! $isOwner) {
            $this->notFound("Wedding [{$slug}] exists but is not published (or you are not logged in as its owner).");
        }

        $templateKey = $invitation->template_key;

        // Same rendering path as the template preview and /i/{invitation} routes.
        $view = TemplateRegistry::viewFor($templateKey);

        if (! view()->exists($view)) {
            $this->notFound("Template view [{$view}] for template [{$templateKey}] does not exist.");
        }

        return view($view, [
            'data'       => $this->payload($invitation->toTemplateData(true)),
            'rsvpUrl'    => null,
            'wedding'    => $invitation,   // kept for templates that still read $wedding
            'invitation' => $invitation,
        ]);
    }

    /**
     * Fill the blanks (default images etc.) the same way the other routes do.
     * withDefaults() is called with one argument first; if your version requires the
     * second flag, it falls back to false (real data, not sample data).
     */
    private function payload(array $data): array
    {
        try {
            return TemplateRegistry::withDefaults($data);
        } catch (\ArgumentCountError) {
            return TemplateRegistry::withDefaults($data, false);
        }
    }

    /** 404 that says WHY while APP_DEBUG=true; a plain 404 in production. */
    private function notFound(string $why): never
    {
        logger()->warning($why);

        abort(404, config('app.debug') ? $why : '');
    }
}
