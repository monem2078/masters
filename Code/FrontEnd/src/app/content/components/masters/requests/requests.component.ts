import { Component, OnInit } from '@angular/core';
import {Master} from '../../../../core/models/master';
import {environment} from '../../../../../environments/environment';
import {ActivatedRoute, Router} from '@angular/router';
import {CrudService} from '../../../../core/services/shared/crud.service';
import {LayoutUtilsService, MessageType} from '../../../../core/services/layout-utils.service';
import {MasterService} from '../../../../core/services/master/master.service';
import {BehaviorSubject} from 'rxjs';

@Component({
	selector: 'm-requests',
	templateUrl: 'requests.component.html'
})

export class RequestsComponent implements OnInit {
    loadingSubject = new BehaviorSubject<boolean>(false);
    loading$ = this.loadingSubject.asObservable();
	public master: Master;
	public numbers;
	public environment = environment;

	constructor(private router: Router, private masterService: MasterService, private route: ActivatedRoute,
				private crudService: CrudService, private layoutUtilsService: LayoutUtilsService
	) {
		this.master = new Master();
	}

	ngOnInit() {
		this.route.params.subscribe(params => {
			this.getMaster(params['id']);
		});
	}

	getMaster(id: number) {
		this.crudService.get('admin/masters', id).subscribe((result: Master) => {
			this.master = result;
		}, err => {
		});
	}

	updateRequestStatus(status: number, id: number) {
		const requestStatus = {
			id: id,
			request_status_type_id: status
		};
		let _title: string = '';
		let _description: string = '';
		let _waitDesciption: string = '';
		let _Message = ``;
		let _failedMessage = ``;
		let _button = '';
		if (status === 2) {
			_title = 'Accept Request';
			_description = 'Are you sure you want to accept this request';
			_waitDesciption = 'Waiting to accept';
			_Message = `Request has been accepted`;
			_failedMessage = `Something want wrong`;
			_button = 'Accept';
		} else {
			_title = 'Reject Request';
			_description = 'Are you sure you want to reject this request';
			_waitDesciption = 'Waiting to reject';
			_Message = `Request has been rejected`;
			_failedMessage = `Something want wrong`;
			_button = 'Reject';
		}

		const dialogRef = this.layoutUtilsService.actionElement(_title, _description, _waitDesciption, _button);
		dialogRef.afterClosed().subscribe(res => {
			if (!res) {
				return;
			}
			this.masterService.updateRequestStatus(requestStatus).
			subscribe(pagedData => {
					this.layoutUtilsService.showActionNotification(_Message, MessageType.Delete);
                    this.route.params.subscribe(params => {
                        this.getMaster(params['id']);
                    });
				},
				error => {
					this.layoutUtilsService.showActionNotification(_failedMessage, MessageType.Delete);
					this.loadingSubject.next(false);
				});
		});
	}

}
