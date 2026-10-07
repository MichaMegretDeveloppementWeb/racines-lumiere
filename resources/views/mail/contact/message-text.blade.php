Racines & Lumière
Nouveau message via le site
Reçu le {{ now()->timezone('Europe/Paris')->format('d/m/Y à H:i') }}

Nom : {!! $contact->name !!}
Adresse e-mail : {!! $contact->email !!}
@if ($contact->phone)
Téléphone : {!! $contact->phone !!}
@endif

Message :
{!! $contact->message !!}

Pour répondre à {!! $contact->name !!}, utilisez la fonction « Répondre » de votre messagerie ou écrivez à {!! $contact->email !!}.
