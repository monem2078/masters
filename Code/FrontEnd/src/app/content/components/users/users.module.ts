import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { UserComponent } from './user/user.component';
import { UserListComponent } from './user-list/user-list.component';
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
       UserComponent, UserListComponent
	],
	exports: [ UserComponent, UserListComponent],
    providers: [],
})

export class UsersModule { }
