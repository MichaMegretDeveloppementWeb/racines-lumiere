@extends('layouts.web', [
    'title' => 'Mentions légales · Racines & Lumière',
    'description' => 'Mentions légales du site de Racines & Lumière, institut de beauté holistique à Sciez.',
    'breadcrumb' => 'Mentions légales',
])

@section('styles')
    @vite('resources/css/web/legal/notice/index.css')
@endsection

@section('content')
    <x-web.legal.document title="Mentions légales" introduction="Les informations relatives à l’édition, à l’hébergement et à l’utilisation de ce site.">
        <nav class="rl-legal-summary" aria-label="Sommaire des mentions légales">
            <ul>
                <li><a href="#editeur">L’éditeur</a></li>
                <li><a href="#hebergement">L’hébergement</a></li>
                <li><a href="#credits">La conception et les crédits</a></li>
                <li><a href="#reservation">Les réservations</a></li>
                <li><a href="#mediation">La médiation</a></li>
            </ul>
        </nav>
        <section id="editeur" class="rl-legal-section" aria-labelledby="publisher-title">
            <h2 id="publisher-title">L’éditeur du site</h2>
            <div>
                <p>Ce site présente Racines &amp; Lumière, maison de beauté holistique située à {{ $institute->city }}.</p>
                <dl>
                    <div><dt>Dénomination sociale</dt><dd>À compléter</dd></div>
                    <div><dt>Forme juridique et capital</dt><dd>À compléter</dd></div>
                    <div><dt>Siège social</dt><dd>À compléter</dd></div>
                    <div><dt>SIRET et immatriculation au RCS</dt><dd>À compléter</dd></div>
                    <div><dt>Numéro de TVA</dt><dd>À compléter, si applicable</dd></div>
                    <div><dt>Direction de la publication</dt><dd>À compléter</dd></div>
                    <div><dt>Adresse de l’institut</dt><dd>{{ $institute->street }}<br>{{ $institute->postalCode }} {{ $institute->city }}</dd></div>
                    <div><dt>E-mail</dt><dd><a href="mailto:{{ $institute->email }}">{{ $institute->email }}</a></dd></div>
                    <div><dt>Téléphone</dt><dd>{{ $institute->phone ?? 'À compléter' }}</dd></div>
                </dl>
            </div>
        </section>
        <section id="hebergement" class="rl-legal-section" aria-labelledby="hosting-title">
            <h2 id="hosting-title">L’hébergement</h2>
            <div>
                <p>Le site est hébergé par Hostinger.</p>
                <p>Hostinger International Ltd<br>61 Lordou Vironos Street<br>6023 Larnaca, Chypre</p>
                <p>Les coordonnées de l’hébergeur sont indiquées dans les <a href="https://www.hostinger.com/fr/legal/termes-universels-de-contrat-de-service" target="_blank" rel="noopener noreferrer">conditions de service de Hostinger<span class="sr-only"> (nouvel onglet)</span></a>.</p>
                <p>Le serveur du site est situé en France. Les sauvegardes de l’hébergement sont conservées en Lituanie.</p>
                <p>Contact : <a href="mailto:support@hostinger.com">support@hostinger.com</a>. L’assistance pour l’hébergement est disponible en ligne.</p>
                <p><a href="https://www.hostinger.com/fr/contact" target="_blank" rel="noopener noreferrer">Contacter Hostinger<span class="sr-only"> (nouvel onglet)</span></a></p>
                <p>Hostinger publie également le numéro <a href="tel:+37064503378">+370 645 03378</a> pour son <a href="https://www.hostinger.com/fr/legal/informations-registraire" target="_blank" rel="noopener noreferrer">bureau d’enregistrement des noms de domaine, Hostinger operations, UAB<span class="sr-only"> (nouvel onglet)</span></a>. Ce numéro ne constitue pas une assistance téléphonique pour l’hébergement.</p>
            </div>
        </section>
        <section id="credits" class="rl-legal-section" aria-labelledby="credits-title">
            <h2 id="credits-title">La conception<br>et les crédits</h2>
            <div>
                <p>Conception et développement : Micha Megret · Développement Web.</p>
                <p>Les textes, les photographies, les illustrations, l’identité visuelle et les éléments graphiques de ce site appartiennent à leurs titulaires respectifs. Leur reproduction ou leur réutilisation est soumise aux autorisations requises, sauf exceptions prévues par la loi.</p>
                <p>Les logos des marques partenaires restent la propriété de ces marques.</p>
                <p>La carte de localisation utilise des données <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener noreferrer">© OpenStreetMap contributors<span class="sr-only"> (nouvel onglet)</span></a>, sous licence ODbL.</p>
            </div>
        </section>
        <section id="reservation" class="rl-legal-section" aria-labelledby="booking-legal-title">
            <h2 id="booking-legal-title">Les réservations<br>et les liens externes</h2>
            <div>
                <p>La réservation des soins s’effectue sur <a href="{{ $institute->bookingUrl }}" target="_blank" rel="noopener noreferrer">Booksy<span class="sr-only"> (nouvel onglet)</span></a>. Les conditions applicables à la réservation et aux prestations sont à consulter avant de confirmer votre rendez-vous.</p>
                <p>Le site propose également des liens vers Instagram et les sites de professionnels indépendants. Ces services disposent de leurs propres conditions d’utilisation et politiques de confidentialité.</p>
            </div>
        </section>
        <section id="mediation" class="rl-legal-section" aria-labelledby="mediation-title">
            <h2 id="mediation-title">La médiation<br>de la consommation</h2>
            <div>
                <p>Pour toute réclamation, contactez d’abord l’institut à <a href="mailto:{{ $institute->email }}">{{ $institute->email }}</a>.</p>
                <p>Si le différend n’est pas résolu après une réclamation écrite, vous pouvez recourir gratuitement à un médiateur de la consommation, dans les conditions prévues par la réglementation.</p>
                <p class="rl-legal-pending">Nom, adresse et site du médiateur désigné par l’institut : à compléter.</p>
            </div>
        </section>
        <p class="rl-legal-related">Pour comprendre l’utilisation de vos données, consultez la <a href="{{ route('legal.privacy') }}">politique de confidentialité</a>.</p>
    </x-web.legal.document>
@endsection
