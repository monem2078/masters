
import {Component, OnInit, ChangeDetectionStrategy, ViewChild} from '@angular/core';
import { NgbTabset } from '@ng-bootstrap/ng-bootstrap';
import { Tab } from '../../../../core/models/enums/tabs';
import {NgbTabChangeEvent} from '@ng-bootstrap/ng-bootstrap';
import {Master} from '../../../../core/models/master';
import {environment} from '../../../../../environments/environment';
import {ActivatedRoute, Router} from '@angular/router';
import {CrudService} from '../../../../core/services/shared/crud.service';
import { NgForm } from '@angular/forms';
import {Category} from '../../../../core/models/category';
import {LayoutUtilsService, MessageType} from '../../../../core/services/layout-utils.service';



@Component({
	selector: 'm-master-page',
	templateUrl: './master-page.component.html',
	changeDetection: ChangeDetectionStrategy.Default,
	styleUrls: ['master-page.component.css']
 })
 export class MasterPageComponent implements OnInit {

	public master: Master;
	public categories: Category[];
	edit: boolean = false;
	submitted: boolean = false;
	public environment = environment;
	// activeIdString: string;

	@ViewChild(NgbTabset)private tabset: NgbTabset;
	constructor(private router: Router, private route: ActivatedRoute,
				private crudService: CrudService, private layoutUtilsService: LayoutUtilsService
	) {
		this.master = new Master();
	}

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
	getMasterDetails(data) {
		this.master = data;
	}
	getCategories(data) {
		this.master = data;
	}
	getPackages(data) {
		this.master = data;
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


	// changeTab(tabId) {
	// 	switch (tabId) {
	// 		case 'TAB1':
	// 			this.activeIdString = Tab.TAB1;
	// 			break;
	// 		case 'TAB2':
	// 			this.activeIdString = Tab.TAB2;
	// 			break;
	// 		case 'TAB3':
	// 			this.activeIdString = Tab.TAB3;
	// 			break;
	// 		case 'TAB4':
	// 			this.activeIdString = Tab.TAB4;
	// 			break;
	// 		case 'TAB5':
	// 			this.activeIdString = Tab.TAB5;
	// 			break;
	// 		case 'TAB6':
	// 			this.activeIdString = Tab.TAB6;
	// 			break;
	// 		default:
	// 			this.activeIdString = Tab.TAB1;
	// 	}
	// }

	// beforeChange($event: NgbTabChangeEvent) {
	// 	this.activeIdString = $event.nextId;
	// }


}
