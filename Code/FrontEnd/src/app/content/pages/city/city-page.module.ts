import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { CommonModule } from '@angular/common';
import { CityPageComponent } from './city-page.component';
import { AddCityPageComponent } from './add-city/add-city-page.component';
import { CityListPageComponent } from './city-list/city-list-page.component';
import { CityModule } from '../../components/city/city.module';

const routes: Routes = [
	{
		path: '',
		component: CityPageComponent,
		children: [
			{
				path: '',
				component: CityListPageComponent,
			},
			{
				path: 'addCity',
				component: AddCityPageComponent,
			}

		]
	}
];


@NgModule({
    imports: [
		CommonModule,
		RouterModule.forChild(routes),
		CityModule
	],
	declarations: [
        CityPageComponent , AddCityPageComponent, CityListPageComponent
    ],
    providers: []
})

export class CityPageModule { }
