import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { MastersUsersListComponent } from './masters-users/masters-users-list.component';
import { PartialsModule } from '../../../content/partials/partials.module';
import {MatTableModule, MatIconModule, MatPaginatorModule, MatProgressSpinnerModule, MatSortModule} from '@angular/material';
import { NgSelectModule } from '@ng-select/ng-select';
import { NgbCollapseModule } from '@ng-bootstrap/ng-bootstrap';

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
		NgSelectModule,
		NgbCollapseModule
	],
	declarations: [
        MastersUsersListComponent
	],
	exports: [  MastersUsersListComponent],
    providers: [],
})

export class MastersUsersModule { }
