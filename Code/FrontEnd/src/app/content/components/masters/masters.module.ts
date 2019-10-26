import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { MasterDataComponent } from './master-data/master-data.component';
import { CategoriesComponent } from './categories/categories.component';
import { ReviewsComponent } from './reviews/reviews.component';
import { PackagesComponent } from './packages/packages.component';
import { RequestsComponent } from './requests/requests.component';
import { MasterListComponent } from './master-list/master-list.component';
import { PartialsModule } from '../../../content/partials/partials.module';
import {MatTableModule, MatIconModule, MatPaginatorModule, MatProgressSpinnerModule, MatSortModule} from '@angular/material';
import { NgSelectModule } from '@ng-select/ng-select';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { from } from 'rxjs';
/* Import Dropzone */
import { DropzoneModule, DropzoneConfigInterface, DROPZONE_CONFIG } from 'ngx-dropzone-wrapper';
import { dropzone } from '../../../core/config/dropzone';

const DEFAULT_DROPZONE_CONFIG: DropzoneConfigInterface = {
	// Change this to your upload POST address:
	acceptedFiles: 'image/*',
	createImageThumbnails: true
};


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
		NgbModule,
		DropzoneModule
	],
	declarations: [
	   MasterListComponent,
	   MasterDataComponent, CategoriesComponent, PackagesComponent, ReviewsComponent, RequestsComponent
	],
	exports: [ MasterListComponent,
		MasterDataComponent, CategoriesComponent,
		PackagesComponent, ReviewsComponent, RequestsComponent
	],
	providers: [
		{
			provide: DROPZONE_CONFIG,
			useValue: DEFAULT_DROPZONE_CONFIG
		}
	],
})

export class MastersModule { }
