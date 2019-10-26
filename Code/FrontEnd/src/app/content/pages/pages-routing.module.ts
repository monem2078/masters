
import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { PagesComponent } from './pages.component';
import { NgxPermissionsGuard } from 'ngx-permissions';
import { ProfileComponent } from './header/profile/profile.component';
import { ErrorPageComponent } from './snippets/error-page/error-page.component';
import { ResetPasswordComponent } from './auth/reset-password/reset-password.component';
import { Authorization } from '../../core/services/shared/authorization';

const routes: Routes = [
	{
		path: '',
		component: PagesComponent,
		// canActivateChild: [Authorization],
		children: [
			{
				path: '',
				component: ProfileComponent
			},
			{
				path: 'users',
				loadChildren: './users/users-page.module#UsersPageModule'
			},
			{
				path: 'roles',
				loadChildren: './roles/roles-page.module#RolesPageModule'
			},
			{
				path: 'masters',
				loadChildren: './masters/masters-page.module#MastersPageModule'
			}
			,
			{
				path: 'mastersUsers',
				loadChildren: './masters-users/masters-users-page.module#MastersUsersPageModule'
			}
			,
			{
				path: 'countries',
				loadChildren: './country/country-page.module#CountryPageModule'
			}
			,
			{
				path: 'cities',
				loadChildren: './city/city-page.module#CityPageModule'
			}
			,
			{
				path: 'categories',
				loadChildren: './category/category-page.module#CategoryPageModule'
			}
			
			
			

		]
	},
	{
		path: 'login',
		// canActivate: [NgxPermissionsGuard],
		loadChildren: './auth/auth.module#AuthModule',
		data: {
			permissions: {
				except: 'Admin'
			}
		},
	},
	{
		path: 'reset-password',
		component: ResetPasswordComponent

	},
	{
		path: '404',
		component: ErrorPageComponent
	},
	{
		path: 'error/:type',
		component: ErrorPageComponent
	},
];

@NgModule({
	imports: [RouterModule.forChild(routes)],
	exports: [RouterModule]
})
export class PagesRoutingModule {}
