import { RouterModule } from '@angular/router';
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { ScrollTopComponent } from './layout/scroll-top/scroll-top.component';
import { TooltipsComponent } from './layout/tooltips/tooltips.component';
import { CoreModule } from '../../core/core.module';
import { PerfectScrollbarModule } from 'ngx-perfect-scrollbar';
import { PortletModule } from './content/general/portlet/portlet.module';
import { SpinnerButtonModule } from './content/general/spinner-button/spinner-button.module';
import { DataTableComponent } from './content/widgets/general/data-table/data-table.component';


import {
	MatInputModule,
    MatSortModule,
    MatProgressSpinnerModule,
    MatTableModule,
    MatPaginatorModule,
    MatSelectModule,
    MatProgressBarModule,
    MatButtonModule,
    MatCheckboxModule,
    MatIconModule,
    MatTooltipModule} from '@angular/material';

@NgModule({
	declarations: [
		ScrollTopComponent,
		TooltipsComponent,
		DataTableComponent,
	],
	exports: [
		ScrollTopComponent,
		TooltipsComponent,
		DataTableComponent,

		PortletModule,
		SpinnerButtonModule
	],
	imports: [
		CommonModule,
		RouterModule,
		NgbModule,
		PerfectScrollbarModule,
		CoreModule,
		PortletModule,
		SpinnerButtonModule,
		MatSortModule,
		MatProgressSpinnerModule,
		MatTableModule,
		MatPaginatorModule,
		MatSelectModule,
		MatProgressBarModule,
		MatButtonModule,
		MatCheckboxModule,
		MatIconModule,
		MatTooltipModule
	]
})
export class PartialsModule {}
