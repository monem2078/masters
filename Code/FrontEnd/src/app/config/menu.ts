// tslint:disable-next-line:no-shadowed-variable
import { ConfigModel } from '../core/interfaces/config';

// tslint:disable-next-line:no-shadowed-variable
export class MenuConfig implements ConfigModel {
	public config: any = {};

	constructor() {
		this.config = {
			header: {
				self: {},
				items: [

				]
			},
			aside: {
				self: {},
				items: [
					{
						title: 'Roles',
						desc: 'Some description goes here',
						root: true,
						icon: 'flaticon-line-graph',
						page: '/roles',
						translate: 'MENU.ROLES'
					},
					{
						title: 'Users',
						desc: 'Some description goes here',
						root: true,
						icon: 'flaticon-line-graph',
						page: '/users',
						translate: 'MENU.USERS'
					},
					{
						title: 'Masters',
						desc: 'Some description goes here',
						root: true,
						icon: 'flaticon-line-graph',
						page: '/masters'
					},
					{
						title: 'Masters Users',
						desc: 'Some description goes here',
						root: true,
						icon: 'la la-users',
						page: '/mastersUsers',
						translate: 'MENU.USERS'
					},
					{
						title: 'Countries',
						desc: 'Some description goes here',
						root: true,
						icon: 'la la-map-marker',
						page: '/countries',
					},
					{
						title: 'cities',
						desc: 'Some description goes here',
						root: true,
						icon: 'la la-map-marker',
						page: '/cities',
					},
					{
						title: 'categories',
						desc: 'Some description goes here',
						root: true,
						icon: 'la la-list',
						page: '/categories',
					},
					
					// {section: 'Components'},
					// {
					// 	title: 'Google Material',
					// 	root: true,
					// 	bullet: 'dot',
					// 	icon: 'flaticon-interface-7',
					// 	submenu: [
					// 		{
					// 			title: 'Form Controls',
					// 			bullet: 'dot',
					// 			submenu: [
					// 				{
					// 					title: 'Auto Complete',
					// 					page: '/material/form-controls/autocomplete'
					// 				}
					// 			]
					// 		},
					// 		{
					// 			title: 'Navigation',
					// 			bullet: 'dot',
					// 			submenu: [
					// 				{
					// 					title: 'Menu',
					// 					page: '/material/navigation/menu'
					// 				}
					// 			]
					// 		}
					// 	]
					// }
				]
			}
		};
	}
}
