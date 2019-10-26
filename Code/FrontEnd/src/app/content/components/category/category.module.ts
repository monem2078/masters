import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { AddCategoryComponent } from './add-category/add-category.component';
import { CategoryListComponent } from './category-list/category-list.component';
import { PartialsModule } from '../../../content/partials/partials.module';
import {MatTableModule, MatIconModule, MatPaginatorModule, MatProgressSpinnerModule, MatSortModule} from '@angular/material';
import { NgbCollapseModule } from '@ng-bootstrap/ng-bootstrap';
import { ImageCropperModule } from 'ngx-image-cropper';
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
		ImageCropperModule
	],
	declarations: [
		AddCategoryComponent, CategoryListComponent
	],
	exports: [ AddCategoryComponent, CategoryListComponent],
    providers: [],
})

export class CategoryModule { }
