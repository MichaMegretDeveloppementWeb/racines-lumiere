// Alpine and its plugins ship without type declarations.
declare module 'alpinejs';
declare module '@alpinejs/collapse';

declare module '@livewire' {
    export const Alpine: typeof import('alpinejs').default;
    export const Livewire: { start(): void };
}
