import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { CommonModule } from '@angular/common';
import { CountryPageComponent } from './country-page.component';
import { AddCountryPageComponent } from './add-country/add-country-page.component';
import { CountryListPageComponent } from './country-list/country-list-page.component';
import { CountryModule } from '../../components/country/country.module';

const routes: Routes = [
	{
		path: '',
		component: CountryPageComponent,
		children: [
			{
				path: '',
				component: CountryListPageComponent,
			},
			{
				path: 'addCountry',
				component: AddCountryPageComponent,
			}

		]
	}
];


@NgModule({
    imports: [
		CommonModule,
		RouterModule.forChild(routes),
		CountryModule
	],
	declarations: [
        CountryPageComponent , AddCountryPageComponent, CountryListPageComponent
    ],
    providers: []
})

export class CountryPageModule { }
