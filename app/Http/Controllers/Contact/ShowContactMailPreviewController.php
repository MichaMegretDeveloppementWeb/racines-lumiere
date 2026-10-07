<?php

declare(strict_types=1);

namespace App\Http\Controllers\Contact;

use App\Data\Contact\ContactMessageData;
use App\Http\Controllers\Controller;
use App\Mail\Contact\ContactMessageMail;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Response;

class ShowContactMailPreviewController extends Controller
{
    /** Temporary local preview using fictional contact details; remove after design approval. */
    public function __invoke(Application $app): Response
    {
        abort_unless($app->isLocal(), 404);

        $contact = new ContactMessageData(
            name: 'Camille Martin',
            email: 'camille@example.com',
            phone: '06 00 00 00 00',
            message: "Bonjour Aurore et Lorie,\n\nJe souhaite m’accorder un moment pour moi et découvrir votre maison. J’hésite entre un soin du visage et un rituel plus complet.\n\nPourriez-vous me conseiller selon les besoins de ma peau et le temps dont je dispose ?\n\nMerci pour votre aide et à bientôt,\nCamille",
        );

        return response((new ContactMessageMail($contact))->render())
            ->header('X-Robots-Tag', 'noindex, nofollow')
            ->header('Cache-Control', 'no-store');
    }
}
