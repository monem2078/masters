import {Component, EventEmitter, OnInit, Output} from '@angular/core';
import {Master} from '../../../../core/models/master';
import {environment} from '../../../../../environments/environment';
import {ActivatedRoute, Router} from '@angular/router';
import {CrudService} from '../../../../core/services/shared/crud.service';
import {City} from '../../../../core/models/city';
import {Category} from '../../../../core/models/category';
import {NgForm} from "@angular/forms";
import {LayoutUtilsService, MessageType} from "../../../../core/services/layout-utils.service";
import {forEach} from "@angular/router/src/utils/collection";
import index from "@angular/cli/lib/cli";

@Component({
	selector: 'm-categories',
	templateUrl: 'categories.component.html',

})

export class CategoriesComponent implements OnInit {
	@Output()
	category: EventEmitter<Master> = new EventEmitter<Master>();
	public master: Master;
	public errors;
	public sub_category = [];
	submitted: boolean = false;
	edit: boolean = false;
	public categories: Array<Category> = [];
	public environment = environment;
	constructor(private router: Router, private route: ActivatedRoute, private layoutUtilsService: LayoutUtilsService,
				private crudService: CrudService) {
		this.master = new Master();
	}

	ngOnInit() {
		this.route.params.subscribe(params => {
			this.getMaster(params['id']);
		});

		this.getCategories();
	}

	getMaster (id: number) {
		this.crudService.get('admin/masters', id).subscribe((result: Master) => {
			this.master = result;
		}, err => {
		});
	}
	selectedCategories() {
		let x = [];
		this.master.categories.forEach( (myObject, index) => {
			x[index] = myObject.id;
		});
		return x;
	}

	getCategories() {
		this.crudService.getList('main-categories').subscribe((result: Array<Category>) => {
			this.categories = result;
		});
	}

	sendData() {
		this.category.emit(this.master);
	}

	checkSubCategory(subCategory: Category) {
		return this.master.sub_category_ids.some(x => x === subCategory.id);
	}

	addOrRemove(id: number) {
		if (this.master.sub_category_ids.some(x => x === id)) {
			const key = this.master.sub_category_ids.indexOf(id);
			this.master.sub_category_ids.splice(key, 1);
		} else {
			this.master.sub_category_ids.push(id);
		}
	}

	update(masterForm: NgForm) {
		const _Message = 'Master updated successfully';
		this.submitted = true;
		this.edit = true;
		if (masterForm.valid) { // submit form if valid
			if (this.master.sub_category_ids.length === 0) {
				this.errors = 'You Must Choose Category';
			} else {
				this.crudService.edit<Master>('admin/masters', this.master, this.master.id).subscribe(
					data => {
						this.layoutUtilsService.showActionNotification(_Message, MessageType.Delete);
					},
					error => {
					});
			}
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
