import {
	Component,
	OnInit,
	Output,
	Input,
	ViewChild,
	OnDestroy,
	ChangeDetectionStrategy,
	ChangeDetectorRef,
	HostBinding
} from '@angular/core';
import { AuthenticationService } from '../../../../core/auth/authentication.service';
import { Router , ActivatedRoute} from '@angular/router';
import { Subject } from 'rxjs';
import { AuthNoticeService } from '../../../../core/auth/auth-notice.service';
import { NgForm } from '@angular/forms';
import * as objectPath from 'object-path';
import { TranslateService } from '@ngx-translate/core';
import { SpinnerButtonOptions } from '../../../partials/content/general/spinner-button/button-options.interface';
import { LayoutConfigService } from '../../../../core/services/layout-config.service';
import { LayoutConfig } from '../../../../config/layout';
import { ResetPassword } from '../../../../core/models/reset-password';
@Component({
	selector: 'm-reset-password',
	templateUrl: './reset-password.component.html',
	styleUrls: ['./reset-password.component.scss'],
	changeDetection: ChangeDetectionStrategy.Default
})
export class ResetPasswordComponent implements OnInit, OnDestroy {
	public model: ResetPassword = { password: '', password_confirmation: '' , token: '' };
	@Output() actionChange = new Subject<string>();
	public loading = false;
	resetCompleted: boolean = false;
	@Input() action: string;

	@ViewChild('f') f: NgForm;
	errors: any = [];
	@HostBinding('id') id = 'm_login';
	@HostBinding('class')

	classses: any = 'm-grid m-grid--hor m-grid--root m-page';
	today: number = Date.now();


	spinner: SpinnerButtonOptions = {
		active: false,
		spinnerSize: 18,
		raised: true,
		buttonColor: 'primary',
		spinnerColor: 'accent',
		fullWidth: false
	};

	constructor(
		private authService: AuthenticationService,
		private router: Router,
		public authNoticeService: AuthNoticeService,
		private translate: TranslateService,
		private cdr: ChangeDetectorRef,
		private route: ActivatedRoute,
		private layoutConfigService: LayoutConfigService,

	) { }

	submit() {
		this.spinner.active = true;
		if (this.validate(this.f)) {
			this.authService.resetPassword(this.model).subscribe(response => {
				if (typeof response !== 'undefined') {
					this.authNoticeService.setNotice(response.message, 'success');
					this.resetCompleted = true;
				}
				this.spinner.active = false;
			},
				error => {
				this.authNoticeService.setNotice(error.error.error, 'error');
				this.spinner.active = false;

			});
		}
	}

	ngOnInit(): void {
		// set login layout to blank
		this.layoutConfigService.setModel(new LayoutConfig({ content: { skin: '' } }), true);

		// demo message to show
		if (!this.authNoticeService.onNoticeChanged$.getValue()) {
			this.authNoticeService.setNotice(null);
		}

		if (this.route.snapshot.queryParamMap.get('token') == null || this.route.snapshot.queryParamMap.get('token') === '') {
			this.router.navigate(['404']);
		} else {
			this.model.token = this.route.snapshot.queryParamMap.get('token');
		}

	}

	ngOnDestroy(): void {
		this.authNoticeService.setNotice(null);
	}

	validate(f: NgForm) {
		if (f.form.status === 'VALID') {
			return true;
		}

		this.errors = [];

		if (objectPath.get(f, 'form.controls.password.errors.required')) {
			this.errors.push(this.translate.instant('AUTH.VALIDATION.INVALID', {name: this.translate.instant('AUTH.INPUT.PASSWORD')}));
		}

		if (objectPath.get(f, 'form.controls.rpassword.errors.pattern')) {
			this.errors.push(this.translate.instant('AUTH.VALIDATION.CONFIRM_PASSWORD',
				{ name: this.translate.instant('AUTH.INPUT.CONFIRM_PASSWORD') }));
		}

		if (this.errors.length > 0) {
			this.authNoticeService.setNotice(this.errors.join('<br/>'), 'error');
			this.spinner.active = false;
		}

		return false;
	}

}
