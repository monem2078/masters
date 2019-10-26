import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { CommonModule } from '@angular/common';
import { MatersUsersPageComponent } from './masters-users-page.component';
import { MastersUsersListPageComponent } from './masters-users/masters-users-list-page.component';
import { MastersUsersModule } from '../../components/masters-users/masters-users.module';
import { Right } from './../../../core/models/enums/rights';

const routes: Routes = [
	{
		path: '',
		component: MatersUsersPageComponent,
		children: [
			{
				path: '',
				component: MastersUsersListPageComponent,
				data: {
					right: Right.USERSLIST
				}
			},
		
		

		]
	}
];


@NgModule({
    imports: [
		CommonModule,
		RouterModule.forChild(routes),
		MastersUsersModule

	],
	declarations: [
        MatersUsersPageComponent, MastersUsersListPageComponent
    ],
    providers: [],
})

export class MastersUsersPageModule { }
