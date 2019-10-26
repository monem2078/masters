
import { LayoutModule } from '../layout/layout.module';
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { PagesRoutingModule } from './pages-routing.module';
import { PagesComponent } from './pages.component';
import { PartialsModule } from '../partials/partials.module';
import { ProfileComponent } from './header/profile/profile.component';
import { CoreModule } from '../../core/core.module';
import { AngularEditorModule } from '@kolkov/angular-editor';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { ErrorPageComponent } from './snippets/error-page/error-page.component';
import { ActionEntityDialogComponent } from '../partials/content/general/modals/action-entity-dialog/action-entity-dialog.component';
import { DeleteEntityDialogComponent } from '../partials/content/general/modals/delete-entity-dialog/delete-entity-dialog.component';
import { ActionNotificationComponent} from '../partials/content/general/action-natification/action-notification.component';
import { MatProgressBarModule, MatDialogModule, MatSnackBarModule, MatIconModule, MatProgressSpinnerModule} from '@angular/material';
import { LayoutUtilsService } from '../../core/services/layout-utils.service';
import { AuthModule } from './auth/auth.module';
@NgModule({
	declarations: [
		PagesComponent,
		ProfileComponent,
		ErrorPageComponent,
		DeleteEntityDialogComponent,
		ActionNotificationComponent,
		ActionEntityDialogComponent
	],
	imports: [
		CommonModule,
		HttpClientModule,
		FormsModule,
		PagesRoutingModule,
		CoreModule,
		LayoutModule,
		PartialsModule,
		AngularEditorModule,
		MatProgressBarModule,
		MatDialogModule,
		MatSnackBarModule,
		MatIconModule,
		MatProgressSpinnerModule,
		AuthModule
	],

	entryComponents: [
		ActionNotificationComponent,
		DeleteEntityDialogComponent,
		ActionEntityDialogComponent,
	],

	providers: [LayoutUtilsService]
})
export class PagesModule {}
