import {Component, EventEmitter, OnInit, Output} from '@angular/core';
import {Master} from '../../../../core/models/master';
import {environment} from '../../../../../environments/environment';
import {ActivatedRoute, Router} from '@angular/router';
import {CrudService} from '../../../../core/services/shared/crud.service';
import {Currency} from '../../../../core/models/currency';
import {Category} from '../../../../core/models/category';
import {NgForm} from "@angular/forms";
import {LayoutUtilsService, MessageType} from "../../../../core/services/layout-utils.service";
import {forkJoin, Observable} from "rxjs";
import {Module} from "../../../../core/models/module";

@Component({
	selector: 'm-packages',
	templateUrl: 'packages.component.html'
})

export class PackagesComponent implements OnInit {
    public master: Master;
    public environment = environment;
    submitted: boolean = false;
    edit: boolean = false;
    public currencies: Observable<Currency[]>;
    public categories: Observable<Category[]>;
    public category: Array<Category> = [];
    public currency: Array<Currency> = [];
    @Output()
    package: EventEmitter<Master> = new EventEmitter<Master>();
    packages = [{}];
    constructor(private router: Router, private route: ActivatedRoute,
                private crudService: CrudService, private layoutUtilsService: LayoutUtilsService
    ) {
        this.master = new Master();
    }
    addSection() {
        this.master.packages.push({});
    }

    deleteSection(index) {
        const _title: string = 'Delete Package';
        const _description: string = 'Are you sure you want to delete this package ?';
        const _waitDesciption: string = 'Deleting Package ...';
        const _deleteMessage = `Package Has Been Deleted`;

        const dialogRef = this.layoutUtilsService.deleteElement(_title, _description, _waitDesciption);
        dialogRef.afterClosed().subscribe(res => {
            if (!res) {
                return;
            }
            this.master.packages.splice(index, 1);
        });
    }

    ngOnInit() {
        this.getCurrencies();
        this.getCategories();
        this.route.params.subscribe(params => {
            forkJoin([this.categories, this.currencies]).subscribe(data => {
                this.category = data[0];
                this.currency = data[1];
                this.getMaster(params['id']);
            });
        });
    }

    getMaster (id: number) {
        this.crudService.get('admin/masters', id).subscribe((result: Master) => {
            this.master = result;
        }, err => {
        });
    }
    getCurrencies() {
        this.currencies = this.crudService.getList('currencies');
    }
    getCategories() {
        this.categories = this.crudService.getList('categories');
    }
    sendData() {
        this.package.emit(this.master);
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
