import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { AddCityComponent } from './add-city/add-city.component';
import { CityListComponent } from './city-list/city-list.component';
import { PartialsModule } from '../../../content/partials/partials.module';
import {MatTableModule, MatIconModule, MatPaginatorModule, MatProgressSpinnerModule, MatSortModule} from '@angular/material';
import { NgbCollapseModule } from '@ng-bootstrap/ng-bootstrap';
import {MatTooltipModule} from '@angular/material/tooltip';
import { NgSelectModule } from '@ng-select/ng-select';
@NgModule({
    imports: [
		CommonModule,
		PartialsModule,
		RouterModule,
		MatTableModule,
		MatIconModule,
		MatPaginatorModule,
		MatProgressSpinnerModule,
		MatSortModule,
		FormsModule,
		NgbCollapseModule,
		MatTooltipModule,
		NgSelectModule
	],
	declarations: [
		AddCityComponent, CityListComponent
	],
	exports: [ AddCityComponent, CityListComponent],
    providers: [],
})

export class CityModule { }
