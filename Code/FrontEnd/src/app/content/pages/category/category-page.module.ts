import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { CommonModule } from '@angular/common';
import { CategoryPageComponent } from './category-page.component';
import { AddCategoryPageComponent } from './add-category/add-category-page.component';
import { CategoryListPageComponent } from './category-list/category-list-page.component';
import { CategoryModule } from '../../components/category/category.module';

const routes: Routes = [
	{
		path: '',
		component: CategoryPageComponent,
		children: [
			{
				path: '',
				component: CategoryListPageComponent,
			},
			{
				path: 'addCategory',
				component: AddCategoryPageComponent,
			}

		]
	}
];


@NgModule({
    imports: [
		CommonModule,
		RouterModule.forChild(routes),
		CategoryModule
	],
	declarations: [
        CategoryPageComponent , AddCategoryPageComponent, CategoryListPageComponent
    ],
    providers: []
})

export class CategoryPageModule { }
