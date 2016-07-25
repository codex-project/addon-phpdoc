import {bootstrap} from '@angular/platform-browser-dynamic';
import {enableProdMode, Type} from '@angular/core';
import {disableDeprecatedForms, provideForms} from '@angular/forms';
import {AppComponent} from './app.component';
import {APP_ROUTER_PROVIDERS} from './app.routes';

declare var ENV: string;

if (ENV === 'production') {
    enableProdMode();
}

bootstrap(<Type> AppComponent, [
    disableDeprecatedForms(),
    provideForms(),
    APP_ROUTER_PROVIDERS
]);
