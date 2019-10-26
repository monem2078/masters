import {Component, EventEmitter, OnInit, Output, ViewChild} from '@angular/core';
import { DropzoneComponent , DropzoneDirective,
	DropzoneConfigInterface } from 'ngx-dropzone-wrapper';
import {Master} from '../../../../core/models/master';
import {ActivatedRoute, Router} from '@angular/router';
import {CrudService} from '../../../../core/services/shared/crud.service';
import {LayoutUtilsService, MessageType} from '../../../../core/services/layout-utils.service';
import {environment} from '../../../../../environments/environment';
import {NgForm} from '@angular/forms';


@Component({
	selector: 'm-master-data',
	templateUrl: './master-data.component.html'
})

export class MasterDataComponent implements OnInit {

    public type;
	edit: boolean = false;
	@Output()
    notify: EventEmitter<Master> = new EventEmitter<Master>();
    public disabled;
    public url;
	submitted: boolean = false;
	public master: Master;
	public id = this.route.snapshot.params.id;
	public environment = environment;
	public config: DropzoneConfigInterface = {
		url: 'http://localhost/Masters/Code/Backend/public/api/v1/upload',
		clickable: true,
		maxFiles: 10,
		autoReset: null,
		errorReset: null,
		cancelReset: null
	};

	@ViewChild(DropzoneComponent) componentRef?: DropzoneComponent;
	@ViewChild(DropzoneDirective) directiveRef?: DropzoneDirective;


	constructor(private router: Router, private route: ActivatedRoute,
	private crudService: CrudService, private layoutUtilsService: LayoutUtilsService
	) {
		this.master = new Master();
	}

	/** Start ngx-dropzone */
	// public toggleType(): void {
	// 	this.type = (this.type === 'component') ? 'directive' : 'component';
	// }

	// public toggleDisabled(): void {
	// 	this.disabled = !this.disabled;
	// }

	public toggleAutoReset(): void {
		this.config.autoReset = this.config.autoReset ? null : 5000;
		this.config.errorReset = this.config.errorReset ? null : 5000;
		this.config.cancelReset = this.config.cancelReset ? null : 5000;
	}

	public toggleMultiUpload(): void {
		this.config.maxFiles = this.config.maxFiles ? 0 : 1;
	}

	public toggleClickAction(): void {
		this.config.clickable = !this.config.clickable;
	}

	// public resetDropzoneUploads(): void {
	// 	if (this.type === 'directive' && this.directiveRef) {
	// 		this.directiveRef.reset();
	// 	} else if (this.type === 'component' && this.componentRef && this.componentRef.directiveRef) {
	// 		this.componentRef.directiveRef.reset();
	// 	}
	// }

	public onUploadInit(args: any): void {
	}

	public onUploadError(args: any): void {
	}

	public onUploadSuccess(args: any): void {
		this.master.url = args[1].url;
	}

	/** End ngx-dropzone */



	ngOnInit() {
		this.route.params.subscribe(params => {
			this.getMaster(params['id']);
		});
	}

	getMaster (id: number) {
		this.crudService.get('admin/masters', id).subscribe((result: Master) => {
			this.master = result;
		}, err => {
		});
	}
	sendData() {
		this.notify.emit(this.master);
	}

	hasError(masterForm: NgForm, field: string, validation: string) {
		if (masterForm && Object.keys(masterForm.form.controls).length > 0 &&
			masterForm.form.controls[field].errors && validation in masterForm.form.controls[field].errors) {
			if (validation) {
				return (masterForm.form.controls[field].dirty &&
					masterForm.form.controls[field].errors[validation]) ||
					(this.edit && masterForm.form.controls[field].errors[validation]);
			}
			return (masterForm.form.controls[field].dirty &&
				masterForm.form.controls[field].invalid) || (this.edit && masterForm.form.controls[field].invalid);
		}
	}

	update(masterForm: NgForm) {
		const _Message = 'Master updated successfully';
		this.submitted = true;
		this.edit = true;
		if (masterForm.valid) { // submit form if valid
			this.crudService.edit<Master>('admin/masters', this.master, this.master.id).subscribe(
				data => {
					this.layoutUtilsService.showActionNotification(_Message, MessageType.Delete);
				},
				error => {
				});
		}
		this.submitted = false;
	}

	delete(id: any) {
		const _title: string = 'Delete Master';
		const _description: string = 'Are you sure you want to delete this master ?';
		const _waitDesciption: string = 'Deleting Master ...';
		const _deleteMessage = `Master Has Been Deleted`;

		const dialogRef = this.layoutUtilsService.deleteElement(_title, _description, _waitDesciption);
		dialogRef.afterClosed().subscribe(res => {
			if (!res) {
				return;
			}
			this.crudService.delete<Master>('admin/masters', id).
			subscribe(pagedData => {
					this.layoutUtilsService.showActionNotification(_deleteMessage, MessageType.Delete);
					this.router.navigate(['/masters']);
				},
				error => {
				});
		});
	}

}
