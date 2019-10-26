import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { CommonModule } from '@angular/common';
import { MastersPageComponent } from './masters-page.component';
import { MasterPageComponent } from './master/master-page.component';
import { MasterListPageComponent } from './master-list/master-list-page.component';
import { MastersModule } from '../../components/masters/masters.module';
import { Right } from './../../../core/models/enums/rights';
import { NgbTabsetModule } from '@ng-bootstrap/ng-bootstrap';
import { MatTabsModule } from '@angular/material';
import { PartialsModule } from '../../../content/partials/partials.module';
import { FormsModule } from '@angular/forms';

const routes: Routes = [
	{
		path: '',
		component: MastersPageComponent,
		children: [
			{
				path: '',
				component: MasterListPageComponent,
				data: {
					right: Right.USERSLIST
				}
			},
			{
				path: 'add',
				component: MasterPageComponent,
				data: {
					right: Right.USERSADD
				}
			},
			{
				path: 'edit/:id',
				component: MasterPageComponent,
				data: {
					right: Right.USERSEDIT
				}
			},

		]
	}
];


@NgModule({
    imports: [
		CommonModule,
		RouterModule.forChild(routes),
		MastersModule,
		NgbTabsetModule,
		MatTabsModule,
		PartialsModule,
		FormsModule,
	],
	declarations: [
        MastersPageComponent, MasterPageComponent, MasterListPageComponent
    ],
    providers: [],
})

export class MastersPageModule { }
