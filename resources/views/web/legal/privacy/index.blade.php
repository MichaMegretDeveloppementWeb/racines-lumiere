@extends('layouts.web', [
    'title' => 'Politique de confidentialité · Racines & Lumière',
    'description' => 'Comment Racines & Lumière traite les informations transmises par le formulaire de contact.',
    'breadcrumb' => 'Politique de confidentialité',
])

@section('styles')
    @vite('resources/css/web/legal/privacy/index.css')
@endsection

@section('content')
    <x-web.legal.document title="Politique de confidentialité" introduction="Ce que deviennent les informations que vous nous confiez, et comment exercer vos droits.">
        <nav class="rl-legal-summary" aria-label="Sommaire de la politique de confidentialité">
            <ul>
                <li><a href="#responsable">Le responsable</a></li>
                <li><a href="#formulaire">Vos messages</a></li>
                <li><a href="#conservation">La conservation</a></li>
                <li><a href="#cookies">Les cookies</a></li>
                <li><a href="#droits">Vos droits</a></li>
            </ul>
        </nav>
        <section id="responsable" class="rl-legal-section" aria-labelledby="controller-title">
            <h2 id="controller-title">Qui utilise<br>vos informations ?</h2>
            <div>
                <p>Racines &amp; Lumière utilise les informations transmises par le formulaire pour répondre à votre demande.</p>
                <p class="rl-legal-pending">Identité juridique et siège du responsable du traitement : à compléter.</p>
                <p>Pour toute question relative à vos données : <a href="mailto:{{ $institute->email }}">{{ $institute->email }}</a>.</p>
            </div>
        </section>
        <section id="formulaire" class="rl-legal-section" aria-labelledby="messages-title">
            <h2 id="messages-title">Lorsque vous<br>nous écrivez</h2>
            <div>
                <p>Le formulaire de contact recueille votre nom, votre adresse e-mail, votre message et, si vous le renseignez, votre numéro de téléphone. Le nom, l’e-mail et le message sont nécessaires pour envoyer votre demande ; le téléphone est facultatif.</p>
                <p>Ces informations servent à vous répondre et à assurer le suivi de notre échange. Le traitement repose sur votre consentement, exprimé en cochant la case prévue avant l’envoi. Vous pouvez le retirer à tout moment en nous écrivant, sans remettre en cause les traitements déjà effectués.</p>
                <p>Ce formulaire ne vous inscrit à aucune liste de prospection. Évitez d’y transmettre des informations de santé ou d’autres renseignements sensibles ; nous pourrons échanger directement si votre demande le nécessite.</p>
                <p>Les messages sont destinés aux personnes habilitées de l’institut. Hostinger assure l’hébergement du site. Le service Hostinger Email assure l’envoi des messages du formulaire et leur réception dans la boîte {{ $institute->email }}.</p>
                <p>Le serveur du site est situé en France et les sauvegardes de l’hébergement sont conservées en Lituanie. Cette localisation ne préjuge pas de celle des serveurs de messagerie ni des autres traitements effectués par le prestataire.</p>
                <p>Hostinger encadre ses traitements de données par un <a href="https://www.hostinger.com/fr/legal/dpa" target="_blank" rel="noopener noreferrer">accord de traitement des données<span class="sr-only"> (nouvel onglet)</span></a>. Cet accord prévoit notamment le recours aux clauses contractuelles types de la Commission européenne pour les transferts hors de l’Espace économique européen vers des pays ne bénéficiant pas d’une décision d’adéquation, lorsqu’elles sont applicables.</p>
                <p>Les informations sur les sous-traitants de Hostinger et leurs localisations figurent dans cet accord.</p>
            </div>
        </section>
        <section id="conservation" class="rl-legal-section" aria-labelledby="retention-title">
            <h2 id="retention-title">La conservation<br>et la sécurité</h2>
            <div>
                <p>Le contenu des messages n’est pas enregistré dans la base de données du site. Il est transmis par e-mail à l’institut et conservé dans sa messagerie pour le suivi de votre demande.</p>
                <p class="rl-legal-pending">Durée de conservation des échanges dans la messagerie : à compléter par l’institut.</p>
                <p>Pour limiter les envois abusifs, une empreinte de l’adresse IP est utilisée pendant une heure. Cette protection repose sur l’intérêt légitime de l’institut à assurer la sécurité de son formulaire.</p>
                <p>Des données techniques de connexion peuvent également figurer dans les journaux du serveur pour la sécurité et le diagnostic des incidents. Leur durée de conservation dépend des réglages de l’hébergement. Le contenu des messages n’est pas enregistré dans ces journaux.</p>
            </div>
        </section>
        <section id="cookies" class="rl-legal-section" aria-labelledby="cookies-title">
            <h2 id="cookies-title">Les cookies<br>et les services externes</h2>
            <div>
                <p>Ce site utilise uniquement des cookies techniques nécessaires à la session et à la protection du formulaire. Il n’intègre ni outil de mesure d’audience ni traceur publicitaire. Les polices et les images de la carte sont servies directement par le site.</p>
                <p>La durée de la session technique est de {{ config('session.lifetime') }} minutes d’inactivité. Vous pouvez supprimer ces cookies dans votre navigateur ; leur blocage peut empêcher l’envoi du formulaire.</p>
                <p>Les réservations sur Booksy, les liens Instagram, les itinéraires et les sites du Cercle de confiance ouvrent des services indépendants. Leurs propres politiques s’appliquent lorsque vous les consultez.</p>
            </div>
        </section>
        <section id="droits" class="rl-legal-section" aria-labelledby="rights-title">
            <h2 id="rights-title">Vos droits,<br>en pratique</h2>
            <div>
                <p>Selon le traitement concerné et les conditions légales applicables, vous pouvez demander l’accès à vos données, leur rectification, leur effacement, la limitation de leur utilisation ou leur portabilité. Vous pouvez aussi retirer votre consentement et vous opposer aux traitements fondés sur l’intérêt légitime.</p>
                <p>Écrivez à <a href="mailto:{{ $institute->email }}">{{ $institute->email }}</a> en précisant votre demande. Une réponse vous sera apportée en principe sous un mois ; une prolongation prévue par la réglementation vous sera expliquée si nécessaire.</p>
                <p>Si vous estimez que vos droits ne sont pas respectés, vous pouvez adresser une réclamation à la <a href="https://www.cnil.fr/fr/adresser-une-plainte" target="_blank" rel="noopener noreferrer">CNIL<span class="sr-only"> (nouvel onglet)</span></a>.</p>
            </div>
        </section>
        <p class="rl-legal-related">Retrouvez l’identification de l’éditeur et de l’hébergeur dans les <a href="{{ route('legal.notice') }}">mentions légales</a>.</p>
    </x-web.legal.document>
@endsection
